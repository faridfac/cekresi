<?php require 'lib/header.php'; ?>
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">DOKUMENTASI</h3>
            </div>
            <div class="panel-body">
              <h4>Request</h4>
              <section id="waybill-request">
                <ul class="nav nav-tabs ro-doc-tabs">
                    <li class="active"><a data-toggle="tab" href="#waybill-request-url">URL</a></li>
                    <li class=""><a data-toggle="tab" href="#waybill-request-parameter">Parameter</a></li>
                    <li class=""><a data-toggle="tab" href="#waybill-request-example">Contoh request</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="waybill-request-url">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <td>Method</td>
                                <td>URL</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>POST</td>
                                <td>https://faridfac.herokuapp.com/api/waybill</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="waybill-request-parameter">
                        <table class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                  <td>Parameter</td>
                                  <td>keterangan</td>
                              </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>waybill</td>
                                <td>Nomor resi</td>
                            </tr>
                            <tr>
                                <td>courier</td>
                                <td>Kode kurir: jne, jnt, tiki, wahana, sicepat, ninja, lion, anteraja, lex</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="waybill-request-example">
                        <iframe src="//api.apiembed.com/?source=https://faridfac.herokuapp.com/api/wb.json&targets=php:curl,node:unirest,java:unirest,python:requests,ruby:native,objc:nsurlsession,go:native" frameborder="0" scrolling="no" width="100%" height="500px" seamless=""></iframe>
                    </div>
                </div>
              </section>
              <h4>Response</h4>
              <section id="waybill-response">
                <ul class="nav nav-tabs ro-doc-tabs">
                    <li class="active"><a data-toggle="tab" href="#waybill-response-success">Response Sukses</a></li>
                    <li class=""><a data-toggle="tab" href="#waybill-response-failed">Response Gagal</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="waybill-response-success">
                        <script src="https://gist.github.com/faridfac/8f6137394ac370e7c1aa49533894f9ea.js"></script>
                    </div>
                    <div class="tab-pane fade" id="waybill-response-failed">
                      <script src="https://gist.github.com/faridfac/3c12dd4e4a271cc2ffcb1681a02ecaaf.js"></script>
                    </div>
                </div>
              </section>
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
