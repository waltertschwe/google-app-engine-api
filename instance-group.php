<?php

session_start();
set_include_path("../src/" . PATH_SEPARATOR . get_include_path());
require_once '/var/www/stirplate/vendor/google-api-php-client/autoload.php';

$client_id = '745205624787-t2fr25tnvr8stqpku9pdgt6j300nrsth.apps.googleusercontent.com'; //Client ID
$service_account_name = '745205624787-t2fr25tnvr8stqpku9pdgt6j300nrsth@developer.gserviceaccount.com'; //Email Address 
$key_file_location = '/var/www/stirplate/vendor/google-api-php-client/project-x-e261a341ad78.p12';

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

$zones = $computeService->zones->get('clean-node-829','us-central1-a');
$template = $computeService->instanceTemplates->get('clean-node-829', 'instance-template-1');
$result = json_encode($template);
echo "<b><-- API CALL: Google_Service_Compute_InstanceTemplates_Resource->get() --></b> <br/>";
echo "<b>RESPONSE:</b><br/>";
echo $result;
error_log(json_encode($result));

$groupService = new Google_Service_Replicapool($client);
//$group = $groupService->instanceGroupManagers->get('project-x', 'us-central1-a', 'instance-group-1');

echo "<br/><br/><b><-- API CALL: Google_Service_Replicapool_InstanceGroupManagers_Resource->listInstanceGroupManagers() --></b> <br/>";
echo "<b> REQUEST = </b><br/>";
echo "https://www.googleapis.com/replicapool/v1beta2/projects/project-x/zones/us-central1-a/instanceGroupManagers";
$group = $groupService->instanceGroupManagers->listInstanceGroupManagers('project-x', 'us-central1-a');

echo "<br/><br/>script complete.";



