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

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">

    <!-- Page Css-->
    <?= $this->renderSection('stylesheet'); ?>
    <style>
        @page {
            size: <?= $data['paperSize']; ?>;
            margin: 4mm;
            size: landscape;
        }

        .styled-table {
            border-collapse: collapse;
            font-size: 0.9em;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: <?= $configIonix->colorPrimaryCSS; ?>;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            border: 1px solid #74788d;
            padding: 8px 10px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid <?= $configIonix->colorPrimaryCSS; ?>;
        }

        .styled-table tbody tr.active-row {
            font-weight: bold;
            color: <?= $configIonix->colorPrimaryCSS; ?>;
        }

        .table>:not(caption)>*>* {
            padding: .3rem .75rem !important;
        }

        .text-center {
            text-align: center
        }

        .p-0 {
            padding: 0;
        }

        .m-0 {
            margin: 0;
        }

        .text-center {
            text-align: center
        }

        .text-end {
            text-align: right
        }

        .w-100 {
            width: 100;
        }

        .p-0 {
            padding: 0;
        }

        .m-0 {
            margin: 0;
        }

        .mb-0 {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <table style="width: 100%;">
        <tr>
            <td>
                <img src="<?= $configIonix->appLogo['square_dark']; ?>" alt="" width="100">
            </td>
            <td class="text-center">
                <h1 class="text-center p-0 m-0"><?= $companyData->name; ?></h1>
                <small><?= parseAddress($companyData, true, false); ?></small>
                <br>
                <small>Email: <?= $companyData->email; ?> | Telp: <?= $companyData->phone; ?></small>
            </td>
        </tr>
    </table>

    <hr class="mb-0" style="border: 3px solid;opacity: 1!important;margin-top: 0px">
    <hr class="mt-1" style="border: 1px solid;opacity: 1!important;">

    <?= $this->renderSection('content'); ?>

    <hr>

    <footer>
        <div class="my-md-auto">
            <p class="text-muted text-center"><?= showCopyright(); ?></p>
        </div>
    </footer>

</body>

</html>