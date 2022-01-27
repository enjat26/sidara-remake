<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
<meta name="scope" content="<?= $libIonix->Encode('training'); ?>">
<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>
<?= link_tag($configIonix->assetsFolder['panel']['library'] . 'select2/css/select2.min.css'); ?>
<?= link_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-datepicker/css/bootstrap-datepicker.min.css'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Pelatihan</h4>

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
                        <h4 class="card-title">Data Pelatihan</h4>
                        <p class="card-title-desc text-justify">
                            Kelola <strong>Data Pelatihan</strong> yang telah atau belum dilaksanakan,
                            Untuk menambah <strong>Peserta</strong>, Anda dapat melakukannya pada bagian Rincian & Kelola.
                        </p>
                        <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                        <div class="button-items mb-4">
                            <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-training"><i class="mdi mdi-refresh me-1"></i> Segarkan</button>
                            <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-training" data-scope="<?= $libIonix->Encode('training'); ?>" key="add-training"><i class="mdi mdi-plus me-1"></i> Tambah pelatihan baru</button>
                        </div>
                    </div>

                    <div class="col-lg-6 ms-auto">
                        <div class="card border border-success">
                            <div class="card-header bg-transparent border-success">
                                <h5 class="text-success"><i class="mdi mdi-check-all me-2"></i>Filter Data</h5>
                                <p class="card-text">Anda dapat memfilter data <strong>Pelatihan</strong> berdasarkan</p>
                            </div>
                            <div class="card-body">
                                <form id="form-export" class="needs-validation" action="" target="_blank" method="GET" novalidate>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-6">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h5>Tahun</h5>
                                                    <p class="text-muted mb-0">Data Pelatihan akan dikelompokan berdasarkan <strong>Tahun</strong></p>
                                                </div>
                                                <div class="card-footer bg-transparent border-top text-center">
                                                    <div class="form-group">
                                                        <select class="form-control select2" name="filter-year" aria-hidden="true" data-placeholder="Pilih tahun...">
                                                            <option></option>
                                                            <?php foreach ($data['modTraining']->fetchData(NULL, false, 'CUSTOM')->groupBy('youth_training_year')->orderBy('youth_training_year', 'DESC')->distinct()->get()->getResult() as $row) : ?>
                                                                <option value="<?= $row->youth_training_year; ?>"><?= $row->youth_training_year; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <div class="row">
                                        <div class="col-md-6 col-xl-7">
                                            <p class="text-muted mb-0">Ekspor Data akan disesuaikan berdasarkan <strong>Filter Data</strong>.</p>
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

                <table id="dt_trainings" class="table table-striped table-borderless align-middle w-100 mt-2">
                    <thead class="table-<?= $configIonix->colorPrimary; ?>">
                        <tr>
                            <th scope="col" class="text-center align-middle" rowspan="2">No</th>
                            <th scope="col" class="text-center align-middle" rowspan="2">Nama Pelatihan</th>
                            <th scope="col" class="text-center align-middle" rowspan="2">Tahun</th>
                            <th scope="col" class="text-center align-middle" colspan="2">Waktu</th>
                            <th scope="col" class="text-center align-middle" rowspan="2">Peserta</th>
                            <th scope="col" class="text-center align-middle" rowspan="2" width="10%">Aksi</th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-center">Tanggal</th>
                            <th scope="col" class="text-center">Lokasi</th>
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

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="card border border-primary">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-2">Grafik Pelatihan</h4>
                    <p class="text-muted">Grafik ini menggambarkan perbandingan jumlah <strong>Peserta</strong> berdasarkan <strong>Pelatihan</strong> pada setiap tahunnya.</p>

                    <div id="chartTraining" class="apex-charts" data-scope="<?= $libIonix->Encode('chart'); ?>" data-val="<?= $libIonix->Encode('training'); ?>" dir="ltr"></div>
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
    </div>
</div>

<div class="modal fade" id="modal-training" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="card-description text-center">
                    Sesuaikan informasi <strong>Pelatihan</strong> berdasarkan <strong>Daerah</strong> pada bidang-bidang dibawah ini.
                    Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                </p>

                <?= customFormOpen('training'); ?>
                <h5 class="text-center my-md-3">Informasi Waktu & Lokasi</h5>

                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <div class="form-group position-relative">
                            <label for="year">Tahun<code>*</code></label>
                            <div class="input-group" id="datepicker-year">
                                <input type="text" name="year" class="form-control" placeholder="Pilih tahun" data-date-container='#datepicker-year' data-provide="yearpicker" data-date-autoclose="true" readonly required>
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6">
                        <div class="form-group position-relative">
                            <label for="dob">Tanggal<code>*</code></label>
                            <div class="input-group" id="datepicker-date">
                                <input type="text" name="date" class="form-control" placeholder="dd/mm/yyyy" data-date-container='#datepicker-date' data-provide="datepicker" data-date-autoclose="true" readonly required>
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
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
                            <select class="form-control select2" name="district" aria-hidden="true" data-placeholder="Pilih kab/kota..." required></select>
                        </div>
                    </div>
                </div>

                <h5 class="text-center my-md-3">Informasi Dasar</h5>

                <div class="form-group position-relative">
                    <label for="name">Nama Pelatihan<code>*</code></label>
                    <input type="text" name="name" class="form-control" placeholder="Masukan nama pelatihan" required>
                </div>

                <div class="form-group position-relative">
                    <label for="explanation">Keterangan</label>
                    <textarea name="explanation" class="form-control" placeholder="Tuliskan keterangan" rows="5"></textarea>
                </div>
                <?= customFormClose(); ?>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                <button type="submit" class="btn btn-primary" onclick="$('#form-training').submit();">Button</button>
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

<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'apexcharts/apexcharts.min.js'); ?>

<?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/youths/trainings/trainings.init.js'); ?>
<?= $this->endSection(); ?>