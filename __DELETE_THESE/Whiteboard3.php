<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'include/App.php';
include_once 'include/TestUser.php';
include_once 'include/TestProcess.php';


use libphonenumber\PhoneNumberFormat;
use CoreBundle\Common\Utility;

if ($container->has('profiler')){
    $container->get('profiler')->disable();
}

$app = new App($container);
$client = $app->client;

//$user_service = $container->get('user');
//$user = $user_service->getUserWithId(12964);
//$user = $user_service->getUserWithId(12965);

$user_service = $container->get('service.user');

//$container->get('libphonenumber.phone_number_util')->parse('+44 7976 984179', libphonenumber\PhoneNumberFormat::NATIONAL);

/*$filter = [
    'has_been_approved' => [
        'operator' => '=',
        'value'    => 0
    ],
];
*/


function status_to_num($status){
    switch ($status) {
        case 'REFER':
            return 2;
            break;
        case 'FAIL':
            return 3;
            break;
        case 'PASS':
            return 1;
            break;
        default:
            return -1;
    }
    
    return false;
}

$filter = [
    'custom__has_been_reyker_approved' => [
        'operator' => '=',
        'value'    => '2'
    ],
];

$users = $user_service->filterUsers([], true);

foreach($users as $key => $user){
    if(empty($user['has_been_approved'])){
        $custom              = Utility::getArrayParam($user, 'custom', []);
        $isReykerApproved = Utility::getArrayParam($custom, \ClientBundle\Service\UserService::HAS_BEEN_REYKER_APPROVED);
        
        if($isReykerApproved == 2 || $isReykerApproved == 3 || empty($isReykerApproved)){
            $response = $container->get('reyker.admin')->reykerDataId($user['reyker_id']);
            if (Utility::getArrayParam($response, 'outcome') == 'success') {
                $reyker_data = Utility::getArrayParam($response, 'data', []);
                if(isset($reyker_data['AML'])){
                    $status = Utility::getArrayParam($reyker_data['AML'], 'AMLStatus');
                    //$data = ['custom' => [\ClientBundle\Service\UserService::HAS_BEEN_REYKER_APPROVED => $reyker_status]];
                    //$this->get('service.user')->updateUser($data);
                    
                    echo $user['full_name'].' : Current Status = '. $isReykerApproved . ' | New Status = '.status_to_num($status)."\n";
                }
            }
        }
    }
}

//$user  = Utility::getArrayParam($users, 0, []);

/*
$res = $client->postRequest('www.google.com');

var_dump($res);*/

//$app->login('georgia.harrison@mailinator.com', 'Password1');
//$data = $app->reykerData();

//FAKE USER SIGNUP

/*
$user = new TestUser();

$user->firstname = 'DUNCAN';
$user->lastname = 'BOWEN';

$signup =[
    'given_name' => 'DUNCAN',
    'family_name' => 'BOWEN',
];

$profile = [
    'honorific_prefix' => 'Mr',
    'family_name' => 'BOWEN',
    'given_name' => 'DUNCAN',
    'birth_date' => '1985-03-14',
    'address' => [
        'building' => '201', 
        'street_address' => 'Julius Road', 
        'city' => 'Bristol', 
        'region' => 'London',
        'country' => 'United Kingdom',
        'postal_code' => 'BS7 8EU'
    ]
];

$user->setSignupDetails($signup);
$user->setProfileDetails($profile);


//resort crowd journey/process
$process = new TestProcess($app);
$process->setUser($user);
$process->signup();

eval(\Psy\sh());

$app->login($user->email, $user->password);
$process->completeProfile();
*/
