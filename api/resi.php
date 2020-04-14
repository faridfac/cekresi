<?php
define("BASEPATH", dirname(__FILE__));
require 'func.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
$maintenance = "0";
if ($maintenance == "1") {
  $result = array('result' => false, 'data' => null, 'message' => 'Maintenance');
	exit(json_encode($result));
}

if ($_GET) {
    $resi = $_GET['waybill'];
    $courier = $_GET['courier'];
    if ($courier == "jne") {
      $result = $PT->JNE(''.$resi.'');
    } else if ($courier == "jnt") {
      $result = $PT->JnT(''.$resi.'');
    } else if ($courier == "wahana") {
      $result = $PT->WAHANA(''.$resi.'');
    } else if ($courier == "sicepat") {
      $result = $PT->SiCepat(''.$resi.'');
    } else if ($courier == "tiki") {
      $result = $PT->TIKI(''.$resi.'');
    } else if ($courier == "ninja") {
      $result = $PT->Ninja(''.$resi.'');
    } else if ($courier == "lion") {
      $result = $PT->Lion(''.$resi.'');
    } else if ($courier == "lex") {
      $result = $PT->LEX(''.$resi.'');
    } else {
      $result = array('result' => false, 'data' => null, 'message' => 'Courier tidak tersedia');
    }
} else {
    $result = array('result' => false, 'data' => null, 'message' => 'Permintaan tidak sesuai');
}
print json_encode($result, JSON_PRETTY_PRINT);
