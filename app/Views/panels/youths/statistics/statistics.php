<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
    <meta name="scope" content="<?= $libIonix->Encode('statistic'); ?>">
<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'select2/css/select2.min.css'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Statistik Pemuda</h4>

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

    <!-- start row -->
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-2">Statistik Pemuda</h4>
                    <p class="text-muted">Grafik ini merupakan statistik jumlah <strong>Pemuda</strong> pada setiap tahunnya dengan <strong>Line Chart</strong>.</p>

                    <div id="lineChartStatistic" class="apex-charts" data-scope="<?= $libIonix->Encode('chart'); ?>" data-val="<?= $libIonix->Encode('statistic'); ?>" dir="ltr"></div>
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-end">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title">Data Statistik Pemuda</h4>
                            <p class="card-title-desc text-justify">
                                Kelola <strong>Statistik Pemuda</strong> pada tahun <strong><?= $session->year;?></strong> yang berada pada usia 16-30 berdasarkan <strong>Kota/Kab</strong>.
                            </p>
                        </div>
                        <div class="col-md-6 col-xl-6">
                            <div class="button-items text-end mb-3">
                                <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-statistic" data-scope="<?= $libIonix->Encode('statistic'); ?>" data-val="add" key="add-statistic"><i class="mdi mdi-plus me-1"></i> Tambah statistik</button>
                                <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-statistic"><i class="mdi mdi-refresh me-1"></i> Segarkan</button>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-export me-1"></i> Ekspor <i class="mdi mdi-chevron-down"></i>
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

                    <table id="dt_statistics" class="table table-striped table-borderless align-middle w-100 mt-3">
                        <thead class="table-<?= $configIonix->colorPrimary;?>">
                            <tr>
                                <th scope="col" class="text-center align-middle" rowspan="2">No</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Kota/Kab</th>
                                <th scope="col" class="text-center align-middle" colspan="3">Jumlah</th>
                                <th scope="col" class="text-center align-middle" rowspan="2">Keterangan</th>
                                <th scope="col" class="text-center align-middle" rowspan="2" width="10%">Aksi</th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center">Laki-laki</th>
                                <th scope="col" class="text-center">Perempuan</th>
                                <th scope="col" class="text-center">Total</th>
                            </tr>
                        </thead>

                        <tfoot class="table-light">
                            <tr>
                                <th scope="col" colspan="2" class="text-end">Jumlah</th>
                                <th scope="col" class="text-center"><strong><?= number_format($data['spCountStatistic']->total_male, 0, ",", ".");?></strong></th>
                                <th scope="col" class="text-center"><strong><?= number_format($data['spCountStatistic']->total_female, 0, ",", ".");?></strong></th>
                                <th scope="col" class="text-center"><strong><?= number_format($data['spCountStatistic']->total, 0, ",", ".");?></strong></th>
                                <th scope="col" colspan="2"></th>
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
                    <h4 class="card-title mb-2">Persentase</h4>
                    <p class="card-title-desc text-justify">Grafik ini menggambarkan persentase jumlah <strong>Pemuda</strong> berdasarkan <strong>Kota/Kab</strong> pada tahun <strong><?= $session->year;?></strong>.</p>

                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <div id="chartMap" data-scope="<?= $libIonix->Encode('chart'); ?>" data-val="<?= $libIonix->Encode('district'); ?>" style="height: 700px"></div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h5 class="text-center my-md-3">Kota/Kab (Banten)</h5>

                            <div id="percentageMap" data-scope="<?= $libIonix->Encode('percentage'); ?>" data-val="<?= $libIonix->Encode('district'); ?>"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-statistic" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Sesuaikan informasi <strong>Pemuda</strong> berdasarkan <strong>Kota/Kab</strong> pada bidang-bidang dibawah ini.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('statistic'); ?>
                    <h5 class="text-center my-md-3">Informasi Kota/Kab</h5>

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

                    <div class="row">
                        <div class="col-sm-6 col-lg-6">
                            <div class="form-group position-relative">
                                <label for="male">Jumlah Laki-laki<code>*</code></label>
                                <input type="number" name="male" class="form-control" placeholder="Angka" required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="form-group position-relative">
                                <label for="female">Jumlah Perempuan<code>*</code></label>
                                <input type="number" name="female" class="form-control" placeholder="Angka" required>
                            </div>
                        </div>
                    </div>

                    <h5 class="text-center my-md-3">Informasi Lainnya</h5>

                    <div class="form-group position-relative">
                        <label for="explanation">Keterangan</label>
                        <textarea name="explanation" class="form-control" placeholder="Tuliskan keterangan" rows="5"></textarea>
                    </div>
                    <?= customFormClose(); ?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="$('#form-statistic').submit();">Button</button>
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

    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'apexcharts/apexcharts.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'amcharts4/core.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'amcharts4/charts.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'amcharts4/animated.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'amcharts4/worldLow.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'amcharts4/maps.js'); ?>

    <?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/youths/statistics.init.js'); ?>
<?= $this->endSection(); ?>
