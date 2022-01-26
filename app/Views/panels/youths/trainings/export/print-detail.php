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
                <h4><strong><?= $data['title'];?></strong></h4>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="row mt-4">
        <div class="col-md-12 col-xl-12">
            <div class="media align-items-center">
                <div class="me-4">
                    <h1 class="fw-medium display-4 mb-0">A.1</h1>
                </div>
                <div class="media-body">
                  <h4 class="mb-2"><?= $data['trainingData']->youth_training_name;?></h4>
                  <p class="text-muted mb-0">Lokasi: <?= $data['trainingData']->district_type.' '.$data['trainingData']->district_name.', '.$data['trainingData']->province_name;?> - Tahun <strong><?= $data['trainingData']->youth_training_year;?></strong></p>
                </div>
            </div>

            <table class="table styled-table align-middle w-100">
                <thead>
                    <tr>
                        <th scope="col" class="text-center" width="5%">No</th>
                        <th scope="col" class="text-center">Nama Peserta</th>
                        <th scope="col" class="text-center">Asal Daerah</th>
                        <th scope="col" class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;?>
                    <?php foreach ($libIonix->builderQuery('youth_training_participants')->where($data['parameters'])->get()->getResult() as $row) : ?>
                        <tr>
                            <th scope="row" class="text-center"><strong><?= $i++;?>.</strong></th>
                            <td>
                                <h6 class="text-truncate mb-0"><?= $row->youth_training_participant_name;?></h6>
                            </td>
                            <td class="text-center"><?= $row->youth_training_participant_location;?></td>
                            <td class="text-center"><?= $row->youth_training_participant_explanation ? $row->youth_training_participant_explanation : '-' ;?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th scope="row" colspan="3" class="text-end">Jumlah Peserta</th>
                        <td class="text-center"><strong><?= $libIonix->builderQuery('youth_training_participants')->where($data['parameters'])->countAllResults();?></strong></td>
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
                <div class="text-center">
                    <p class="text-muted text-start">Powered by:</p>
                    <img src="<?= $configIonix->appLogo['landscape_dark'];?>" alt="" height="60">
                </div>
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
