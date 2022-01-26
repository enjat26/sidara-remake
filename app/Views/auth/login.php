<?= $this->extend($configIonix->viewLayout['auth']);?>

<?= $this->section('meta');?>

<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'select2/css/select2.min.css');?>

    <style media="screen">
      body {
        background-image: url(<?= core_url('image/background/abstract.png');?>);
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        width: 100%;
        height: 100%;
      }
    </style>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-<?= $configIonix->colorPrimary;?> bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-<?= $configIonix->colorPrimary;?> p-4">
                                        <h5 class="text-<?= $configIonix->colorPrimary;?>">Selamat datang!</h5>
                                        <p>Gunakan Akun Anda untuk melanjutkan</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="<?= $configIonix->mediaFolder['image'].'content/login.png';?>" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="avatar-md profile-user-wid mb-4">
                                <span class="avatar-title rounded-circle bg-light">
                                    <img src="<?= $configIonix->appLogo['square_dark'];?>" alt="" class="rounded-circle" height="50">
                                </span>
                            </div>

                            <h4 class="text-center">Masuk</h4>
                            <p class="text-muted text-center">Bagi Anda yang sudah terdaftar, silahkan <strong>Login</strong>.</p>

                            <div class="p-2">
                                <?php if ($session->getFlashdata('alertType') && $session->getFlashdata('alertMessage')): ?>
                                  <div class="alert alert-<?= $session->getFlashdata('alertType');?> alert-dismissible fade show mt-4" role="alert">
                                      <?= $session->getFlashdata('alertMessage');?>
                                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>
                                <?php endif; ?>

                                <?= customFormOpen('login');?>
                                    <div class="form-group position-relative">
                                        <?= inputIdentity();?>
                                    </div>

                                    <div class="form-group position-relative">
                                        <?= inputPassword();?>
                                    </div>

                                    <div class="form-group position-relative">
                                        <label for="year">Tahun</label>
                                        <select class="form-control select2" name="year" aria-hidden="true" data-placeholder="Pilih tahun..." required>
                                            <option></option>
                                            <?php foreach ($libIonix->builderQuery('years')->orderBy('year', 'DESC')->get()->getResult() as $row): ?>
                                                <option value="<?= $row->year;?>"><?= $row->year;?></option>
                                            <?php endforeach; ?>
                                        </select>
                                     </div>

                                    <?php if ($configIonix->allowRemembering === true): ?>
                                      <div class="form-group position-relative">
                                          <?= inputRememberMe();?>
                                      </div>
                                    <?php endif; ?>

                                    <div class="d-grid mt-3">
                                        <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light">Masuk</button>
                                    </div>
                                <?= customFormClose()?>
                            </div>

                            <div id="tooltip-container" class="mt-2 text-center">
                                <div class="separator mb-3">
                                    <h5 class="font-size-14 mb-0">Ikuti kami</h5>
                                </div>

                                <ul class="list-inline">
                                    <?php foreach ($libIonix->getQuery('social_media', ['social_provider' => 'social_provider.sosprov_id = social_media.sosprov_id'], ['user_id' => NULL])->getResult() as $row): ?>
                                        <li class="list-inline-item">
                                            <a href="<?= $row->sosprov_url.$row->sosmed_key;?>" target="_blank" class="social-list-item text-white" style="background-color: #<?= $row->sosprov_color;?>; border-color: #<?= $row->sosprov_color;?>">
                                                <i class="mdi mdi-<?= $row->sosprov_name;?> font-size-14" data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?= $row->sosprov_name;?>"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <?php if ($configIonix->homePage == true): ?>
                                <div class="text-center mt-4">
                                    Kembali ke <a href="<?= core_url();?>" class="text-<?= $configIonix->colorPrimary;?>">Halaman Utama</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <?php if ($configIonix->allowRegistration == true): ?>
                          <p>Belum punya Akun? <a href="<?= core_url('register');?>" class="fw-medium text-<?= $configIonix->colorPrimary;?>">Daftar sekarang</a></p>
                        <?php endif; ?>

                        <?php if ($configIonix->viewCopyright == true): ?>
                          <?= showCopyright();?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end account-pages -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'select2/js/select2.min.js');?>

    <?= script_tag($configIonix->assetsFolder['local'].'js/auth/login.init.js');?>
<?= $this->endSection();?>
