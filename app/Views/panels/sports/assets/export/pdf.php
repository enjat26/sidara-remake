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
            <th class="text-center align-middle">No</th>
            <th class="text-center align-middle">Nama Sarana/Prasarana</th>
            <th class="text-center align-middle">Kategori</th>
            <th class="text-center align-middle">Jenis/Tipe</th>
            <th class="text-center align-middle">Tahun</th>
            <th class="text-center align-middle">Kondisi</th>
            <th class="text-center align-middle">Pengelolaan</th>
            <th class="text-center align-middle">Oleh</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php foreach ($data['modAsset']->fetchData($data['parameters'])->get()->getResult() as $row) : ?>
            <tr>
                <td class="text-center"><strong><?= $i++; ?>.</strong></td>
                <td><?= $row->asset_name; ?></td>
                <td class="text-center"><?= $row->asset_type; ?></td>
                <td>
                    <?= $row->asset_category_name; ?> <br><?= parseAssetType($row->asset_category_type)->text; ?>
                </td>
                <td class="text-center"><?= $row->asset_production_year; ?></td>
                <td class="text-center"><?= $row->asset_condition; ?></td>
                <td>
                    <?= $row->asset_managed_by; ?> <br> <?= $row->asset_management; ?>
                </td>
                <td class="text-center">
                    <?= $libIonix->getUserData(['users.user_id' => $row->asset_created_by], 'object')->name; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th scope="row" colspan="7" class="text-end">Jumlah Sarana & Prasarana</th>
            <td class="text-center"><strong><?= $data['modAsset']->fetchData($data['parameters'])->countAllResults(); ?></strong></td>
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