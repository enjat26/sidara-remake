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
    <div class="col-8">
        <div class="text-center">
            <h4 class="mb-1"><strong><?= $data['title']; ?></strong></h4>
            <p><?= $data['subTitle']; ?></p>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<div class="row mt-4">
    <div class="col-md-12 col-xl-12">
        <table class="table styled-table align-middle w-100">
            <thead>
                <tr>
                    <th scope="col" class="text-center align-middle" rowspan="2">No</th>
                    <th scope="col" class="text-center align-middle" rowspan="2">Nama Lengkap</th>
                    <th scope="col" class="text-center align-middle" rowspan="2">Jenis Kelamin</th>
                    <th scope="col" class="text-center align-middle" rowspan="2">Cabang Olahraga</th>
                    <th scope="col" class="text-center align-middle" colspan="3">Sertifikasi</th>
                    <th scope="col" class="text-center align-middle" rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th scope="col" class="text-center">Kategori</th>
                    <th scope="col" class="text-center">Tingkat</th>
                    <th scope="col" class="text-center">Tahun</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($data['modCertification']->fetchData()->get()->getResult() as $row) : ?>
                    <tr>
                        <th scope="row" class="text-center"><strong><?= $i++; ?>.</strong></th>
                        <td>
                            <h6 class="text-truncate mb-0"><?= $row->sport_certification_name; ?></h6>
                        </td>
                        <td class="text-center"><?= parseGender($row->sport_certification_gender); ?></td>
                        <td class="text-center">
                            <div class="media">
                                <div class="media-body overflow-hidden my-auto">
                                    <h5 class="text-truncate font-size-14 mb-0"><?= $row->sport_cabor_name; ?></h5>
                                </div>
                            </div>
                        </td>
                        <td class="text-center"><?= $row->sport_certification_category; ?></td>
                        <td class="text-center"><?= $row->sport_certification_level; ?></td>
                        <td class="text-center"><?= $row->sport_certification_year; ?></td>
                        <td class="text-center"><?= $row->sport_certification_explanation ? $row->sport_certification_explanation : '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th scope="row" colspan="7" class="text-end">Jumlah Peserta Sertifikasi</th>
                    <td class="text-center"><strong><?= $data['modCertification']->fetchData($data['parameters'])->countAllResults(); ?></strong></td>
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

<table class="table table-bordered w-100" style="border: 1px solid black;opacity: 1!important;">
    <tr>
        <td class="my-auto">
            <p class="text-muted">
                <small>*Scan pada <code>Kode QR</code> dibawah ini untuk melihat tampilan <strong>Data Organisasi</strong> pada <strong>Website</strong>.</small>
            </p>
            <div class="text-center">
                <img src="<?= $libIonix->generateQRCode($data['qrData'], NULL, $configIonix->QRSize)->getDataUri(); ?>" alt="" class="img-thumbnail" width="160">
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
                document.title = "<?= $data['fileName']; ?>";
                window.print();
            }, 2e3)
        });
    </script>
<?php endif; ?>
<?= $this->endSection(); ?>