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
          margin: 9mm;
        }

        .styled-table {
            border-collapse: collapse;
            font-size: 0.9em;
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
    </style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
    <div class="row justify-content-center my-md-3">
        <div class="col-8">
            <div class="text-center">
                <h4 class="mb-1"><strong><?= $data['title'];?></strong></h4>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <table width="100%" cellspacing="0" cellpadding="0" style="width: 100%; padding: 25px 32px; color: #343030;">
        <tbody>
            <?php if ($data['subTitle']->province): ?>
                <tr>
                    <table width="100%" cellspacing="0" cellpadding="0" style="padding-bottom: 20px;">
                        <tbody>
                            <tr>
                                <td class="p-4" style="width: 57%; vertical-align: top;">
                                    <div class="clearfix"></div>
                                </td>
                                <td class="p-4" style="width: 43%; vertical-align: top; padding-left: 30px;">
                                    <table width="100%" cellspacing="0" cellpadding="0">
                                        <tbody>
                                          <?php if ($data['subTitle']->province): ?>
                                              <tr>
                                                  <th scope="row">Pada Provinsi:</th>
                                                  <td><?= $data['subTitle']->province;?></td>
                                              </tr>
                                          <?php endif; ?>
                                            <?php if ($data['subTitle']->district): ?>
                                                <tr>
                                                    <th scope="row">Pada Kota/Kab:</th>
                                                    <td><?= $data['subTitle']->district;?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </tr>
            <?php endif; ?>
            <tr>
              <table class="table styled-table align-middle w-100">
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
                      <?php $i = 1;?>
                      <?php foreach ($data['modOrganization']->fetchData($data['parameters'])->get()->getResult() as $row) : ?>
                          <tr>
                              <th scope="row" class="text-center"><strong><?= $i++; ?>.</strong></th>
                              <td>
                                  <h6 class="text-truncate mb-0"><?= $row->sport_organization_name;?></h6>
                                  <p class="text-muted mb-0"><?= strtoupper($row->sport_organization_code);?></p>
                              </td>
                              <td class="text-center"><?= $row->sport_organization_leader;?></td>
                              <td class="text-center"><?= $row->district_type.' '.$row->district_name.', '.$row->province_name;?></td>
                              <td class="text-center"><?= $row->sport_organization_year_start.' - '.$row->sport_organization_year_end;?></td>
                              <td class="text-center"><?= $row->sport_organization_file_id ? 'Dokumen terlampir' : '-' ;?></td>
                              <td>
                                  <h6 class="text-truncate mb-0"><?= $libIonix->getUserData(['users.user_id' => $row->sport_organization_created_by], 'object')->name;?></h6>
                                  <p class="text-muted mb-0"><?= $libIonix->getUserData(['users.user_id' => $row->sport_organization_created_by], 'object')->role_name;?></p>
                              </td>
                          </tr>
                      <?php endforeach; ?>
                      <tr>
                          <th scope="row" colspan="6" class="text-end">Jumlah Organisasi Olahraga</th>
                          <td class="text-center"><strong><?= $data['modOrganization']->fetchData($data['parameters'])->countAllResults();?></strong></td>
                      </tr>
                  </tbody>
              </table>
            </tr>
            <tr>
                <td>
                    <div class="text-end">
                        <small><i>*Dicetak pada <?= parseDate(now(), 'dS F Y - g:i A T')?></i></small>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- end table -->

    <table class="table table-bordered w-100" style="border: 1px solid black;opacity: 1!important;">
        <tr>
            <td class="my-auto">
                <p class="text-muted">
                    <small>*Scan pada <code>Kode QR</code> dibawah ini untuk melihat tampilan <strong>Pelatihan</strong> pada <strong>Website</strong>.</small>
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
