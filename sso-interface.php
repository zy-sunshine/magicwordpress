<?php
require( dirname(__FILE__) . '/wp-load.php' );
include_once SSO_ROOT.'./utils/functions.inc.php';
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
$tgt = $_GET['tgt'];
var_dump($username." ".$password." ".$email);
// regist_user($username, $password, $email);

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
$credentials = array('user_login' => $username, 'user_password' => $password, 'rememberme' => true);

sso_wp_signon($tgt, $credentials);

function sso_wp_signon($credentials = '', $secure_cookie = ''){
    // function copy from wp_signon and modify it to adapt with sso login.

    // get user info with tgt
    $userinfo_sso = sso_get_user_info($tgt);
    if(! ($userinfo_sso && array_key_exists('un', $userinfo_sso)) ){
        wp_redirect(add_query_arg(array('action' => 'login_site_perm', 'site' => 'magicwordpress', 'fromurl' => network_home_url()), SSO_URL));
    }

    if ( empty($credentials) ) {
        if ( ! empty($_POST['log']) )
            $credentials['user_login'] = $_POST['log'];
        if ( ! empty($_POST['pwd']) )
            $credentials['user_password'] = $_POST['pwd'];
        if ( ! empty($_POST['rememberme']) )
            $credentials['remember'] = $_POST['rememberme'];
    }

    if ( !empty($credentials['remember']) )
        $credentials['remember'] = true;
    else
        $credentials['remember'] = false;

    // TODO do we deprecate the wp_authentication action?
    //do_action_ref_array('wp_authenticate', array(&$credentials['user_login'], &$credentials['user_password']));

    if ( '' === $secure_cookie )
        $secure_cookie = is_ssl();

    $secure_cookie = apply_filters('secure_signon_cookie', $secure_cookie, $credentials);

    global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie
    $auth_secure_cookie = $secure_cookie;

    add_filter('authenticate', 'wp_authenticate_cookie', 30, 3);

    //$user = wp_authenticate($credentials['user_login'], $credentials['user_password']);
    $userdata = get_user_by('login', $userinfo_sso['un']);
    $user =  new WP_User($userdata->ID);

    if ( is_wp_error($user) ) {
        if ( $user->get_error_codes() == array('empty_username', 'empty_password') ) {
            $user = new WP_Error('', '');
        }

        return $user;
    }

    wp_set_auth_cookie($user->ID, $credentials['remember'], $secure_cookie);
    do_action('wp_login', $user->user_login, $user);
    return $user;
}

?>
