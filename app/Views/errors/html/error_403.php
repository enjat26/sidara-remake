<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="utf-8"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= strtoupper(config('ionix')->appCode);?> <?= ucwords(config('ionix')->appType);?> - Halaman terlarang</title>
	<meta name="site-url" content="<?= BASE . PUBLICURL;?>">
	<meta name="author" content="Uben Wisnu">

	<!-- Google font-->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">

	<!-- App css -->
	<link id="bootstrap-style" href="<?= config('ionix')->assetsFolder['panel']['stylesheet'];?>bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="<?= config('ionix')->assetsFolder['panel']['stylesheet'];?>icons.min.css" rel="stylesheet" type="text/css" />
	<link id="app-style" href="<?= config('ionix')->assetsFolder['panel']['stylesheet'];?>app.min.css" rel="stylesheet" type="text/css" />
	<link href="<?= config('ionix')->assetsFolder['panel']['stylesheet'];?>custom.css" rel="stylesheet" type="text/css" />

	<!-- App favicon -->
	<link rel="shortcut icon" href="<?= BASE . PUBLICURL . 'favicon.ico';?>" type="image/ico">

	<style media="screen">
		body {
			background-image: url(<?= BASE . PUBLICURL . 'image/background/abstract.png';?>);
			background-size: cover;
			background-repeat: no-repeat;
			background-position: center;
			width: 100%;
			height: 100%;
		}
	</style>

</head>

<body>

	<div class="account-pages my-5 pt-sm-5">
			<div class="container">
					<div class="row justify-content-center">
							<div class="col-lg-12">
									<div class="text-center mb-5">
											<h1 class="display-2 fw-medium">4<i class="mdi mdi-lifebuoy mdi-spin text-danger display-2"></i>3</h1>
											<?php if (ENVIRONMENT !== 'development'): ?>
													<h3 class="text-uppercase">Halaman Terlarang</h3>
												<?php else: ?>
													<h3 class="text-uppercase"><?= esc($message);?></h3>
											<?php endif; ?>

											<div class="row justify-content-center">
												<div class="col-lg-7">
													<p>Maaf! Anda tidak memiliki akses untuk memuat halaman ini, halaman yang Anda coba akses mungkin memiliki akses terbatas. Jika Anda merasa ini adalah kesalahan, hubungi administrator</p>
												</div>
											</div>

											<div class="mt-3 text-center">
												<a class="btn btn-primary waves-effect waves-light me-2" href="javascript:void(0);" onclick="history.back();">Kembali ke halaman sebelumnya</a>
												<a class="btn btn-light waves-effect waves-light" href="<?= panel_url('logout');?>">Login kembali</a>
											</div>
									</div>
							</div>
					</div>
					<div class="row justify-content-center">
							<div class="col-md-8 col-xl-6">
									<div>
											<img src="<?= config('ionix')->mediaFolder['image'].'content/403.png';?>" alt="" class="img-fluid">
									</div>
							</div>
					</div>
			</div>
	</div>
	<!-- end account-pages -->

	<!-- Right Sidebar -->
  <div class="right-bar hidden">
      <div data-simplebar class="h-100">
          <div class="rightbar-title d-flex align-items-center px-3 py-4">
              <h5 class="m-0 me-2">Personalisasi</h5>

              <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                  <i class="mdi mdi-close noti-icon"></i>
              </a>
          </div>

          <!-- Settings -->
          <hr class="mt-0" />
          <h6 class="text-center mb-0" key="t-theme">Pilih Tema</h6>

          <div class="p-4">
              <div class="mb-2">
                  <img src="<?= config('ionix')->mediaFolder['image'].'preview/layout-1.jpg';?>" class="img-fluid img-thumbnail" alt="">
              </div>

              <div class="form-check form-switch mb-3">
                  <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" data-bsStyle="<?= config('ionix')->assetsFolder['panel']['stylesheet'].'bootstrap.min.css';?>" data-appStyle="<?= config('ionix')->assetsFolder['panel']['stylesheet'].'app.min.css';?>" checked>
                  <label class="form-check-label" for="light-mode-switch">Mode Terang</label>
              </div>

              <div class="mb-2">
                  <img src="<?= config('ionix')->mediaFolder['image'].'preview/layout-2.jpg';?>" class="img-fluid img-thumbnail" alt="">
              </div>
              <div class="form-check form-switch mb-3">
                  <input class="form-check-input theme-choice" type="checkbox" id="combination-mode-switch" data-bsStyle="<?= config('ionix')->assetsFolder['panel']['stylesheet'].'bootstrap.min.css';?>" data-appStyle="<?= config('ionix')->assetsFolder['panel']['stylesheet'].'app.min.css';?>">
                  <label class="form-check-label" for="combination-mode-switch">Mode Kombinasi</label>
              </div>

              <div class="mb-2">
                  <img src="<?= config('ionix')->mediaFolder['image'].'preview/layout-3.jpg';?>" class="img-fluid img-thumbnail" alt="">
              </div>
              <div class="form-check form-switch mb-5">
                  <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch" data-bsStyle="<?= config('ionix')->assetsFolder['panel']['stylesheet'].'bootstrap-dark.min.css';?>" data-appStyle="<?= config('ionix')->assetsFolder['panel']['stylesheet'].'app-dark.min.css';?>">
                  <label class="form-check-label" for="dark-mode-switch">Mode Gelap</label>
              </div>
          </div>

      </div>
      <!-- end slimscroll-menu-->
  </div>
  <!-- /Right-bar -->

  <!-- Right bar overlay-->
  <div class="rightbar-overlay"></div>

	<!-- Plugin js-->
	<script type="text/javascript" src="<?= config('ionix')->assetsFolder['panel']['library'];?>jquery/jquery.min.js"></script>
	<script type="text/javascript" src="<?= config('ionix')->assetsFolder['panel']['library'];?>bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="<?= config('ionix')->assetsFolder['panel']['library'];?>metismenu/metisMenu.min.js"></script>
	<script type="text/javascript" src="<?= config('ionix')->assetsFolder['panel']['library'];?>simplebar/simplebar.min.js"></script>
	<script type="text/javascript" src="<?= config('ionix')->assetsFolder['panel']['library'];?>node-waves/waves.min.js"></script>

</body>

</html>
