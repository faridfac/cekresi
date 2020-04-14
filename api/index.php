<?php
require '../lib/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($config['web']['maintenance'] == 1) {
  $result = array('result' => false, 'data' => null, 'message' => 'Maintenance');
	exit(json_encode($result));
}
$result = array('result' => true, 'data' => null, 'message' => 'its work');
print json_encode($result);
