<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>

<?= $this->endSection();?>

<?= $this->section('stylesheet');?>

<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Beranda</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / <?= ucwords(uri_segment(1));?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- start row -->
    <div id="ask-notification" class="row justify-content-center mt-3 hidden">
        <div class="col-xl-12">
            <div class="alert border-0 border-start border-5 border-info py-2">
                <div class="d-flex align-items-center">
                    <div class="font-size-20 text-info"><i class="mdi mdi-information-variant"></i></div>
                    <div class="ms-3">
                        <div>
                            Jika Anda ingin mendapatkan <strong>Notifikasi</strong> dari <strong><?= strtoupper($configIonix->appCode);?> <?= $configIonix->appType;?></strong>,
                            Anda harus mengizinkan <strong>Notifikasi</strong> pada browser dengan mengklik tombol <a href="javascript:void(0)" class="text-<?= $configIonix->colorPrimary;?>" key="ask-notification">Aktifkan</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/dashboard.init.js');?>
<?= $this->endSection();?>
