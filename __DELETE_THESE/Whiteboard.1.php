<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'include/App.php';
include_once 'include/TestUser.php';
include_once 'include/TestProcess.php';

if ($container->has('profiler')){
    $container->get('profiler')->disable();
}

$app = new App($container);

//$app->login('georgia.harrison@mailinator.com', 'Password1');
//$data = $app->reykerData();

//FAKE USER SIGNUP
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

