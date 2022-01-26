<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
<meta name="scope" content="<?= $libIonix->Encode('asset'); ?>">
<meta name="params" content="<?= uri_segment(2); ?>">
<meta name="arguments" content="<?= $data['arguments']; ?>">
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
            <h4 class="mb-sm-0 font-size-18">Kelola <?= $data['assetData']->asset_type; ?> pada <?= $data['arguments'] == 'youths' ? 'Bidang Kepemudaan' : 'Bidang Olahraga'; ?></h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active"><?= $userData->role_name; ?> / <?= ucwords(uri_segment(1)); ?> / <?= ucwords(uri_segment(3)); ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row justify-content-center">
    <div class="col-md-6 col-xl-3">
        <?php if ($data['assetData']->asset_approve == -1 || $data['assetData']->asset_approve == 0 || $data['assetData']->asset_approve == 2) : ?>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Aksi</h4>
                    <p class="text-muted">Silahkan gunakan tombol dibawah ini untuk melakukan Aksi terhadap Data ini</p>

                    <div class="d-grid mt-2">
                        <?php if (isStakeholder() == false && $data['assetData']->asset_approve == -1) : ?>
                            <a href="javascript:void(0);" class="btn btn-danger" key="del-asset"><i class="mdi mdi-trash-can-outline align-middle me-1"></i>Hapus Data</a>
                        <?php elseif (isStakeholder() == true && $data['assetData']->asset_approve == 0) : ?>
                            <?php if ($data['assetData']->asset_created_by == $userData->user_id) : ?>
                                <a href="javascript:void(0);" class="btn btn-info" data-scope="<?= $libIonix->Encode('resub') ?>" key="upd-resub"><i class="mdi mdi-reply align-middle me-1"></i> Ajukan Ulang</a>
                            <?php endif; ?>
                        <?php elseif (isStakeholder() == false && $data['assetData']->asset_approve == 2) : ?>
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

                    <?php if (!$data['assetData']->asset_created_by) : ?>
                        <div class="avatar-sm mx-auto mb-4">
                            <span class="avatar-title rounded-circle bg-<?= $configIonix->colorPrimary; ?> bg-soft text-<?= $configIonix->colorPrimary; ?> font-size-16">
                                ?
                            </span>
                        </div>
                        <h5 class="font-size-15 mb-1"><a href="javascript:void(0);" class="text-dark">Pengguna tidak ditemukan</a></h5>
                        <p class="text-muted mb-0">-</p>
                    <?php else : ?>
                        <?php if ($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->avatar) : ?>
                            <div class="my-2">
                                <a class="image-popup-no-margins" href="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->avatar)) ?>">
                                    <img src="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->avatar)) ?>" alt="" class="avatar-sm rounded-circle">
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="avatar-sm mx-auto my-2">
                                <span class="avatar-title rounded-circle bg-light" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->role_color; ?>;">
                                    <?= substr($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->name, 0, 1); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <h5 class="font-size-15 mb-1">
                            <a href="<?= panel_url('u/' . $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->username); ?>" target="_blank" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->role_color; ?>;">
                                <strong><?= parseFullName($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->name, $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->role_access); ?></strong>
                            </a>
                        </h5>
                        <p class="text-muted mb-0">
                            <span class="badge rounded-pill font-size-12" style="background-color: <?= hexToRGB($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->role_color, 18); ?>;color: #<?= $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->role_color; ?>">
                                <?= $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_created_by], 'object')->role_name; ?>
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

                    <?php if (!$data['assetData']->asset_approve_by) : ?>
                        <div class="avatar-sm mx-auto mb-4">
                            <span class="avatar-title rounded-circle bg-<?= $configIonix->colorPrimary; ?> bg-soft text-<?= $configIonix->colorPrimary; ?> font-size-16">
                                ?
                            </span>
                        </div>
                        <h5 class="font-size-15 mb-1"><a href="javascript:void(0);" class="text-dark">Pengguna tidak ditemukan</a></h5>
                        <p class="text-muted mb-0">-</p>
                    <?php else : ?>
                        <?php if ($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->avatar) : ?>
                            <div class="my-2">
                                <a class="image-popup-no-margins" href="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->avatar)) ?>">
                                    <img src="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->avatar)) ?>" alt="" class="avatar-sm rounded-circle">
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="avatar-sm mx-auto my-2">
                                <span class="avatar-title rounded-circle bg-light" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->role_color; ?>;">
                                    <?= substr($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->name, 0, 1); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <h5 class="font-size-15 mb-1">
                            <a href="<?= panel_url('u/' . $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->username); ?>" target="_blank" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->role_color; ?>;">
                                <strong><?= parseFullName($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->name, $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->role_access); ?></strong>
                            </a>
                        </h5>
                        <p class="text-muted mb-0">
                            <span class="badge rounded-pill font-size-12" style="background-color: <?= hexToRGB($libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->role_color, 18); ?>;color: #<?= $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->role_color; ?>">
                                <?= $libIonix->getUserData(['users.user_id' => $data['assetData']->asset_approve_by], 'object')->role_name; ?>
                            </span>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (isStakeholder() == true) : ?>
            <?php if ($data['assetData']->asset_created_by == $userData->user_id && $data['assetData']->asset_approve == 1 || $data['assetData']->asset_approve == 3) : ?>
                <div class="mb-4">
                    <div class="d-grid">
                        <a href="javascript:void(0);" class="btn btn-<?= $configIonix->colorPrimary; ?>" data-bs-toggle="modal" data-bs-target="#modal-image" data-scope="<?= $libIonix->Encode('image'); ?>" key="add-image"><i class="mdi mdi-upload align-middle me-1"></i>Unggah Foto/Gambar</a>
                    </div>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="mb-4">
                <div class="d-grid">
                    <a href="javascript:void(0);" class="btn btn-<?= $configIonix->colorPrimary; ?>" data-bs-toggle="modal" data-bs-target="#modal-image" data-scope="<?= $libIonix->Encode('image'); ?>" key="add-image"><i class="mdi mdi-upload align-middle me-1"></i>Unggah Foto/Gambar</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($libIonix->builderQuery('youth_asset_images')->where(['asset_id' => $data['assetData']->asset_id])->countAllResults() == false) : ?>
            <div class="row justify-content-center">
                <div class="col-md-6 col-xl-10">
                    <img src="<?= $configIonix->mediaFolder['image'] . 'content/no-result.png'; ?>" alt="" class="img-thumbnail bg-transparent mx-auto" style="border: none;">
                    <p class="text-muted text-center">Maaf, sepertinya <strong>Foto/Gambar</strong> kosong atau tidak ada.</p>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        <?php else : ?>
            <?php foreach ($libIonix->builderQuery('youth_asset_images')->where(['asset_id' => $data['assetData']->asset_id])->get()->getResult() as $row) : ?>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="avatar-sm product-ribbon tooltip-container">
                            <span class="avatar-title rounded-circle bg-soft bg-danger">
                                <a href="javascript:void(0);" class="list-inline-item text-danger border-0" data-bs-container=".tooltip-container" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hapus" data-scope="<?= $libIonix->Encode('image'); ?>" data-val="<?= $libIonix->Encode($row->asset_image_id); ?>" key="del-image">
                                    <i class="mdi mdi-trash-can-outline font-size-18"></i>
                                </a>
                            </span>
                        </div>

                        <div class="product-img position-relative">
                            <div class="zoom-gallery">
                                <a href="<?= core_url('source/asset/' . $libIonix->Encode($row->asset_image_source)); ?>" title="">
                                    <img src="<?= core_url('source/asset/' . $libIonix->Encode($row->asset_image_source)); ?>" alt="" class="img-fluid mx-auto d-block">
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <!-- end col -->

    <div class="col-md-6 col-xl-9">
        <div class="card">
            <div class="card-body">
                <div class="float-end">
                    <?= parseStatusData($data['assetData']->asset_approve)->badge; ?>
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light ms-3" onclick="history.back();"><i class="mdi mdi-arrow-left me-1"></i> Kembali</button>
                </div>
                <h4 class="card-title">Pengaturan</h4>
                <p class="card-title-desc">Kelola informasi terhadap <strong><?= $data['assetData']->asset_type; ?> <?= $data['arguments'] == 'youths' ? 'Pemuda' : 'Olahraga'; ?></strong> ini.</p>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#editor" role="tab">
                            <span class="d-block d-sm-none"><i class="fas fa-pen"></i></span>
                            <span class="d-none d-sm-block">Sunting</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="editor" role="tabpanel">
                        <div class="p-2">
                            <div class="my-4">
                                <h3 class="card-caption text-center">Mengubah Informasi</h3>
                                <p class="card-description text-center">
                                    Sesuaikan informasi lengkap terhadap <strong><?= $data['assetData']->asset_type; ?> <?= $data['arguments'] == 'youths' ? 'Pemuda' : 'Olahraga'; ?></strong> ini.
                                    Pada bagian <strong>Konten</strong> dapat diisi seperti profil atau informasi tambahan lainnya yang akan di informasikan kepada <strong>Publik</strong>.
                                </p>
                            </div>

                            <?= customFormOpen('asset'); ?>
                            <h5 class="text-center mt-3 mb-3">Informasi Atribut</h5>
                            <div class="row">
                                <div class="col-sm-6 col-lg-12">
                                    <div class="form-group position-relative">
                                        <label for="type">Jenis<code>*</code></label>
                                        <select class="form-control select2" name="type" aria-hidden="true" data-placeholder="Pilih jenis..." required>
                                            <option></option>
                                            <option value="Sarana">Sarana</option>
                                            <option value="Prasarana">Prasarana</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-lg-6">
                                    <div class="form-group position-relative">
                                        <label for="category">Kategori<code>*</code></label>
                                        <select class="form-control select2" name="category" aria-hidden="true" data-placeholder="Pilih kategori..." required>
                                            <option></option>
                                            <?php foreach ($libIonix->builderQuery('youth_asset_categorys')->get()->getResult() as $row) : ?>
                                                <option value="<?= $row->asset_category_id; ?>"><?= $row->asset_category_name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-6">
                                    <div class="form-group position-relative">
                                        <label for="category_type">Tipe Kategori</label>
                                        <select class="form-control select2" name="category_type" aria-hidden="true" data-placeholder="Pilih tipe kategori...">
                                            <option></option>
                                            <option value="0">Tanpa Kategori</option>
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
                                <textarea id="description" name="description" class="form-control" placeholder="Deskripsikan sarana/prasarana" rows="5" maxlength="500" data-provide="maxlength" required></textarea>
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

                            <div class="form-group position-relative">
                                <label for="map">Link Google Map</label>
                                <input type="text" name="map" class="form-control" placeholder="Sematkan link google map">
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

                            <div class="py-2 border-top">
                                <div class="text-end">
                                    <button type="reset" class="btn btn-secondary waves-effect waves-light">Batal</button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                                </div>
                            </div>
                            <?= customFormClose(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<div class="modal fade" id="modal-image" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="card-description text-center">
                    Unggah <strong>Foto/Gambar</strong> pada bagian ini sebagai <strong>Media</strong> yang akan ditampilkan.
                    Ektensi file yang didukung hanya <code>jpg</code>, <code>jpeg</code>, dan <code>png</code>.
                    Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                </p>

                <?= customFormOpen('image'); ?>
                <h5 class="text-center my-md-3">Media</h5>

                <div class="form-group position-relative">
                    <label class="form-label">Unggah foto/gambar disini<code>*</code> <code>(Max. <?= $configIonix->maximumSize['image']; ?>B)</code></label>
                    <input type="file" name="image" class="dropify" accept="image/x-png, image/jpeg, image/jpg" data-max-file-size="<?= $configIonix->maximumSize['image']; ?>" data-show-errors="true" data-allowed-formats="landscape" data-allowed-file-extensions="jpg jpeg png" required>
                </div>
                <?= customFormClose(); ?>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                <button type="submit" class="btn btn-<?= $configIonix->colorPrimary; ?>" onclick="$('#form-image').submit();">Button</button>
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
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'tinymce/tinymce.min.js'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'magnific-popup/jquery.magnific-popup.min.js'); ?>
<?= script_tag($configIonix->assetsFolder['panel']['library'] . 'dropify/dist/js/dropify.min.js'); ?>

<?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/youths/assets/asset-detail.init.js'); ?>

<?php if ($data['assetData']->asset_approve == -1 || $data['assetData']->asset_approve == 0 || $data['assetData']->asset_approve == 2) : ?>
    <script type="text/javascript">
        $('.form-control').prop('disabled', true),
            $('form button').prop('disabled', true);
    </script>
<?php endif; ?>
<?= $this->endSection(); ?>