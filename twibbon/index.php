<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<title>Trial Twibbon</title>
</head>
<body style="background: #f9f9f9">
	<nav class="navbar navbar-light bg-white shadow-sm">
		<div class="container">
			<a class="navbar-brand" href="http://faridfac.herokuapp.com/twibbon/">
				<img src="wfi.jpg" width="30" height="30">
				<span class="navbar-brand mb-0 h1">Trial Twibbon</span>
			</a>
		</div>
	</nav>
	<div class="container my-5">
		<div class="row">
			<div class="col-lg-6 offset-lg-3 col-md-12 mb-4">
				<div class="card card-body shadow-sm mb-4">
					<div class="alert alert-info" role="alert">
						<small>Tips: <br>- Gunakan ukuran foto 1:1 untuk hasil terbaik<br>- Max file 5MB</small>
					</div>
					<form method="post" enctype="multipart/form-data">
						<div class="form-group">
                <label><b>Upload foto</b></label>
								<input type="file" name="file" id="image" accept="image/*" class="p-1 img-thumbnail btn-block">
            </div>
						<button type="submit" name="submit" class="btn btn-primary btn-sm">
							Buat Twibbon!
						</button>
						<a href="#" title="Cara penggunaan" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal" style="color:#fff;">
							Cara penggunaan
						</a>
					</form>
				</div>
				<div class="card card-body shadow-sm">
					<div class="font-weight-light">
						<b>RESULT :</b><br>
						<?php
						if (isset($_POST['submit']))
						{
							$namafile       = $_FILES["file"]["name"];
							$filegambar     = substr($namafile, 0, strripos($namafile, '.'));
							$ekstensifile   = substr($namafile, strripos($namafile, '.'));
							$ukuranfile     = $_FILES["file"]["size"];
							$jenisfile      = array('.jpg','.jpeg','.png','.bmp', '.JPG','.JPEG','.PNG','.BMP');

							if (!empty($filegambar))
							{
								if ($ukuranfile <= 5000000)
								{
									if (in_array($ekstensifile, $jenisfile) && ($ukuranfile <= 500000))
									{
										$namabaru = time().'_'.uniqid().'_'.date("Ymdhis").'_n'.$ekstensifile;
										if (file_exists("images/" . $namabaru))
										{
											echo '<script>';
											echo 'alert("Terjadi kesalahan, silahkan coba lagi");';
											echo '</script>';
										}
										else
										{
											move_uploaded_file($_FILES["file"]["tmp_name"], "images/" . $namabaru);
											$gambar = "images/" . $namabaru;
											echo "<br>";
											echo '<img src="'.$gambar.'" id="img1" width="500px" height="500px" hidden="true" class="img-fluid">';
											echo '<img src="twib.png" id="img2" width="500px" height="500px" hidden="true" class="img-fluid">';
											echo '<h2><canvas id="canvas" class="img-fluid"></canvas></h2>';
											echo '<a id="download" class="btn btn-primary mb-3 text-white">Download gambar</a>';
										}
									}
									else
									{
										echo '<script>';
										echo 'alert("File yang diupload harus gambar");';
										echo '</script>';
										unlink($_FILES["file"]["tmp_name"]);
									}
								}
								else
								{
									echo '<script>';
									echo 'alert("Ukuran file tidak boleh lebih dari 5MB");';
									echo '</script>';
								}
							}
							else
							{
								echo '<script>';
								echo 'alert("Gambar tidak boleh kosong");';
								echo '</script>';
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal modal-fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modallabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<b>Cara penggunaan</b>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<i>Upload foto yang ingin kamu jadikan twibbon, pilih file dan klik Buat Twibbon!.</i>
				</div>
			</div>
		</div>
	</div>

	<script>
		window.onload = function () {
			var img1 = document.getElementById('img1');
			var img2 = document.getElementById('img2');
			var canvas = document.getElementById("canvas");
			var context = canvas.getContext("2d");
			var width = img2.width;
			var height = img2.height;
			canvas.width = width;
			canvas.height = height;

			context.drawImage(img1, 0, 1, width, height);
			var image1 = context.getImageData(0, 0, width, height);
			var imageData1 = image1.data;
			context.drawImage(img2, 0, 0, width, height);
			var image2 = context.getImageData(0, 0, width, height);
			var imageData2 = image2.data;
		};

		function downloadCanvas(link, canvasId, filename) {
			link.href = document.getElementById(canvasId).toDataURL();
			link.download = filename;
		}

		document.getElementById('download').addEventListener('click', function() {
			downloadCanvas(this, 'canvas', 'wfi-twibbon.png');
		}, false);

	</script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
