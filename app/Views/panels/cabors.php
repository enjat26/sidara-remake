<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
    <meta name="scope" content="<?= $libIonix->Encode('cabor'); ?>">
<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>

<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Cabang Olahraga</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name; ?> / <?= ucwords(uri_segment(1)); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Data Cabang Olahraga</h4>
                    <p class="text-muted text-justify">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    </p>

                    <div class="button-items mb-4">
                        <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-cabor"><i class="mdi mdi-refresh me-1"></i>Segarkan</button>
                        <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-cabor" key="add-cabor"><i class="mdi mdi-plus me-1"></i>Tambah cabang olahraga baru</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <table id="dt_cabors" class="table table-striped table-borderless align-middle w-100 mt-2">
                        <thead class="table-<?= $configIonix->colorPrimary;?>">
                            <tr>
                                <th scope="col" class="text-center" width="5%">No</th>
                                <th scope="col" class="text-center">Kode</th>
                                <th scope="col" class="text-center">Nama Cabang Olahraga</th>
                                <th scope="col" class="text-center">Deskripsi</th>
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

    <div class="modal fade" id="modal-cabor" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Sesuaikan informasi <strong>Cabang Olahraga</strong> pada bidang-bidang dibawah ini.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('cabor'); ?>
                        <h5 class="text-center my-3">Informasi Dasar</h5>

                        <div class="row">
                          <div class="col-sm-6 col-lg-4">
                            <div class="form-group position-relative">
                                <label for="code">Kode<code>*</code></label>
                                <input type="text" name="code" class="form-control" placeholder="Masukan kode" maxlength="15" data-provide="maxlength" required>
                            </div>
                          </div>
                          <div class="col-sm-6 col-lg-8">
                            <div class="form-group position-relative">
                                <label for="name">Nama<code>*</code></label>
                                <input type="text" name="name" class="form-control" placeholder="Masukan nama cabang olahraga" required>
                            </div>
                          </div>
                        </div>

                        <div class="form-group position-relative">
                            <label for="description">Deskripsi</label>
                            <textarea id="description" name="description" class="form-control" placeholder="Masukan deskripsi cabang olahraga" rows="5"></textarea>
                        </div>

                        <div class="alert border-0 border-start border-5 border-info py-2">
                            <div class="d-flex align-items-center">
                                <div class="font-size-20 text-info"><i class="mdi mdi-information-variant"></i></div>
                                <div class="ms-3">
                                    <div>
                                        Pastikan <strong>Cabang Olahraga</strong> yang akan diinput belum ada sebelumnya.
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?= customFormClose(); ?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary; ?> waves-effect waves-light" onclick="$('#form-cabor').submit();">Button</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->
<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
    <?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/cabors.init.js'); ?>
<?= $this->endSection(); ?>
