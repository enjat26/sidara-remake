<?= doctype();?>
<html lang="id">

      <head>

        <meta charset="utf-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex, nofollow">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="application-name" content="<?= $configIonix->appCode;?>">
        <meta name="apple-mobile-web-app-title" content="<?= $title;?>">
        <meta name="msapplication-starturl" content="<?= core_url();?>">
        <title><?= $title;?></title>
        <meta name="site-url" content="<?= core_url();?>">
        <?= csrf_meta();?>
        <?= $this->renderSection('meta');?>
        <meta name="description" content="<?= $libIonix->getCompanyData()->description;?>">
        <meta name="keywords" content="<?= $libIonix->getCompanyData()->tags;?>">
        <meta name="author" content="Uben Wisnu">
        <meta property="og:locale" content="id_ID" />
        <meta property="og:url" content="<?= core_url();?>"/>
        <meta property="og:media" content="<?= $configIonix->mediaFolder['default'];?>"/>
        <meta property="og:type" content="<?= $libIonix->getCompanyData()->type;?>"/>
        <meta property="og:site_name" content="<?= $title;?>">
        <meta property="og:title" content="<?= $title;?>"/>
        <meta property="og:description" content="<?= $libIonix->getCompanyData()->description;?>"/>
        <meta property="og:image" content="<?= core_url('favicon.ico');?>"/>
        <meta property="og:image:width" content="800" />
        <meta property="og:image:height" content="800" />

        <!-- Plugin Css-->
        <?= link_tag($configIonix->assetsFolder['panel']['library'].'izitoast/dist/css/iziToast.min.css');?>
        <?= link_tag($configIonix->assetsFolder['panel']['library'].'sweetalert2/sweetalert2.min.css');?>
        <?= link_tag($configIonix->assetsFolder['pages']['library'].'animate/animate.compat.css');?>
        <?= link_tag($configIonix->assetsFolder['pages']['library'].'owl.carousel/assets/owl.carousel.min.css');?>
        <?= link_tag($configIonix->assetsFolder['pages']['library'].'owl.carousel/assets/owl.theme.default.min.css');?>
        <?= link_tag($configIonix->assetsFolder['panel']['library'].'magnific-popup/magnific-popup.css');?>

        <!-- Web Fonts  -->
      	<link id="googleFonts" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800%7CShadows+Into+Light&display=swap" rel="stylesheet" type="text/css">

        <!-- Page Css-->
        <?= $this->renderSection('stylesheet');?>

        <!-- App Css-->
        <link id="bootstrap-style" href="<?= $configIonix->assetsFolder['panel']['stylesheet'];?>bootstrap.min.css" rel="stylesheet" type="text/css" />
        <?= link_tag($configIonix->assetsFolder['pages']['stylesheet'].'theme.css');?>
        <?= link_tag($configIonix->assetsFolder['pages']['stylesheet'].'theme-elements.css');?>
        <?= link_tag($configIonix->assetsFolder['pages']['stylesheet'].'theme-blog.css');?>
        <?= link_tag($configIonix->assetsFolder['pages']['stylesheet'].'theme-shop.css');?>
        <?= link_tag($configIonix->assetsFolder['panel']['stylesheet'].'icons.min.css');?>
        <link id="skinCSS" href="<?= $configIonix->assetsFolder['pages']['stylesheet'];?>style.css" rel="stylesheet" type="text/css" />
        <?= link_tag($configIonix->assetsFolder['pages']['stylesheet'].'custom.css');?>
        <?= link_tag($configIonix->assetsFolder['panel']['stylesheet'].'custom.css');?>

        <!-- App favicon -->
        <?= link_tag(core_url('favicon.ico'), 'shortcut icon', 'image/ico');?>

        <!-- Started Js -->
        <?= script_tag($configIonix->assetsFolder['pages']['library'].'modernizr/modernizr.min.js');?>

    </head>

    <body data-plugin-page-transition>

        <div class="body">
            <header id="header" class="header-effect-shrink" data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyChangeLogo': true, 'stickyStartAt': 120, 'stickyHeaderContainerHeight': 70}">
                <div class="header-body border-top-0">
                    <div class="header-top">
                        <div class="container">
                            <div class="header-row py-2">
                                <div class="header-column justify-content-start">
                                    <div class="header-row">
                                        <nav class="header-nav-top">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item nav-item-anim-icon d-none d-md-block">
                                                    <a class="nav-link ps-0" href="<?= core_url('youths');?>"><i class="fas fa-angle-right"></i> Kepemudaan</a>
                                                </li>
                                                <li class="nav-item nav-item-anim-icon d-none d-md-block">
                                                    <a class="nav-link" href="<?= core_url('sports');?>"><i class="fas fa-angle-right"></i> Olahraga</a>
                                                </li>
                                                <li class="nav-item nav-item-left-border nav-item-left-border-remove nav-item-left-border-sm-show">
                                                    <span class="ws-nowrap">
                                                      <?php if (isLoggedIn() == true): ?>
                                                              <i class="fas fa-user"></i>
                                                              <a href="<?= panel_url('dashboard');?>">Logged in as <?= $userData->name;?></a>
                                                          <?php else: ?>
                                                              <i class="fas fa-sign-in-alt"></i>
                                                              <a href="<?= core_url('login');?>">Login</a>
                                                      <?php endif; ?>
                                                    </span>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                                <?php if ($libIonix->builderQuery('social_media')->join('social_provider', 'social_provider.sosprov_id = social_media.sosprov_id')->where(['user_id' => NULL])->countAllResults() > 0): ?>
                                    <div class="header-column justify-content-end">
                                        <div class="header-row">
                                            <?php if ($libIonix->builderQuery('social_media')->join('social_provider', 'social_provider.sosprov_id = social_media.sosprov_id')->where(['user_id' => NULL])->countAllResults() > 0): ?>
                                                <ul class="header-social-icons social-icons d-none d-sm-block social-icons-clean">
                                                    <?php foreach ($libIonix->getQuery('social_media', ['social_provider' => 'social_provider.sosprov_id = social_media.sosprov_id'], ['user_id' => NULL])->getResult() as $row): ?>
                                                        <li class="social-icons-<?= $row->sosprov_name;?>">
                                                            <a href="<?= $row->sosprov_url.$row->sosmed_key;?>" class="no-footer-css" target="_blank" title="<?= $row->sosprov_name;?>"><i class="fab fa-<?= $row->sosprov_name;?>"></i></a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="header-container container">
                        <div class="header-row">
                            <div class="header-column">
                                <div class="header-row">
                                    <div class="header-logo">
                                        <a href="<?= core_url();?>">
                                            <img src="<?= $configIonix->appLogo['landscape_dark'];?>" alt="<?= $companyData->name;?>" height="48" data-sticky-height="40">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="header-column justify-content-end">
                                <div class="header-row">
                                    <div class="header-nav header-nav-line header-nav-top-line header-nav-top-line-with-border order-2 order-lg-1">
                                        <div class="header-nav-main header-nav-main-square header-nav-main-effect-2 header-nav-main-sub-effect-1">
                                            <nav class="collapse">
                                                <ul class="nav nav-pills" id="mainNav">
                                                    <li><a href="<?= core_url();?>">Beranda</a></li>
                                                    <li class="dropdown">
                                                        <a href="<?= core_url('youths');?>" class="dropdown-item dropdown-toggle">Kepemudaan</a>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('youths/youths');?>">Pemuda/i</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('youths/management_organizations');?>">Organisasi Pengelola</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('youths/organizations');?>">Organisasi</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('youths/trainings');?>">Pelatihan</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('youths/achievements');?>">Prestasi</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('youths/assets');?>">Sarana dan Prasarana</a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="dropdown">
                                                        <a href="<?= core_url('sports');?>" class="dropdown-item dropdown-toggle">Olahraga</a>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('sports/management_organizations');?>">Organisasi Pengelola</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('sports/organizations');?>">Organisasi</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('sports/clubs');?>">Klub</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('sports/certifications');?>">Sertifikasi</a>
                                                            </li>
                                                            <li>
                                                              <a class="dropdown-item" href="<?= core_url('sports/cabors');?>">Cabang Olahraga</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('sports/atlets');?>">Atlet</a>
                                                            </li>
                                                            <li class="dropdown-submenu">
                                                                <a href="<?= core_url('sports/achievements');?>" class="dropdown-item">Prestasi</a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="<?= core_url('sports/championships');?>">Kejuaraan</a></li>
                                                                    <li><a class="dropdown-item" href="<?= core_url('sports/achievements');?>">Olahraga</a></li>
                                                                </ul>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= core_url('sports/assets');?>">Sarana dan Prasarana</a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>

                                        <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav"><i class="fas fa-bars"></i></button>
                                    </div>

                                    <?= $this->renderSection('search');?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div role="main" class="main">
                <?= $this->renderSection('content');?>
            </div>

            <footer id="footer" class="bg-color-dark-scale-2 border border-end-0 border-start-0 border-bottom-0 border-color-light-3 mt-0">
          			<div class="container text-center my-3 py-5">
            				<a href="<?= core_url();?>">
              					<img src="<?= $configIonix->appLogo['landscape_light'];?>" alt="<?= $companyData->name;?>" height="48" class="mb-4">
            				</a>
            				<p class="text-4 mb-4">
                        <?= $companyData->description;?> <br>
                        Email: <a href="mailto:<?= $companyData->email;?>" class="text-color-light text-decoration-none"><?= $companyData->email;?></a> | Telepon: <a href="tel:<?= $companyData->phone;?>" class="text-color-light text-decoration-none"><?= $companyData->phone;?></a>
                    </p>

                    <ul class="social-icons social-icons-big social-icons-dark-2">
                        <?php foreach ($libIonix->getQuery('social_media', ['social_provider' => 'social_provider.sosprov_id = social_media.sosprov_id'], ['user_id' => NULL])->getResult() as $row): ?>
                            <li class="social-icons-instagram">
                                <a href="<?= $row->sosprov_url.$row->sosmed_key;?>" class="social-icons-<?= $row->sosprov_name;?>" target="_blank" title="<?= $row->sosprov_name;?>"><i class="mdi mdi-<?= $row->sosprov_name;?>"></i></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
          			</div>
          			<div class="copyright bg-dark py-4">
            				<div class="container text-center py-2">
              					<p class="mb-0 text-2"><?= showCopyright();?></p>
            				</div>
          			</div>
        		</footer>
        </div>
        <!-- end of body -->

        <!-- Plugin js-->
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'jquery/jquery.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'bootstrap/js/bootstrap.bundle.min.js');?>

        <!-- Library js-->
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'bootstrap-maxlength/bootstrap-maxlength.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'jquery-validation/jquery.validate.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'jquery-validation/localization/messages_id.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'izitoast/dist/js/iziToast.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'sweetalert2/sweetalert2.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'moment/min/moment-with-locales.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'magnific-popup/jquery.magnific-popup.min.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['library'].'jquery.appear/jquery.appear.min.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['library'].'jquery.easing/jquery.easing.min.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['library'].'jquery.cookie/jquery.cookie.min.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['library'].'lazysizes/lazysizes.min.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['library'].'isotope/jquery.isotope.min.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['library'].'owl.carousel/owl.carousel.min.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['library'].'vide/jquery.vide.min.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['library'].'vivus/vivus.min.js');?>

        <!-- Page js-->
        <?= $this->renderSection('javascript');?>

        <!-- App js -->
        <?= script_tag($configIonix->assetsFolder['local'].'js/alert.js');?>
        <?= script_tag($configIonix->assetsFolder['local'].'js/script.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['javascript'].'theme.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['javascript'].'theme.init.js');?>
        <?= script_tag($configIonix->assetsFolder['pages']['javascript'].'app.js');?>

        <script type="text/javascript">
            var notificationTone      = "<?= $configIonix->notificationTone;?>";
            var notificationRealtime  = "<?= $configIonix->notificationRealtime;?>";
        </script>

        <?php if ($session->getFlashdata('alertToastr')): ?>
          <script type="text/javascript">pushToastr('<?= $session->getFlashdata('alertToastr')['type'];?>', '<?= $session->getFlashdata('alertToastr')['header'];?>', '<?= $session->getFlashdata('alertToastr')['message'];?>');</script>
        <?php endif; ?>

        <?php if ($session->getFlashdata('alertSwal')): ?>
          <script type="text/javascript">pushSwal('<?= $session->getFlashdata('alertSwal')['type'];?>', '<?= ucwords($session->getFlashdata('alertSwal')['type']);?>', '<?= $session->getFlashdata('alertSwal')['message'];?>');</script>
        <?php endif; ?>

    </body>
</html>
