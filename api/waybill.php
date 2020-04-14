<?php
define("BASEPATH", dirname(__FILE__));
require 'func.php';
require '../lib/config.php';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
if ($config['web']['maintenance'] == 1) {
  $result = array('result' => false, 'data' => null, 'message' => 'Maintenance');
	exit(json_encode($result));
}
if ($_POST) {
	if ($_POST['courier'] == "jnt") {
		$result = $PT->JnT($_POST['waybill']);
	} else if ($_POST['courier'] == "jne") {
		$result = $PT->JNE($_POST['waybill']);
	} else if ($_POST['courier'] == "sicepat") {
		$result = $PT->SiCepat($_POST['waybill']);
	} else if ($_POST['courier'] == "tiki") {
		$result = $PT->TIKI($_POST['waybill']);
	} else if ($_POST['courier'] == "anteraja") {
		$result = $PT->AnterAja($_POST['waybill']);
	} else if ($_POST['courier'] == "wahana") {
		$result = $PT->WAHANA($_POST['waybill']);
	} else if ($_POST['courier'] == "ninja") {
		$result = $PT->Ninja($_POST['waybill']);
	} else if ($_POST['courier'] == "lion") {
		$result = $PT->Lion($_POST['waybill']);
	} else if ($_POST['courier'] == "lex") {
		$result = $PT->LEX($_POST['waybill']);
	} else {
		$result = array('result' => false, 'data' => null, 'message' => 'Permintaan tidak sesuai');
	}
} else {
	$result = array('result' => false, 'data' => null, 'message' => 'Permintaan tidak sesuai');
}
print json_encode($result, JSON_PRETTY_PRINT);
