<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
    <meta name="scope" content="<?= $libIonix->Encode('championship'); ?>">
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
            <h4 class="mb-sm-0 font-size-18">Kejuaraan Olahraga</h4>

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
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <h4 class="card-title">Data Kejuaraan Olahraga</h4>
                        <p class="card-title-desc text-justify">
                            Kelola setiap Data <strong>Kejuaraan Olahraga</strong> yang sudah diikuti pada Halaman ini.
                            Anda dapat mengikutsertakan para <strong>Atlet</strong> dalam setiap kejuaraan pada bagian Rincian & Kelola.
                            <?php if (isStakeholder() == true): ?>
                                Jika Anda menambah atau melakukan perubahan, data tersebut harus melalui tahap verifikasi dari <strong><?= $companyData->name;?></strong> sebelum di tayangkan.
                            <?php endif; ?>
                        </p>

                        <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                        <div class="button-items mb-4">
                            <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-championship"><i class="mdi mdi-refresh me-1"></i> Segarkan</button>
                            <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-championship" data-scope="<?= $libIonix->Encode('championship'); ?>" key="add-championship"><i class="mdi mdi-plus me-1"></i> Tambah kejuaraan olahraga baru</button>
                        </div>
                    </div>

                    <div class="col-lg-6 ms-auto">
                        <div class="card border border-success">
                            <div class="card-header bg-transparent border-success">
                                <h5 class="text-success"><i class="mdi mdi-check-all me-2"></i>Filter Data</h5>
                                <p class="card-text">Anda dapat memfilter data <strong>Kejuaraan Olahraga</strong> berdasarkan</p>
                            </div>
                            <div class="card-body">
                                <form id="form-export" class="needs-validation" action="<?= panel_url(uri_segment(1).'/export/print');?>" target="_blank" method="GET" novalidate>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-6">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h5>Tahun</h5>
                                                    <p class="text-muted mb-0">Data Kejuaraan akan dikelompokan berdasarkan <strong>Tahun</strong></p>
                                                </div>
                                                <div class="card-footer bg-transparent border-top text-center">
                                                    <div class="form-group">
                                                        <select class="form-control select2" name="filter-year" aria-hidden="true" data-placeholder="Pilih tahun...">
                                                            <option></option>
                                                            <?php foreach ($data['modChampionship']->fetchData(NULL, false, 'CUSTOM')->groupBy('sport_championship_year')->orderBy('sport_championship_year', 'DESC')->distinct()->get()->getResult() as $row) : ?>
                                                                <option value="<?= $row->sport_championship_year; ?>"><?= $row->sport_championship_year; ?></option>
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
                                            <p class="text-muted">Ekspor Data akan disesuaikan berdasarkan <strong>Filter Data</strong>.</p>
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

                <table id="dt_championships" class="table table-striped table-borderless align-middle w-100 mt-2">
                    <thead class="table-<?= $configIonix->colorPrimary;?>">
                        <tr>
                            <th scope="col" class="text-center align-middle">No</th>
                            <th scope="col" class="text-center align-middle">Nama Kejuaraan/Kode</th>
                            <th scope="col" class="text-center align-middle">Tingkat</th>
                            <th scope="col" class="text-center align-middle">Tahun</th>
                            <th scope="col" class="text-center align-middle">Lokasi</th>
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

<div class="modal fade" id="modal-championship" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="card-description text-center">
                    Sesuaikan informasi <strong>Kejuaraan</strong> yang diikuti pada bidang-bidang dibawah ini.
                    Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                </p>

                <?= customFormOpen('championship'); ?>
                    <h5 class="text-center my-md-3">Informasi Dasar</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="code">Kode</label>
                                <input type="text" name="code" class="form-control" placeholder="Masukan kode kejuaraan" maxlength="25" data-provide="maxlength">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="year">Tahun<code>*</code></label>
                                <div class="input-group" id="datepicker-year">
                                    <input type="text" name="year" class="form-control" placeholder="Pilih tahun" data-date-container='#datepicker-year' data-provide="yearpicker" data-date-autoclose="true" readonly required>
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group position-relative">
                        <label for="name">Nama Kejuaraan<code>*</code></label>
                        <input type="text" name="name" class="form-control" placeholder="Masukan nama kejuaraan" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="level">Tingkat<code>*</code></label>
                                <select class="form-control select2" name="level" aria-hidden="true" data-placeholder="Pilih tingkat..." required>
                                    <option></option>
                                    <option value="Internasional">Internasional</option>
                                    <option value="Nasional">Nasional</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="category">Kategori<code>*</code></label>
                                <select class="form-control select2" name="category" aria-hidden="true" data-placeholder="Pilih kategori..." required></select>
                            </div>
                        </div>
                    </div>

                    <h5 class="text-center mt-3 mb-3">Informasi Lainnya</h5>

                    <div class="form-group position-relative">
                        <label for="location">Lokasi</label>
                        <input type="text" name="location" class="form-control" placeholder="Masukan lokasi kejuaraan">
                    </div>

                    <div class="form-group position-relative">
                        <label for="explanation">Keterangan</label>
                        <textarea name="explanation" class="form-control" placeholder="Tuliskan keterangan" rows="5"></textarea>
                    </div>
                <?= customFormClose(); ?>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                <button type="submit" class="btn btn-primary" onclick="$('#form-championship').submit();">Button</button>
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

    <?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/sports/championships/championships.init.js'); ?>
<?= $this->endSection(); ?>
