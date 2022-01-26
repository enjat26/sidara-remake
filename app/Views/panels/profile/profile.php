<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>
    <meta name="scope" content="<?= $libIonix->Encode('profile');?>">
<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'select2/css/select2.min.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'bootstrap-datepicker/css/bootstrap-datepicker.min.css');?>

    <style media="screen">
        .activity-list {
          width: 100%!important;
          max-height: 1150px!important
        }
    </style>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Profil Pengguna</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / <?= ucwords(uri_segment(1));?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <?php if (ENVIRONMENT == 'demo'): ?>
        <!-- start row -->
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="alert alert-danger text-center" role="alert">
                    <i class="mdi mdi-alert-circle align-middle me-1"></i>
                    Dalam mode <strong><?= ucwords(ENVIRONMENT);?></strong>, beberapa fungsi dalam halaman ini telah dinonaktifkan.
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-12 col-xl-12">
            <div class="card overflow-hidden">
                <div class="cover bg-<?= $configIonix->colorPrimary;?> bg-soft"></div>

                <div class="card-body pt-0">
                    <div class="row justify-content-center">
                        <div class="col-md-12 col-xl-12">
                            <div class="float-end">
                                <ul class="list-inline user-chat-nav mt-2">
                                    <li class="list-inline-item">
                                        <div class="dropdown dropstart">
                                            <button class="btn nav-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="javascript:void(0);" class="dropdown-item" data-scope="<?= $libIonix->Encode('image');?>" data-val="<?= $libIonix->Encode('cover');?>" key="del-cover"><i class="mdi mdi-camera-off font-size-16 align-middle text-danger me-1"></i> Hapus Cover</a>
                                                <a href="javascript:void(0);" class="dropdown-item" data-scope="<?= $libIonix->Encode('image');?>" data-val="<?= $libIonix->Encode('avatar');?>" key="del-avatar"><i class="mdi mdi-camera-off font-size-16 align-middle text-danger me-1"></i> Hapus Avatar</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="text-center">
                                <div class="avatar avatar-xl bg-white mx-auto mb-4" <?= $userData->avatar ? 'style="height: auto!important"' : '' ;?>></div>

                                <h3 class="text-truncate" key="name"></h3>
                                <p class="text-muted">
                                    <span class="badge rounded-pill font-size-12" style="background-color: <?= hexToRGB($userData->role_color, 18);?>;color: #<?= $userData->role_color;?>"><?= $userData->role_name;?></span>
                                </p>

                                <div class="button-items mt-4">
                                    <button type="button" class="btn btn-<?= $configIonix->colorPrimary;?> btn-label waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-profile" data-scope="<?= $libIonix->Encode('profile');?>" key="upd-profile"><i class="mdi mdi-circle-edit-outline label-icon"></i> Ubah Informasi</button>
                                    <button type="button" class="btn btn-light btn-label waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-password" data-scope="<?= $libIonix->Encode('password');?>" key="upd-password"><i class="mdi mdi-form-textbox-password label-icon"></i> Ganti Kata Sandi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="row justify-content-center">
        <div class="col-md-6 col-xl-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informasi Pribadi</h4>
                    <p class="text-muted text-justify mb-4">Pengaturan ini hanya dapat dilihat oleh Anda</p>

                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">UUID</th>
                                <td>: <span key="uuid"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Username</th>
                                <td>: <span key="username"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Biografi</h5>
                    <p class="text-muted text-justify mb-0" key="bio"></p>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informasi Lainnya</h4>

                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">Alamat</th>
                                <td style="word-wrap: break-word">: <span class="text-justify" key="address"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informasi Kontak</h4>

                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">Email</th>
                                <td>: <span key="email"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">No. Telepon</th>
                                <td>: <span key="phone"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card -->

            <?php if ($configIonix->notificationTelegram == true): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Notifikasi Langsung (Beta)</h5>
                        <p class="text-muted text-justify">
                            Dengan menautkan <strong>Messenger</strong> yang tersedia, Anda akan mendapatkan <strong>Notifikasi</strong> langsung melalui <strong>Messenger</strong> yang dikaitkan.
                        </p>

                        <?php if ($data['queryTelegram']->countAllResults() == false): ?>
                                <div class="d-flex justify-content-center">
                                    <div class="button-items">
                                        <button type="button" class="btn btn-info waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-telegram" data-scope="<?= $libIonix->Encode('telegram');?>" data-val="add" key="add-telegram"><i class="mdi mdi-telegram me-1"></i>Hubungkan ke Telegram</button>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php $telegramData = $data['queryTelegram']->get(1)->getRow();?>
                                <div class="row justify-content-center">
                                    <div class="col-6">
                                        <div class="social-source text-center mt-3">
                                            <div class="float-end">
                                                <button type="button" class="btn-close" aria-label="Close" data-scope="<?= $libIonix->Encode('telegram');?>" data-val="<?= $libIonix->Encode($telegramData->notification_telegram_id)?>" key="del-telegram"></button>
                                            </div>
                                            <div class="avatar-xs mx-auto mb-3">
                                                <span class="avatar-title bg-info rounded-circle font-size-16">
                                                    <i class="mdi mdi-telegram text-white"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-15 mb-0">Paired with <?= $telegramData->notification_telegram_chat_id;?></h5>
                                        </div>
                                    </div>
                                </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- end card -->
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="javascript:void(0);" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-social" data-scope="<?= $libIonix->Encode('social');?>" data-val="add" key="add-social"><i class="mdi mdi-plus"></i> Tautkan media sosial</a>
                        </div>
                    </div>
                    <!-- end dropdown -->
                    <h4 class="card-title mb-4">Media Sosial</h4>

                    <div class="row social-media" data-scope="<?= $libIonix->Encode('social');?>"></div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-md-6 col-xl-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-9 col-sm-8">
                            <div class="p-4">
                                <h5 class="text-<?= $configIonix->colorPrimary;?>">Safe Mode</h5>
                                <p class="text-muted text-justify">Fitur ini akan otomatis menyaring dan menyembunyikan informasi pribadi Anda dari orang lain saat melihat profil Anda. Atur dengan mengaktifkan/menonaktifkan <strong>Safe Mode</strong> ini.</p>

                                <?= customFormOpen('safe');?>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-switch mx-auto mb-0">
                                            <input type="checkbox" name="safe" class="form-check-input" data-scope="<?= $libIonix->Encode('safe');?>">
                                        </div>
                                    </div>
                                <?= customFormClose();?>
                                <p class="text-center">(Klik untuk merubahnya)</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-4 align-self-center">
                            <img src="<?= $configIonix->mediaFolder['image'].'content/privacy.png';?>" alt="" class="img-fluid d-block">
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <a href="javascript:void(0);" class="btn btn-sm btn-outline-danger waves-effect waves-light" data-scope="<?= $libIonix->Encode('activity');?>" key="del-activity"> Hapus Aktivitas</a>
                    </div>
                    <h4 class="card-title mb-5">Aktivitas Login</h4>

                    <div class="activity-list" data-simplebar>
                        <ul class="activity verti-timeline list-unstyled" data-scope="<?= $libIonix->Encode('activity');?>"></ul>
                    </div>
                </div>
            </div>
            <!-- end-card -->
        </div>
    </div>
    <!-- end row -->

    <div class="hidden">
        <?= customFormOpen('image');?>
            <input type="file" id="image" name="image" class="form-control" accept="image/x-png, image/gif, image/jpg, image/jpeg" data-scope="<?= $libIonix->Encode('image');?>">
        <?= customFormClose();?>
    </div>

    <div class="modal fade" id="modal-profile" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Informasi Pribadi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 class="card-caption text-center">Mengubah informasi pribadi</h3>
                    <p class="card-description text-center">Informasi mengenai nama, alamat dan lainnya agar dapat digunakan pada Aplikasi ini. Lengkapi identitas Pribadi Anda sesuai dengan informasi yang ada.</p>

                    <?= customFormOpen('profile');?>
                        <h5 class="text-center mt-3 mb-3">Informasi Dasar</h5>

                        <div class="form-group position-relative">
                            <label for="name">Nama Lengkap<code>*</code></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukan nama lengkap" required>
                        </div>

                        <div class="form-group position-relative">
                          <label for="bio">Biografi</label>
                          <textarea id="bio" name="bio" class="form-control" placeholder="Deskripsikan biografi anda" rows="5"></textarea>
                        </div>

                        <h5 class="text-center mt-3 mb-3">Informasi Lokasi</h5>

                        <div class="form-group position-relative">
                            <label for="address">Alamat</label>
                            <input type="text" name="address" class="form-control" placeholder="Masukan alamat anda">
                        </div>

                        <div class="form-group position-relative">
                            <label for="country">Negara</label>
                            <select class="form-control select2" name="country" aria-hidden="true" data-placeholder="Pilih negara..." data-scope="<?= $libIonix->Encode('province');?>">
                                <option></option>
                                <?php foreach ($data['modCountry']->fetchData(NULL, false, 'CUSTOM')->orderBy('country_name', 'ASC')->get()->getResult() as $row): ?>
                                    <option value="<?= $row->country_id;?>"><?= ucwords($row->country_name);?> (<?= strtoupper($row->country_iso3);?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="province">Provinsi</label>
                                    <select class="form-control select2" name="province" aria-hidden="true" data-placeholder="Pilih provinsi..." data-scope="<?= $libIonix->Encode('district');?>"></select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="district">Kab/Kota</label>
                                    <select class="form-control select2" name="district" aria-hidden="true" data-placeholder="Pilih kab/kota..." data-scope="<?= $libIonix->Encode('subdistrict');?>"></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="subdistrict">Kecamatan</label>
                                    <select class="form-control select2" name="subdistrict" aria-hidden="true" data-placeholder="Pilih kecamatan..." data-scope="<?= $libIonix->Encode('village');?>"></select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="village">Kelurahan/Desa</label>
                                    <select class="form-control select2" name="village" aria-hidden="true" data-placeholder="Pilih kel/desa..."></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="zipcode">Kode POS</label>
                                    <input type="number" name="zipcode" class="form-control" placeholder="Masukan kode pos" maxlength="5" data-provide="maxlength">
                                </div>
                            </div>
                        </div>

                        <h5 class="text-center mt-3 mb-3">Informasi Kontak</h5>

                        <div class="form-group position-relative">
                            <div class="d-block">
                                <label for="email">Email<code>*</code></label>
                                <div class="float-end">
                                    <p class="text-muted mb-0"><code>ex.</code> <i>xxxxx@xxxxx.xxx</i></p>
                                </div>
                            </div>
                            <input type="email" name="email" class="form-control" placeholder="Masukan alamat email" required>
                        </div>

                        <div class="form-group position-relative">
                            <div class="d-block">
                              <label for="phone">No. Telepon</label>
                                  <div class="float-end">
                                      <p class="text-muted mb-0"><code>ex.</code> <i>08xxxxxxxxxx</i> <code>atau</code> <i>02xx-xxxxxxxx</i></p>
                                  </div>
                            </div>
                            <input type="text" name="phone" class="form-control" placeholder="Masukan nomor telepon" onkeypress="return isNumberKey(event);">
                        </div>
                    <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" onclick="$('#form-profile').submit();">Simpan</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <div class="modal fade" id="modal-password" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Ubah Kata Sandi</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p class="card-description text-center">
                    Setel Ulang <strong>Kata Sandi</strong> Anda pada bagian ini, sebaiknya gunakan <strong>Kata Sandi</strong> yang rumit agar Akun Anda lebih aman.
                  </p>

                  <?= customFormOpen('password');?>
                      <div class="form-group position-relative">
                         <?= inputPassword(false, false);?>
                      </div>

                      <div class="form-group position-relative">
                         <?= inputPasswordConfirmation();?>
                      </div>

                      <div class="alert alert-warning text-center" role="alert">
                          <strong>Kata Sandi</strong> setidaknya harus mengandung Huruf Besar, Huruf Kecil dan Angka.
                      </div>
                  <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" onclick="$('#form-password').submit();">Ganti</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <div class="modal fade" id="modal-telegram" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Hubungkan ke Telegram</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Silahkan klik pada tombol <strong><i>Generate Token</i></strong> untuk mendapatkan <strong><i>Token</i></strong> yang harus dikirimkan ke <a href="https://t.me/<?= $configIonix->telegramBot;?>" target="_blank">Bot Telegram</a> untuk mengaitkan Akun Anda ke Telegram.
                    </p>

                    <div id="gnr-telegram">
                        <div class="d-flex justify-content-center">
                            <div class="button-items">
                                <button type="button" class="btn btn-<?= $configIonix->colorPrimary?> waves-effect waves-light" key="pair-telegram"><i class="mdi mdi-code-greater-than-or-equal me-1"></i>Generate Token</button>
                            </div>
                        </div>
                    </div>

                    <div id="tkn-telegram" class="hidden">
                        <label for="">Token</label>
                        <pre class="text-center"></pre>

                        <div class="alert alert-info text-center mt-4 mb-0" role="alert">
                            Silahkan salin <strong>token</strong> berikut dan kirimkan ke <strong><a href="https://t.me/<?= $configIonix->telegramBot;?>" target="_blank">Bot Telegram</a></strong>.
                            Jika sudah mendapatkan <strong>respon</strong>, silahkan untuk <a href="javascript:void(0)" onclick="location.reload();">me<i>refresh</i></a> halaman ini.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <div class="modal fade" id="modal-social" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Tautkan Media Sosial</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                      Pilih Provider <strong>Media Sosial</strong> yang tersedia dan masukkan <strong>username</strong> Media Sosial yang Anda miliki ke dalam URL di bawah ini.
                      Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('social');?>
                        <div class="form-group position-relative">
                            <label for="sosprov">Provider<code>*</code></label>
                            <select name="sosprov" class="form-control select2" data-placeholder="Pilih provider..." data-scope="<?= $libIonix->Encode('sosprov');?>" required>
                                <option></option>
                                <?php foreach ($libIonix->getQuery('social_provider')->getResult() as $row): ?>
                                  <option value="<?= $libIonix->Encode($row->sosprov_id);?>"><?= ucwords($row->sosprov_name);?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group position-relative">
                            <label for="sosmed">Media Sosial URL<code>*</code></label>
                            <div class="input-group">
                                <div class="input-group-text" key="sosprov-url">URL</div>
                                <input type="text" name="sosmed" class="form-control" placeholder="Masukan username media sosial" required>
                            </div>
                        </div>

                        <div class="alert alert-info text-center mt-4 mb-0" role="alert">
                            Dengan menautkan <strong>Media Sosial</strong>, orang-orang akan dengan mudah menemukan Anda.
                        </div>
                    <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" onclick="$('#form-social').submit();">Tautkan</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'select2/js/select2.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'bootstrap-datepicker/js/bootstrap-datepicker.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js');?>

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/profile.init.js');?>
<?= $this->endSection();?>
