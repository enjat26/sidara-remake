<?= $this->extend($configIonix->viewLayout['print']); ?>

<?= $this->section('meta'); ?>

<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>
<style>
    body {
        -webkit-print-color-adjust: exact;
    }

    @page {
        size: <?= $data['paperSize']; ?>;
        margin: 9mm;
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
        padding: 12px 15px;
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
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center my-md-3">
    <div class="col-6">
        <div class="text-center">
            <h4 class="mb-1"><strong>Data Prestasi Olahraga</strong></h4>

            <?php if (empty($request->getGet('filter-level')) && !empty($request->getGet('filter-year'))) : ?>
                <h5>(Pada Tahun <?= $request->getGet('filter-year'); ?>)</h5>
            <?php endif; ?>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<div class="row mt-4">
    <div class="col-md-12 col-xl-12">
        <table class="table styled-table align-middle w-100">
            <thead class="">
                <tr>
                    <th scope="col" class="text-center align-middle" rowspan="2">No</th>
                    <th scope="col" class="text-center align-middle" rowspan="2">Nama Atlet</th>
                    <th scope="col" class="text-center align-middle" rowspan="2">Cabang Olaharaga</th>
                    <th scope="col" class="text-center align-middle" colspan="4">Kejuaraan</th>
                    <th scope="col" class="text-center align-middle" rowspan="2">Medali</th>
                </tr>
                <tr>
                    <th scope="col" class="text-center">Nama Kejuaraan</th>
                    <th scope="col" class="text-center">Nomor/Kelas</th>
                    <th scope="col" class="text-center">Hasil</th>
                    <th scope="col" class="text-center">Tahun</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;

                if (isStakeholder() == true) {
                    $where = [
                        'sport_achievement_created_by' => $userData->user_id,
                        'sport_achievement_approve' => 3
                    ];
                } else {
                    $where = ['sport_achievement_approve' => 3];
                }
                ?>
                <?php foreach ($data['modAchievement']->fetchData($where, false, 'CUSTOM')->like($data['like'])->orderBy('sport_championship_year', 'ASC')->get()->getResult() as $row) : ?>
                    <tr>
                        <th scope="row" class="text-center"><strong><?= $i++; ?>.</strong></th>
                        <td>
                            <div class="media">
                                <div class="media-body overflow-hidden my-auto">
                                    <h5 class="text-truncate font-size-14 mb-1"><?= $row->sport_atlet_name; ?></h5>
                                    <p class="text-muted mb-0"><?= $row->sport_atlet_code; ?></p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="media">
                                <div class="media-body overflow-hidden my-auto">
                                    <h5 class="text-truncate font-size-14 mb-1"><?= $row->sport_cabor_name; ?></h5>
                                    <p class="text-muted mb-0">Jenis: <?= $row->sport_cabor_type_name; ?></p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <h6 class="text-truncate mb-0"><?= $row->sport_championship_name; ?></h6>
                        </td>
                        <td class="text-center"><?= $row->sport_achievement_number ? $row->sport_achievement_number : '-'; ?></td>
                        <td class="text-center"><?= $row->sport_achievement_result ? $row->sport_achievement_result : '-'; ?></td>
                        <td class="text-center"><strong><?= $row->sport_championship_year; ?></strong></td>
                        <td class="text-center"><?= $row->sport_medal_name; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th scope="row" colspan="7" class="text-end">Jumlah Prestasi</th>
                    <td class="text-center"><strong><?= $data['modAchievement']->fetchData($where)->like($data['like'])->countAllResults(); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-12 col-xl-12">
        <div class="text-end">
            <small><i>*Dicetak pada <?= parseDate(now(), 'dS F Y - g:i A T') ?></i></small>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row mt-4">
    <div class="col-md-12 col-xl-12">
        <table class="table table-bordered align-middle w-100" style="border: 2px solid black">
            <tbody>
                <tr>
                    <td scope="row" width="100%">
                        <p class="text-muted text-center mb-0"><strong><i>Ini adalah cetakan komputer, tanda tangan tidak diperlukan.</i></strong></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<table class="table table-bordered w-100" style="border: 1px solid black;opacity: 1!important;">
    <tr>
        <td class="my-auto">
            <p class="text-muted">
                <small>*Scan pada <code>Kode QR</code> dibawah ini untuk melihat tampilan <strong>Data Prestasi Olahraga</strong> pada <strong>Website</strong>.</small>
            </p>
            <div class="text-center">
                <img src="<?= $libIonix->generateQRCode($data['qrData'])->getDataUri(); ?>" alt="" class="img-thumbnail" width="150">
            </div>
        </td>

        <td width="30%" class="align-middle">
            <p class="text-muted text-start">Powered by:</p>
            <img src="<?= $configIonix->appLogo['landscape_dark']; ?>" alt="" height="60">
        </td>
    </tr>
</table>
<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<?php if (ENVIRONMENT !== 'development') : ?>
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                document.title = "Database Prestasi (<?= parseDate(time()); ?>)";
                window.print();
            }, 2e3)
        });
    </script>
<?php endif; ?>

<script type="text/javascript">
    'use strict'

    var css = '@page { size: <?= $data['paperSize']; ?>; margin: 11mm 17mm 17mm 17mm; }',
        head = document.head || document.getElementsByTagName('head')[0],
        style = document.createElement('style');

    style.type = 'text/css';
    style.media = 'print';

    if (style.styleSheet) {
        style.styleSheet.cssText = css;
    } else {
        style.appendChild(document.createTextNode(css));
    }

    head.appendChild(style);
</script>
<?= $this->endSection(); ?>