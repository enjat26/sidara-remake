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
            <th scope="col" class="text-center align-middle">No</th>
            <th scope="col" class="text-center align-middle">Nama Organisasi</th>
            <th scope="col" class="text-center align-middle">Ketua Organisasi</th>
            <th scope="col" class="text-center align-middle">Asal Daerah</th>
            <th scope="col" class="text-center align-middle">Periode</th>
            <th scope="col" class="text-center align-middle">Lampiran</th>
            <th scope="col" class="text-center align-middle">Dibuat Oleh</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php foreach ($data['modOrganization']->fetchData($data['parameters'])->get()->getResult() as $row) : ?>
            <tr>
                <th scope="row" class="text-center"><strong><?= $i++; ?>.</strong></th>
                <td>
                    <h6 class="text-truncate mb-0"><?= $row->sport_organization_name; ?></h6>
                    <p class="text-muted mb-0"><?= strtoupper($row->sport_organization_code); ?></p>
                </td>
                <td class="text-center"><?= $row->sport_organization_leader; ?></td>
                <td class="text-center"><?= $row->district_type . ' ' . $row->district_name . ', ' . $row->province_name; ?></td>
                <td class="text-center"><?= $row->sport_organization_year_start . ' - ' . $row->sport_organization_year_end; ?></td>
                <td class="text-center"><?= $row->sport_organization_file_id ? 'Dokumen terlampir' : '-'; ?></td>
                <td>
                    <h6 class="text-truncate mb-0"><?= $libIonix->getUserData(['users.user_id' => $row->sport_organization_created_by], 'object')->name; ?></h6>
                    <p class="text-muted mb-0"><?= $libIonix->getUserData(['users.user_id' => $row->sport_organization_created_by], 'object')->role_name; ?></p>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th scope="row" colspan="6" class="text-end">Jumlah Organisasi Olahraga</th>
            <td class="text-center"><strong><?= $data['modOrganization']->fetchData($data['parameters'])->countAllResults(); ?></strong></td>
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