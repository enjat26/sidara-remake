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

        <!-- Google font-->
    		<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">

        <!-- Plugin Css-->
        <?= link_tag($configIonix->assetsFolder['auth']['library'].'izitoast/dist/css/iziToast.min.css');?>
        <?= link_tag($configIonix->assetsFolder['auth']['library'].'sweetalert2/sweetalert2.min.css');?>
        <?= link_tag($configIonix->assetsFolder['panel']['library'].'magnific-popup/magnific-popup.css');?>

        <!-- Page Css-->
        <?= $this->renderSection('stylesheet');?>

        <!-- App Css-->
        <link id="bootstrap-style" href="<?= $configIonix->assetsFolder['auth']['stylesheet'];?>bootstrap.min.css" rel="stylesheet" type="text/css" />
        <?= link_tag($configIonix->assetsFolder['auth']['stylesheet'].'icons.min.css');?>
        <link id="app-style" href="<?= $configIonix->assetsFolder['auth']['stylesheet'];?>app.min.css" rel="stylesheet" type="text/css" />
        <?= link_tag($configIonix->assetsFolder['auth']['stylesheet'].'custom.css');?>

        <!-- App favicon -->
        <?= link_tag(core_url('favicon.ico'), 'shortcut icon', 'image/ico');?>

    </head>

    <body>

        <?= $this->renderSection('content');?>

        <?= $this->include('layouts/theme');?>

        <!-- Plugin js-->
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'jquery/jquery.min.js');?>
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'bootstrap/js/bootstrap.bundle.min.js');?>
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'metismenu/metisMenu.min.js');?>
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'simplebar/simplebar.min.js');?>
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'node-waves/waves.min.js');?>
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'moment/min/moment-with-locales.min.js');?>

        <!-- Library js-->
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'bootstrap-maxlength/bootstrap-maxlength.min.js');?>
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'jquery-validation/jquery.validate.min.js');?>
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'jquery-validation/localization/messages_id.min.js');?>
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'izitoast/dist/js/iziToast.min.js');?>
        <?= script_tag($configIonix->assetsFolder['auth']['library'].'sweetalert2/sweetalert2.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'magnific-popup/jquery.magnific-popup.min.js');?>

        <!-- Page js-->
        <?= $this->renderSection('javascript');?>

        <!-- App js -->
        <?= script_tag($configIonix->assetsFolder['local'].'js/alert.js');?>
        <?= script_tag($configIonix->assetsFolder['local'].'js/script.js');?>
        <?= script_tag($configIonix->assetsFolder['auth']['javascript'].'app.js');?>

        <script type="text/javascript">
            var notificationRealtime  = "<?= $configIonix->notificationRealtime;?>";
            var notificationTone      = "<?= $configIonix->notificationTone;?>";
        </script>

        <?php if ($session->getFlashdata('welcome')): ?>
          <script type="text/javascript">pushToastr('info', 'Welcome!', '<?= $session->getFlashdata('welcome');?>');</script>
        <?php endif; ?>

        <?php if ($session->getFlashdata('alertToastr')): ?>
          <script type="text/javascript">pushToastr('<?= $session->getFlashdata('alertToastr')['type'];?>', '<?= $session->getFlashdata('alertToastr')['header'];?>', '<?= $session->getFlashdata('alertToastr')['message'];?>');</script>
        <?php endif; ?>

        <?php if ($session->getFlashdata('alertSwal')): ?>
          <script type="text/javascript">pushSwal('<?= $session->getFlashdata('alertSwal')['type'];?>', '<?= ucwords($session->getFlashdata('alertSwal')['type']);?>', '<?= $session->getFlashdata('alertSwal')['message'];?>');</script>
        <?php endif; ?>

    </body>
</html>
