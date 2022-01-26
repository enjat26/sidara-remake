<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>

<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'select2/css/select2.min.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'dropify/dist/css/dropify.min.css');?>
<?= $this->endSection();?>

<?= $this->section('app-search');?>
    <form class="app-search d-none d-lg-block" action="<?= panel_url('files')?>" method="GET" novalidate>
        <div class="position-relative">
            <input type="text" name="search[value]" class="form-control" placeholder="Cari berkas..." value="<?= !empty($request->getGet('search')['value']) ? $request->getGet('search')['value'] : '';?>">
            <span class="mdi mdi-magnify"></span>
        </div>
    </form>
<?= $this->endSection();?>

<?= $this->section('mobile-search');?>
    <div class="dropdown d-inline-block d-lg-none ms-2">
        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-magnify"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
            <form class="p-3" action="<?= panel_url('files')?>" method="GET" novalidate>
                <div class="form-group m-0">
                    <div class="input-group">
                        <input type="text" name="search[value]" class="form-control" placeholder="Cari berkas..." value="<?= !empty($request->getGet('search')['value']) ? $request->getGet('search')['value'] : '';?>">
                        <div class="input-group-append">
                            <button class="btn btn-<?= $configIonix->colorPrimary;?>" type="submit"><i class="mdi mdi-magnify"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-18">Pengelola File</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / <?= ucwords(uri_segment(1));?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <?php if (ENVIRONMENT == 'demo'): ?>
        <!-- start row -->
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="alert alert-danger text-center" role="alert">
                    <i class="mdi mdi-alert-circle align-middle me-1"></i>
                    Dalam mode <strong><?= ucwords(ENVIRONMENT);?></strong>, beberapa fungsi dalam halaman ini telah dinonaktifkan.
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    <?php endif; ?>

    <div class="row justify-content-center mt-3">
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-grid">
                        <a href="javascript:void(0);" class="btn btn-<?= $configIonix->colorPrimary;?>" data-bs-toggle="modal" data-bs-target="#modal-file" data-scope="<?= $libIonix->Encode('file');?>" key="add-file"><i class="mdi mdi-upload align-middle me-1"></i>Unggah Berkas</a>
                    </div>

                    <div class="clearfix"></div>

                    <p class="card-title-desc text-justify">
                      Kelola berkas-berkas yang terunggah pada Aplikasi <strong><?= strtoupper($configIonix->appCode);?> <?= ucwords($configIonix->appType);?></strong> seperti <strong>Format</strong> atau <strong>Lampiran</strong>.
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-titlr">Penyimpanan</h6>

                    <h5 class="mb-0 text-<?= $configIonix->colorPrimary;?> font-weight-bold"><?= parseFileSize(disk_total_space(WRITEPATH)-disk_free_space(WRITEPATH));?> <span class="float-end text-secondary"><?= parseFileSize(disk_total_space(WRITEPATH));?></span></h5>
                    <p class="mb-0 mt-2"><span class="text-secondary">Digunakan</span> <span class="float-end text-<?= $configIonix->colorPrimary;?>">Total</span></p>

                    <div class="progress mt-3" style="height:7px;">
                        <div class="progress-bar bg-<?= $configIonix->colorPrimary;?>" role="progressbar" style="width: <?= floor(((disk_total_space(WRITEPATH)-disk_free_space(WRITEPATH))/disk_total_space(WRITEPATH))*100);?>%" aria-valuenow="<?= floor(((disk_total_space(WRITEPATH)-disk_free_space(WRITEPATH))/disk_total_space(WRITEPATH))*100);?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-9">
            <?php if (!$data['resultFile']): ?>
                    <!-- start row -->
                    <div class="row justify-content-center mt-3">
                        <div class="col-md-6 col-xl-6">
                            <img src="<?= $configIonix->mediaFolder['image'].'content/no-result.png';?>" alt="" class="img-thumbnail bg-transparent mx-auto" style="border: none;">
                            <?php if (!empty($request->getGet('search[value]'))): ?>
                                    <p class="text-muted text-center">Maaf, <strong>Berkas</strong> yang Anda cari tidak ditemukan.</p>
                                <?php else: ?>
                                    <p class="text-muted text-center">Maaf, saat ini <strong>Berkas</strong> kosong atau tidak tersedia.</p>
                            <?php endif; ?>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($data['resultFile'] as $row): ?>
                            <div class="col-12 col-lg-4">
                                <div class="card shadow-none border radius-15">
                                    <div class="card-body">
                                        <div>
                                            <div class="float-end">
                                                <div class="dropdown dropstart">
                                                    <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="<?= $libIonix->generateFileLink($row->file_id, 'view');?>" target="_blank"><i class="mdi mdi-eye align-middle text-info me-1"></i>Pratinjau</a>
                                                        <a class="dropdown-item" href="<?= $libIonix->generateFileLink($row->file_id);?>" target="_blank"><i class="mdi mdi-cloud-download-outline align-middle text-success me-1"></i>Unduh</a>
                                                        <?php if ($row->user_id == $userData->user_id): ?>
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-file" data-scope="<?= $libIonix->Encode('file');?>" data-val="<?= $libIonix->Encode($row->file_id);?>" key="upd-file"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-<?= $configIonix->colorPrimary;?> me-1"></i>Ubah Informasi</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="javascript:void(0);" data-scope="<?= $libIonix->Encode('file');?>" data-val="<?= $libIonix->Encode($row->file_id);?>" key="del-file"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="avatar-sm">
                                                  <span class="avatar-title rounded-circle bg-<?= parseFileIcon($row->file_extension)->color;?> bg-soft font-size-24">
                                                      <i class="<?= parseFileIcon($row->file_extension)->icon;?>"></i>
                                                  </span>
                                            </div>
                                        </div>

                                        <h6 class="card-title mt-3"><?= $row->file_name.'.'.$row->file_extension;?></h6>
                                        <?php if ($row->file_description): ?>
                                                <p class="text-muted"><?= $row->file_description;?></p>
                                            <?php else: ?>
                                                <p class="text-muted"><i>Tidak ada deskripsi</i></p>
                                        <?php endif; ?>

                                        <div class="d-flex justify-content-between">
                                            <small><i class="mdi mdi-download align-middle me-1"></i><?= $row->file_download_attempt;?>x</small>
                                            <small><i class="mdi mdi-label align-middle me-1 ms-2"></i><?= $row->file_type;?></small>

                                            <small class="ms-auto"><?= parseFileSize($row->file_size);?></small>
                                        </div>

                                        <hr>

                                        <small>
                                           Diunggah oleh <a href="<?= panel_url('u/'.$libIonix->getUserData(['users.user_id' => $row->user_id], 'object')->username);?>" target="_blank"><strong><?= parseFullName($libIonix->getUserData(['users.user_id' => $row->user_id], 'object')->name, $libIonix->getUserData(['users.user_id' => $row->user_id], 'object')->role_access, 12);?></strong></a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!--end row-->

                    <div class="row justify-content-center">
                        <div class="col-md-6 col-xl-8">
                            <?= $data['pageFile']->links('files', 'rounded_pagination');?>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
            <?php endif; ?>
        </div>
    </div>
    <!--end row-->

    <div class="modal fade" id="modal-file" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="upload hidden">
                        <p class="card-description text-center">
                            Unggah <strong>Berkas</strong> yang akan diterbitkan dan dapat di unduh oleh <strong>Umum</strong>.
                            Ektensi file yang didukung hanya <code>JPG</code>, <code>JPEG</code>, <code>PNG</code>, <code>Ms. Excel</code>, <code>Ms. Word</code>, dan <code>PDF</code>.
                            Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                        </p>
                    </div>

                    <form id="form-file" class="needs-validation" action="javascript:void(0);" method="POST" novalidate>
                        <h5 class="text-center my-md-3">Informasi Dasar</h5>

                        <div class="form-group position-relative">
                            <label class="form-label" for="name">Nama Berkas</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukan nama berkas">
                        </div>

                        <div class="upload hidden">
                            <div class="alert border-0 border-start border-5 border-info py-2">
                                <div class="d-flex align-items-center">
                                    <div class="font-size-18 text-info"><i class="mdi mdi-information-variant"></i></div>
                                    <div class="ms-3">
                                        <div>
                                            Jika Anda tidak memberikan <strong>Nama Berkas</strong>, maka akan digunakan dengan <strong>Nama Berkas</strong> yang asli.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="upload hidden">
                            <div class="form-group position-relative">
                                <label class="form-label" for="type">Jenis<code>*</code></label>
                                <select name="type" class="form-control select2" data-placeholder="Pilih jenis..." required>
                                    <option></option>
                                    <option value="attachment">Lampiran</option>
                                    <option value="format">Format</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group position-relative">
                            <label class="form-label" for="description">Deskripsi</label>
                            <textarea id="description" name="description" class="form-control" placeholder="Tuliskan deskripsi singkat" rows="5"></textarea>
                        </div>

                        <div class="upload hidden">
                            <h5 class="text-center my-md-3">Unggahan</h5>

                            <div class="form-group position-relative">
                                <label class="form-label">Unggah Berkas disini <code>(Max. <?= $configIonix->maximumSize['file'];?>B)</code></label>
                                <input type="file" name="file" class="dropify" accept="image/jpg, image/jpeg, image/x-png, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msword, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, application/pdf" data-max-file-size="<?= $configIonix->maximumSize['file'];?>" data-show-errors="true" data-allowed-file-extensions="jpg jpeg png xls xlsx doc docx pdf" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?>" onclick="$('#form-file').submit();">Button</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'select2/js/select2.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'dropify/dist/js/dropify.min.js');?>

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/files.init.js');?>
<?= $this->endSection();?>
