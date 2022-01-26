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
                <h4 class="mb-sm-0 font-size-18">Tentang Aplikasi</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / <?= ucwords(uri_segment(1))?></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- start row -->
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="page-title-box text-center">
                <h3>Informasi Aplikasi</h3>
                <p class="text-muted">Pada halaman ini Anda dapat membaca informasi mengenai Aplikasi <strong><?= strtoupper($configIonix->appCode);?> <?= ucwords($configIonix->appType);?></strong> beserta perubahan yang telah dilakukan untuk diketahui oleh <strong>Pengguna</strong>.</p>
            </div>
        </div>
    </div>
    <!-- end row -->

    <!-- start row -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                  <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                      <a class="nav-link mb-2 active" id="v-pills-welcome-tab" data-bs-toggle="pill" href="#v-pills-welcome" role="tab" aria-controls="v-pills-welcome" aria-selected="true"><i class="mdi mdi-bookmark ms-2 me-2"></i>Memperkenalkan</a>
                      <a class="nav-link mb-2" id="v-pills-changelog-tab" data-bs-toggle="pill" href="#v-pills-changelog" role="tab" aria-controls="v-pills-changelog" aria-selected="false"><i class=" mdi mdi-history ms-2 me-2"></i>Riwayat Perubahan</a>
                  </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-welcome" role="tabpanel" aria-labelledby="v-pills-welcome-tab">
                            <?= view('panels/abouts/intro.php');?>
                        </div>
                        <div class="tab-pane fade" id="v-pills-changelog" role="tabpanel" aria-labelledby="v-pills-changelog-tab">
                            <?= view('panels/abouts/change-logs.php');?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End row -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>

<?= $this->endSection();?>
