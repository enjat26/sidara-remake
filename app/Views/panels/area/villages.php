<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>
    <meta name="scope" content="<?= $libIonix->Encode('village');?>">
<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'select2/css/select2.min.css');?>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Desa/Kelurahan</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / <?= $configIonix->homePage === true ? ucwords($uri->getSegment(2)) : ucwords($uri->getSegment(1)) ;?></li>
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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                          <h4 class="card-title mb-3">Data Desa/Kelurahan</h4>
                          <p class="card-title-desc text-justify">
                              Desa/Kelurahan merupakan wilayah kerja lurah sebagai perangkat daerah kabupaten atau kota.
                              Desa/Kelurahan dipimpin oleh seorang <strong>Lurah/Kepala Desa</strong> dan terbagi menjadi dua bagian yaitu Desa dan Kelurahan.
                          </p>
                          <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                          <div class="button-items mb-4">
                              <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-village"><i class="mdi mdi-refresh me-1"></i>Segarkan</button>
                              <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-village" key="add-village"><i class="mdi mdi-plus me-1"></i>Tambah desa/kelurahan baru</button>
                          </div>
                        </div>

                        <div class="col-lg-6 ms-auto">
                          <div class="card border border-success">
                              <div class="card-header bg-transparent border-success">
                                  <h5 class="text-success"><i class="mdi mdi-check-all me-2"></i>Filter Data</h5>
                                  <p class="card-text">Anda dapat memfilter data <strong>Desa/Kelurahan</strong> berdasarkan</p>
                              </div>
                              <div class="card-body">
                                  <div class="row justify-content-center">
                                      <div class="col-sm-6">
                                          <div class="card border">
                                              <div class="card-body">
                                                  <h5>Negara</h5>
                                                  <p class="text-muted mb-0"><strong>Desa/Kelurahan</strong> akan dikelompokan sesuai <strong>Negara</strong></p>
                                              </div>
                                              <div class="card-footer bg-transparent border-top text-center">
                                                  <div class="form-group">
                                                      <select class="form-control select2" name="filter-country" aria-hidden="true" data-placeholder="Pilih negara..." data-scope="<?= $libIonix->Encode('province');?>">
                                                          <option></option>
                                                          <?php foreach ($data['modCountry']->fetchData(NULL, false, 'CUSTOM')->orderBy('country_name', 'ASC')->get()->getResult() as $row): ?>
                                                              <option value="<?= $row->country_id;?>"><?= ucwords($row->country_name);?> (<?= strtoupper($row->country_iso3);?>)</option>
                                                          <?php endforeach; ?>
                                                      </select>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="card border">
                                              <div class="card-body">
                                                  <h5>Provinsi</h5>
                                                  <p class="text-muted mb-0"><strong>Desa/Kelurahan</strong> akan dikelompokan sesuai <strong>Provinsi</strong></p>
                                              </div>
                                              <div class="card-footer bg-transparent border-top text-center">
                                                  <div class="form-group">
                                                      <select class="form-control select2" name="filter-province" aria-hidden="true" data-placeholder="Pilih provinsi..." data-scope="<?= $libIonix->Encode('district');?>"></select>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="col-sm-6 mt-md-5">
                                          <div class="card border">
                                              <div class="card-body">
                                                  <h5>Daerah</h5>
                                                  <p class="text-muted mb-0"><strong>Desa/Kelurahan</strong> akan dikelompokan sesuai <strong>Kota/Kabupaten</strong></p>
                                              </div>
                                              <div class="card-footer bg-transparent border-top text-center">
                                                  <div class="form-group">
                                                      <select class="form-control select2" name="filter-district" aria-hidden="true" data-placeholder="Pilih kota/kab..." data-scope="<?= $libIonix->Encode('subdistrict');?>"></select>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="card border">
                                              <div class="card-body">
                                                  <h5>Kecamatan</h5>
                                                  <p class="text-muted mb-0"><strong>Desa/Kelurahan</strong> akan dikelompokan sesuai <strong>Kecamatan</strong></p>
                                              </div>
                                              <div class="card-footer bg-transparent border-top text-center">
                                                  <div class="form-group">
                                                      <select class="form-control select2" name="filter-subdistrict" aria-hidden="true" data-placeholder="Pilih kecamatan..."></select>
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
                            <table id="dt_villages" class="table table-striped table-borderless align-middle w-100 mt-2">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center" width="5%">No</th>
                                        <th scope="col" class="text-center">Nama Desa/Kelurahan</th>
                                        <th scope="col" class="text-center">Kecamatan</th>
                                        <th scope="col" class="text-center">Daerah</th>
                                        <th scope="col" class="text-center">Provinsi</th>
                                        <th scope="col" class="text-center">Negara</th>
                                        <th scope="col" class="text-center" width="15%">Map</th>
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

    <div class="modal fade" id="modal-village" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Sesuaikan informasi <strong>Desa/Kelurahan</strong> pada bidang-bidang dibawah ini.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('village');?>
                        <h5 class="text-center my-md-3">Wilayah</h5>

                        <div class="form-group position-relative">
                            <label for="country">Negara<code>*</code></label>
                            <select class="form-control select2" name="country" aria-hidden="true" data-placeholder="Pilih negara..." data-scope="<?= $libIonix->Encode('province');?>" required>
                                <option></option>
                                <?php foreach ($data['modCountry']->fetchData(NULL, false, 'CUSTOM')->orderBy('country_name', 'ASC')->get()->getResult() as $row): ?>
                                    <option value="<?= $row->country_id;?>"><?= ucwords($row->country_name);?> (<?= strtoupper($row->country_iso3);?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group position-relative">
                                    <label for="province">Provinsi<code>*</code></label>
                                    <select class="form-control select2" name="province" aria-hidden="true" data-placeholder="Pilih provinsi..." data-scope="<?= $libIonix->Encode('district');?>" required></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative">
                                    <label for="district">Kota/Kabupaten<code>*</code></label>
                                    <select class="form-control select2" name="district" aria-hidden="true" data-placeholder="Pilih kota/kab..." data-scope="<?= $libIonix->Encode('subdistrict');?>" required></select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group position-relative">
                            <label for="subdistrict">Kecamatan<code>*</code></label>
                            <select class="form-control select2" name="subdistrict" aria-hidden="true" data-placeholder="Pilih kecamatan..." required></select>
                        </div>

                        <h5 class="text-center my-md-3">Informasi Dasar</h5>

                        <div class="row">
                            <div class="col-md-6 col-xl-4">
                                <div class="form-group position-relative">
                                    <label for="type">Jenis<code>*</code></label>
                                    <select class="form-control select2" name="type" aria-hidden="true" data-placeholder="Pilih jenis..." required>
                                      <option></option>
                                      <option value="Kel.">Kelurahan</option>
                                      <option value="Desa">Desa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-8">
                                <div class="form-group position-relative">
                                    <label for="name">Nama Desa/Kelurahan<code>*</code></label>
                                    <input type="text" name="name" class="form-control" placeholder="Masukan nama desa/kelurahan" required>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-center my-md-3">Informasi Lainnya</h5>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="latitude">Latitude</label>
                                    <input type="text" name="latitude" class="form-control" placeholder="Masukan kode latitude">
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" name="longitude" class="form-control" placeholder="Masukan kode longitude">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info text-center" role="alert">
                            <strong>Latitude</strong> dan <strong>Longitude</strong> boleh dikosongkan, lalu apa itu <strong>Latitude</strong> dan <strong>Longitude</strong>? <a href="https://insanpelajar.com/latitude-dan-longitude/" target="_blank">Cari tau</a>.
                        </div>
                    <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" onclick="$('#form-village').submit();">Button</button>
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

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/area/villages.init.js');?>
<?= $this->endSection();?>
