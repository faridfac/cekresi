<?php
require 'config.php';
if ($config['web']['maintenance'] == 1) {
	exit("Under Maintenance");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo $config['web']['meta_description'] ?>">
<meta name="keywords" content="<?php echo $config['web']['meta_keywords'] ?>">
<title><?php echo $config['web']['title'] ?></title>
<link rel="shortcut icon" href="https://cdn.iconscout.com/icon/premium/png-512-thumb/atom-383-608767.png">
<link rel="stylesheet" href="assets/css/bootstrap.css">
<link rel="stylesheet" href="assets/css/bootswatch.min.css">
<script src="assets/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/main.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<style type="text/css">.hidden{display:none}.ui-autocomplete-loading{background:#fff url(assets/img/ui-anim_basic_16x16.gif) right center no-repeat}.ui-menu-item{font-weight:700;font-size:11px}.table-result{font-size:11px}.table-result th{padding:5px 3px!important}.table-result td{padding:5px 3px!important}.services{background:#d7e8fa;padding:30px 0;min-height:200px;color:#333;border-bottom:solid 1px #ccc}.about{background:#d7e8fa;padding:60px 0;min-height:500px;color:#333;border-bottom:solid 1px #ccc}.footer-bg{color:#fff;background:#1e1e1e;padding:60px 0;font-size:11px}.top_title{text-align:center;margin-bottom:15px}.site-footer{margin:40px 0 0;padding:10px 0 10px;border-top:1px solid #ddd;color:#999;background:#fff;font-size:13px;line-height:1.55em;font-family:Source Sans Pro;text-align:center}</style>
<script src="assets/js/debug-protection.js"></script>
<script>if(self != top) { top.location = self.location; }</script>
</head>
<body>
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="/" class="navbar-brand"><img src="assets/images/cekresi.png" alt="Cek Ongkir" Title="Home"></a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav navbar-right">
             <li><a href="/">Home</a></li>
             <li><a href="/doc">Dokumentasi API</a></li>
	     <li><a href="https://cektagihan.herokuapp.com/">Cek Tagihan</a></li>  
	  </ul>
        </div>
      </div>
    </div>
	<div class="container">
		<div class="row">
        <div class="col-lg-8 col-lg-offset-2">
          <center>
              <h2>Cek Resi</h2>
              <p class="lead">Otomatis, Cepat, Akurat</p>
          </center>
