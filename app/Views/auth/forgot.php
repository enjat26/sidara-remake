<?= $this->extend($configIonix->viewLayout['auth']);?>

<?= $this->section('meta');?>

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
                                        <h5 class="text-primary">Lupa Kata Sandi</h5>
                                        <p>Gunakan alamat Email Anda.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="<?= $configIonix->mediaFolder['image'].'content/forgot.png';?>" alt="" class="img-fluid">
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
                                <p class="text-muted text-center">Masukan <strong>Email</strong> yang terdaftar dan instruksi akan dikirimkan kepada Anda!</p>

                                <?= customFormOpen('forgot');?>
                                    <div class="form-group position-relative">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" placeholder="Masukan alamat email anda" required autofocus>
                                    </div>

                                    <div class="alert alert-warning text-center mb-4" role="alert">
                                        Jika pemulihan <strong>Kata Sandi</strong> tidak terkirim pada <strong>Inbox</strong> Anda, mungkin berada di <strong>Spam</strong>.
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary w-md waves-effect waves-light">Kirim</button>
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
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <p>Anda sudah ingat? <a href="<?= base_url('/login');?>" class="fw-medium text-primary">Masuk sekarang</a></p>

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
    <?= script_tag($configIonix->assetsFolder['local'].'js/auth/forgot.init.js');?>
<?= $this->endSection();?>
