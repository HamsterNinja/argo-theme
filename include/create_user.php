<?
add_action( 'rest_api_init', function () {
    register_rest_route( 'amadreh/v1/', '/add-user/', array(
          'methods' => WP_REST_Server::CREATABLE,
          'callback' => 'addUser',
      ) );
});

function addUser(WP_REST_Request $request){
    custom_registration_function();
    return new WP_REST_Response('success', 200);
}

function createNewUser($POST, $FILES){
    $username = get_random_unique_username( "user_" );
    $password = wp_generate_password(12, false);
    $email = sanitize_email( $_POST['email'] );
    $first_name = sanitize_text_field( $_POST['first_name'] );
    $last_name = sanitize_text_field( $_POST['last_name'] );
    $phone = sanitize_text_field( $_POST['phone'] );
    $notice_sms = sanitize_text_field( $_POST['notice_sms'] );
    $notice_email = sanitize_text_field( $_POST['notice_email'] );

    $newUser = new customUser();
    $newUser->setUsername($username)
    ->setPassword($password)
    ->setEmail($email)
    ->setFirstName($first_name)
    ->setLastName($last_name)
    ->setPhone($phone)
    ->setNoticeSms($notice_sms)
    ->setNoticeEmail($notice_email);

    return $newUser;
}

function custom_registration_function() {  
    global $reg_errors;
    $newUser = createNewUser($_POST, $_FILES);
    registration_validation($newUser);
    complete_registration($newUser);
}


function registration_validation($newUser)  {
    global $reg_errors;
    $reg_errors = new WP_Error;
    if ( empty( $newUser->username ) || empty( $newUser->password ) || empty( $newUser->email ) ) {
        $reg_errors->add('field', 'Требуемое поля пусто');
    }
    if ( strlen( $newUser->username ) < 4 ) {
        $reg_errors->add( 'username_length', 'Логин должен быть длиннее 4 символов' );
    }
    if ( username_exists( $newUser->username ) )
        $reg_errors->add('user_name', 'Логин занят');
    if ( ! validate_username( $newUser->username ) ) {
        $reg_errors->add( 'username_invalid', 'Недопустимый логин' );
    }
    if ( strlen( $newUser->password ) < 5 ) {
        $reg_errors->add( 'password', 'Пароль должен быть длиннее 5 символов' );
    }
    if ( !is_email( $newUser->email ) ) {
        $reg_errors->add( 'email_invalid', 'Email не валидный' );
    }
    if ( email_exists( $newUser->email ) ) {
        $reg_errors->add( 'email', 'Email уже используется' );
    }
    if ( is_wp_error( $reg_errors ) ) {
        foreach ( $reg_errors->get_error_messages() as $error ) {
            wp_send_json_error( $error );
        }
    }
}

function complete_registration($newUser) {
    global $reg_errors;
    if ( count( $reg_errors->get_error_messages() ) < 1 ) {
                
        $userdata = array(
            'user_login' => $newUser->username,
            'nickname' => $newUser->username,
            'user_pass' => $newUser->password,
            'user_email' => $newUser->email,
        );
        $user = wp_insert_user( $userdata );

        update_usermeta($user, 'first_name', $newUser->first_name);
        update_usermeta($user, 'last_name', $newUser->last_name);
        update_usermeta($user, 'phone', $newUser->phone);
        update_usermeta($user, 'billing_mobile_phone', $newUser->phone);
        update_usermeta($user, 'notice_sms', +filter_var($newUser->notice_sms, FILTER_VALIDATE_BOOLEAN));
        update_usermeta($user, 'notice_email', +filter_var($newUser->notice_email, FILTER_VALIDATE_BOOLEAN));

        userLogin($newUser->username, $newUser->password);
        wp_new_user_notification( $user, null, 'both' );
        wp_send_json_success();
    }
}

function get_random_unique_username( $prefix = '' ){
    $user_exists = 1;
    do {
       $rnd_str = sprintf("%0d", mt_rand(1, 999999));
       $user_exists = username_exists( $prefix . $rnd_str );
    } 
    while( $user_exists > 0 );
    return $prefix . $rnd_str;
}

function userLogin($username, $password){
    $info = [];
    $info['user_login'] = $username;
    $info['user_password'] = $password;
    $info['remember'] = true;
    $user_signon = wp_signon( $info, false );
}