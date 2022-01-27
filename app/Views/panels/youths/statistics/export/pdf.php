<?= $this->extend($configIonix->viewLayout['pdf']); ?>

<?= $this->section('meta'); ?>

<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>

<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="text-center">
    <h2 class="mb-0"><strong><?= $data['title']; ?></strong></h2>
    <p class="m-0"><?= $data['subTitle']; ?></p>
</div>
<br>

<table class="table styled-table align-middle" style="width: 100%;">
    <thead>
        <tr>
            <th scope="col" class="text-center align-middle" rowspan="2" width="5%">No</th>
            <th scope="col" class="text-center align-middle" rowspan="2">Uraian</th>
            <th scope="col" class="text-center align-middle" rowspan="2">Kota/Kab</th>
            <th scope="col" class="text-center align-middle" colspan="3">Jumlah</th>
            <th scope="col" class="text-center align-middle" rowspan="2">Keterangan</th>
        </tr>
        <tr>
            <th scope="col" class="text-center">Laki-laki</th>
            <th scope="col" class="text-center">Perempuan</th>
            <th scope="col" class="text-center">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php foreach ($data['modStatistic']->fetchData($data['parameters'])->get()->getResult() as $row) : ?>
            <tr>
                <th scope="row" class="text-center"><strong><?= $i++; ?>.</strong></th>
                <td class="text-center">Pemuda Usia 16-30 Tahun</td>
                <td><?= $row->district_type . ' ' . $row->district_name; ?></td>
                <td class="text-center"><?= number_format($row->statistic_male, 0, ",", "."); ?></td>
                <td class="text-center"><?= number_format($row->statistic_female, 0, ",", "."); ?></td>
                <td class="text-center"><?= number_format(($row->statistic_male + $row->statistic_female), 0, ",", "."); ?></td>
                <td class="text-center">
                    <?php if ($row->statistic_explanation) : ?>
                        <?= $row->statistic_explanation; ?>
                    <?php else : ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th scope="row" colspan="3" class="text-end">Jumlah Pemuda</th>
            <td class="text-center"><strong><?= number_format($data['spCountStatistic']->total_male, 0, ",", "."); ?></strong></td>
            <td class="text-center"><strong><?= number_format($data['spCountStatistic']->total_female, 0, ",", "."); ?></strong></td>
            <td class="text-center"><strong><?= number_format($data['spCountStatistic']->total, 0, ",", "."); ?></strong></td>
            <th scope="col" colspan="1"></th>
        </tr>
    </tbody>
</table>
<br>
<div class="text-end">
    <small><i>*Dicetak pada <?= parseDate(now(), 'dS F Y - g:i A T') ?></i></small>
</div>
<br>
<!-- end row -->

<table style="border: 1px solid black;opacity: 1!important; width:100%">
    <tr>
        <td class="my-auto">
            <p class="text-muted">
                <small>*Scan pada <strong>Kode QR</strong> dibawah ini untuk melihat tampilan <strong>Data Statistik Pemuda</strong> pada <strong>Website</strong>.</small>
            </p>
            <div class="text-center">
                <img src="<?= $libIonix->generateQRCode($data['qrData'], NULL, $configIonix->QRSize)->getDataUri(); ?>" alt="" class="img-thumbnail" width="100">
            </div>
        </td>

        <td width="30%" class="align-middle">
            <p class="text-muted text-start">Powered by:</p>
            <img src="<?= $configIonix->appLogo['landscape_dark']; ?>" alt="" height="45">
        </td>
    </tr>
</table>
<?= $this->endSection(); ?>