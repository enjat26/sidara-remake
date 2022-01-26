<?= $this->extend($configIonix->viewLayout['print']);?>

<?= $this->section('meta');?>

<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <style>
        @page {
          size: <?= $data['paperSize'];?>;
          margin: 9mm;
        }
    </style>
<?= $this->endSection();?>

<?= $this->section('content');?>
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
            <table class="table table-striped align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center" width="5%">#</th>
                        <th scope="col" class="text-center">Kode Provinsi</th>
                        <th scope="col" class="text-center">Nama Provinsi</th>
                        <th scope="col" class="text-center">Negara</th>
                        <th scope="col" class="text-center">Geografis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;?>
                    <?php foreach ($data['modProvince']->fetchData($data['parameters'], false, 'CUSTOM')->orderBy('province_name', 'ASC')->get()->getResult() as $row): ?>
                        <tr>
                            <th scope="row" class="text-center"><strong><?= $i++;?>.</strong></th>
                            <td class="text-center"><strong><?= $row->province_id;?></strong></td>
                            <td><?= $row->province_name;?></td>
                            <td>
                                <div class="media">
                                    <div class="align-self-center me-3">
                                        <?php if (file_exists($configIonix->uploadsFolder['flag'].$row->country_iso3.'.jpg')): ?>
                                                <img src="<?= $configIonix->mediaFolder['image'].'flags/'.$row->country_iso3.'.jpg';?>" alt="<?= $row->country_name;?>" class="rounded" height="20">
                                            <?php else: ?>
                                                <img src="<?= $configIonix->mediaFolder['image'].'default/country-iso3.jpg';?>" alt="<?= $row->country_name;?>" class="rounded" height="20">
                                        <?php endif; ?>
                                    </div>
                                    <div class="media-body overflow-hidden my-auto">
                                        <h6 class="text-truncate mb-0"><?= $row->country_name;?></h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="media">
                                    <div class="media-body overflow-hidden text-center my-auto">
                                        <p class="text-muted mb-1">Latitude: <strong><?= $row->province_latitude ? $row->province_latitude : '-';?></strong></p>
                                        <p class="text-muted mb-0">Longitude: <strong><?= $row->province_longitude ? $row->province_longitude : '-';?></strong></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th scope="row" colspan="4" class="text-end">Jumlah Provinsi</th>
                        <td class="text-center"><strong><?= $data['modProvince']->fetchData($data['parameters'], false, 'CUSTOM')->countAllResults();?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-12 col-xl-12">
            <div class="text-end">
                <small><i>*Dicetak pada <?= parseDate(now(), 'dS F Y - g:i A T')?></i></small>
            </div>
        </div>
    </div>
    <!-- end row -->

    <table class="table table-bordered w-100 mt-4" style="border: 1px solid black;opacity: 1!important;">
        <tr>
            <td class="my-auto">
                <div class="text-center">
                    <p class="text-muted text-start">Powered by:</p>
                    <img src="<?= $configIonix->appLogo['landscape_dark'];?>" alt="" height="60">
                </div>
            </td>
        </tr>
    </table>
<?= $this->endSection();?>

<?= $this->section('javascript');?>
    <?php if (ENVIRONMENT !== 'development'): ?>
        <script type="text/javascript">
            $(document).ready(function(){
              setTimeout(function() {
                document.title = '<?= $data['fileName'];?>';
                window.print();
              }, 2e3)
            });
        </script>
    <?php endif; ?>
<?= $this->endSection();?>
