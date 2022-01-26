<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>
    <meta name="scope" content="<?= $libIonix->Encode('user');?>">
<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'select2/css/select2.min.css');?>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Pengguna</h4>

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
        <div class="col-lg-3">
            <div class="card blog-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <div class="me-3">
                            <p class="text-muted mb-2">Hak Akses</p>
                            <h5 class="mb-0"><?= $data['modRole']->fetchData()->countAllResults();?></h5>
                        </div>

                        <div class="avatar-sm ms-auto">
                            <div class="avatar-title bg-light rounded-circle text-<?= $configIonix->colorPrimary;?> font-size-20">
                                <i class="mdi mdi-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card mini-stats-wid">
                <div class="card-body">

                    <div class="d-flex flex-wrap">
                        <div class="me-3">
                            <p class="text-muted mb-2"><i>Stakeholder</i></p>
                            <h5 class="mb-0"><span data-val="<?= $libIonix->Encode('total-stakeholder');?>" key="total-stakeholder"></span></h5>
                        </div>

                        <div class="avatar-sm ms-auto">
                            <div class="avatar-title bg-light rounded-circle text-<?= $configIonix->colorPrimary;?> font-size-20">
                                <i class="mdi mdi-account-tie"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card blog-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <div class="me-3">
                            <p class="text-muted mb-2">Total Pengguna</p>
                            <h5 class="mb-0"><span data-val="<?= $libIonix->Encode('total-user');?>" key="total-user"></span></h5>
                        </div>

                        <div class="avatar-sm ms-auto">
                            <div class="avatar-title bg-light rounded-circle text-<?= $configIonix->colorPrimary;?> font-size-20">
                                <i class="mdi mdi-account"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                          <h4 class="card-title">Manajemen Pengguna</h4>
                          <p class="card-title-desc text-justify">
                              Kelola setiap <strong>Pengguna</strong> pada halaman ini, Anda dapat mengatur Informasi dari setiap <strong>Pengguna</strong> yang ada.
                              Perlu diketahui, Anda tidak dapat melakukan aksi apapun terhadap <strong>Pengguna</strong> dengan level diatas Anda.
                          </p>
                          <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                          <div class="button-items mb-4">
                              <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-user"><i class="mdi mdi-refresh me-1"></i>Segarkan</button>
                              <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-user" key="add-user"><i class="mdi mdi-plus me-1"></i>Tambah pengguna baru</button>
                          </div>
                        </div>

                        <div class="col-lg-6 ms-auto">
                          <div class="card border border-success">
                              <div class="card-header bg-transparent border-success">
                                  <h5 class="text-success"><i class="mdi mdi-check-all me-2"></i>Filter Data</h5>
                                  <p class="card-text">Anda dapat memfilter data <strong>Pengguna</strong> berdasarkan</p>
                              </div>
                              <div class="card-body">
                                  <div class="row justify-content-center">
                                      <div class="col-sm-6">
                                          <div class="card border">
                                              <div class="card-body">
                                                  <h5>Hak Akses</h5>
                                                  <p class="text-muted mb-0"><strong>Pengguna</strong> akan dikelompokan sesuai <strong>Hak Akses</strong></p>
                                              </div>
                                              <div class="card-footer bg-transparent border-top text-center">
                                                  <div class="form-group">
                                                      <select class="form-control select2" name="filter-role" aria-hidden="true" data-placeholder="Pilih hak akses...">
                                                          <option></option>
                                                          <?php foreach ($data['modRole']->fetchData(NULL, false, 'CUSTOM')->orderBy('role_access', 'DESC')->get()->getResult() as $row): ?>
                                                              <option value="<?= $row->role_id;?>"><?= $row->role_name;?></option>
                                                          <?php endforeach; ?>
                                                      </select>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- end card -->
                        </div>
                    </div>
                    <!-- end row -->

                    <hr>

                    <div class="row mt-5">
                        <div class="col-12">
                            <table id="dt_users" class="table table-striped table-borderless align-middle w-100 mt-2">
                                <thead class="table-<?= $configIonix->colorPrimary;?>">
                                    <tr>
                                        <th scope="col" class="text-center" width="5%">#</th>
                                        <th scope="col" class="text-center">Nama Pengguna/UUID</th>
                                        <th scope="col" class="text-center">Username</th>
                                        <th scope="col" class="text-center">Email</th>
                                        <th scope="col" class="text-center" width="10%">Hak Akses</th>
                                        <th scope="col" class="text-center" width="10%">Status</th>
                                        <th scope="col" class="text-center" width="10%">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                            <!-- end table -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="modal fade" id="modal-user" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                      Sesuaikan identitas <strong>Pengguna</strong> pada bidang-bidang dibawah ini.
                      Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('user');?>
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

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="username">Username<code>*</code></label>
                                    <input type="text" name="username" class="form-control" placeholder="Masukan username" minlength="3" maxlength="30" data-provide="maxlength" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="password">Kata Sandi</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" placeholder="Masukan kata sandi" minlength="<?= $configIonix->minimumPasswordLength;?>" aria-label="Password" aria-describedby="password-show" autocomplete="off">
                                        <button id="password-show" type="button" class="btn btn-light" tabindex="-1"><i class="mdi mdi-eye-outline"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning text-center" role="alert">
                            Format <strong>Kata Sandi</strong> setidaknya harus mengandung Huruf Besar, Huruf Kecil dan Angka.
                        </div>

                        <div class="form-group position-relative">
                            <label for="name">Nama Lengkap<code>*</code></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukan nama lengkap" required>
                        </div>

                        <h5 class="text-center my-md-3">Infromasi Kontak</h5>

                        <div class="form-group position-relative">
                            <div class="d-block">
                              <label for="email">Email</label>
                              <div class="float-end">
                                  <p class="text-muted mb-0"><code>ex.</code> <i>xxxxx@xxxxx.xxx</i></p>
                              </div>
                            </div>
                            <input type="email" name="email" class="form-control" placeholder="Masukan alamat email">
                        </div>

                        <div class="alert alert-info text-center" role="alert">
                            Jika Anda tidak memberikan <strong>Kata Sandi</strong> pada Akun ini, maka <strong>Kata Sandi</strong> secara otomatis akan diisi dengan <strong><?= $configIonix->passwordDefault;?></strong>.
                        </div>
                    <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" onclick="$('#form-user').submit();">Button</button>
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

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/users/users.init.js');?>
<?= $this->endSection();?>
