<?php 
add_action( 'admin_enqueue_scripts', 'import_epanel_javascript' );
function import_epanel_javascript( $hook_suffix ) {
	if ( 'admin.php' == $hook_suffix && isset( $_GET['import'] ) && isset( $_GET['step'] ) && 'wordpress' == $_GET['import'] && '1' == $_GET['step'] )
		add_action( 'admin_head', 'admin_headhook' );
}

function admin_headhook(){ ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$("p.submit").before("<p><input type='checkbox' id='importepanel' name='importepanel' value='1' style='margin-right: 5px;'><label for='importepanel'>Replace ePanel settings with sample data values</label></p>");
		});
	</script>
<?php }

add_action('import_end','importend');
function importend(){
	global $wpdb, $shortname;
	
	#make custom fields image paths point to sampledata/sample_images folder
	$sample_images_postmeta = $wpdb->get_results("SELECT meta_id, meta_value FROM $wpdb->postmeta WHERE meta_value REGEXP 'http://et_sample_images.com'");
	if ( $sample_images_postmeta ) {
		foreach ( $sample_images_postmeta as $postmeta ){
			$template_dir = get_template_directory_uri();
			if ( is_multisite() ){
				switch_to_blog(1);
				$main_siteurl = site_url();
				restore_current_blog();
				
				$template_dir = $main_siteurl . '/wp-content/themes/' . get_template();
			}
			preg_match( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $postmeta->meta_value, $matches );
			$image_path = $matches[1];
			
			$local_image = preg_replace( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $template_dir . '/sampledata/sample_images/$1.jpg', $postmeta->meta_value );
			
			$local_image = preg_replace( '/s:55:/', 's:' . strlen( $template_dir . '/sampledata/sample_images/' . $image_path . '.jpg' ) . ':', $local_image );
			
			$wpdb->update( $wpdb->postmeta, array( 'meta_value' => $local_image ), array( 'meta_id' => $postmeta->meta_id ), array( '%s' ) );
		}
	}

	if ( !isset($_POST['importepanel']) )
		return;
	
	$importOptions = 'YTo0Mzp7czowOiIiO047czoxNzoiYnVzaW5lc3NjYXJkX2xvZ28iO3M6MDoiIjtzOjIwOiJidXNpbmVzc2NhcmRfZmF2aWNvbiI7czowOiIiO3M6MjU6ImJ1c2luZXNzY2FyZF9jb2xvcl9zY2hlbWUiO3M6NDoiR3JleSI7czoyNzoiYnVzaW5lc3NjYXJkX2VuaGFuY2VfanF1ZXJ5IjtzOjI6Im9uIjtzOjI2OiJidXNpbmVzc2NhcmRfZXhjbHVkZV9wYWdlcyI7TjtzOjIzOiJidXNpbmVzc2NhcmRfc29ydF9wYWdlcyI7czoxMDoicG9zdF90aXRsZSI7czoyMzoiYnVzaW5lc3NjYXJkX29yZGVyX3BhZ2UiO3M6MzoiYXNjIjtzOjI2OiJidXNpbmVzc2NhcmRfY3VzdG9tX2NvbG9ycyI7TjtzOjIyOiJidXNpbmVzc2NhcmRfY2hpbGRfY3NzIjtOO3M6MjU6ImJ1c2luZXNzY2FyZF9jaGlsZF9jc3N1cmwiO3M6MDoiIjtzOjI3OiJidXNpbmVzc2NhcmRfY29sb3JfbWFpbmZvbnQiO3M6MDoiIjtzOjI3OiJidXNpbmVzc2NhcmRfY29sb3JfbWFpbmxpbmsiO3M6MDoiIjtzOjI3OiJidXNpbmVzc2NhcmRfY29sb3JfcGFnZWxpbmsiO3M6MDoiIjtzOjI3OiJidXNpbmVzc2NhcmRfY29sb3JfaGVhZGluZ3MiO3M6MDoiIjtzOjI4OiJidXNpbmVzc2NhcmRfY29sb3JfcGFnZXRpdGxlIjtzOjA6IiI7czozNToiYnVzaW5lc3NjYXJkX2NvbG9yX3BhZ2V0aXRsZV9zaGFkb3ciO3M6MDoiIjtzOjI3OiJidXNpbmVzc2NhcmRfc2VvX2hvbWVfdGl0bGUiO047czozMzoiYnVzaW5lc3NjYXJkX3Nlb19ob21lX2Rlc2NyaXB0aW9uIjtOO3M6MzA6ImJ1c2luZXNzY2FyZF9zZW9faG9tZV9rZXl3b3JkcyI7TjtzOjMxOiJidXNpbmVzc2NhcmRfc2VvX2hvbWVfY2Fub25pY2FsIjtOO3M6MzE6ImJ1c2luZXNzY2FyZF9zZW9faG9tZV90aXRsZXRleHQiO3M6MDoiIjtzOjM3OiJidXNpbmVzc2NhcmRfc2VvX2hvbWVfZGVzY3JpcHRpb250ZXh0IjtzOjA6IiI7czozNDoiYnVzaW5lc3NjYXJkX3Nlb19ob21lX2tleXdvcmRzdGV4dCI7czowOiIiO3M6MjY6ImJ1c2luZXNzY2FyZF9zZW9faG9tZV90eXBlIjtzOjI3OiJCbG9nTmFtZSB8IEJsb2cgZGVzY3JpcHRpb24iO3M6MzA6ImJ1c2luZXNzY2FyZF9zZW9faG9tZV9zZXBhcmF0ZSI7czozOiIgfCAiO3M6Mjk6ImJ1c2luZXNzY2FyZF9zZW9fc2luZ2xlX3RpdGxlIjtOO3M6MzU6ImJ1c2luZXNzY2FyZF9zZW9fc2luZ2xlX2Rlc2NyaXB0aW9uIjtOO3M6MzI6ImJ1c2luZXNzY2FyZF9zZW9fc2luZ2xlX2tleXdvcmRzIjtOO3M6MzM6ImJ1c2luZXNzY2FyZF9zZW9fc2luZ2xlX2Nhbm9uaWNhbCI7TjtzOjM1OiJidXNpbmVzc2NhcmRfc2VvX3NpbmdsZV9maWVsZF90aXRsZSI7czo5OiJzZW9fdGl0bGUiO3M6NDE6ImJ1c2luZXNzY2FyZF9zZW9fc2luZ2xlX2ZpZWxkX2Rlc2NyaXB0aW9uIjtzOjE1OiJzZW9fZGVzY3JpcHRpb24iO3M6Mzg6ImJ1c2luZXNzY2FyZF9zZW9fc2luZ2xlX2ZpZWxkX2tleXdvcmRzIjtzOjEyOiJzZW9fa2V5d29yZHMiO3M6Mjg6ImJ1c2luZXNzY2FyZF9zZW9fc2luZ2xlX3R5cGUiO3M6MjE6IlBvc3QgdGl0bGUgfCBCbG9nTmFtZSI7czozMjoiYnVzaW5lc3NjYXJkX3Nlb19zaW5nbGVfc2VwYXJhdGUiO3M6MzoiIHwgIjtzOjMyOiJidXNpbmVzc2NhcmRfc2VvX2luZGV4X2Nhbm9uaWNhbCI7TjtzOjM0OiJidXNpbmVzc2NhcmRfc2VvX2luZGV4X2Rlc2NyaXB0aW9uIjtOO3M6Mjc6ImJ1c2luZXNzY2FyZF9zZW9faW5kZXhfdHlwZSI7czoyNDoiQ2F0ZWdvcnkgbmFtZSB8IEJsb2dOYW1lIjtzOjMxOiJidXNpbmVzc2NhcmRfc2VvX2luZGV4X3NlcGFyYXRlIjtzOjM6IiB8ICI7czozNjoiYnVzaW5lc3NjYXJkX2ludGVncmF0ZV9oZWFkZXJfZW5hYmxlIjtzOjI6Im9uIjtzOjM0OiJidXNpbmVzc2NhcmRfaW50ZWdyYXRlX2JvZHlfZW5hYmxlIjtzOjI6Im9uIjtzOjI5OiJidXNpbmVzc2NhcmRfaW50ZWdyYXRpb25faGVhZCI7czowOiIiO3M6Mjk6ImJ1c2luZXNzY2FyZF9pbnRlZ3JhdGlvbl9ib2R5IjtzOjA6IiI7fQ==';
	
	/*global $options;
	
	foreach ($options as $value) {
		if( isset( $value['id'] ) ) { 
			update_option( $value['id'], $value['std'] );
		}
	}*/
	
	$importedOptions = unserialize(base64_decode($importOptions));
	
	foreach ($importedOptions as $key=>$value) {
		if ($value != '') update_option( $key, $value );
	}
} ?>