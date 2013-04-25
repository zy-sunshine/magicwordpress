<?php 

add_action( 'after_setup_theme', 'et_setup_theme' );
if ( ! function_exists( 'et_setup_theme' ) ){
	function et_setup_theme(){
		require_once(TEMPLATEPATH . '/epanel/custom_functions.php');  

		require_once(TEMPLATEPATH . '/epanel/options_businesscard.php');

		require_once(TEMPLATEPATH . '/epanel/core_functions.php'); 

		require_once(TEMPLATEPATH . '/epanel/post_thumbnails_businesscard.php');
	}
}

if (get_option('businesscard_enhance_jquery') == 'on') {
	if (!is_admin()) { add_action('init', 'cycle_script'); }
	add_filter('post_gallery', 'bcard_gallery', 10, 2);
};
	
function cycle_script(){
	wp_enqueue_script('jquery');
	wp_enqueue_script('cyclescript', get_bloginfo('template_directory').'/js/jquery.cycle.all.min.js');
};

function bcard_gallery($output, $attr) {
	global $post, $wp_locale;
	
	static $instance = 0;
	$instance++;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = $wp_locale->text_direction == 'rtl' ? 'right' : 'left'; 
	
	$selector = "gallery-{$instance}";
	
	if ($columns == 2) {
		$margin_right = 0;
		$margin_left = 22;
		$height = 230;
		$top = 98;
		$caption_top = -55;
		$border_size = 8;
		$caption_width = 190;
		$caption_bottom = 15;
		$caption_left = 9;
	}
	else {
		$margin_right = 0;
		$margin_left = 8;
		$height = 177;
		$top = 73;
		$caption_top = -55;
		$border_size = 3;
		$caption_width = 138;
		$caption_bottom = 10;
		$caption_left = 4;
	}; 

	$output = apply_filters('gallery_style', "
		<style type='text/css'>
			#{$selector} {
				margin: 10px auto 15px;
				height: {$height}px;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				margin-left:{$margin_left}px;
				margin-right:{$margin_right}px;
				text-align: center;
				position: relative;
				width: {$itemwidth}%;			}
			#{$selector} img {
				border: {$border_size}px solid #ffffff;
				margin-top: -5px;
			}
			#{$selector} img.active {
				-moz-box-shadow: #7c7b7b 4px 4px 5px; 
				-webkit-box-shadow: #7c7b7b 4px 4px 5px;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
				bottom:{$caption_bottom}px;
				left:{$caption_left}px;
				position: absolute;
				width: {$caption_width}px;
				padding: 4px;
				border: 1px solid #ffffff;
				background: #ffffff;
				font-family: Georgia, serif;
				font-size: 11px;
				color: #000000;
				text-shadow: none;
				line-height: 17px;
				font-style: italic;
			}
			a.prevgallery, a.nextgallery { top: {$top}px; }
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->
		<div class='gallery-wrap' id='{$selector}-container'>
			<div id='$selector' class='gallery galleryid-{$id}'>");

	$i = 0;
	
	$output .= "<div class='slide'>";
	
	foreach ( $attachments as $id => $attachment ) {
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon'>
				$link
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 && ($i <= count($attachments)) ) {
			//$output .= '<br style="clear: both" />';
			$output .= "</div>";
			if ($i <> count($attachments) ) $output .= "<div class='slide'>";
		}
		
	}
	
	if (count($attachments) % $columns <> 0 ) $output .= "</div>\n";

	$output .= "</div>\n <a href='#' class='prevgallery'>Previous</a><a href='#' class='nextgallery'>Next</a></div>";
		
	$output .= "<script type='text/javascript'>\n
	jQuery(document).ready(function() {
		jQuery('#{$selector}').cycle({
			timeout: 0,
			speed: 300,
			cleartypeNoBg: true,
			next: '#{$selector}-container a.nextgallery',
			prev: '#{$selector}-container a.prevgallery',
			fx: 'scrollHorz'
		});
	});
	</script>";
	
	return $output;
} ?>