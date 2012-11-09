<?php
require( dirname(__FILE__) . '/wp-load.php' );
// Useage:
// http://127.0.0.1/linuxfans/magicwordpress/sso-interface.php?un=sunshine&pw=magic&email=zy.sunshine@hotmail.com
/**
 * Authenticate the user using the username and password.
 * Check user, if not exist sync it from sso server.
 */
define('SSO_ROOT', dirname(dirname(__FILE__))."/magicsso/");
$username = $_GET['un'];
$password = $_GET['pw'];
$email = $_GET['email'];
var_dump($username." ".$password." ".$email);
regist_user($username, $password, $email);
function regist_user($username, $password, $email){

#   $user_details = wpmu_validate_user_signup( $username, $email );
#   var_dump($user_details);
    #unset( $user_details[ 'errors' ]->errors[ 'user_email_used' ] );
    #if ( is_wp_error( $user_details[ 'errors' ] ) && !empty( $user_details[ 'errors' ]->errors ) ) {
    #   $add_user_errors = $user_details[ 'errors' ];
    #} else {

    // <option value="subscriber" selected="selected">订阅者</option>
    // <option value="administrator">管理员</option>
    // <option value="editor">编辑</option>
    // <option value="author">作者</option>
    // <option value="contributor">投稿者</option>
    global $wpdb;
    $role = 'subscriber';
    if(1){
        #$sanitized_user_login = sanitize_user( $username );
        # random generate password
        #$user_pass = wp_generate_password( 12, false);
        #var_dump('======');
        #die($sanitized_user_login);
        $user_id = wp_create_user( $username, $password, $email );
        #var_dump('======');
        die(var_dump($user_id));
//      $new_user_login = apply_filters('pre_user_login', sanitize_user(stripslashes($username), true));

#### another way to add user. from wp-admin/adduser.php
//      add_filter( 'wpmu_signup_user_notification', '__return_false' ); // Disable confirmation email
// die($new_user_login);
//      wpmu_signup_user( $new_user_login, $email, array( 'add_to_blog' => $wpdb->blogid, 'new_role' => $role ) );

//      $key = $wpdb->get_var( $wpdb->prepare( "SELECT activation_key FROM {$wpdb->signups} WHERE user_login = %s AND user_email = %s", $new_user_login, $email ) );
//      wpmu_activate_signup( $key );
        // $redirect = add_query_arg( array('update' => 'addnoconfirmation'), '/' );

        // wp_redirect( $redirect );
        // die();


// Some possible return error of $user_id
// { ["errors"]=> array(1) { ["existing_user_email"]=> array(1) { [0]=> string(36) "该电子邮件地址已被注册。" } } ["error_data"]=> array(0) { } }
    }
}

?>
