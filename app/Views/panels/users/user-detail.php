<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>
    <meta name="scope" content="<?= $libIonix->Encode('user');?>">
    <meta name="params" content="<?= uri_segment(2);?>">
<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'select2/css/select2.min.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'bootstrap-datepicker/css/bootstrap-datepicker.min.css');?>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Kelola Pengguna</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / <?= ucwords(uri_segment(3));?> / @<span key="username"></span></li
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

    <?php if ($data['clientData']->active == false): ?>
        <div class="row justify-content-center mb-4">
            <div class="col-md-6 col-xl-8">
                <div class="alert alert-danger text-center mt-4 mb-0" role="alert">
                    <strong>Pengguna</strong> ini telah di<strong>nonaktifkan</strong> atau <strong>dibanned</strong> dari <strong>sistem</strong>.
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
      <div class="col-md-6 col-xl-4">
          <div class="card overflow-hidden <?= $data['clientData']->active == false ? 'bg-soft bg-danger' : '' ;?>">
              <div class="bg-<?= $configIonix->colorPrimary;?> bg-soft" key="cover"></div>
              <div class="card-body pt-0">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="float-end">
                              <div class="mt-3" key="active"></div>
                          </div>
                          <div class="avatar-md profile-user-wid mb-4" key="avatar"></div>
                          <h5 class="text-truncate" key="fullname">?</h5>
                          <p class="text-muted">
                              <span class="badge rounded-pill font-size-12" style="background-color: <?= hexToRGB($data['clientData']->role_color, 18);?>;color: #<?= $data['clientData']->role_color;?>"><?= $data['clientData']->role_name;?></span>
                          </p>
                      </div>
                  </div>
              </div>
          </div>
          <!-- end card -->

          <div class="card <?= $data['clientData']->active == false ? 'bg-soft bg-danger' : '' ;?>">
              <div class="card-body">
                  <h4 class="card-title mb-4">Informasi Akun</h4>
                  <p class="text-muted">Perlu diketahui bahwa informasi ini tidak dapat dirubah karena menjadi <strong>Identitas</strong> dasar Akun.</p>

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

          <div class="card <?= $data['clientData']->active == false ? 'bg-soft bg-danger' : '' ;?>">
              <div class="card-body">
                  <h4 class="card-title mb-4">Biografi</h4>
                  <p class="text-muted text-justify mb-0" key="bio"></p>
              </div>
          </div>
          <!-- end card -->

          <div class="card <?= $data['clientData']->active == false ? 'bg-soft bg-danger' : '' ;?>">
              <div class="card-body">
                  <h4 class="card-title mb-4">Informasi Lainnya</h4>

                  <table class="table mb-0">
                      <tbody>
                          <tr>
                              <th scope="row" width="30%">Alamat</th>
                              <td>: <span key="address"></span></td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
          <!-- end card -->

          <div class="card <?= $data['clientData']->active == false ? 'bg-soft bg-danger' : '' ;?>">
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
      </div>
      <!-- end col -->
      <div class="col-md-6 col-xl-8">
          <div class="card">
              <div class="card-body">
                  <div class="float-end">
                      <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" onclick="history.back();"><i class="mdi mdi-arrow-left me-1"></i>Kembali</button>
                  </div>
                  <h4 class="card-title">Pengaturan</h4>
                  <p class="card-title-desc">Kelola Akun dan informasi terhadap <strong>Pengguna</strong> ini.</p>

                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                      <li class="nav-item">
                          <a class="nav-link active" data-bs-toggle="tab" href="#profile" role="tab">
                              <span class="d-block d-sm-none"><i class="mdi mdi-card-account-details"></i></span>
                              <span class="d-none d-sm-block">Profil</span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" data-bs-toggle="tab" href="#password" role="tab">
                              <span class="d-block d-sm-none"><i class="mdi mdi-form-textbox-password"></i></span>
                              <span class="d-none d-sm-block">Kata Sandi</span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" data-bs-toggle="tab" href="#settings1" role="tab">
                              <span class="d-block d-sm-none"><i class="mdi mdi-shield-account"></i></span>
                              <span class="d-none d-sm-block">Keamanan</span>
                          </a>
                      </li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content p-2">
                      <div class="tab-pane active" id="profile" role="tabpanel">
                          <div class="py-2">
                              <h3 class="card-caption text-center">Mengubah informasi pribadi</h3>
                              <p class="card-description text-center">
                                Informasi mengenai nama, alamat dan lainnya agar dapat digunakan pada Aplikasi ini. Lengkapi identitas Pribadi sesuai dengan informasi yg ada.
                                Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                              </p>

                              <?= customFormOpen('profile');?>
                                  <h5 class="text-center my-md-3">Kewenangan</h5>

                                  <div class="form-group position-relative">
                                      <label for="roles">Hak Akses<code>*</code></label>
                                      <select class="form-control select2" name="role" aria-hidden="true" data-placeholder="Pilih hak akses..." required>
                                          <option></option>
                                          <?php foreach ($data['modRole']->fetchData(NULL, false, 'CUSTOM')->orderBy('role_access', 'DESC')->get()->getResult() as $row): ?>
                                              <option value="<?= $row->role_code;?>"><?= $row->role_name;?></option>
                                          <?php endforeach; ?>
                                      </select>
                                  </div>

                                  <h5 class="text-center my-md-3">Informasi Dasar</h5>

                                  <div class="form-group position-relative">
                                      <label for="fullname">Nama Lengkap<code>*</code></label>
                                      <input type="text" name="fullname" class="form-control" placeholder="Masukan nama lengkap" required>
                                  </div>

                                  <div class="form-group position-relative">
                                    <label for="bio">Biografi</label>
                                    <textarea id="bio" name="bio" class="form-control" placeholder="Deskripsikan biografi pengguna" rows="5"></textarea>
                                  </div>

                                  <h5 class="text-center mt-3 mb-3">Informasi Lokasi</h5>

                                  <div class="form-group position-relative">
                                      <label for="address">Alamat</label>
                                      <input type="text" name="address" class="form-control" placeholder="Masukan alamat pengguna">
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
                                          <label for="email">Email</label>
                                          <div class="float-end">
                                              <p class="text-muted mb-0"><code>ex.</code> <i>xxxxx@xxxxx.xxx</i></p>
                                          </div>
                                      </div>
                                      <input type="email" name="email" class="form-control" placeholder="Masukan alamat email">
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

                                  <div class="py-2 border-top">
                                      <div class="text-end">
                                          <button type="reset" class="btn btn-secondary waves-effect waves-light">Batal</button>
                                          <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" key="upd-user">Simpan</button>
                                      </div>
                                  </div>
                              <?= customFormClose();?>
                          </div>
                      </div>
                      <div class="tab-pane" id="password" role="tabpanel">
                          <div class="py-2">
                              <h3 class="card-caption text-center">Mengubah kata sandi</h3>
                              <p class="card-description text-center">
                                Setel Ulang <strong>Kata Sandi</strong> pada bagian ini, sebaiknya gunakan <strong>Kata Sandi</strong> yang rumit agar Akun lebih aman.
                              </p>

                              <?= customFormOpen('password');?>
                                  <div class="form-group position-relative">
                                     <?= inputPassword(false, false);?>
                                  </div>

                                  <div class="form-group position-relative">
                                     <?= inputPasswordConfirmation();?>
                                  </div>

                                  <div class="alert alert-warning text-center" role="alert">
                                      Format <strong>Kata Sandi</strong> setidaknya harus mengandung Huruf Besar, Huruf Kecil dan Angka.
                                  </div>

                                  <div class="py-2 border-top">
                                      <div class="text-end">
                                          <button type="reset" class="btn btn-secondary waves-effect waves-light">Batal</button>
                                          <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" data-scope="<?= $libIonix->Encode('password');?>" key="upd-password">Ganti</button>
                                      </div>
                                  </div>
                              <?= customFormClose();?>
                          </div>
                      </div>
                      <div class="tab-pane" id="settings1" role="tabpanel">
                          <div class="py-2">
                              <h5 class="text-<?= $configIonix->colorPrimary;?>">Safe Mode</h5>
                              <p class="text-muted text-justify">
                                Fitur ini akan <strong>otomatis</strong> menyaring dan menyembunyikan informasi pribadi <strong>Pengguna</strong> ini dari orang lain saat melihat profil.
                                Perlu diketahui bahwa fitur ini hanya dapat digunakan oleh <strong>Pengguna</strong> ini pada Halaman Profil, jadi Anda tidak dapat melakukan aksi apapun.
                              </p>

                              <?php if ($data['clientData']->safe_mode == true): ?>
                                    <div class="alert alert-success text-center mt-4 mb-0" role="alert">
                                        <strong>Pengguna</strong> ini mengaktifkan <strong>Keamanan Privasi</strong>.
                                    </div>
                                  <?php else: ?>
                                    <div class="alert alert-warning text-center mt-4 mb-0" role="alert">
                                        <strong>Pengguna</strong> ini tidak menggunakan atau belum mengaktifkan <strong>Keamanan Privasi</strong>.
                                    </div>
                              <?php endif; ?>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- end col -->
    </div>
    <!-- end row -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'select2/js/select2.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'bootstrap-datepicker/js/bootstrap-datepicker.min.js');?>

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/users/user-detail.init.js');?>

    <?php if ($data['clientData']->active == false): ?>
        <script type="text/javascript">
            $('.form-control').prop('disabled', true),
            $('form button').prop('disabled', true);
        </script>
    <?php endif; ?>
<?= $this->endSection();?>
