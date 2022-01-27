<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
<meta name="scope" content="<?= $libIonix->Encode('atlet'); ?>">
<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>
<?= link_tag($configIonix->assetsFolder['panel']['library'] . 'select2/css/select2.min.css'); ?>
<?= link_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-datepicker/css/bootstrap-datepicker.min.css'); ?>
<?= link_tag($configIonix->assetsFolder['panel']['library'] . 'dropify/dist/css/dropify.min.css'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Atlet</h4>

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
    <div class="col-lg-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex flex-wrap">
                    <div class="me-3">
                        <p class="text-muted mb-2">Cabang Olahraga</p>
                        <h5 class="mb-0"><?= $libIonix->builderQuery('cabors')->countAllResults(); ?></h5>
                    </div>

                    <div class="avatar-sm ms-auto">
                        <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                            <i class="mdi mdi-bullseye-arrow"></i>
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
                        <p class="text-muted mb-2">Kab/Kota (<strong>Banten</strong>)</p>
                        <h5 class="mb-0"><?= $libIonix->builderQuery('districts')->where(['province_id' => $configIonix->defaultProvince])->countAllResults(); ?></h5>
                    </div>

                    <div class="avatar-sm ms-auto">
                        <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                            <i class="mdi mdi-google-maps"></i>
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
                        <p class="text-muted mb-2">Atlet</p>
                        <h5 class="mb-0"><span data-scope="<?= $libIonix->Encode('atlet'); ?>" data-val="<?= $libIonix->Encode('total'); ?>" key="total"></span></h5>
                    </div>

                    <div class="avatar-sm ms-auto">
                        <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                            <i class="mdi mdi-run"></i>
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
                        <h4 class="card-title">Manajemen Atlet</h4>
                        <p class="card-title-desc text-justify">
                            Kelola setiap <strong>Atlet</strong> pada tingkat <strong>Pelajar/Umum</strong> yang dinaungi dan dibina oleh
                            <?php if (isStakeholder() == false) : ?>
                                <strong><?= $companyData->name; ?></strong> dan <strong>Organisasi Olahraga</strong> lainnya,
                            <?php else : ?>
                                <strong><?= $userData->name; ?></strong>,
                            <?php endif; ?>
                            Anda dapat mengatur informasi dari setiap <strong>Atlet</strong> pada bagian Rincian & Kelola.
                        </p>
                        <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                        <div class="button-items mb-4">
                            <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-atlet"><i class="mdi mdi-refresh me-1"></i> Segarkan</button>
                            <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-atlet" data-scope="<?= $libIonix->Encode('atlet'); ?>" key="add-atlet"><i class="mdi mdi-plus me-1"></i> Tambah atlet baru</button>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="card-title mb-1">Persentase</h4>
                                <p class="text-muted">Grafik ini menggambarkan perbandingan jumlah <strong>Laki-laki</strong> dan <strong>Perempuan</strong> dari seluruh <strong>Atlet</strong>.</p>

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
                                <p class="card-text">Anda dapat memfilter data atlet berdasarkan</p>
                            </div>
                            <div class="card-body">
                                <form id="form-export" class="needs-validation" action="" target="_blank" method="GET" novalidate>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-6">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h5>Jenis Kelamin</h5>
                                                    <p class="text-muted mb-0">Atlet akan dikelompokan berdasarkan <strong>Jenis Kelamin</strong></p>
                                                </div>
                                                <div class="card-footer bg-transparent border-top text-center">
                                                    <div class="form-group">
                                                        <select class="form-control select2" name="filter-gender" aria-hidden="true" data-placeholder="Pilih jenis kelamin...">
                                                            <option></option>
                                                            <option value="L">Laki-laki</option>
                                                            <option value="P">Perempuan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card border">
                                                <div class="card-body">
                                                    <h5>Cabang Olahraga</h5>
                                                    <p class="text-muted mb-0">Atlet akan dikelompokan berdasarkan <strong>Cabang Olahraga</strong></p>
                                                </div>
                                                <div class="card-footer bg-transparent border-top text-center">
                                                    <div class="form-group">
                                                        <select class="form-control select2" name="filter-cabor" aria-hidden="true" data-placeholder="Pilih cabang olahraga...">
                                                            <option></option>
                                                            <?php foreach ($data['modCabor']->fetchData()->get()->getResult() as $row) : ?>
                                                                <option value="<?= $row->cabor_id; ?>"><?= $row->cabor_name; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->

                                        <div class="col-sm-6">
                                            <div class="card border mt-lg-5">
                                                <div class="card-body">
                                                    <h5>Asal Daerah</h5>
                                                    <p class="text-muted mb-0">Atlet akan dikelompokan berdasarkan <strong>Daerah</strong> yang ada di <strong>Provinsi Banten</strong></p>
                                                </div>
                                                <div class="card-footer bg-transparent border-top text-center">
                                                    <div class="form-group">
                                                        <select class="form-control select2" name="filter-area" aria-hidden="true" data-placeholder="Pilih daerah...">
                                                            <option></option>
                                                            <?php foreach ($data['modDistrict']->fetchData(['provinces.province_id' => $configIonix->defaultProvince])->get()->getResult() as $row) : ?>
                                                                <option value="<?= $row->district_id; ?>"><?= $row->district_type . ' ' . $row->district_name; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!-- end col -->
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

                <table id="dt_atlets" class="table table-striped table-borderless align-middle w-100 mt-2">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" width="5%">No</th>
                            <th scope="col" class="text-center">Nama Atlet</th>
                            <th scope="col" class="text-center">Jenis Kelamin</th>
                            <th scope="col" class="text-center">Cabang Olahraga</th>
                            <th scope="col" class="text-center">Tingkat</th>
                            <th scope="col" class="text-center">Asal Daerah</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Aktif</th>
                            <th scope="col" class="text-center">Dibuat Oleh</th>
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

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="card border border-primary">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div id="chartMap" data-scope="<?= $libIonix->Encode('chart'); ?>" data-val="<?= $libIonix->Encode('district'); ?>" style="height: 700px"></div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <h5 class="text-center my-md-3">Wilayah Kota/Kab (Banten)</h5>

                        <div id="percentageMap" data-scope="<?= $libIonix->Encode('percentage'); ?>" data-val="<?= $libIonix->Encode('district'); ?>"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-atlet" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="card-description text-center">
                    Sesuaikan informasi <strong>Atlet</strong> pada bidang-bidang dibawah ini.
                    Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                </p>

                <?= customFormOpen('atlet'); ?>
                <label class="form-label">Foto</label>
                <div class="d-flex align-items-center">
                    <div class="mx-auto" style="height: auto;">
                        <button type="button" class="btn-close text-left" aria-label="Close" style="background: transparent;position: absolute!important;" key="upd-avatar">
                            <i class="mdi mdi-pencil-box-outline text-white font-size-18"></i>
                        </button>
                        <img src="<?= $configIonix->mediaFolder['image'] . 'default/avatar.jpg'; ?>" alt="" class="rounded img-thumbnail" data-src="<?= $configIonix->mediaFolder['image'] . 'default/avatar.jpg'; ?>" style="max-width: 150px" key="avatar">
                    </div>
                </div>

                <div class="hidden">
                    <input id="image" type="file" name="image" accept="image/*" class="form-control">
                </div>

                <h5 class="text-center my-md-3">Informasi Dasar</h5>
                <div class="row">
                    <div class="col-sm-6 col-lg-6">
                        <div class="form-group position-relative">
                            <label for="cabor">Cabang Olahraga<code>*</code></label>
                            <select class="form-control select2" name="cabor" aria-hidden="true" data-placeholder="Pilih cabang olahraga..." data-scope="<?= $libIonix->Encode('type'); ?>" required>
                                <option></option>
                                <?php foreach ($data['modCabor']->fetchData()->get()->getResult() as $row) : ?>
                                    <option value="<?= $row->cabor_id; ?>"><?= $row->cabor_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <div class="form-group position-relative">
                            <label for="name">Nama Atlet<code>*</code></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukan nama lengkap atlet" required>
                        </div>
                    </div>
                </div>

                <div class="form-group position-relative">
                    <label for="level">Tingkat<code>*</code></label>
                    <select class="form-control select2" name="level" aria-hidden="true" data-placeholder="Pilih tingkat..." required>
                        <option></option>
                        <option value="Pelajar">Pelajar</option>
                        <option value="Umum">Umum</option>
                    </select>
                </div>

                <div class="form-group position-relative">
                    <label for="explanation">Keterangan</label>
                    <textarea id="explanation" name="explanation" class="form-control" placeholder="Tuliskan keterangan" rows="5"></textarea>
                </div>

                <h5 class="text-center my-md-3">Informasi Daerah</h5>

                <div class="row">
                    <div class="col-sm-6 col-lg-6">
                        <div class="form-group position-relative">
                            <label for="province">Provinsi<code>*</code></label>
                            <select class="form-control select2" name="province" aria-hidden="true" data-placeholder="Pilih provinsi..." data-scope="<?= $libIonix->Encode('district'); ?>" required>
                                <option></option>
                                <?php foreach ($data['modProvince']->fetchData(['provinces.province_id' => $configIonix->defaultProvince])->get()->getResult() as $row) : ?>
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

                <h5 class="text-center my-md-3">Informasi Lainnya</h5>

                <div class="row">
                    <div class="col-sm-6 col-lg-6">
                        <div class="form-group position-relative">
                            <div class="form-group position-relative">
                                <label for="pob">Tempat Lahir<code>*</code></label>
                                <input type="text" name="pob" class="form-control" placeholder="Masukan tempat lahir" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <div class="form-group position-relative">
                            <label for="dob">Tanggal Lahir<code>*</code></label>
                            <div class="input-group" id="datepicker-dob">
                                <input type="text" name="dob" class="form-control" placeholder="Pilih tanggal" data-date-container='#datepicker-dob' data-provide="datepicker" data-date-autoclose="true" readonly required>
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-lg-6">
                        <div class="form-group position-relative">
                            <label for="gender">Jenis Kelamin<code>*</code></label>
                            <select name="gender" class="form-control select2" data-placeholder="Pilih jenis kelamin..." required>
                                <option></option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <div class="form-group position-relative">
                            <label for="religion">Agama<code>*</code></label>
                            <select name="religion" class="form-control select2" data-placeholder="Pilih agama..." required>
                                <option></option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Budha">Budha</option>
                            </select>
                        </div>
                    </div>
                </div>

                <h5 class="text-center my-md-3">Informasi Kontak</h5>

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
                            <p class="text-muted mb-0"><code>ex.</code> <i>8xxxxxxxxxx</i></p>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="input-group-append">
                            <select name="phoneid" class="form-control">
                                <option value="62">+62</option>
                            </select>
                        </div>
                        <input type="number" name="phone" class="form-control" placeholder="Masukan nomor telepon" minlength="11" maxlength="11" data-provide="maxlength">
                    </div>
                </div>
                <?= customFormClose(); ?>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                <button type="submit" class="btn btn-primary" onclick="$('#form-atlet').submit();">Button</button>
            </div>
        </div>
        <!-- end modal-content -->
    </div>
    <!-- end modal-dialog -->
</div>
<!-- end modal -->

<?php if ($userData->role_access == 100) : ?>
    <div class="modal fade" id="modal-import" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= customFormOpen('import'); ?>
                    <h5 class="text-center my-md-3">Lampiran</h5>

                    <div class="form-group position-relative">
                        <label>Unggah Berkas disini <code>(Max. <?= $configIonix->maximumSize['file']; ?>B)</code></label>
                        <input type="file" name="file" class="dropify" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" data-max-file-size="<?= $configIonix->maximumSize['file']; ?>" data-show-errors="true" data-allowed-file-extensions="xls xlsx" required>
                    </div>
                    <?= customFormClose(); ?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="$('#form-import').submit();">Button</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->
<?php endif; ?>
<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'select2/js/select2.min.js'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'dropify/dist/js/dropify.min.js'); ?>

<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'chart.js/Chart.bundle.min.js'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'amcharts4/core.js'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'amcharts4/charts.js'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'amcharts4/animated.js'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'amcharts4/worldLow.js'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'amcharts4/maps.js'); ?>

<?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/sports/atlets/atlets.init.js'); ?>
<?= $this->endSection(); ?>