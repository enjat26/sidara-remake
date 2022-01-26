<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
    <meta name="scope" content="<?= $libIonix->Encode('asset'); ?>">
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
                <h4 class="mb-sm-0 font-size-18">Sarana & Prasarana</h4>

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
                            <h4 class="card-title">Data Sarana & Prasarana</h4>
                            <p class="card-title-desc text-justify">
                                Kelola setiap informasi <strong>Sarana & Prasarana</strong> pada halaman ini yang berada dalam ruang lingkup <strong>Pemuda</strong>.
                                Anda dapat meyertakan Foto/Gambar serta Konten tambahan dari <strong>Sarana & Prasarana</strong> pada bagian Rincian & Kelola.
                            </p>

                            <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                            <div class="button-items mb-4">
                                <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-asset"><i class="mdi mdi-refresh me-1"></i> Segarkan</button>
                                <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-asset" data-scope="<?= $libIonix->Encode('asset'); ?>" key="add-asset"><i class="mdi mdi-plus me-1"></i> Tambah sarana & prasarana baru</button>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="card-title mb-1">Persentase</h4>
                                    <p class="text-muted">Grafik ini menggambarkan perbandingan jumlah <strong>Sarana & Prasarana</strong> berdasarkan <strong>Tipe</strong>.</p>

                                    <div class="row justify-content-center">
                                        <div class="col-md-6 col-xl-8">
                                            <div class="mt-4 mt-sm-0 mb-4" style="position: relative;">
                                                <canvas id="chartType" data-scope="<?= $libIonix->Encode('chart'); ?>" data-val="<?= $libIonix->Encode('type'); ?>" height="260"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 ms-auto">
                            <div class="card border border-success">
                                <div class="card-header bg-transparent border-success">
                                    <?php if (isStakeholder() == false) : ?>
                                        <div class="float-end category">
                                            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light me-1" data-bs-toggle="modal" data-bs-target="#modal-category" data-scope="<?= $libIonix->Encode('category'); ?>" key="add-category">
                                                <i class="mdi mdi-plus align-middle" data-bs-container=".float-end.category" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Tambah kategori baru" aria-label="Tambah kategori baru"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-info waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-list-category" key="list-category">
                                                <i class="mdi mdi-menu align-middle" data-bs-container=".float-end.category" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Lihat daftar kategori" aria-label="Lihat daftar kategori"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <h5 class="text-success"><i class="mdi mdi-check-all me-2"></i>Filter Data</h5>
                                    <p class="card-text">Anda dapat memfilter data <strong>pramuka</strong> berdasarkan</p>
                                </div>
                                <div class="card-body">
                                    <form id="form-export" class="needs-validation" action="" target="_blank" method="GET" novalidate>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-6">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <h5>Tipe</h5>
                                                        <p class="text-muted mb-0"><strong>Sarana & Prasarana</strong> akan dikelompokan sesuai <strong>Tipe</strong></p>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top text-center">
                                                        <div class="form-group">
                                                            <select class="form-control select2" name="filter-type" aria-hidden="true" data-placeholder="Pilih tipe..." required>
                                                                <option></option>
                                                                <option value="0">Tidak ada tipe</option>
                                                                <option value="A">Tipe A</option>
                                                                <option value="B">Tipe B</option>
                                                                <option value="C">Tipe C</option>
                                                            </select>
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

                    <table id="dt_assets" class="table table-striped table-borderless align-middle w-100 mt-2">
                        <thead class="table-<?= $configIonix->colorPrimary;?>">
                            <tr>
                                <th scope="col" class="text-center" width="5%">No</th>
                                <th scope="col" class="text-center">Nama Sarana/Prasarana</th>
                                <th scope="col" class="text-center">Kategori</th>
                                <th scope="col" class="text-center">Jenis</th>
                                <th scope="col" class="text-center">Tipe</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Oleh</th>
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

    <div class="modal fade" id="modal-list-category" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Kategori Sarana & Prasarana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Perlu diperhatikan bahwa saat menghapus <strong>Kategori</strong>, akan berdampak pada data lainnya yang berkaitan.
                    </p>

                    <?php if ($libIonix->builderQuery('sport_asset_categorys')->countAllResults() == false) : ?>
                        <!-- start row -->
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-6 col-xl-8">
                                <img src="<?= $configIonix->mediaFolder['image'] . 'content/no-result.png'; ?>" alt="" class="img-thumbnail bg-transparent mx-auto" style="border: none;">
                                <p class="text-muted text-center">Maaf, saat ini <strong>Kategori</strong> kosong atau tidak tersedia.</p>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                    <?php else : ?>
                        <div class="category-list p-2" data-simplebar>
                            <?php $i = 1; ?>
                            <?php foreach ($libIonix->builderQuery('sport_asset_categorys')->orderBy('asset_category_id', 'DESC')->get()->getResult() as $row) : ?>
                                <div class="animated-list-item border shadow-none mb-2">
                                    <div class="p-2">
                                        <div class="float-end">
                                            <div class="dropdown dropstart">
                                                <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="putCategory('<?= $libIonix->Encode('category');?>', '<?= $libIonix->Encode($row->asset_category_id);?>');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="javascript:void(0);" data-scope="<?= $libIonix->Encode('category') ?>" data-val="<?= $libIonix->Encode($row->asset_category_id); ?>" key="del-category"><i class="mdi mdi-trash-can-outline align-middle text-danger me-1"></i>Hapus</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <div class="avatar-xs align-self-center me-2">
                                                <div class="avatar-title rounded bg-transparent text-<?= $configIonix->colorPrimary; ?> font-size-20">
                                                    <?= $i++; ?>
                                                </div>
                                            </div>

                                            <div class="overflow-hidden me-auto align-middle">
                                                <h5 class="font-size-13 text-truncate mb-0"><?= $row->asset_category_name; ?></h5>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <div>
                                                <small>
                                                    <?= parseDateDiff($row->asset_category_created_at)->getRelative(); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- end customer list -->
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <div class="modal fade" id="modal-category" role="dialog" aria-labelledby="modal-title" aria-hidden="true" data-scope="<?= $libIonix->Encode('category');?>">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Sesuaikan informasi <strong>Kategori Sarana & Prasarana</strong> pada bidang-bidang dibawah ini.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('category'); ?>
                        <div class="form-group position-relative">
                            <label for="name">Nama Kategori<code>*</code></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukan nama kategori" required>
                        </div>
                    <?= customFormClose(); ?>

                    <div class="alert border-0 border-start border-5 border-info py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-size-18 text-info"><i class="mdi mdi-information-variant"></i></div>
                            <div class="ms-3">
                                <div>
                                    Anda tidak dapat menambahkan <strong>Katagori</strong> yang sama.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="$('#form-category').submit();">Button</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <div class="modal fade" id="modal-asset" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Sesuaikan informasi <strong>Sarana/Prasarana</strong> pada bidang-bidang dibawah ini.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('asset'); ?>
                        <h5 class="text-center mt-3 mb-3">Informasi Atribut</h5>

                        <div class="form-group position-relative">
                            <label for="type">Jenis<code>*</code></label>
                            <select class="form-control select2" name="type" aria-hidden="true" data-placeholder="Pilih jenis..." required>
                                <option></option>
                                <option value="Sarana">Sarana</option>
                                <option value="Prasarana">Prasarana</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="category">Kategori<code>*</code></label>
                                    <select class="form-control select2" name="category" aria-hidden="true" data-placeholder="Pilih kategori..." required>
                                        <option></option>
                                        <?php foreach ($libIonix->builderQuery('sport_asset_categorys')->get()->getResult() as $row) : ?>
                                            <option value="<?= $row->asset_category_id; ?>"><?= $row->asset_category_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="category_type">Tipe<code>*</code></label>
                                    <select class="form-control select2" name="category_type" aria-hidden="true" data-placeholder="Pilih tipe kategori..." required>
                                        <option></option>
                                        <option value="0">Tidak Ada</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php if (isStakeholder() == true) : ?>
                            <div class="alert border-0 border-start border-5 border-info py-2">
                                <div class="d-flex align-items-center">
                                    <div class="font-size-18 text-info"><i class="mdi mdi-information-variant"></i></div>
                                    <div class="ms-3">
                                        <div>
                                            Jika <strong>Kategori</strong> belum terdaftar, silahkan hubungi <strong><?= $companyData->name; ?></strong> untuk menambahkannya.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <h5 class="text-center mt-3 mb-3">Informasi Dasar</h5>

                        <div class="form-group position-relative">
                            <label for="name">Nama Sarana/Prasarana<code>*</code></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukan nama sarana/prasarana" required>
                        </div>

                        <div class="form-group position-relative">
                            <label for="description">Deskripsi<code>*</code></label>
                            <textarea id="description" name="description" class="form-control" placeholder="Deskripsikan sarana/prasarana" rows="5" maxlength="225" data-provide="maxlength" required></textarea>
                        </div>

                        <h5 class="text-center mt-3 mb-3">Informasi Lainnya</h5>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="year">Tahun Pembuatan<code>*</code></label>
                                    <div class="input-group" id="datepicker-year">
                                        <input type="text" name="year" class="form-control" placeholder="Pilih tahun" data-date-container='#datepicker-year' data-provide="yearpicker" data-date-autoclose="true" readonly required>
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="condition">Kondisi<code>*</code></label>
                                    <select class="form-control select2" name="condition" aria-hidden="true" data-placeholder="Pilih kondisi..." required>
                                        <option></option>
                                        <option value="Layak">Layak</option>
                                        <option value="Tidak Layak">Tidak Layak</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-4">
                                <div class="form-group position-relative">
                                    <label for="management">Pengelolaan<code>*</code></label>
                                    <select class="form-control select2" name="management" aria-hidden="true" data-placeholder="Pilih pengelolaan..." required>
                                        <option></option>
                                        <option value="Pemerintah">Pemerintah</option>
                                        <option value="Swasta">Swasta</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-8">
                                <div class="form-group position-relative">
                                    <label for="managedby">Dikelola oleh<code>*</code></label>
                                    <input type="text" name="managedby" class="form-control" placeholder="Masukan nama pengelola" required>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-center mt-3 mb-3">Informasi Lokasi</h5>

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
                    <?= customFormClose(); ?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="$('#form-asset').submit();">Button</button>
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

    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'chart.js/Chart.bundle.min.js'); ?>

    <?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/sports/assets/assets.init.js'); ?>
<?= $this->endSection(); ?>
