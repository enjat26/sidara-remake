<?= $this->extend($configIonix->viewLayout['auth']);?>

<?= $this->section('meta');?>
    <meta name="token" content="<?= uri_segment(1);?>">
<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <style media="screen">
      body {
        background-image: url(<?= base_url('image/background/abstract.png');?>);
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
                        <div class="bg-primary bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Pemulihan Kata Sandi</h5>
                                        <p>Ubah Kata Sandi lama dengan yang baru.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="<?= $configIonix->mediaFolder['image'].'content/recovery.png';?>" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                              <div class="avatar-md profile-user-wid mb-4">
                                  <span class="avatar-title rounded-circle bg-light">
                                      <img src="<?= $configIonix->appLogo['square_dark'];?>" alt="" class="rounded-circle" height="50">
                                  </span>
                              </div>
                            </div>

                            <div class="p-2">
                                <p class="text-muted text-center">Harap gunakan <strong>Kata Sandi</strong> yang <strong><i>Rumit</i></strong> demi keamanan <strong>Akun</strong> Anda.</p>

                                <?= customFormOpen('recovery');?>
                                    <div class="form-group position-relative">
                                       <?= inputPassword(false, false);?>
                                    </div>

                                    <div class="form-group position-relative">
                                       <?= inputPasswordConfirmation();?>
                                    </div>

                                    <div class="alert alert-warning text-center" role="alert">
                                        Format <strong>Kata Sandi</strong> setidaknya harus mengandung Huruf Besar, Huruf Kecil dan Angka.
                                    </div>

                                    <div class="d-grid mt-3">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Ganti</button>
                                    </div>
                                <?= customFormClose()?>
                            </div>

                            <div id="tooltip-container" class="mt-4 text-center">
                                <div class="separator">
                                  <h5 class="font-size-14 mb-0">Ikuti Kami</h5>
                                </div>

                                <ul class="list-inline mt-3">
                                  <?php foreach ($libIonix->getQuery('social_media', ['social_provider' => 'social_provider.sosprov_id = social_media.sosprov_id'], ['user_id' => NULL])->getResult() as $row): ?>
                                      <li class="list-inline-item">
                                          <a href="<?= $row->sosprov_url.$row->sosmed_key;?>" target="_blank" class="social-list-item text-white" style="background-color: #<?= $row->sosprov_color;?>; border-color: #<?= $row->sosprov_color;?>">
                                              <i class="mdi mdi-<?= $row->sosprov_name;?> font-size-14" data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?= $row->sosprov_name;?>"></i>
                                          </a>
                                      </li>
                                  <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-center">
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
    <?= script_tag($configIonix->assetsFolder['local'].'js/auth/recovery.init.js');?>
<?= $this->endSection();?>
