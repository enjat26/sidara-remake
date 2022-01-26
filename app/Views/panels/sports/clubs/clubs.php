<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
<meta name="scope" content="<?= $libIonix->Encode('club'); ?>">
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
            <h4 class="mb-sm-0 font-size-18">Klub Olahraga</h4>

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
                        <h4 class="card-title">Data Klub Olahraga</h4>
                        <p class="card-title-desc text-justify">
                            Kelola setiap Data <strong>Klub Olahraga</strong> yang ada di <strong>Provinsi Banten</strong> pada Halaman ini.
                            Informasi <strong>Klub Olahraga</strong> sangat berguna agar <strong>Publik</strong> dapat mendukung atau bahkan bergabung dengan <strong>Klub</strong> tersebut.
                            <?php if (isStakeholder() == true) : ?>
                                Jika Anda menambah atau melakukan perubahan, data tersebut harus melalui tahap verifikasi dari <strong><?= $companyData->name; ?></strong> sebelum di tayangkan.
                            <?php endif; ?>
                        </p>

                        <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                        <div class="button-items mb-4">
                            <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-club"><i class="mdi mdi-refresh me-1"></i> Segarkan</button>
                            <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-club" data-scope="<?= $libIonix->Encode('club'); ?>" key="add-club"><i class="mdi mdi-plus me-1"></i> Tambah klub olahraga baru</button>
                        </div>
                    </div>

                    <div class="col-lg-6 ms-auto">
                        <div class="card border border-success">
                            <div class="card-header bg-transparent border-success">
                                <h5 class="text-success"><i class="mdi mdi-check-all me-2"></i>Filter Data</h5>
                                <p class="card-text">Anda dapat memfilter data <strong>Klub Olahraga</strong> berdasarkan</p>
                            </div>
                            <div class="card-body">
                                <form id="form-export" class="needs-validation" action="<?= panel_url(uri_segment(1) . '/export/print'); ?>" target="_blank" method="GET" novalidate>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-6">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h5>Provinsi</h5>
                                                    <p class="text-muted mb-0"><strong>Organisasi</strong> akan dikelompokan sesuai <strong>Provinsi</strong></p>
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
                                                    <h5>Daerah</h5>
                                                    <p class="text-muted mb-0"><strong>Organisasi</strong> akan dikelompokan sesuai <strong>Kota/Kabupaten</strong></p>
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
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="$('#form-export').submit();"><i class="mdi mdi-file-pdf text-danger me-1"></i> Cetak/PDF</a>
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

                <table id="dt_clubs" class="table table-striped table-borderless align-middle w-100 mt-2">
                    <thead class="table-<?= $configIonix->colorPrimary;?>">
                        <tr>
                            <th scope="col" class="text-center align-middle">No</th>
                            <th scope="col" class="text-center align-middle">Nama Klub/Periode/Kode</th>
                            <th scope="col" class="text-center align-middle">Ketua Klub</th>
                            <th scope="col" class="text-center align-middle">Asal Daerah</th>
                            <th scope="col" class="text-center align-middle">Organisasi</th>
                            <th scope="col" class="text-center align-middle">Lampiran</th>
                            <th scope="col" class="text-center align-middle">Status</th>
                            <th scope="col" class="text-center align-middle">Dibuat Oleh</th>
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

<div class="modal fade" id="modal-club" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="card-description text-center">
                    Sesuaikan informasi <strong>Klub</strong> pada bidang-bidang dibawah ini.
                    Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                </p>

                <?= customFormOpen('club'); ?>
                <h5 class="text-center my-md-3">Informasi Dasar</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <label for="code">Kode</label>
                            <input type="text" name="code" class="form-control" placeholder="Masukan kode klub" maxlength="25" data-provide="maxlength">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <label for="year">Periode<code>*</code></label>
                            <div class="input-group" id="datepicker-year">
                                <input type="text" name="year" class="form-control" placeholder="Pilih tahun" data-date-container='#datepicker-year' data-provide="yearpicker" data-date-autoclose="true" readonly required>
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group position-relative">
                    <label for="sport_organization_id">Organisasi<code>*</code></label>
                    <select class="form-control select2" name="sport_organization_id" aria-hidden="true" data-placeholder="Pilih organisasi..." required>
                        <option></option>
                        <?php foreach ($data['modOrganization']->fetchData(['sport_organization_created_by' => $userData->user_id,'sport_organization_approve' => 3])->get()->getResult() as $row) : ?>
                            <option value="<?= $row->sport_organization_id; ?>"><?= ucwords($row->sport_organization_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group position-relative">
                    <label for="name">Nama Klub<code>*</code></label>
                    <input type="text" name="name" class="form-control" placeholder="Masukan nama klub" required>
                </div>

                <div class="form-group position-relative">
                    <label for="leader">Nama Ketua<code>*</code></label>
                    <input type="text" name="leader" class="form-control" placeholder="Masukan nama ketua klub" required>
                </div>

                <h5 class="text-center mt-3 mb-3">Informasi Lokasi</h5>

                <div class="form-group position-relative">
                    <label for="address">Alamat</label>
                    <input type="text" name="address" class="form-control" placeholder="Masukan alamat klub">
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
                    <label>Unggah Berkas disini <code>(Max. <?= $configIonix->maximumSize['file']; ?>B)</code></label>
                    <input type="file" name="file" class="dropify" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msword, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, application/pdf" data-max-file-size="<?= $configIonix->maximumSize['file']; ?>" data-show-errors="true" data-allowed-file-extensions="xls xlsx doc docx pdf">
                </div>

                <div class="alert alert-success text-center hidden" role="alert" key="file-existing">
                    <strong>Klub</strong> ini sudah mengunggah lampiran atau berkas. Jika ingin merubahnya, Anda dapat menggungah berkas yang baru.
                </div>

                <div class="alert alert-warning text-center hidden" role="alert" key="file-missing">
                    <strong>Klub</strong> ini belum memiliki lampiran atau berkas yang diunggah, silahkan untuk menggungahnya.
                </div>
                <?= customFormClose(); ?>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                <button type="submit" class="btn btn-primary" onclick="$('#form-club').submit();">Button</button>
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
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'dropify/dist/js/dropify.min.js'); ?>

<?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/sports/clubs.init.js'); ?>
<?= $this->endSection(); ?>