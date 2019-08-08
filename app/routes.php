<?php
// Routes

$app->get('/post/{id}', 'App\Controller\HomeController:viewPost')
    ->setName('view_post');



$app->get('/', 'App\Controller\HomeController:viewHome')
    ->setName('home');

// ==============sign-up=================
$app->get('/qi/sign-up', 'App\Controller\SignUPViewContoller:view_sign_up')
    ->setName('user_signup');

$app->get('/qi/authorized_user/{authorized_code}', 'App\Controller\SignUPController:user_authorized_process')
    ->setName('authorized_user');

$app->post('/qi/sign-up/process', 'App\Controller\SignUPController:sign_up_process')
    ->setName('sign_up_process');

$app->post('/android/user/sign-up/process', 'App\Controller\SignUPController:mobile_sign_up_process')
    ->setName('android_sign_up_process');
    
$app->post('/android/user/get_user_data', 'App\Model\DBModel:getUserInfo_usn')
    ->setName('getUserInfo_usn');    
//======================================

// ==============sign-in=================
$app->get('/qi/sign-in', 'App\Controller\SignInController:view_sign_in')
    ->setName('user_signin');

$app->post('/qi/sign-in/process', 'App\Controller\SignInController:sign_in_process')
    ->setName('user_signin');

$app->post('/android/user/sign-in/process/', 'App\Controller\SignInController:android_sign_in_process')
    ->setName('android_user_signin');
//======================================


// ==============Sign-out=================
$app->post('/qi/user/sign-out/process', 'App\Controller\SignOutController:sign_out_process')
    ->setName('sign_out_process');
    
$app->post('/android/user/sign-out/process', 'App\Controller\SignOutController:android_sign_out_process')
    ->setName('sign_out_process');
//======================================

// ==============Change Password=================
$app->get('/qi/user/change_password', 'App\Controller\MypageController:change_pw_view')
    ->setName('change_password');

$app->post('/qi/user/change_password/process', 'App\Controller\MypageController:web_change_pw_process')
    ->setName('web_change_pw_process');

$app->post('/android/user/change_password/process', 'App\Controller\MypageController:android_change_pw_process')
    ->setName('android_change_pw_process');    
//========================================


// ==============Forgotten PW=================
$app->get('/qi/user/forgotten_password', 'App\Controller\ForgottenPWController:forgottenPW_view')
    ->setName('user_forgotten_password');

$app->post('/qi/user/forgotten_password/process', 'App\Controller\ForgottenPWController:forgotten_password_process')
    ->setName('user_forgotten_password');

$app->post('/android/user/forgotten_password/process', 'App\Controller\ForgottenPWController:android_forgotten_password_process')
    ->setName('android_forgotten_password_process');

$app->get('/qi/user/forgotten_password/password_change/{verification_code_cpw}', 'App\Controller\ForgottenPWController:forgottenPW_changePW_view')
    ->setName('forgottenPW_changePW_view');

$app->post('/qi/user/forgotten_password/password_change/process', 'App\Controller\ForgottenPWController:forgottenPW_changePW_process')
    ->setName('forgottenPW_changePW_process');

$app->post('/qi/user/forgotten_password/password_change/getusn', 'App\Controller\ForgottenPWController:forgottenPW_getUsn')
    ->setName('forgottenPW_getUsn');

$app->get('/qi/authorized_user_fp/{verification_code_cpw}', 'App\Controller\ForgottenPWController:user_authorized_fp_process')
    ->setName('authorized_user_fp');
//======================================


// ==============SensorManagement=================


$app->post('/qi/device/hr_record', 'App\Controller\SensorManagement:heart_rate_record')
    ->setName('heart_rate_record');

$app->post('/qi/device/registrate_process', 'App\Controller\SensorManagement:sensor_registration_process')
    ->setName('sensor_registration_process');

$app->post('/qi/device/deregistrate_process', 'App\Controller\SensorManagement:web_sensor_deregistration_process')
    ->setName('web_sensor_deregistration_process');

$app->post('/android/device/deregistrate_process', 'App\Controller\SensorManagement:android_sensor_deregistration_process')
    ->setName('android_sensor_deregistration_process');

$app->post('/qi/device/list_view', 'App\Controller\SensorManagement:web_device_list_view_process')
    ->setName('android_device_list_view_process');

$app->post('/android/device/list_view', 'App\Controller\SensorManagement:android_device_list_view_process')
    ->setName('android_device_list_view_process');


//======================================


// ==============MyPage=================
$app->get('/qi/user/mypage', 'App\Controller\MypageController:mypage_view')
    ->setName('MypageController');
//======================================

// ==============ID CANCELLATION=========
$app->get('/qi/user/idCancellation', 'App\Controller\IDCancellationcontroller:idCancellation_view')
    ->setName('id_cancellation');
    
$app->post('/qi/user/idCancellation/process', 'App\Controller\IDCancellationcontroller:idCancellation_process')
    ->setName('id_cancellation_process');

$app->post('/android/user/idCancellation/process', 'App\Controller\IDCancellationcontroller:android_idCancellation_process')
    ->setName('android_idCancellation_process');
//======================================

// ==============Profile=================
$app->get('/qi/user/profile', 'App\Controller\ProfileController:profile_view')
    ->setName('profile_view');
//======================================

// ==============HOME PAGE=================
$app->post('/qi/device/aq_data', 'App\Controller\HomeController:web_aq_data_response')
    ->setName('web_aq_data_response');

$app->post('/qi/device/aq_data/move_map', 'App\Controller\HomeController:web_realtime_aq_data_move_map_response')
    ->setName('web_realtime_aq_data_move_map_response');

$app->post('/qi/android/aq_data/move_map', 'App\Controller\HomeController:android_realtime_aq_data_move_map_response')
    ->setName('android_realtime_aq_data_move_map_response');
//======================================


//================Air Quality================
$app->post('/qi/device/aq_record', 'App\Controller\SensorManagement:airQuality_record')
    ->setName('airQuality_record');
    
$app->get('/qi/device/aq_data/historical', 'App\Controller\SensorManagement:aq_historical_view')
    ->setName('aq_historical_view');

$app->post('/qi/device/aq_data/historical/process', 'App\Controller\SensorManagement:web_get_historical_aq_data')
    ->setName('web_get_historical_aq_data');

$app->post('/qi/device/aq_data/historical/chartdata/process', 'App\Controller\SensorManagement:web_get_historical_aq_chart_data')
    ->setName('web_get_historical_aq_chart_data');
//===========================================
