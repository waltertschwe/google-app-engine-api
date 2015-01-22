<?php

function getAllInstances($client) {
	
	$results = array();
	$zone = 'us-central1-a';
	$project = 'clean-node-829';
	$instances = array('instance-1a', 'instance-2a', 'instance-4a', 'instance-6a', 'instance-7a', 'instance-8a');
	
	$computeService = new Google_Service_Compute($client);
	
	foreach($instances as $instance) {
		try {
			$getResponse = $computeService->instances->get($project, $zone, $instance);
			$results[] = $getResponse;
		} catch (Exception $e) {	    
  	    	error_log($e->getMessage());
		}
	}
	
	return $results;
	
}

function googleConnect() {
	
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
	
	return $client;
}
