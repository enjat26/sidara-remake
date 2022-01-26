<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>
    <meta name="scope" content="<?= $libIonix->Encode('district');?>">
<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'select2/css/select2.min.css');?>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Daerah</h4>

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
                  <h4 class="card-title mb-3">Data Daerah (Kota/Kabupaten)</h4>
                  <p class="card-title-desc text-justify">
                      Daerah, dalam konteks pembagian administratif adalah kesatuan masyarakat hukum yang mempunyai batas-batas wilayah yang berwenang mengatur dan
                      mengurus urusan pemerintahan dan kepentingan masyarakat setempat menurut prakarsa sendiri berdasarkan aspirasi masyarakat.
                      Kelola <strong>Kota/Kabupaten</strong> yang dijadikan sebagai <strong>Atribut Wilayah</strong>.
                  </p>

                  <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                  <div class="button-items mb-4">
                      <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-district"><i class="mdi mdi-refresh me-1"></i>Segarkan</button>
                      <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-district" key="add-district"><i class="mdi mdi-plus me-1"></i>Tambah kota/kabupaten baru</button>
                  </div>
              </div>
          </div>
      </div>

      <div class="col-md-6 col-xl-9">
        <div class="card">
          <div class="card-body">
              <div class="card border border-success">
                  <div class="card-header bg-transparent border-success">
                      <h5 class="text-success"><i class="mdi mdi-check-all me-2"></i>Filter Data</h5>
                      <p class="card-text">Anda dapat memfilter data <strong>Daerah</strong> berdasarkan</p>
                  </div>
                  <div class="card-body">
                      <form id="form-export" class="needs-validation" action="<?= panel_url('districts/export/print');?>" target="_blank" method="GET" novalidate>
                          <div class="row justify-content-end">
                              <div class="col-sm-4">
                                  <div class="card border">
                                      <div class="card-body">
                                          <h5>Negara</h5>
                                          <p class="text-muted mb-0"><strong>Daerah</strong> akan dikelompokan sesuai <strong>Negara</strong></p>
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
                              </div>

                              <div class="col-sm-4">
                                  <div class="card border">
                                      <div class="card-body">
                                          <h5>Provinsi</h5>
                                          <p class="text-muted mb-0"><strong>Daerah</strong> akan dikelompokan sesuai <strong>Provinsi</strong></p>
                                      </div>
                                      <div class="card-footer bg-transparent border-top text-center">
                                          <div class="form-group">
                                              <select class="form-control select2" name="filter-province" aria-hidden="true" data-placeholder="Pilih provinsi..."></select>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <div class="col-sm-4">
                                  <div class="card border">
                                      <div class="card-body">
                                          <h5>Jenis Daerah</h5>
                                          <p class="text-muted mb-0"><strong>Daerah</strong> akan dikelompokan sesuai <strong>Jenis</strong></p>
                                      </div>
                                      <div class="card-footer bg-transparent border-top text-center">
                                          <div class="form-group">
                                              <select class="form-control select2" name="filter-type" aria-hidden="true" data-placeholder="Pilih jenis...">
                                                  <option></option>
                                                  <?php foreach ($data['modDistrict']->groupBy('district_type')->distinct()->get()->getResult() as $row): ?>
                                                      <option value="<?= $row->district_type;?>"><?= $row->district_type;?></option>
                                                  <?php endforeach; ?>
                                              </select>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- end row -->

                          <div class="row">
                              <div class="col-md-6 col-xl-7 my-auto">
                                  <p class="text-muted mb-0">Ekspor Data akan ditampilkan berdasarkan filter <strong>Provinsi</strong> yang dipilih.</p>
                              </div>

                              <div class="col-md-6 col-xl-5">
                                  <div class="text-center">
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                              <i class="mdi mdi-database-export-outline me-1"></i>Ekspor Data <i class="mdi mdi-chevron-down"></i>
                                          </button>
                                          <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">
                                              <a class="dropdown-item" href="javascript:void(0);" onclick="$('#form-export').submit();"><i class="mdi mdi-file-pdf text-danger me-1"></i>Cetak/PDF</a>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- end row -->
                      </form>
                  </div>
              </div>
              <!-- end card -->

              <hr class="mt-4 mb-4">

              <table id="dt_districts" class="table table-striped table-borderless align-middle w-100 mt-2">
                  <thead>
                      <tr>
                          <th scope="col" class="text-center" width="5%">No</th>
                          <th scope="col" class="text-center">Jenis Daerah</th>
                          <th scope="col" class="text-center">Nama Daerah</th>
                          <th scope="col" class="text-center">Provinsi</th>
                          <th scope="col" class="text-center">Negara</th>
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

    <div class="modal fade" id="modal-district" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Sesuaikan informasi <strong>Kota/Kabupaten</strong> pada bidang-bidang dibawah ini.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('district');?>
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

                        <div class="form-group position-relative">
                            <label for="province">Provinsi<code>*</code></label>
                            <select class="form-control select2" name="province" aria-hidden="true" data-placeholder="Pilih provinsi..." required></select>
                        </div>

                        <h5 class="text-center my-md-3">Informasi Dasar</h5>

                        <div class="row">
                            <div class="col-md-6 col-xl-4">
                                <div class="form-group position-relative">
                                    <label for="type">Jenis<code>*</code></label>
                                    <select class="form-control select2" name="type" aria-hidden="true" data-placeholder="Pilih jenis..." required>
                                      <option></option>
                                      <option value="Kab.">Kabupaten</option>
                                      <option value="Kota">Kota</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-8">
                                <div class="form-group position-relative">
                                    <label for="name">Nama Daerah<code>*</code></label>
                                    <input type="text" name="name" class="form-control" placeholder="Masukan nama kota/kabupaten" required>
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
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" onclick="$('#form-district').submit();">Button</button>
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

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/area/districts.init.js');?>
<?= $this->endSection();?>
