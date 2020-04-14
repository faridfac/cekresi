<?php
define("BASEPATH", dirname(__FILE__));
error_reporting(0);

require 'function.php';
$resi = $_POST['waybill'];
$courier = $_POST['courier'];

if ($courier == "jne") {
  $result = $PT->JNE(''.$resi.'');
  $data = $result['data'];
  $history = $data['tracking'];
  if ($result['result'] == false) {
    echo '<div class="alert alert-info">Maaf, nomor resi <b>'.$resi.'</b> tidak ditemukan. Atau sistem kami sedang mengalami masalah karena terlalu banyak yang melakukan pencarian secara bersamaan, silahkan ulangi kembali / tunggu beberapa saat lagi.</div>';
  } else {
?>

<h3 class="top_title"><?php echo $data['courier'] ?></h3>
<h5 style="margin:0 0 5px 0;">I. Informasi Pengiriman</h5>
<table class="table">
    <tbody>
        <tr>
            <td width="130">No Resi</td>
            <td>:</td>
            <td><b><?php echo $data['waybill'] ?></b></td>
        </tr>
        <tr>
            <td>Service</td>
            <td>:</td>
            <td><b><?php echo $data['service'] ?></b></td>
        </tr>
        <tr>
            <td valign="top">Dikirim tanggal</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['date'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim dari</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['name']."<br>".$data['shipped']['city'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim ke</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['received']['name']."<br>".$data['received']['city'] ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>:</td>
            <td><b><?php echo $data['status'] ?></b></td>
        </tr>
    </tbody>
</table>

<?php
if ($data['status'] == "DELIVERED") {
  $label = "delivered";
} else {
  $label = "delivery";
}
?>

<div id="tracking">
  <div class="text-center tracking-status-<?php echo $label ?>">
    <p class="tracking-status text-tight">History Pengiriman</p>
  </div>
  <div class="tracking-list">
    <?php
      for ($i=0; $i < count($history); $i++) {
    ?>
    <div class="tracking-item">
      <div class="tracking-icon status-<?php echo $label ?>">
          <svg class="svg-inline--fa fa-shipping-fast fa-w-20" aria-hidden="true" data-prefix="fas" data-icon="shipping-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
              <path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path>
          </svg>
      </div>
      <?php
        $date = explode(" ", $history[$i]['date']);
        $desc = explode("[", $history[$i]['desc']);
      ?>
      <div class="tracking-date"><?php echo $date[0] ?><span><?php echo $date[1] ?></span></div>
      <div class="tracking-content"><?php echo $desc[0] ?><span><?php echo "[$desc[1]" ?></span></div>
    </div>
    <?php } ?>
  </div>
</div>

<?php
}
} else if ($courier == "jnt") {
  $result = $PT->JnT(''.$resi.'');
  $data = $result['data'];
  $history = $data['tracking'];
  if ($result['result'] == false) {
    echo '<div class="alert alert-info">Maaf, nomor resi <b>'.$resi.'</b> tidak ditemukan. Atau sistem kami sedang mengalami masalah karena terlalu banyak yang melakukan pencarian secara bersamaan, silahkan ulangi kembali / tunggu beberapa saat lagi.</div>';
  } else {
?>

<h3 class="top_title"><?php echo $data['courier'] ?></h3>
<h5 style="margin:0 0 5px 0;">I. Informasi Pengiriman</h5>
<table class="table">
    <tbody>
        <tr>
            <td width="130">No Resi</td>
            <td>:</td>
            <td><b><?php echo $data['waybill'] ?></b></td>
        </tr>
        <tr>
            <td valign="top">Dikirim tanggal</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped'] ?></td>
        </tr>
    </tbody>
</table>
<?php
if(preg_match('/has been received/i', $response)){
  $label = "delivered";
} else {
  $label = "delivery";
}
?>
<div id="tracking">
  <div class="text-center tracking-status-<?php echo $label ?>">
    <p class="tracking-status text-tight">History Pengiriman</p>
  </div>
  <div class="tracking-list">
    <?php
      for ($i=0; $i < count($history); $i++) {
    ?>
    <div class="tracking-item">
      <div class="tracking-icon status-<?php echo $label ?>">
          <svg class="svg-inline--fa fa-shipping-fast fa-w-20" aria-hidden="true" data-prefix="fas" data-icon="shipping-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
              <path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path>
          </svg>
      </div>
      <div class="tracking-date"><?php echo $history[$i]['time'] ?><span></span></div>
      <div class="tracking-content"><?php echo $history[$i]['desc'] ?><span></span></div>
    </div>
    <?php } ?>
  </div>
</div>

<?php
}
} else if ($courier == "wahana") {
  $result = $PT->WAHANA(''.$resi.'');
  $data = $result['data'];
  $history = $data['tracking'];
  if ($result['result'] == false) {
    echo '<div class="alert alert-info">Maaf, nomor resi <b>'.$resi.'</b> tidak ditemukan. Atau sistem kami sedang mengalami masalah karena terlalu banyak yang melakukan pencarian secara bersamaan, silahkan ulangi kembali / tunggu beberapa saat lagi.</div>';
  } else {
?>

<h3 class="top_title"><?php echo $data['courier'] ?></h3>
<h5 style="margin:0 0 5px 0;">I. Informasi Pengiriman</h5>
<table class="table">
    <tbody>
        <tr>
            <td width="130">No Resi</td>
            <td>:</td>
            <td><b><?php echo $data['waybill'] ?></b></td>
        </tr>
        <tr>
            <td valign="top">Dikirim tanggal</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['date'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim dari</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['name']." (".$data['shipped']['phone'].")<br>".$data['shipped']['addr'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim ke</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['received']['name']." (".$data['received']['phone'].")<br>".$data['received']['addr'] ?></td>
        </tr>
        <tr>
            <td valign="top">Status Diterima</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['received']['recipient'] ?></td>
        </tr>
    </tbody>
</table>
<div id="tracking-pre"></div>
<div id="tracking">
  <div class="text-center tracking-status-delivery">
    <p class="tracking-status text-tight">History Pengiriman</p>
  </div>
  <div class="tracking-list">
    <?php
      for ($i=0; $i < count($history); $i++) {
    ?>
    <div class="tracking-item">
      <div class="tracking-icon status-delivery">
          <svg class="svg-inline--fa fa-shipping-fast fa-w-20" aria-hidden="true" data-prefix="fas" data-icon="shipping-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
              <path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path>
          </svg>
      </div>
      <div class="tracking-date"><?php echo $history[$i]['date'] ?><span></span></div>
      <div class="tracking-content"><?php echo $history[$i]['desc'] ?><span></span></div>
    </div>
    <?php } ?>
  </div>
</div>

<?php
}
} else if ($courier == "sicepat") {
  $result = $PT->SiCepat(''.$resi.'');
  $data = $result['data'];
  $history = $data['tracking'];
  if ($result['result'] == false) {
    echo '<div class="alert alert-info">Maaf, nomor resi <b>'.$resi.'</b> tidak ditemukan. Atau sistem kami sedang mengalami masalah karena terlalu banyak yang melakukan pencarian secara bersamaan, silahkan ulangi kembali / tunggu beberapa saat lagi.</div>';
  } else {
?>

<h3 class="top_title"><?php echo $data['courier'] ?></h3>
<h5 style="margin:0 0 5px 0;">I. Informasi Pengiriman</h5>
<table class="table">
    <tbody>
        <tr>
            <td width="130">No Resi</td>
            <td>:</td>
            <td><b><?php echo $data['waybill'] ?></b></td>
        </tr>
        <tr>
            <td>Service</td>
            <td>:</td>
            <td><b><?php echo $data['service'] ?></b></td>
        </tr>
        <tr>
            <td valign="top">Dikirim tanggal</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['date'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim dari</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['name']."<br>".$data['shipped']['addr'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim ke</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['received']['name']."<br>".$data['received']['addr'] ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>:</td>
            <td><b><?php echo $data['status'] ?></b></td>
        </tr>
    </tbody>
</table>
<?php
if ($data['status'] == "DELIVERED") {
  $label = "delivered";
} else {
  $label = "delivery";
}
?>
<div id="tracking">
  <div class="text-center tracking-status-<?php echo $label ?>">
    <p class="tracking-status text-tight">History Pengiriman</p>
  </div>
  <div class="tracking-list">
    <?php
      for ($i=0; $i < count($history); $i++) {
    ?>
    <div class="tracking-item">
      <div class="tracking-icon status-<?php echo $label ?>">
          <svg class="svg-inline--fa fa-shipping-fast fa-w-20" aria-hidden="true" data-prefix="fas" data-icon="shipping-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
              <path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path>
          </svg>
      </div>
      <?php
        $date = explode(" ", $history[$i]['date']);
        $desc = explode("[", $history[$i]['desc']);
      ?>
      <div class="tracking-date"><?php echo $date[0] ?><span><?php echo $date[1] ?></span></div>
      <div class="tracking-content"><?php echo $desc[0] ?><span><?php echo "[$desc[1]" ?></span></div>
    </div>
    <?php } ?>
  </div>
</div>

<?php
}
} else if ($courier == "tiki") {
  $result = $PT->TIKI(''.$resi.'');
  $data = $result['data'];
  $history = $data['tracking'];
  if ($result['result'] == false) {
    echo '<div class="alert alert-info">Maaf, nomor resi <b>'.$resi.'</b> tidak ditemukan. Atau sistem kami sedang mengalami masalah karena terlalu banyak yang melakukan pencarian secara bersamaan, silahkan ulangi kembali / tunggu beberapa saat lagi.</div>';
  } else {
?>

<h3 class="top_title"><?php echo $data['courier'] ?></h3>
<h5 style="margin:0 0 5px 0;">I. Informasi Pengiriman</h5>
<table class="table">
    <tbody>
        <tr>
            <td width="130">No Resi</td>
            <td>:</td>
            <td><b><?php echo $data['waybill'] ?></b></td>
        </tr>
        <tr>
            <td>Service</td>
            <td>:</td>
            <td><b><?php echo $data['service'] ?></b></td>
        </tr>
        <tr>
            <td valign="top">Dikirim tanggal</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['date'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim dari</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['name']."<br>".$data['shipped']['addr'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim ke</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['received']['name']."<br>".$data['received']['addr'] ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>:</td>
            <td><b><?php $pecah = explode("-",$history[0]['desc']); echo $pecah[0] ?></b></td>
        </tr>
    </tbody>
</table>
<div id="tracking">
  <div class="text-center tracking-status-delivery">
    <p class="tracking-status text-tight">History Pengiriman</p>
  </div>
  <div class="tracking-list">
    <?php
      for ($i=0; $i < count($history); $i++) {
    ?>
    <div class="tracking-item">
      <div class="tracking-icon status-delivery">
          <svg class="svg-inline--fa fa-shipping-fast fa-w-20" aria-hidden="true" data-prefix="fas" data-icon="shipping-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
              <path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path>
          </svg>
      </div>
      <?php
        $date = explode(" ", $history[$i]['date']);
        $desc = explode(" - ", $history[$i]['desc']);
      ?>
      <div class="tracking-date"><?php echo $date[0] ?><span><?php echo $date[1] ?></span></div>
      <div class="tracking-content"><?php echo $desc[0] ?><span><?php echo $desc[1] ?></span></div>
    </div>
    <?php } ?>
  </div>
</div>

<?php
}
} else if ($courier == "anteraja") {
  $result = $PT->AnterAja(''.$resi.'');
  $data = $result['data'];
  $history = $data['tracking'];
  if ($result['result'] == false) {
    echo '<div class="alert alert-info">Maaf, nomor resi <b>'.$resi.'</b> tidak ditemukan. Atau sistem kami sedang mengalami masalah karena terlalu banyak yang melakukan pencarian secara bersamaan, silahkan ulangi kembali / tunggu beberapa saat lagi.</div>';
  } else {
?>

<h3 class="top_title"><?php echo $data['courier'] ?></h3>
<h5 style="margin:0 0 5px 0;">I. Informasi Pengiriman</h5>
<table class="table">
    <tbody>
        <tr>
            <td width="130">No Resi</td>
            <td>:</td>
            <td><b><?php echo $data['waybill'] ?></b></td>
        </tr>
        <tr>
            <td>Service</td>
            <td>:</td>
            <td><b><?php echo $data['service'] ?></b></td>
        </tr>
        <tr>
            <td valign="top">Dikirim tanggal</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['date'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim dari</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['name']."<br>".$data['shipped']['addr'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim ke</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['received']['name']."<br>".$data['received']['addr'] ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>:</td>
            <td><b><?php $pecah = explode("-", $history[4]['desc']); echo $pecah[1] ?></b></td>
        </tr>
    </tbody>
</table>
<div id="tracking">
  <div class="text-center tracking-status-delivery">
    <p class="tracking-status text-tight">History Pengiriman</p>
  </div>
  <div class="tracking-list">
    <?php
      for ($i=0; $i < count($history); $i++) {
    ?>
    <div class="tracking-item">
      <div class="tracking-icon status-delivery">
          <svg class="svg-inline--fa fa-shipping-fast fa-w-20" aria-hidden="true" data-prefix="fas" data-icon="shipping-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
              <path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path>
          </svg>
      </div>
      <?php
        $date = explode(" ", $history[$i]['date']);
        $desc = explode(" - ", $history[$i]['desc']);
      ?>
      <div class="tracking-date"><?php echo $date[0] ?><span><?php echo $date[1] ?></span></div>
      <div class="tracking-content"><?php echo $desc[0] ?><span><?php echo $desc[1] ?></span></div>
    </div>
    <?php } ?>
  </div>
</div>

<?php
}
} else if ($courier == "ninja") {
  $result = $PT->Ninja(''.$resi.'');
  $data = $result['data'];
  $history = $data['tracking'];
  if ($result['result'] == false) {
    echo '<div class="alert alert-info">Maaf, nomor resi <b>'.$resi.'</b> tidak ditemukan. Atau sistem kami sedang mengalami masalah karena terlalu banyak yang melakukan pencarian secara bersamaan, silahkan ulangi kembali / tunggu beberapa saat lagi.</div>';
  } else {
?>

<h3 class="top_title"><?php echo $data['courier'] ?></h3>
<h5 style="margin:0 0 5px 0;">I. Informasi Pengiriman</h5>
<table class="table">
    <tbody>
        <tr>
            <td width="130">No Resi</td>
            <td>:</td>
            <td><b><?php echo $data['waybill'] ?></b></td>
        </tr>
        <tr>
            <td>Service</td>
            <td>:</td>
            <td><b><?php echo $data['service'] ?></b></td>
        </tr>
        <tr>
            <td valign="top">Dikirim tanggal</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['date'] ?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim dari</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['name']?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim ke</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['received']['name']?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>:</td>
            <td><b><?php echo $data['status'] ?></b></td>
        </tr>
    </tbody>
</table>
<?php
if ($data['status'] == "Completed") {
  $label = "delivered";
} else {
  $label = "delivery";
}
?>
<div id="tracking">
  <div class="text-center tracking-status-<?php echo $label ?>">
    <p class="tracking-status text-tight">History Pengiriman</p>
  </div>
  <div class="tracking-list">
    <?php
      for ($i=0; $i < count($history); $i++) {
    ?>
    <div class="tracking-item">
      <div class="tracking-icon status-<?php echo $label ?>">
          <svg class="svg-inline--fa fa-shipping-fast fa-w-20" aria-hidden="true" data-prefix="fas" data-icon="shipping-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
              <path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path>
          </svg>
      </div>
      <?php
        $date = explode(" ", $history[$i]['date']);
        $desc = explode(" - ", $history[$i]['desc']);
      ?>
      <div class="tracking-date"><?php echo $date[0] ?><span><?php echo $date[1] ?></span></div>
      <div class="tracking-content"><?php echo $desc[0] ?><span><?php echo $desc[1] ?></span></div>
    </div>
    <?php } ?>
  </div>
</div>

<?php
}
} else if ($courier == "lion") {
  $result = $PT->Lion(''.$resi.'');
  $data = $result['data'];
  $history = $data['tracking'];
  if ($result['result'] == false) {
    echo '<div class="alert alert-info">Maaf, nomor resi <b>'.$resi.'</b> tidak ditemukan. Atau sistem kami sedang mengalami masalah karena terlalu banyak yang melakukan pencarian secara bersamaan, silahkan ulangi kembali / tunggu beberapa saat lagi.</div>';
  } else {
?>

<h3 class="top_title"><?php echo $data['courier'] ?></h3>
<h5 style="margin:0 0 5px 0;">I. Informasi Pengiriman</h5>
<table class="table">
    <tbody>
        <tr>
            <td width="130">No Resi</td>
            <td>:</td>
            <td><b><?php echo $data['waybill'] ?></b></td>
        </tr>
        <tr>
            <td valign="top">Pengirim</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['name']?></td>
        </tr>
        <tr>
            <td valign="top">Dikirim tanggal</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped']['date'] ?></td>
        </tr>
    </tbody>
</table>
<div id="tracking">
  <div class="text-center tracking-status-delivery">
    <p class="tracking-status text-tight">History Pengiriman</p>
  </div>
  <div class="tracking-list">
    <?php
      for ($i=0; $i < count($history); $i++) {
    ?>
    <div class="tracking-item">
      <div class="tracking-icon status-delivery">
          <svg class="svg-inline--fa fa-shipping-fast fa-w-20" aria-hidden="true" data-prefix="fas" data-icon="shipping-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
              <path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path>
          </svg>
      </div>
      <div class="tracking-date"><?php echo $history[$i]['date'] ?><span></span></div>
      <div class="tracking-content"><?php echo $history[$i]['desc'] ?><span></span></div>
    </div>
    <?php } ?>
  </div>
</div>

<?php
}
} else if ($courier == "lex") {
  $result = $PT->LEX(''.$resi.'','id');
  $data = $result['data'];
  $history = $data['tracking'];
  if ($result['result'] == false) {
    echo '<div class="alert alert-info">Maaf, nomor resi <b>'.$resi.'</b> tidak ditemukan. Atau sistem kami sedang mengalami masalah karena terlalu banyak yang melakukan pencarian secara bersamaan, silahkan ulangi kembali / tunggu beberapa saat lagi.</div>';
  } else {
?>

<h3 class="top_title"><?php echo $data['courier'] ?></h3>
<h5 style="margin:0 0 5px 0;">I. Informasi Pengiriman</h5>
<table class="table">
    <tbody>
        <tr>
            <td width="130">No Resi</td>
            <td>:</td>
            <td><b><?php echo $data['waybill'] ?></b></td>
        </tr>
        <tr>
            <td valign="top">Pembuatan Pesanan</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['shipped'] ?></td>
        </tr>
        <tr>
            <td valign="top">Status</td>
            <td valign="top">:</td>
            <td valign="top"><?php echo $data['status'] ?></td>
        </tr>
    </tbody>
</table>
<?php
if ($data['status'] == "Terkirim") {
  $label = "delivered";
} else {
  $label = "delivery";
}
?>
<div id="tracking">
  <div class="text-center tracking-status-<?php echo $label ?>">
    <p class="tracking-status text-tight">History Pengiriman</p>
  </div>
  <div class="tracking-list">
    <?php
      for ($i=0; $i < count($history); $i++) {
    ?>
    <div class="tracking-item">
      <div class="tracking-icon status-<?php echo $label ?>">
          <svg class="svg-inline--fa fa-shipping-fast fa-w-20" aria-hidden="true" data-prefix="fas" data-icon="shipping-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
              <path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path>
          </svg>
      </div>
      <div class="tracking-date"><?php echo $history[$i]['date'] ?><span></span></div>
      <div class="tracking-content"><?php echo $history[$i]['desc'] ?><span></span></div>
    </div>
    <?php } ?>
  </div>
</div>

<?php
}
} else {
  // echo 'its work';
  require '403.html';
}
?>
