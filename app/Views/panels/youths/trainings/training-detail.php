<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
<meta name="scope" content="<?= $libIonix->Encode('training'); ?>">
<meta name="params" content="<?= uri_segment(2); ?>">
<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>
<?= link_tag($configIonix->assetsFolder['panel']['library'] . 'dropify/dist/css/dropify.min.css'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Kelola Pelatihan</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active"><?= $userData->role_name; ?> / <?= ucwords(uri_segment(1)); ?> / <?= ucwords(uri_segment(3)); ?></li>
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
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="media">
                            <div class="media-body align-self-center">
                                <div class="text-muted">
                                    <h5 class="mb-1"><?= $data['trainingData']->youth_training_name; ?></h5>
                                    <p class="text-muted">Lokasi: <?= $data['trainingData']->district_type . ' ' . $data['trainingData']->district_name . ', ' . $data['trainingData']->province_name; ?></p>
                                    <p class="mb-0">Keterangan: <?= $data['trainingData']->youth_training_explanation ? $data['trainingData']->youth_training_explanation : '-'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 align-self-center">
                        <div class="text-lg-center mt-4 mt-lg-0">
                            <div class="row">
                                <div class="col-4">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Tanggal</p>
                                        <h5 class="mb-0"><?= parseDate($data['trainingData']->youth_training_date); ?></h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Tahun</p>
                                        <h5 class="mb-0"><?= $data['trainingData']->youth_training_year; ?></h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Peserta</p>
                                        <h5 class="mb-0"><?= $libIonix->builderQuery('youth_training_participants')->where(['youth_training_id' => $data['trainingData']->youth_training_id])->countAllResults(); ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 d-none d-lg-block">
                        <div class="clearfix mt-4 mt-lg-0">
                            <div class="float-end">
                                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" onclick="history.back();"><i class="mdi mdi-arrow-left align-middle me-1"></i> Kembali</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<div class="d-flex justify-content-end">
    <ul class="list-inline user-chat-nav text-end">
        <li class="list-inline-item import">
            <div class="dropdown">
                <button type="button" class="btn btn-success waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-import" data-bs-container=".list-inline-item.import" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Impor Data"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" data-popper-placement="bottom-start" style="margin: 0px;">
                    <?php if ($libIonix->builderQuery('files')->where(['file_type' => 'format', 'file_name' => 'Format_training_participant', 'file_extension' => 'xlsx'])->countAllResults() == true) : ?>
                        <a class="dropdown-item" href="<?= $libIonix->generateFileLink($libIonix->builderQuery('files')->where(['file_type' => 'format', 'file_name' => 'Format_training_participant', 'file_extension' => 'xlsx'])->get()->getRow()->file_id); ?>" target="_blank"><i class="mdi mdi-download align-middle me-2"></i>Unduh Format Excel</a>
                    <?php endif; ?>
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-import" data-scope="<?= $libIonix->Encode('participant'); ?>" key="add-import"><i class="mdi mdi-import align-middle me-2"></i>Impor Excel</a>
                </div>
            </div>
        </li>

        <li class="list-inline-item export">
            <div class="dropdown">
                <button type="button" class="btn btn-secondary waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-export" data-bs-container=".list-inline-item.export" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ekspor Data"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" data-popper-placement="bottom-start" style="margin: 0px;">
                    <a class="dropdown-item" href="<?= panel_url('youth_trainings/export/print/' . uri_segment(2)); ?>" target="_blank"><i class="mdi mdi-file-pdf align-middle text-danger me-2"></i>Cetak/PDF</a>
                </div>
            </div>
        </li>

        <li class="list-inline-item">
            <button type="button" class="btn btn-danger waves-effect waves-light" data-scope="<?= $libIonix->Encode('allparticipant'); ?>" data-val="<?= uri_segment(2); ?>" key="del-allparticipant">
                <i class="mdi mdi-trash-can-outline me-1"></i> Hapus Seluruh Peserta
            </button>
        </li>
    </ul>
</div>
<!-- end d-flex -->

<div class="row">
    <div class="col-xl-12">
        <table class="table project-list-table table-nowrap align-middle table-borderless">
            <thead class="table-<?= $configIonix->colorPrimary; ?>">
                <tr>
                    <th scope="col" class="text-center" style="width: 100px">No</th>
                    <th scope="col" class="text-center">Nama Peserta</th>
                    <th scope="col" class="text-center">Asal Daerah</th>
                    <th scope="col" class="text-center">Keterangan</th>
                    <th scope="col" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($libIonix->builderQuery('youth_training_participants')->where(['youth_training_id' => $data['trainingData']->youth_training_id])->get()->getResult() as $row) : ?>
                    <tr>
                        <td class="text-center"><strong><?= $i++; ?>.</strong></td>
                        <td>
                            <h6 class="text-truncate mb-0"><?= $row->youth_training_participant_name; ?></h6>
                        </td>
                        <td class="text-center"><?= $row->youth_training_participant_location; ?></td>
                        <td class="text-center"><?= $row->youth_training_participant_explanation ? $row->youth_training_participant_explanation : '-'; ?></td>
                        <td class="text-center">
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="javascript:void(0);" data-scope="<?= $libIonix->Encode('participant'); ?>" data-val="<?= $libIonix->Encode($row->youth_training_participant_id); ?>" key="del-participant"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

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
<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'dropify/dist/js/dropify.min.js'); ?>

<?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/youths/trainings/training-detail.init.js'); ?>
<?= $this->endSection(); ?>