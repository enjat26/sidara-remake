<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
    <meta name="scope" content="<?= $libIonix->Encode('achievement'); ?>">
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
                <h4 class="mb-sm-0 font-size-18">Prestasi Olahraga</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name; ?> / Achievement / Sports</li>
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
                            <h4 class="card-title">Data Prestasi Olahraga</h4>
                            <p class="card-title-desc text-justify">
                                Kelola <strong>Prestasi</strong> yang telah diraih oleh para <strong>Atlet</strong> dengan dengan memberikan informasi dari <strong>Kejuaraan</strong> yang diikuti sampai perolehan Medali.
                            </p>

                            <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                            <div class="button-items mb-4">
                                <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-achievement"><i class="mdi mdi-refresh me-1"></i> Segarkan</button>
                                <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-achievement" data-scope="<?= $libIonix->Encode('achievement'); ?>" data-val="add" key="add-achievement"><i class="mdi mdi-plus me-1"></i> Tambah prestasi baru</button>
                            </div>
                        </div>

                        <div class="col-lg-6 ms-auto">
                            <div class="card border border-success">
                                <div class="card-header bg-transparent border-success">
                                    <h5 class="text-success"><i class="mdi mdi-check-all me-2"></i>Filter Data</h5>
                                    <p class="card-text">Anda dapat memfilter data <strong>Prestasi Olahraga</strong> berdasarkan</p>
                                </div>
                                <div class="card-body">
                                    <form id="form-export" class="needs-validation" action="<?= panel_url(uri_segment(1) . '/export/print'); ?>" target="_blank" method="GET" novalidate>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-6">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <h5>Tahun</h5>
                                                        <p class="text-muted mb-0">Prestasi akan dikelompokan berdasarkan <strong>Tahun</strong></p>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top text-center">
                                                        <div class="form-group position-relative">
                                                            <select class="form-control select2" name="filter-year" aria-hidden="true" data-placeholder="Pilih tahun...">
                                                                <option></option>
                                                                <?php if ($data['modAchievement']->countAllResults() == false) : ?>
                                                                    <option value="<?= date('Y'); ?>"><?= date('Y'); ?></option>
                                                                <?php else : ?>
                                                                    <?php foreach ($data['modAchievement']->fetchData()->groupBy('sport_championships.sport_championship_year')->orderBy('sport_championships.sport_championship_year', 'DESC')->distinct()->get()->getResult() as $row) : ?>
                                                                        <option value="<?= $row->sport_championship_year; ?>"><?= $row->sport_championship_year; ?></option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
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
                                                <p class="text-muted">Ekspor Data akan disesuaikan berdasarkan <i>Filter</i> yang dipilih.</p>
                                            </div>

                                            <div class="col-md-6 col-xl-5">
                                                <div class="d-grid">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="mdi mdi-export me-1"></i> Ekspor Data <i class="mdi mdi-chevron-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="$('#form-export').submit();"><i class="mdi mdi-file-pdf text-danger me-1"></i>Cetak/PDF</a>
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

                    <table id="dt_achievements" class="table table-striped table-borderless align-middle w-100 mt-2">
                        <thead class="table-<?= $configIonix->colorPrimary;?>">
                            <tr>
                                <th scope="col" class="text-center align-middle" rowspan="2">No</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Nama Atlet</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Cabang Olaharaga</th>
                                <th scope="col" class="text-center align-middle" colspan="2">Kejuaraan</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Tahun</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Medali</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Status</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Dibuat Oleh</th>
                                <th scope="col" class="text-center align-middle" rowspan="2" width="10%">Aksi</th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center">Nama/Nomor/Kelas</th>
                                <th scope="col" class="text-center">Hasil</th>
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

    <div class="modal fade" id="modal-achievement" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Sesuaikan informasi <strong>Prestasi Olahraga</strong> pada bidang-bidang dibawah ini.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('achievement');?>
                        <h5 class="text-center my-md-3">Informasi Kejuaraan</h5>

                        <div class="form-group position-relative">
                            <label for="championship">Kejuaraan<code>*</code></label>
                            <select class="form-control select2" name="championship" aria-hidden="true" data-placeholder="Pilih kejuaraan..." data-scope="<?= $libIonix->Encode('atlet');?>" required>
                                <option></option>
                                <?php foreach ($data['modChampionship']->fetchData()->get()->getResult() as $row) : ?>
                                    <option value="<?= $row->sport_championship_id; ?>"><?= $row->sport_championship_name;?> (<?= $row->sport_championship_year;?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group position-relative">
                            <label for="atlet">Atlet<code>*</code></label>
                            <select class="form-control select2" name="atlet" aria-hidden="true" data-placeholder="Pilih atlet..." required></select>
                        </div>

                        <h5 class="text-center my-md-3">Informasi Dasar</h5>

                        <div class="form-group position-relative">
                            <label for="number">Nomor/Kelas</label>
                            <input type="text" name="number" class="form-control" placeholder="Masukan nama nomor/kelas">
                        </div>

                        <div class="form-group position-relative">
                            <label for="medal">Medali<code>*</code></label>
                            <select class="form-control select2" name="medal" aria-hidden="true" data-placeholder="Pilih medali..." required>
                                <option></option>
                                <?php foreach ($data['modMedal']->fetchData()->get()->getResult() as $row) : ?>
                                    <option value="<?= $row->sport_medal_id; ?>"><?= $row->sport_medal_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group position-relative">
                            <label for="result">Hasil Pertandingan</label>
                            <textarea id="result" name="result" class="form-control" placeholder="Tuliskan hasil pertandingan" rows="5"></textarea>
                        </div>
                    <?= customFormClose();?>

                    <div class="alert border-0 border-start border-5 border-info py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-size-18 text-info"><i class="mdi mdi-information-variant"></i></div>
                            <div class="ms-3">
                                <div>
                                    Anda tidak dapat menambahkan <strong>Prestasi Olahraga</strong> jika <strong>Kejuaraan</strong> belum didaftarkan dan <strong>Atlet</strong> belum diikut sertakan dalam <strong>Kejuaraan</strong> tersebut.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="$('#form-achievement').submit();">Button</button>
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

    <?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/sports/achievements.init.js'); ?>
<?= $this->endSection(); ?>
