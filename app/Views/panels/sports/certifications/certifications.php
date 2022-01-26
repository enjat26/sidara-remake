<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>
    <meta name="scope" content="<?= $libIonix->Encode('certification');?>">
<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'select2/css/select2.min.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'bootstrap-datepicker/css/bootstrap-datepicker.min.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'dropify/dist/css/dropify.min.css');?>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Sertifikasi (Wasit/Juri/Pelatih)</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / <?= ucwords(uri_segment(1));?></li>
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
                            <h4 class="card-title">Data Sertifikasi</h4>
                            <p class="card-title-desc text-justify">
                                Kelola data <strong>Peserta Sertifikasi</strong> Wasit/Juri/Pelatih yang telah dilaksanakan.
                                Data <strong>Sertifikasi</strong> yang terdaftar akan ditayangkan untuk <strong>Publik</strong>.
                            </p>

                            <p class="mb-4">Beberapa aksi yang dapat Anda lakukan.</p>

                            <div class="button-items mb-4">
                                <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-certification"><i class="mdi mdi-refresh me-1"></i> Segarkan</button>
                                <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-certification" data-scope="<?= $libIonix->Encode('certification');?>" key="add-certification"><i class="mdi mdi-plus me-1"></i> Tambah sertifikasi baru</button>
                            </div>
                        </div>

                        <div class="col-lg-6 ms-auto">
                            <div class="card border border-success">
                                <div class="card-header bg-transparent border-success">
                                    <h5 class="text-success"><i class="mdi mdi-check-all me-2"></i>Filter Data</h5>
                                    <p class="card-text">Anda dapat memfilter data <strong>Sertifikasi</strong> berdasarkan</p>
                                </div>
                                <div class="card-body">
                                    <form id="form-export" class="needs-validation" action="<?= panel_url(uri_segment(1).'/export/print');?>" target="_blank" method="GET" novalidate>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-6">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <h5>Tahun</h5>
                                                        <p class="text-muted mb-0">Sertifikasi akan dikelompokan berdasarkan <strong>Tahun</strong></p>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top text-center">
                                                        <div class="form-group">
                                                            <select class="form-control select2" name="filter-year" aria-hidden="true" data-placeholder="Pilih tahun...">
                                                              <option></option>
                                                              <?php foreach ($data['modCertification']->groupBy('sport_certification_year')->orderBy('sport_certification_year', 'DESC')->distinct()->get()->getResult() as $row): ?>
                                                                <option value="<?= $row->sport_certification_year;?>"><?= $row->sport_certification_year;?></option>
                                                              <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card border">
                                                    <div class="card-body">
                                                        <h5>Cabang Olahraga</h5>
                                                        <p class="text-muted mb-0">Sertifikasi akan dikelompokan berdasarkan <strong>Cabang Olahraga</strong></p>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top text-center">
                                                        <div class="form-group">
                                                            <select class="form-control select2" name="filter-cabor" aria-hidden="true" data-placeholder="Pilih cabang olahraga...">
                                                              <option></option>
                                                              <?php foreach ($data['modCabor']->get()->getResult() as $row): ?>
                                                                <option value="<?= $row->sport_cabor_code;?>"><?= $row->sport_cabor_name;?></option>
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
                                                        <h5>Kategori</h5>
                                                        <p class="text-muted mb-0">Sertifikasi akan dikelompokan berdasarkan <strong>Kategori</strong></p>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top text-center">
                                                        <div class="form-group">
                                                            <select class="form-control select2" name="filter-category" aria-hidden="true" data-placeholder="Pilih katagori...">
                                                              <option></option>
                                                              <?php foreach ($data['modCertification']->groupBy('sport_certification_category')->orderBy('sport_certification_category', 'ASC')->distinct()->get()->getResult() as $row): ?>
                                                                <option value="<?= $row->sport_certification_category;?>"><?= $row->sport_certification_category;?></option>
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
                                    <?= customFormClose();?>
                                </div>
                                <!-- end card-body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <hr>

                    <?php if ($userData->role_access == 100): ?>
                        <div class="d-flex justify-content-end">
                            <ul class="list-inline user-chat-nav text-start">
                                <li class="list-inline-item">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-import me-1"></i> Impor Data
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" data-popper-placement="bottom-start" style="margin: 0px;">
                                            <?php if ($libIonix->builderQuery('files')->where(['file_type' => 'format', 'file_name' => 'Format_certification', 'file_extension' => 'xlsx'])->countAllResults() == true): ?>
                                                <a class="dropdown-item" href="<?= $libIonix->generateFileLink($libIonix->builderQuery('files')->where(['file_type' => 'format', 'file_name' => 'Format_certification', 'file_extension' => 'xlsx'])->get()->getRow()->file_id);?>" target="_blank"><i class="mdi mdi-download align-middle me-2"></i>Unduh Format Excel</a>
                                            <?php endif; ?>
                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-import" data-val="add" key="add-import"><i class="mdi mdi-import align-middle me-2"></i>Impor Excel</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- end d-flex -->
                    <?php endif; ?>

                    <table id="dt_certifications" class="table table-striped table-borderless align-middle w-100 mt-2">
                        <thead class="table-<?= $configIonix->colorPrimary;?>">
                            <tr>
                                <th scope="col" class="text-center align-middle" rowspan="2">No</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Nama Peserta</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Jenis Kelamin</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Cabang Olahraga</th>
                                <th scope="col" class="text-center align-middle" colspan="4">Sertifikasi</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Keterangan</th>
                                <th scope="col" class="text-center align-middle" rowspan="2" width="10%">Aksi</th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center">Nomor</th>
                                <th scope="col" class="text-center">Kategori</th>
                                <th scope="col" class="text-center">Tingkat</th>
                                <th scope="col" class="text-center">Tahun</th>
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

    <div class="modal fade" id="modal-certification" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Sesuaikan informasi <strong>Peserta Sertifikasi</strong> pada bidang-bidang dibawah ini.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('certification');?>
                        <h5 class="text-center my-md-3">Informasi Sertifikasi</h5>

                        <div class="form-group position-relative">
                            <label for="category">Kategori<code>*</code></label>
                            <select class="form-control select2" name="category" aria-hidden="true" data-placeholder="Pilih kategori..." required>
                              <option></option>
                              <option value="Wasit">Wasit</option>
                              <option value="Juri">Juri</option>
                              <option value="Pelatih">Pelatih</option>
                            </select>
                        </div>

                        <div class="form-group position-relative">
                            <label for="cabor">Cabang Olahraga<code>*</code></label>
                            <select class="form-control select2" name="cabor" aria-hidden="true" data-placeholder="Pilih cabang olahraga..." required>
                              <option></option>
                              <?php foreach ($data['modCabor']->fetchData()->get()->getResult() as $row): ?>
                                  <option value="<?= $row->sport_cabor_code;?>"><?= $row->sport_cabor_name;?></option>
                              <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-xl-6">
                              <div class="form-group position-relative">
                                  <label for="level">Tingkat<code>*</code></label>
                                  <input type="text" name="level" class="form-control" placeholder="Masukan tingkat sertifikasi" required>
                              </div>
                            </div>
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group position-relative">
                                    <label for="year">Tahun<code>*</code></label>
                                    <div class="input-group" id="datepicker-year">
                                        <input type="text" name="year" class="form-control" placeholder="Pilih tahun" data-date-container='#datepicker-year' data-provide="yearpicker" data-date-autoclose="true" readonly required>
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-center my-md-3">Informasi Dasar</h5>

                        <div class="form-group position-relative">
                            <label for="name">Nama Lengkap<code>*</code></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukan nama lengkap" required>
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
                          <label for="explanation">Keterangan</label>
                          <textarea name="explanation" class="form-control" placeholder="Tuliskan keterangan" rows="5"></textarea>
                        </div>
                    <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                  <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                  <button type="submit" class="btn btn-primary" onclick="$('#form-certification').submit();">Button</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <?php if ($userData->role_access == 100): ?>
        <div class="modal fade" id="modal-import" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title"></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?= customFormOpen('import');?>
                            <h5 class="text-center my-md-3">Lampiran</h5>

                            <div class="form-group position-relative">
                              <label>Unggah Berkas disini <code>(Max. <?= $configIonix->maximumSize['file'];?>B)</code></label>
                              <input type="file" name="file" class="dropify" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" data-max-file-size="<?= $configIonix->maximumSize['file'];?>" data-show-errors="true" data-allowed-file-extensions="xls xlsx" required>
                            </div>
                        <?= customFormClose();?>
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
<?= $this->endSection();?>

<?= $this->section('javascript');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'select2/js/select2.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'bootstrap-datepicker/js/bootstrap-datepicker.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'dropify/dist/js/dropify.min.js');?>

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/sports/certifications/certifications.init.js');?>
<?= $this->endSection();?>
