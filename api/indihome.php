<?php
function getStr($start, $end, $string) {
    if (!empty($string)) {
    $setring = explode($start,$string);
    $setring = explode($end,$setring[1]);
    return $setring[0];
    }
}
function checkbill($no){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://transaksi.klikmbc.co.id/ppob/cektagihanppob-billpaymentsver3.php');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, 'products=SPEEDY&nomortujuan='.$no.'');
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  $headers = array();
  $headers[] = 'User-Agent: Mozilla';
  $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
  $headers[] = 'Host: transaksi.klikmbc.co.id';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  return $response;
}
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
$maintenance = "0";
if ($maintenance == "1") {
  $result = array('result' => false, 'data' => null, 'message' => 'Maintenance');
	exit(json_encode($result, JSON_PRETTY_PRINT));
}

if ($_GET['nomor']) {
    $no = $_GET['nomor'];
    $check = checkbill($no);
    $nomor = getStr('<td><b>NO PELANGGAN</b></td><td>:</td><td>', '</td><td></td>', $check);
    $nama = getStr('<td><b>NAMA PELANGGAN</b></td><td>:</td><td>', '</td><td></td>', $check);
    $bulan = getStr('<td style="text-transform:uppercase;"><b>Tagihan Bulan</b></td><td>:</td><td>', '</td><td></td>', $check);
    $ambil_tagihan = getStr('<td><b>TOTAL TAGIHAN</b></td><td>:</td><td>', '</div></td><td></td>', $check);
    $tagihan = substr($ambil_tagihan,28);
    $error = getStr('<center>ERROR SPEEDY ', '<br><br><a href="cektagihan-billpaymentsver3.php">Back</a></center>', $check);
    $err_result = substr($error,15);
    if (preg_match('/:/i', $error)) {
      $result = array('result' => false, 'data' => null, 'message' => $err_result);
    } else {
    	$result = array('result' => true, 'data' => [ 'id_pelanggan' => $nomor, 'nama_pelanggan' => $nama, 'bulan_thn' => $bulan, 'jumlah_tagihan' => $tagihan ],'message' => 'Successfully Check Bill.');
    }
} else {
    $result = array('result' => false, 'data' => null, 'message' => 'Permintaan tidak sesuai');
}
print json_encode($result, JSON_PRETTY_PRINT);
