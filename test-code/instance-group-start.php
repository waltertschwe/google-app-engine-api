<?php

session_start();
set_include_path("../src/" . PATH_SEPARATOR . get_include_path());
require_once '/var/www/stirplate/vendor/google-api-php-client/autoload.php';

$client_id = '745205624787-1foerdj03m8puueea9f6ijboqgvitu5p.apps.googleusercontent.com'; //Client ID
$service_account_name = '745205624787-1foerdj03m8puueea9f6ijboqgvitu5p@developer.gserviceaccount.com'; //Email Address 
$key_file_location = 'project-x-6981a5aa51e9.p12';
 
$client = new Google_Client();
if (isset($_SESSION['service_token'])) {
  $client->setAccessToken($_SESSION['service_token']);
}

$key = file_get_contents($key_file_location);
$cred = new Google_Auth_AssertionCredentials(
    $service_account_name,
    array('https://www.googleapis.com/auth/compute', 'https://www.googleapis.com/auth/cloud-platform'),
    $key
);

$client->setAssertionCredentials($cred);
if($client->getAuth()->isAccessTokenExpired()) {
  $client->getAuth()->refreshTokenWithAssertion($cred);
}
$_SESSION['service_token'] = $client->getAccessToken();

$serviceToken = $_SESSION['service_token'];
$computeService = new Google_Service_Compute($client);

$instancGet = $_GET['instances'];
$instances = explode("|", $instancGet);
$zone = 'us-central1-a';
$project = 'clean-node-829';

foreach($instances as $instanceName) {
	try {
		$getResponse = $computeService->instances->get($project, $zone, $instanceName);
		error_log(json_encode($getResponse));
		if(!empty($getResponse)) {
			$status = $getResponse->status;
			error_log("status = " . $status);
			if($status == "RUNNING") {
				echo "<b>" . $instanceName . " </b>is already running. Taking no action.<br/>";
			} else {
				$startResponse = $computeService->instances->start($project, $zone, $instanceName);
				echo "<b>" . $instanceName . " </b> is now starting up.<br/>";
				error_log($startResponse);
			}
		}
	} catch (Exception $e) {
	    echo "<b>" . $instanceName . "</b> is not found logging error.<br/>";
  	    error_log($e->getMessage());
	}
}

echo "<br/>script complete";




