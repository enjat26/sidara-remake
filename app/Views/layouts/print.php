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
        <meta name="site-url" content="<?= panel_url();?>">
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

        <!-- Page Css-->
        <?= $this->renderSection('stylesheet');?>

        <!-- App Css-->
        <link id="bootstrap-style" href="<?= $configIonix->assetsFolder['panel']['stylesheet'];?>bootstrap.min.css" rel="stylesheet" type="text/css" />
        <?= link_tag($configIonix->assetsFolder['panel']['stylesheet'].'icons.min.css');?>
        <link id="app-style" href="<?= $configIonix->assetsFolder['panel']['stylesheet'];?>app.min.css" rel="stylesheet" type="text/css" />
        <?= link_tag($configIonix->assetsFolder['panel']['stylesheet'].'custom.css');?>

        <!-- App favicon -->
        <?= link_tag(core_url('favicon.ico'), 'shortcut icon', 'image/ico');?>

    </head>

    <body class="bg-white">

        <div class="row justify-content-center">
            <div class="col-12">
                <table class="table table-borderless w-100">
                    <tr>
                        <div class="media">
                            <img src="<?= $configIonix->appLogo['square_dark'];?>" alt="" width="100">
                            <div class="media-body my-auto">
                                <div class="text-center">
                                    <h1 class="text-truncate mb-0"><?= $companyData->name;?></h1>
                                    <address class="mb-0">
                                        <?= parseAddress($companyData, true, false);?>
                                    </address>
                                    <address class="mb-0">
                                        Email: <?= $companyData->email;?> | Telp: <?= $companyData->phone;?>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </tr>
                </table>
            </div>
        </div>

        <hr class="mb-0" style="border: 3px solid;opacity: 1!important;margin-top: -5px">
        <hr class="mt-1" style="border: 1px solid;opacity: 1!important;">

        <?= $this->renderSection('content');?>

        <hr>

        <footer>
            <div class="my-md-auto">
                <p class="text-muted text-center"><?= showCopyright();?></p>
            </div>
        </footer>

        <!-- Plugin js-->
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'jquery/jquery.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'bootstrap/js/bootstrap.bundle.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'metismenu/metisMenu.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'simplebar/simplebar.min.js');?>
        <?= script_tag($configIonix->assetsFolder['panel']['library'].'node-waves/waves.min.js');?>

        <!-- Page js-->
        <?= $this->renderSection('javascript');?>

        <!-- App js -->
        <?= script_tag($configIonix->assetsFolder['panel']['javascript'].'app.js');?>

    </body>
</html>
