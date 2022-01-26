<?= $this->extend($configIonix->viewLayout['print']); ?>

<?= $this->section('meta'); ?>

<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>
    <style>
        body {
          -webkit-print-color-adjust:exact;
        }

        @page {
          size: <?= $data['paperSize'];?>;
          margin: 4mm;
          size: landscape;
        }

        .styled-table {
            border-collapse: collapse;
            font-size: 0.7em;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: <?= $configIonix->colorPrimaryCSS;?>;
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
            border-bottom: 2px solid <?= $configIonix->colorPrimaryCSS;?>;
        }

        .styled-table tbody tr.active-row {
            font-weight: bold;
            color: <?= $configIonix->colorPrimaryCSS;?>;
        }

        .table>:not(caption)>*>* {
          padding: .3rem .75rem!important;
        }
    </style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
    <div class="row justify-content-center my-md-3">
        <div class="col-8">
            <div class="text-center">
                <h4 class="mb-0"><strong><?= $data['title'];?></strong></h4>
                <p class="mb-1"><?= $data['subTitle'];?></p>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <table class="table styled-table align-middle w-100">
                <thead>
                    <tr>
                        <th scope="col" class="text-center align-middle">No</th>
                        <th scope="col" class="text-center align-middle">Nama/Jenis Usaha</th>
                        <th scope="col" class="text-center align-middle">Pelaku Usaha/JK</th>
                        <th scope="col" class="text-center align-middle">Jumlah Karyawan</th>
                        <th scope="col" class="text-center align-middle">Alamat</th>
                        <th scope="col" class="text-center align-middle">Kota/Kab</th>
                        <th scope="col" class="text-center align-middle">Lampiran</th>
                        <th scope="col" class="text-center align-middle">Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;?>
                    <?php foreach ($data['modEntrepreneurship']->fetchData($data['parameters'])->get()->getResult() as $row): ?>
                        <tr>
                            <th scope="row" class="text-center"><strong><?= $i++; ?>.</strong></th>
                            <td>
                                <h6 class="text-truncate mb-0"><?= $row->entrepreneurship_business_name;?></h6>
                                <p class="text-muted mb-0"><?= $row->entrepreneurship_business_type;?></p>
                            </td>
                            <td>
                                <h6 class="text-truncate mb-0"><?= $row->entrepreneurship_ownership;?></h6>
                                <p class="text-muted mb-0"><?= parseGender($row->entrepreneurship_ownership_gender);?></p>
                            </td>
                            <td class="text-center"><strong><?= number_format($row->entrepreneurship_total_employee, 0, ",", ".");?></strong></td>
                            <td class="text-center">
                                <?php if ($row->entrepreneurship_address): ?>
                                        <?= $row->entrepreneurship_address;?>
                                    <?php else: ?>
                                        -
                                <?php endif; ?>
                            </td>
                            <td><?= $row->district_type . ' ' . $row->district_name; ?></td>
                            <td class="text-center">
                                <?php if ($row->file_id): ?>
                                        <strong>Terlampir</strong>
                                    <?php else: ?>
                                        -
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?= $libIonix->getUserData(['users.user_id' => $row->entrepreneurship_created_by], 'object')->name;?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                            <th scope="row" colspan="7" class="text-end">Jumlah Prestasi Wirausaha</th>
                            <td class="text-center"><strong><?= $data['modEntrepreneurship']->fetchData($data['parameters'])->countAllResults();?></strong></td>
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

    <table class="table table-bordered w-100 mt-3" style="border: 1px solid black;opacity: 1!important;">
        <tr>
            <td class="my-auto">
                <p class="text-muted">
                    <small>*Scan pada <code>Kode QR</code> dibawah ini untuk melihat tampilan <strong>Data Statistik Pemuda</strong> pada <strong>Website</strong>.</small>
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

<?= $this->section('javascript'); ?>
    <?php if (ENVIRONMENT !== 'development'): ?>
        <script type="text/javascript">
            $(document).ready(function(){
              setTimeout(function() {
                document.title = "<?= $data['fileName'];?>";
                window.print();
              }, 2e3)
            });
        </script>
    <?php endif; ?>
<?= $this->endSection(); ?>
