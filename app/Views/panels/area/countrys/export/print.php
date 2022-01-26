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
                <h4><strong><?= $data['headerTitle'];?></strong></h4>
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
                        <th scope="col" class="text-center">Kode Negara</th>
                        <th scope="col" class="text-center">Nama Negara / Iso2</th>
                        <th scope="col" class="text-center">Kode (Iso3)</th>
                        <th scope="col" class="text-center">Regional</th>
                        <th scope="col" class="text-center">Geografis</th>
                        <th scope="col" class="text-center">Lainnya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;?>
                    <?php foreach ($data['modCountry']->fetchData(NULL, false, 'CUSTOM')->orderBy('country_name', 'ASC')->get()->getResult() as $row): ?>
                        <tr>
                            <th scope="row" class="text-center"><strong><?= $i++;?>.</strong></th>
                            <td class="text-center"><strong><?= $row->country_id;?></strong></td>
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
                                        <h6 class="text-truncate mb-1"><?= $row->country_name;?></h6>
                                        <p class="text-muted mb-0"><?= strtoupper($row->country_iso2);?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><strong><?= strtoupper($row->country_iso3);?></strong></td>
                            <td class="text-center"><?= $row->country_region;?>/<?= $row->country_capital;?></td>
                            <td>
                                <div class="media">
                                    <div class="media-body overflow-hidden text-center my-auto">
                                        <p class="text-muted mb-1">Latitude: <strong><?= $row->country_latitude;?></strong></p>
                                        <p class="text-muted mb-0">Longitude: <strong><?= $row->country_longitude;?></strong></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="media">
                                    <div class="media-body overflow-hidden my-auto">
                                        <ul class="list-unstyled product-list mb-0">
                                            <li><i class="mdi mdi-chevron-right me-1"></i> Mata Uang: <strong><?= $row->country_currency;?></strong> (<strong><?= $row->country_currency_symbol;?></strong>)</li>
                                            <li><i class="mdi mdi-chevron-right me-1"></i> Kode Telepon: <strong>+<?= $row->country_phone_code;?></strong></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th scope="row" colspan="6" class="text-end">Jumlah Negara</th>
                        <td class="text-center"><strong><?= $data['modCountry']->fetchData()->countAllResults();?></strong></td>
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
                document.title = "<?= $data['fileName'];?>";
                window.print();
              }, 2e3)
            });
        </script>
    <?php endif; ?>
<?= $this->endSection();?>
