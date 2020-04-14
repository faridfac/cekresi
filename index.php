<?php
require 'lib/header.php';
?>
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">CEK RESI</h3>
            </div>
            <div class="panel-body">
                <div id="form_search">
                    <div class="form-group">
                        <label class="control-label">Nomor Resi</label>
                        <input class="form-control input-md" type="text" name="waybill" id="waybill">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Kurir</label>
                        <select class="form-control input-lg" name="courier" id="courier" required>
                            <option value="jne" selected>JNE</option>
		                        <option value="jnt">J&T</option>
                            <option value="tiki">TIKI</option>
                            <option value="wahana">Wahana</option>
                            <option value="sicepat">Sicepat</option>
                            <option value="anteraja">Anteraja</option>
                            <option value="lion">Lion Parcel</option>
                            <option value="ninja">Ninja Xpress</option>
                            <option value="lex">Lazada Express</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-block btn-info btn-md" id="btnCheck" onclick="cek_resi()">Cek Resi</button>
                    <button type="button" class="btn btn-block btn-info btn-md disabled hidden" id="btnSearch"><i class="fal fa-spin fa-spinner-third"></i> Searching..</button>
                </div>
                <div id="form_result"></div>
            </div>
          </div>
        </div>
    </div><br>
		<div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div id="resi_result"></div>
        </div>
    </div>
<?php require 'lib/footer.php'; ?>
