<?= doctype(); ?>
<html lang="id">

<head>

    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="<?= $configIonix->appCode; ?>">
    <meta name="apple-mobile-web-app-title" content="<?= $title; ?>">
    <meta name="msapplication-starturl" content="<?= core_url(); ?>">
    <title><?= $title; ?></title>
    <meta name="site-url" content="<?= panel_url(); ?>">
    <?= csrf_meta(); ?>
    <?= $this->renderSection('meta'); ?>
    <meta name="description" content="<?= $libIonix->getCompanyData()->description; ?>">
    <meta name="keywords" content="<?= $libIonix->getCompanyData()->tags; ?>">
    <meta name="author" content="Uben Wisnu">
    <meta property="og:locale" content="id_ID" />
    <meta property="og:url" content="<?= core_url(); ?>" />
    <meta property="og:media" content="<?= $configIonix->mediaFolder['default']; ?>" />
    <meta property="og:type" content="<?= $libIonix->getCompanyData()->type; ?>" />
    <meta property="og:site_name" content="<?= $title; ?>">
    <meta property="og:title" content="<?= $title; ?>" />
    <meta property="og:description" content="<?= $libIonix->getCompanyData()->description; ?>" />
    <meta property="og:image" content="<?= core_url('favicon.ico'); ?>" />
    <meta property="og:image:width" content="800" />
    <meta property="og:image:height" content="800" />

    <!-- Plugin Css-->
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'izitoast/dist/css/iziToast.min.css'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'sweetalert2/sweetalert2.min.css'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'magnific-popup/magnific-popup.css'); ?>

    <!-- Datatable-->
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-bs4/css/dataTables.bootstrap4.min.css'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css'); ?>

    <!-- Page Css-->
    <?= $this->renderSection('stylesheet'); ?>

    <!-- App Css-->
    <link id="bootstrap-style" href="<?= $configIonix->assetsFolder['panel']['stylesheet']; ?>bootstrap.min.css" rel="stylesheet" type="text/css" />
    <?= link_tag($configIonix->assetsFolder['panel']['stylesheet'] . 'icons.min.css'); ?>
    <link id="app-style" href="<?= $configIonix->assetsFolder['panel']['stylesheet']; ?>app.min.css" rel="stylesheet" type="text/css" />
    <?= link_tag($configIonix->assetsFolder['panel']['stylesheet'] . 'custom.css'); ?>

    <!-- App favicon -->
    <?= link_tag(core_url('favicon.ico'), 'shortcut icon', 'image/ico'); ?>

</head>

<body id="body" data-sidebar="light">

    <?php if (ENVIRONMENT !== 'development') : ?>
        <!-- Preloader start-->
        <div id="preloader">
            <div id="status">
                <div class="spinner-chase">
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="<?= panel_url('dashboard'); ?>" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="<?= $configIonix->appLogo['square_dark']; ?>" alt="" height="40" style="margin-left: -9px" key="logo|square-dark">
                            </span>
                            <span class="logo-lg">
                                <img src="<?= $configIonix->appLogo['landscape_dark']; ?>" alt="" height="40" key="logo|landscape-dark">
                            </span>
                        </a>

                        <a href="<?= panel_url('dashboard'); ?>" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="<?= $configIonix->appLogo['square_light']; ?>" alt="" height="40" style="margin-left: -9px" key="logo|square-light">
                            </span>
                            <span class="logo-lg">
                                <img src="<?= $configIonix->appLogo['landscape_light']; ?>" alt="" height="40" key="logo|landscape-light">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>

                    <!-- App Search-->
                    <?= $this->renderSection('app-search'); ?>
                </div>

                <div class="d-flex">

                    <!-- Mobile Search-->
                    <?= $this->renderSection('mobile-search'); ?>

                    <div class="dropdown d-none d-lg-inline-block ms-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="mdi mdi-fullscreen"></i>
                        </button>
                    </div>

                    <!-- App notification-->
                    <?= $this->include('layouts/notifications'); ?>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php if (!$userData->avatar) : ?>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle" style="background-color: <?= hexToRGB($userData->role_color, 18); ?>; color: #<?= $userData->role_color; ?>"><?= substr($userData->name, 0, 1); ?></span>
                                    </div>
                                    <span class="d-none d-xl-inline-block ms-2 me-2" key="name"><?= parseFullName($userData->name, $userData->role_access); ?></span>
                                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                                </div>
                            <?php else : ?>
                                <img class="rounded-circle header-profile-user" src="<?= core_url('content/user/' . $libIonix->Encode($userData->uuid) . '/' . $libIonix->Encode($userData->avatar)); ?>" alt="">
                                <span class="d-none d-xl-inline-block ms-2 me-2" key="name"><?= parseFullName($userData->name, $userData->role_access); ?></span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            <?php endif; ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a href="<?= panel_url('profile'); ?>" class="dropdown-item"><i class="mdi mdi-account font-size-16 align-middle me-1"></i><span>Profil Pengguna</span></a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= panel_url('logout'); ?>" class="dropdown-item text-danger"><i class="mdi mdi-arrow-collapse-right font-size-16 align-middle text-danger me-1"></i><span>Keluar</span></a>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                            <i class="mdi mdi-palette-outline"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <div class="separator mt-3">
                        <h5 class="mb-0">Tahun <?= $session->year;?></h5>
                    </div>

                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <?php foreach ($dbDefault->query("SELECT * FROM menu_group WHERE group_id IN(SELECT group_id FROM menu_page INNER JOIN menu_access ON menu_access.menu_id = menu_page.menu_id WHERE role_access = '" . $userData->role_access . "')")->getResult() as $menuGroup) : ?>
                            <li class="menu-title"><?= $menuGroup->group_title; ?></li>
                            <?php foreach ($dbDefault->query("SELECT * FROM menu_page WHERE group_id = $menuGroup->group_id AND menu_parent = 0 AND menu_id IN(SELECT menu_id FROM menu_access WHERE role_access = '" . $userData->role_access . "') ORDER BY menu_order")->getResult() as $menuPages) : ?>
                                <?php if ($libIonix->getQuery('menu_page', NULL, ['menu_parent' => $menuPages->menu_id])->getNumRows() > 0) : ?>
                                    <li>
                                        <a href="<?= $menuPages->menu_link; ?>" class="waves-effect has-arrow">
                                            <i class="<?= $menuPages->menu_icon; ?>"></i>
                                            <span><?= ucwords($menuPages->menu_title); ?></span>
                                        </a>
                                        <ul class="sub-menu" aria-expanded="false">
                                            <?php foreach ($libIonix->getQuery('menu_page', NULL, ['menu_parent' => $menuPages->menu_id])->getResult() as $subMenu) : ?>
                                                <?php if ($libIonix->getQuery('menu_access', NULL, ['role_access' => $userData->role_access, 'menu_id' => $subMenu->menu_id])->getNumRows() > 0) : ?>
                                                    <li>
                                                        <a href="<?= panel_url($subMenu->menu_link); ?>">
                                                            <i class="mdi mdi-minus align-middle font-size-10"></i><span><?= ucwords($subMenu->menu_title); ?></span>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php else : ?>
                                    <li>
                                        <a href="<?= panel_url($menuPages->menu_link); ?>" class="waves-effect">
                                            <i class="<?= $menuPages->menu_icon; ?>"></i>
                                            <span><?= ucwords($menuPages->menu_title); ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <!-- break -->
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <?= $this->renderSection('content'); ?>

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php if ($configIonix->viewCopyright == true) : ?>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <?php if ($configIonix->viewCopyright == true) : ?>
                                    <?= showCopyright(); ?>
                                <?php else : ?>
                                    <div class="clearfix"></div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php if ($configIonix->viewVersion == true) : ?>
                                    <div class="text-right d-none d-sm-block">
                                        <?= showVersion(); ?>
                                    </div>
                                <?php else : ?>
                                    <div class="clearfix"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </footer>
            <?php endif; ?>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <?= $this->include('layouts/theme'); ?>

    <?= $this->renderSection('switcher'); ?>

    <!-- Plugin js-->
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'jquery/jquery.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap/js/bootstrap.bundle.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'metismenu/metisMenu.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'simplebar/simplebar.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'node-waves/waves.min.js'); ?>

    <!-- Library js-->
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-maxlength/bootstrap-maxlength.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'jquery-validation/jquery.validate.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'jquery-validation/localization/messages_id.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'izitoast/dist/js/iziToast.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'sweetalert2/sweetalert2.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'magnific-popup/jquery.magnific-popup.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'moment/min/moment-with-locales.min.js'); ?>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <!-- Datatable js -->
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net/js/jquery.dataTables.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-bs4/js/dataTables.bootstrap4.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-buttons/js/dataTables.buttons.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'jszip/jszip.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'pdfmake/build/pdfmake.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'pdfmake/build/vfs_fonts.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-buttons/js/buttons.html5.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-buttons/js/buttons.print.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-buttons/js/buttons.colVis.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-responsive/js/dataTables.responsive.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js'); ?>

    <!-- Page js-->
    <?= $this->renderSection('javascript'); ?>

    <!-- App js -->
    <?= script_tag($configIonix->assetsFolder['local'] . 'js/alert.js'); ?>
    <?= script_tag($configIonix->assetsFolder['local'] . 'js/script.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['javascript'] . 'app.js'); ?>

    <script type="text/javascript">
        var notificationTone = "<?= $configIonix->notificationTone; ?>";
        var notificationRealtime = "<?= $configIonix->notificationRealtime; ?>";
        var pusherAppKey = "<?= $configIonix->pusherAppKey; ?>";
        var pusherAppCluster = "<?= $configIonix->pusherAppCluster; ?>";
    </script>

    <?php if ($dbDefault->table('menu_access')->where(['menu_id' => $dbDefault->table('menu_page')->getWhere(['menu_link' => 'notifications'])->getRow()->menu_id, 'role_access' => $libIonix->getUserData(NULL, 'object')->role_access])->countAllResults() == true) : ?>
        <?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/notifications.init.js'); ?>
    <?php endif; ?>

    <?php if ($session->getFlashdata('welcome')) : ?>
        <script type="text/javascript">
            pushToastr('info', 'Welcome!', '<?= $session->getFlashdata('welcome'); ?>');
        </script>
    <?php endif; ?>

    <?php if ($session->getFlashdata('alertToastr')) : ?>
        <script type="text/javascript">
            pushToastr('<?= $session->getFlashdata('alertToastr')['type']; ?>', '<?= $session->getFlashdata('alertToastr')['header']; ?>', '<?= $session->getFlashdata('alertToastr')['message']; ?>');
        </script>
    <?php endif; ?>

    <?php if ($session->getFlashdata('alertSwal')) : ?>
        <script type="text/javascript">
            pushSwal('<?= $session->getFlashdata('alertSwal')['type']; ?>', '<?= $session->getFlashdata('alertSwal')['header']; ?>', '<?= $session->getFlashdata('alertSwal')['message']; ?>');
        </script>
    <?php endif; ?>

</body>

</html>
