<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
    <meta name="scope" content="<?= $libIonix->Encode('scout'); ?>">
<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'select2/css/select2.min.css'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-datepicker/css/bootstrap-datepicker.min.css'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'dropify/dist/css/dropify.min.css');?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Prestasi Pramuka</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name; ?> / <?= ucwords(uri_segment(1)); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-12 col-xl-12">
          <div class="alert border-0 border-start border-5 border-info py-2">
              <div class="d-flex align-items-center">
                  <div class="font-size-20 text-info"><i class="mdi mdi-information-variant"></i></div>
                  <div class="ms-3">
                      <div>
                          Halaman ini telah sesuaikan datanya dengan <strong>Tahun</strong> yang dipilih pada saat <strong>Login</strong>.
                      </div>
                  </div>
              </div>
          </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <h4 class="card-title">Data Prestasi Pramuka</h4>
                            <p class="card-title-desc text-justify">
                                Kelola setiap Data <strong>Prestasi Pramuka</strong> yang ada di <strong>Provinsi Banten</strong> pada Halaman ini.
                                Informasi <strong>Prestasi Pramuka</strong> sangat berguna agar <strong>Publik</strong> dapat mengapresiasi <strong>Peserta</strong> yang berprestasi.
                            </p>

                            <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                            <div class="button-items mb-4">
                                <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-scout"><i class="mdi mdi-refresh me-1"></i> Segarkan</button>
                                <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-scout" data-scope="<?= $libIonix->Encode('scout'); ?>" key="add-scout"><i class="mdi mdi-plus me-1"></i> Tambah prestasi pramuka baru</button>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="card-title mb-1">Persentase</h4>
                                    <p class="text-muted">Grafik ini menggambarkan perbandingan jumlah <strong>Laki-laki</strong> dan <strong>Perempuan</strong> dari seluruh <strong>Peserta</strong>.</p>

                                    <div class="row justify-content-center">
                                        <div class="col-md-6 col-xl-8">
                                            <div class="mt-4 mt-sm-0 mb-4" style="position: relative;">
                                                <canvas id="chartGender" data-scope="<?= $libIonix->Encode('chart'); ?>" data-val="<?= $libIonix->Encode('gender'); ?>" height="260"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 ms-auto">
                            <div class="card border border-success">
                                <div class="card-header bg-transparent border-success">
                                    <h5 class="text-success"><i class="mdi mdi-check-all me-2"></i>Filter Data</h5>
                                    <p class="card-text">Anda dapat memfilter data <strong>pramuka</strong> berdasarkan</p>
                                </div>
                                <div class="card-body">
                                    <form id="form-export" class="needs-validation" action="" target="_blank" method="GET" novalidate>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-6">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <h5>Provinsi</h5>
                                                        <p class="text-muted mb-0"><strong>Prestasi Pramuka</strong> akan dikelompokan sesuai <strong>Provinsi</strong></p>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top text-center">
                                                        <div class="form-group">
                                                            <select class="form-control select2" name="filter-province" aria-hidden="true" data-placeholder="Pilih provinsi..." data-scope="<?= $libIonix->Encode('district'); ?>" required>
                                                                <option></option>
                                                                <?php foreach ($data['modProvince']->fetchData(['province_id' => $configIonix->defaultProvince])->get()->getResult() as $row) : ?>
                                                                    <option value="<?= $row->province_id; ?>"><?= ucwords($row->province_name); ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <h5>Kota/Kab</h5>
                                                        <p class="text-muted mb-0"><strong>Prestasi Pramuka</strong> akan dikelompokan sesuai <strong>Kota/Kab</strong></p>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top text-center">
                                                        <div class="form-group">
                                                            <select class="form-control select2" name="filter-district" aria-hidden="true" data-placeholder="Pilih kota/kab..."></select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->

                                        <div class="row">
                                            <div class="col-md-6 col-xl-7">
                                                <p class="text-muted">Ekspor Data akan disesuaikan berdasarkan <i>Filter</i> yang dipilih.</p>
                                            </div>

                                            <div class="col-md-6 col-xl-5">
                                                <div class="d-grid">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="mdi mdi-export me-1"></i> Ekspor Data <i class="mdi mdi-chevron-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">
                                                            <a class="dropdown-item" href="javascript:void(0);" key="export-print"><i class="mdi mdi-printer text-dark me-1"></i> Cetak</a>
                                                            <a class="dropdown-item" href="javascript:void(0);" key="export-pdf"><i class="mdi mdi-file-pdf text-danger me-1"></i> PDF</a>
                                                            <a class="dropdown-item" href="javascript:void(0);" key="export-excel"><i class="mdi mdi-file-excel text-success me-1"></i> Excel</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                        <?= customFormClose(); ?>
                                </div>
                                <!-- end card-body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <hr>

                    <table id="dt_scouts" class="table table-striped table-borderless align-middle w-100 mt-2">
                        <thead class="table-<?= $configIonix->colorPrimary;?>">
                            <tr>
                                <th scope="col" class="text-center align-middle">No</th>
                                <th scope="col" class="text-center align-middle">Nama/JK Peserta</th>
                                <th scope="col" class="text-center align-middle">Nama/Tingkat Kejuaraan</th>
                                <th scope="col" class="text-center align-middle">Kota/Kab</th>
                                <th scope="col" class="text-center align-middle">Lampiran</th>
                                <th scope="col" class="text-center align-middle">Status</th>
                                <th scope="col" class="text-center align-middle">Oleh</th>
                                <th scope="col" class="text-center align-middle" width="10%">Aksi</th>
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

    <div class="modal fade" id="modal-view-scout" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center my-md-3">Informasi Dasar</h5>

                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">Nama Kejuaraan</th>
                                <td>: <span key="championship"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Tingkat</th>
                                <td>: <span key="level"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Penyelenggara</th>
                                <td>: <span key="organizer"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Hasil</th>
                                <td>: <span key="result"></span></td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="text-center my-md-3">Informasi Peserta</h5>

                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">Nama Peserta</th>
                                <td>: <span key="name"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Jenis Kelamin</th>
                                <td>: <span key="gender"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Alamat</th>
                                <td>: <span key="address"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Kota/Kab</th>
                                <td>: <span key="district"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Oleh</th>
                                <td>: <span key="created_by"></span></td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="text-center my-md-3">Lampiran</h5>

                    <span key="file"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <div class="modal fade" id="modal-scout" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Sesuaikan informasi <strong>Prestasi Pramuka</strong> pada bidang-bidang dibawah ini.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('scout'); ?>
                        <h5 class="text-center my-md-3">Informasi Dasar</h5>

                        <div class="form-group position-relative">
                            <label for="championship">Nama Kejuaraan<code>*</code></label>
                            <input type="text" name="championship" class="form-control" placeholder="Masukan nama kejuaraan" required>
                        </div>

                        <div class="form-group position-relative">
                            <label for="level">Tingkat<code>*</code></label>
                            <select name="level" class="form-control select2" data-placeholder="Pilih tingkat kejuaraan..." required>
                                <option></option>
                                <option value="Internasional">Internasional</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Daerah">Daerah</option>
                            </select>
                        </div>

                        <div class="form-group position-relative">
                            <label for="organizer">Penyelenggara</label>
                            <input type="text" name="organizer" class="form-control" placeholder="Masukan nama penyelenggara">
                        </div>

                        <div class="form-group position-relative">
                            <label for="result">Hasil<code>*</code></label>
                            <input type="text" name="result" class="form-control" placeholder="Masukan hasil kejuaraan">
                        </div>

                        <h5 class="text-center mt-3 mb-3">Informasi Peserta</h5>

                        <div class="form-group position-relative">
                            <label for="name">Nama Peserta<code>*</code></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukan nama peserta" required>
                        </div>

                        <div class="form-group position-relative">
                            <label for="gender">Jenis Kelamin<code>*</code></label>
                            <select name="gender" class="form-control select2" data-placeholder="Pilih jenis kelamin..." required>
                                <option></option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group position-relative">
                            <label for="address">Alamat</label>
                            <input type="text" name="address" class="form-control" placeholder="Masukan alamat usaha">
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="province">Provinsi<code>*</code></label>
                                    <select class="form-control select2" name="province" aria-hidden="true" data-placeholder="Pilih provinsi..." data-scope="<?= $libIonix->Encode('district'); ?>" required>
                                        <option></option>
                                        <?php foreach ($data['modProvince']->fetchData(['province_id' => $configIonix->defaultProvince])->get()->getResult() as $row) : ?>
                                            <option value="<?= $row->province_id; ?>"><?= ucwords($row->province_name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="district">Kab/Kota<code>*</code></label>
                                    <select class="form-control select2" name="district" aria-hidden="true" data-placeholder="Pilih kab/kota..." data-scope="<?= $libIonix->Encode('subdistrict'); ?>" required></select>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-center my-md-3">Lampiran</h5>

                        <div class="form-group position-relative">
                            <label>Unggah Berkas disini <code>(Max. <?= $configIonix->maximumSize['file'];?>B)</code></label>
                            <input type="file" name="file" class="dropify" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msword, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, application/pdf" data-max-file-size="<?= $configIonix->maximumSize['file'];?>" data-show-errors="true" data-allowed-file-extensions="xls xlsx doc docx pdf">
                        </div>

                        <div class="alert alert-success text-center hidden" role="alert" key="file-existing">
                            <strong>Prestasi Pramuka</strong> ini sudah mengunggah lampiran atau berkas. Jika ingin merubahnya, Anda dapat menggungah berkas yang baru.
                        </div>

                        <div class="alert alert-warning text-center hidden" role="alert" key="file-missing">
                            <strong>Prestasi Pramuka</strong> ini belum memiliki lampiran atau berkas yang diunggah, silahkan untuk menggungahnya.
                        </div>
                    <?= customFormClose(); ?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="$('#form-scout').submit();">Button</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->
<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'select2/js/select2.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'dropify/dist/js/dropify.min.js');?>

    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'chart.js/Chart.bundle.min.js'); ?>

    <?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/youths/scouts.init.js'); ?>
<?= $this->endSection(); ?>
