<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>
    <meta name="scope" content="<?= $libIonix->Encode('country');?>">
<?= $this->endSection();?>

<?= $this->section('stylesheet');?>

<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Negara</h4>

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
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Data Negara</h4>
                    <p class="card-title-desc text-justify">
                        Negara adalah organisasi kekuasaan yang berdaulat dengan tata pemerintahan yang melaksanakan tata tertib atas orang-orang di daerah tertentu.
                        Negara juga digunakan dalam Aplikasi <strong><?= strtoupper($configIonix->appCode);?></strong> sebagai identitas yang dibutuhkan sebagai <strong>Atribut</strong> dalam informasi seperti Instansi/Badan Usaha, Pengguna, dan lainnya.
                    </p>

                    <div class="button-items mb-4">
                        <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-country"><i class="mdi mdi-refresh me-1"></i>Segarkan</button>
                        <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-country" key="add-country"><i class="mdi mdi-plus me-1"></i>Tambah negara baru</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <ul class="list-inline user-chat-nav text-end">
                        <li class="list-inline-item">
                            <div class="dropdown">
                                <button type="button" class="btn btn-secondary waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="mdi mdi-database-export-outline me-1"></i>Ekspor Data <i class="mdi mdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" data-popper-placement="bottom-start" style="margin: 0px;">
                                    <a class="dropdown-item" href="<?= panel_url('countrys/export/print');?>" target="_blank"><i class="mdi mdi-file-pdf align-middle text-danger me-2"></i>Cetak/PDF</a>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <table id="dt_countrys" class="table table-striped table-borderless align-middle w-100 mt-2">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" width="5%">No</th>
                                <th scope="col" class="text-center">Nama Negara</th>
                                <th scope="col" class="text-center">Ibu Kota</th>
                                <th scope="col" class="text-center">Regional</th>
                                <th scope="col" class="text-center">Lainnya</th>
                                <th scope="col" class="text-center" width="15%">Map</th>
                                <th scope="col" class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                    <!-- end table -->
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="modal fade" id="modal-country" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Sesuaikan informasi <strong>Negara</strong> pada bidang-bidang dibawah ini.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('country');?>
                        <label class="form-label">Gambar</label>
                        <div class="d-flex align-items-center">
                            <div class="mx-auto" style="height: auto;">
                                <button type="button" class="btn-close text-left" aria-label="Close" style="background: transparent;position: absolute!important;" key="upd-avatar">
                                    <i class="mdi mdi-pencil-box-outline text-white font-size-18"></i>
                                </button>
                                <img src="<?= $configIonix->mediaFolder['image'].'default/country-iso3.jpg';?>" alt="" class="rounded img-thumbnail" data-src="<?= $configIonix->mediaFolder['image'].'default/country-iso3.jpg';?>" width="200" key="avatar">
                            </div>
                        </div>

                        <div class="hidden">
                            <input id="image" type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="form-control">
                        </div>

                        <div class="alert alert-warning text-center mt-4" role="alert">
                            Jika Gambar atau Gambar pada <strong>Negara</strong> tidak diberikan atau disematkan, maka akan ditampilkan dengan <strong>Gambar Default</strong>.
                            Format yang diizinkan hanya <strong>JPG, JPEG, dan PNG</strong>.
                        </div>

                        <h5 class="text-center my-md-3">Informasi Dasar</h5>

                        <div class="row">
                          <div class="col-sm-6 col-lg-6">
                            <div class="form-group position-relative">
                                <label for="iso2">Kode ISO 2<code>*</code></label>
                                <input type="text" name="iso2" class="form-control" placeholder="Masukan kode iso2" minlength="3" maxlength="3" data-provide="maxlength" required>
                            </div>
                          </div>
                          <div class="col-sm-6 col-lg-6">
                            <div class="form-group position-relative">
                                <label for="iso3">Kode ISO 3<code>*</code></label>
                                <input type="text" name="iso3" class="form-control" placeholder="Masukan kode iso3" minlength="2" maxlength="2" data-provide="maxlength" required>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="name">Nama Negara<code>*</code></label>
                                    <input type="text" name="name" class="form-control" placeholder="Masukan nama negara" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="capital">Ibu Kota<code>*</code></label>
                                    <input type="text" name="capital" class="form-control" placeholder="Masukan nama ibu kota" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="region">Benua<code>*</code></label>
                                    <input type="text" name="region" class="form-control" placeholder="Masukan nama benua" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="subregion">Regional<code>*</code></label>
                                    <input type="text" name="subregion" class="form-control" placeholder="Masukan nama regional" required>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-center my-md-3">Informasi Lainnya</h5>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="latitude">Latitude<code>*</code></label>
                                    <input type="text" name="latitude" class="form-control" placeholder="Masukan kode latitude" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="longitude">Longitude<code>*</code></label>
                                    <input type="text" name="longitude" class="form-control" placeholder="Masukan kode longitude" required>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info text-center" role="alert">
                            Apa itu <strong>Latitude</strong> dan <strong>Longitude</strong>? <a href="https://insanpelajar.com/latitude-dan-longitude/" target="_blank">Cari tau</a>.
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="currency">Mata Uang<code>*</code></label>
                                    <input type="text" name="currency" class="form-control" placeholder="Masukan mata uang" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="symbol">Simbol Mata Uang<code>*</code></label>
                                    <input type="text" name="symbol" class="form-control" placeholder="Masukan simbol mata uang" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="phone">Kode Telepon<code>*</code></label>
                                    <input type="number" name="phone" class="form-control" placeholder="Masukan kode telepon" minlength="1" maxlength="3" data-provide="maxlength" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                  <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                  <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" onclick="$('#form-country').submit();">Button</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>
    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/area/countrys.init.js');?>
<?= $this->endSection();?>
