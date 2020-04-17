<?php
define("BASEPATH", dirname(__FILE__));
require "function.php";
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
$maintenance = "0";
if ($maintenance == "1") {
  $result = array('result' => false, 'data' => null, 'message' => 'Maintenance');
	exit(json_encode($result));
}

if ($_GET) {
  if ($_GET['key'] == "farid") {
    $spotify = new spotify();
    $nama = $spotify->nama();
    $pecah = explode(" ",$nama);
    $email = strtolower($pecah[0].$pecah[1].rand(10,9999))."@gmail.com";
    $pass = $pecah[0]."23";
    $create = $spotify->createAccount($email, $nama, $pass);
    $js = json_decode($create, true);
    if ($js['status'] == "1") {
      $result = array('result' => true, 'data' => array('email' => ''.$email.'', 'pass' => ''.$pass.''), 'message' => 'Successfully Register');
    } else {
      $err = $spotify->get_between($create, 'errors":{"', '"},"country');
      $result = array('result' => false, 'data' => null, 'message' => ''.$err.'');
    }
  } else {
      $result = array('result' => false, 'data' => null, 'message' => 'Key salah');
  }
} else {
    $result = array('result' => false, 'data' => null, 'message' => 'Permintaan tidak sesuai');
}
print json_encode($result, JSON_PRETTY_PRINT);
?>
