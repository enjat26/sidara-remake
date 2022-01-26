<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
    <meta name="scope" content="<?= $libIonix->Encode('championship'); ?>">
    <meta name="params" content="<?= uri_segment(2); ?>">
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
                <h4 class="mb-sm-0 font-size-18">Kelola Kejuaraan Olahraga</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / <?= ucwords(uri_segment(1));?> / <?= ucwords(uri_segment(3));?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">
        <div class="col-md-6 col-xl-4">
            <?php if ($data['championshipData']->sport_championship_approve == -1 || $data['championshipData']->sport_championship_approve == 0 || $data['championshipData']->sport_championship_approve == 2) : ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Aksi</h4>
                        <p class="text-muted">Silahkan gunakan tombol dibawah ini untuk melakukan Aksi terhadap Data ini</p>

                        <div class="d-grid mt-2">
                            <?php if (isStakeholder() == false && $data['championshipData']->sport_championship_approve == -1) : ?>
                                <a href="javascript:void(0);" class="btn btn-danger" class="btn btn-info" data-scope="<?= $libIonix->Encode('purgedelete') ?>" key="del-championship"><i class="mdi mdi-trash-can-outline align-middle me-1"></i>Hapus Data</a>
                            <?php elseif (isStakeholder() == true && $data['championshipData']->sport_championship_approve == 0) : ?>
                                <?php if ($data['championshipData']->sport_championship_created_by == $userData->user_id) : ?>
                                    <a href="javascript:void(0);" class="btn btn-info" data-scope="<?= $libIonix->Encode('resub') ?>" key="upd-resub"><i class="mdi mdi-reply align-middle me-1"></i> Ajukan Ulang</a>
                                <?php endif; ?>
                            <?php elseif (isStakeholder() == false && $data['championshipData']->sport_championship_approve == 2) : ?>
                                <a href="javascript:void(0);" class="btn btn-primary" data-scope="<?= $libIonix->Encode('verify') ?>" key="upd-verify"><i class="mdi mdi-check-circle align-middle me-1"></i> Verifikasi</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <p class="text-muted">Dibuat oleh</p>

                        <?php if (!$data['championshipData']->sport_championship_created_by) : ?>
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-<?= $configIonix->colorPrimary; ?> bg-soft text-<?= $configIonix->colorPrimary; ?> font-size-16">
                                    ?
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-1"><a href="javascript:void(0);" class="text-dark">Pengguna tidak ditemukan</a></h5>
                            <p class="text-muted mb-0">-</p>
                        <?php else : ?>
                            <?php if ($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->avatar) : ?>
                                <div class="my-2">
                                    <a class="image-popup-no-margins" href="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->avatar)) ?>">
                                        <img src="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->avatar)) ?>" alt="" class="avatar-sm rounded-circle">
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="avatar-sm mx-auto my-2">
                                    <span class="avatar-title rounded-circle bg-light" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->role_color; ?>;">
                                        <?= substr($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->name, 0, 1); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <h5 class="font-size-15 mb-1">
                                <a href="<?= panel_url('u/' . $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->username); ?>" target="_blank" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->role_color; ?>;">
                                    <strong><?= parseFullName($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->name, $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->role_access); ?></strong>
                                </a>
                            </h5>
                            <p class="text-muted mb-0">
                                <span class="badge rounded-pill font-size-12" style="background-color: <?= hexToRGB($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->role_color, 18); ?>;color: #<?= $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->role_color; ?>">
                                    <?= $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_created_by], 'object')->role_name; ?>
                                </span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <p class="text-muted">Disetujui oleh</p>

                        <?php if (!$data['championshipData']->sport_championship_approve_by) : ?>
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-<?= $configIonix->colorPrimary; ?> bg-soft text-<?= $configIonix->colorPrimary; ?> font-size-16">
                                    ?
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-1"><a href="javascript:void(0);" class="text-dark">Pengguna tidak ditemukan</a></h5>
                            <p class="text-muted mb-0">-</p>
                        <?php else : ?>
                            <?php if ($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->avatar) : ?>
                                <div class="my-2">
                                    <a class="image-popup-no-margins" href="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->avatar)) ?>">
                                        <img src="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->avatar)) ?>" alt="" class="avatar-sm rounded-circle">
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="avatar-sm mx-auto my-2">
                                    <span class="avatar-title rounded-circle bg-light" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->role_color; ?>;">
                                        <?= substr($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->name, 0, 1); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <h5 class="font-size-15 mb-1">
                                <a href="<?= panel_url('u/' . $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->username); ?>" target="_blank" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->role_color; ?>;">
                                    <strong><?= parseFullName($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->name, $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->role_access); ?></strong>
                                </a>
                            </h5>
                            <p class="text-muted mb-0">
                                <span class="badge rounded-pill font-size-12" style="background-color: <?= hexToRGB($libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->role_color, 18); ?>;color: #<?= $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->role_color; ?>">
                                    <?= $libIonix->getUserData(['users.user_id' => $data['championshipData']->sport_championship_approve_by], 'object')->role_name; ?>
                                </span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->

        <div class="col-md-6 col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <?= parseStatusData($data['championshipData']->sport_championship_approve)->badge; ?>
                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light ms-3" onclick="history.back();"><i class="mdi mdi-arrow-left align-middle me-1"></i> Kembali</button>
                    </div>
                    <h4 class="card-title">Pengaturan</h4>
                    <p class="card-title-desc">Kelola informasi terhadap <strong>Kejuaraan Olahraga</strong> ini.</p>
                    <div class="p-2">
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

                            <div class="py-2 border-top">
                                <div class="text-end">
                                    <button type="reset" class="btn btn-secondary waves-effect waves-light">Batal</button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" data-scope="<?= $libIonix->Encode('championship'); ?>" key="upd-championship">Simpan</button>
                                </div>
                            </div>
                        <?= customFormClose(); ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <button type="button" class="btn btn-success btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-atlet" data-scope="<?= $libIonix->Encode('atlet'); ?>" key="add-atlet"><i class="mdi mdi-plus align-middle me-1"></i> Tambah peserta</button>
                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-scope="<?= $libIonix->Encode('allparticipant'); ?>" data-val="<?= uri_segment(2); ?>" key="del-all-participant">
                            <i class="mdi mdi-trash-can-outline me-1"></i> Hapus Seluruh Peserta
                        </button>
                    </div>
                    <h4 class="card-title">Daftar Atlet</h4>
                    <p class="card-title-desc">Kelola informasi terhadap <strong>Atlet</strong> yang mengikuti <strong>Kejuaraan</strong> ini.</p>

                    <table id="dt_participants" class="table table-striped table-borderless align-middle w-100 mt-2" data-scope="<?= $libIonix->Encode('participant');?>">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" width="5%">No</th>
                                <th scope="col" class="text-center">Nama Atlet</th>
                                <th scope="col" class="text-center">Jenis Kelamin</th>
                                <th scope="col" class="text-center">Cabang Olahraga</th>
                                <th scope="col" class="text-center">Asal Daerah</th>
                                <th scope="col" class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="modal fade" id="modal-atlet" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                    <table id="dt_atlets" class="table table-striped table-borderless align-middle w-100 mt-2" data-scope="<?= $libIonix->Encode('atlet');?>">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" width="5%">No</th>
                                <th scope="col" class="text-center">Nama Atlet</th>
                                <th scope="col" class="text-center">Jenis Kelamin</th>
                                <th scope="col" class="text-center">Cabang Olahraga</th>
                                <th scope="col" class="text-center">Asal Daerah</th>
                                <th scope="col" class="text-center">Dibuat oleh</th>
                                <th scope="col" class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
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
<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'select2/js/select2.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js'); ?>

    <?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/sports/championships/championship-detail.init.js'); ?>

    <?php if ($data['championshipData']->sport_championship_approve == -1 || $data['championshipData']->sport_championship_approve == 0 || $data['championshipData']->sport_championship_approve == 2) : ?>
        <script type="text/javascript">
            $('.form-control').prop('disabled', true),
                $('[key=add-atlet]').prop('disabled', true),
                $('[key=del-all-participant]').prop('disabled', true),
                $('form button').prop('disabled', true);
        </script>
    <?php endif; ?>
<?= $this->endSection(); ?>
