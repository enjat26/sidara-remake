<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
    <meta name="scope" content="<?= $libIonix->Encode('atlet'); ?>">
    <meta name="params" content="<?= uri_segment(2); ?>">
<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'select2/css/select2.min.css'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'bootstrap-datepicker/css/bootstrap-datepicker.min.css'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'tagify/dist/tagify.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'dropify/dist/css/dropify.min.css'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Kelola Atlet</h4>

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
        <div class="col-md-6 col-xl-4">
            <?php if ($data['atletData']->sport_atlet_approve == -1 || $data['atletData']->sport_atlet_approve == 0 || $data['atletData']->sport_atlet_approve == 2) : ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Aksi</h4>
                        <p class="text-muted">Silahkan gunakan tombol dibawah ini untuk melakukan Aksi terhadap Data ini</p>

                        <div class="d-grid mt-2">
                            <?php if (isStakeholder() == false && $data['atletData']->sport_atlet_approve == -1) : ?>
                                <a href="javascript:void(0);" class="btn btn-danger" key="del-atlet"><i class="mdi mdi-trash-can-outline align-middle me-1"></i>Hapus Data</a>
                            <?php elseif (isStakeholder() == true && $data['atletData']->sport_atlet_approve == 0) : ?>
                                <?php if ($data['atletData']->sport_atlet_created_by == $userData->user_id) : ?>
                                    <a href="javascript:void(0);" class="btn btn-info" data-scope="<?= $libIonix->Encode('resub') ?>" key="upd-resub"><i class="mdi mdi-reply align-middle me-1"></i> Ajukan Ulang</a>
                                <?php endif; ?>
                            <?php elseif (isStakeholder() == false && $data['atletData']->sport_atlet_approve == 2) : ?>
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

                        <?php if (!$data['atletData']->sport_atlet_created_by) : ?>
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-<?= $configIonix->colorPrimary; ?> bg-soft text-<?= $configIonix->colorPrimary; ?> font-size-16">
                                    ?
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-1"><a href="javascript:void(0);" class="text-dark">Pengguna tidak ditemukan</a></h5>
                            <p class="text-muted mb-0">-</p>
                        <?php else : ?>
                            <?php if ($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->avatar) : ?>
                                <div class="my-2">
                                    <a class="image-popup-no-margins" href="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->avatar)) ?>">
                                        <img src="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->avatar)) ?>" alt="" class="avatar-sm rounded-circle">
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="avatar-sm mx-auto my-2">
                                    <span class="avatar-title rounded-circle bg-light" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->role_color; ?>;">
                                        <?= substr($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->name, 0, 1); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <h5 class="font-size-15 mb-1">
                                <a href="<?= panel_url('u/' . $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->username); ?>" target="_blank" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->role_color; ?>;">
                                    <strong><?= parseFullName($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->name, $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->role_access); ?></strong>
                                </a>
                            </h5>
                            <p class="text-muted mb-0">
                                <span class="badge rounded-pill font-size-12" style="background-color: <?= hexToRGB($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->role_color, 18); ?>;color: #<?= $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->role_color; ?>">
                                    <?= $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_created_by], 'object')->role_name; ?>
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

                        <?php if (!$data['atletData']->sport_atlet_approve_by) : ?>
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-<?= $configIonix->colorPrimary; ?> bg-soft text-<?= $configIonix->colorPrimary; ?> font-size-16">
                                    ?
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-1"><a href="javascript:void(0);" class="text-dark">Pengguna tidak ditemukan</a></h5>
                            <p class="text-muted mb-0">-</p>
                        <?php else : ?>
                            <?php if ($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->avatar) : ?>
                                <div class="my-2">
                                    <a class="image-popup-no-margins" href="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->avatar)) ?>">
                                        <img src="<?= core_url('content/user/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->uuid) . '/' . $libIonix->Encode($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->avatar)) ?>" alt="" class="avatar-sm rounded-circle">
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="avatar-sm mx-auto my-2">
                                    <span class="avatar-title rounded-circle bg-light" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->role_color; ?>;">
                                        <?= substr($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->name, 0, 1); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <h5 class="font-size-15 mb-1">
                                <a href="<?= panel_url('u/' . $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->username); ?>" target="_blank" style="color: #<?= $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->role_color; ?>;">
                                    <strong><?= parseFullName($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->name, $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->role_access); ?></strong>
                                </a>
                            </h5>
                            <p class="text-muted mb-0">
                                <span class="badge rounded-pill font-size-12" style="background-color: <?= hexToRGB($libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->role_color, 18); ?>;color: #<?= $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->role_color; ?>">
                                    <?= $libIonix->getUserData(['users.user_id' => $data['atletData']->sport_atlet_approve_by], 'object')->role_name; ?>
                                </span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <div class="dropdown dropstart">
                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="javascript:void(0);" class="dropdown-item" data-scope="<?= $libIonix->Encode('image'); ?>" data-val="<?= $libIonix->Encode('avatar|' . $libIonix->Decode(uri_segment(2))); ?>" key="del-image"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus Avatar</a>
                            </div>
                        </div>
                        <!-- end dropdown -->
                    </div>

                    <div class="text-center">
                        <div class="mb-4">
                            <button type="button" class="btn btn-sm" style="position: absolute!important;" data-scope="<?= $libIonix->Encode('image'); ?>" data-val="<?= $libIonix->Encode('avatar|' . $libIonix->Decode(uri_segment(2))); ?>" key="upd-image">
                                <i class="mdi mdi-pencil-box-outline font-size-20 text-white"></i>
                            </button>
                            <img src="" alt="" class="img-thumbnail rounded" width="150" key="avatar">
                        </div>

                        <h5 class="font-size-15 mb-1" key="atletname"></h5>
                        <p class="text-muted mb-2" key="code"></p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Biografi</h5>
                    <p class="text-muted text-justify mb-0" key="bio"></p>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informasi Cabang Olahraga</h4>

                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="50%">Cabang Olahraga</th>
                                <td>: <strong key="cabor"></strong></td>
                            </tr>
                            <tr>
                                <th scope="row">Jenis Cabang Olahraga</th>
                                <td>: <span key="type"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informasi Lainnya</h4>

                    <table class="table mb-0">
                        <tbody>
                            <!-- <tr>
                                  <th scope="row" width="30%">Alamat</th>
                                  <td style="word-wrap: break-word">: <span class="text-justify" key="address"></span></td>
                              </tr> -->
                            <tr>
                                <th scope="row">Usia</th>
                                <td>: <span key="age"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Tempat / Tanggal Lahir</th>
                                <td>: <span key="birthday"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Jenis Kelamin</th>
                                <td>: <span key="gender"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Agama</th>
                                <td>: <span key="religion"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informasi Kontak</h4>

                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">Email</th>
                                <td>: <span key="email"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">No. Telepon</th>
                                <td>: <span key="phone"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="javascript:void(0);" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-social" data-scope="<?= $libIonix->Encode('social'); ?>" data-val="<?= uri_segment(2); ?>" key="add-social"><i class="mdi mdi-plus"></i> Tautkan media sosial</a>
                        </div>
                    </div>
                    <!-- end dropdown -->
                    <h4 class="card-title mb-4">Media Sosial</h4>

                    <div class="row social-media" data-scope="<?= $libIonix->Encode('social'); ?>"></div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-md-6 col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <?= parseStatusData($data['atletData']->sport_atlet_approve)->badge; ?>
                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light ms-3" onclick="history.back();"><i class="mdi mdi-arrow-left me-1"></i> Kembali</button>
                    </div>
                    <h4 class="card-title">Pengaturan</h4>
                    <p class="card-title-desc">Kelola informasi terhadap <strong>Atlet</strong> ini.</p>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#profile" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Profil</span>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                              <a class="nav-link" data-bs-toggle="tab" href="#achievement" role="tab">
                                  <span class="d-block d-sm-none"><i class="fas fa-trophy"></i></span>
                                  <span class="d-none d-sm-block">Prestasi</span>
                              </a>
                          </li> -->
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="profile" role="tabpanel">
                            <div class="p-2">
                                <div class="my-4">
                                    <h3 class="card-caption text-center">Mengubah Informasi</h3>
                                    <p class="card-description text-center">Sesuaikan informasi lengkap terhadap <strong>Profil Atlet</strong> ini.</p>
                                </div>

                                <?= customFormOpen('atlet'); ?>
                                <h5 class="text-center mt-3 mb-3">Cabang Olahraga</h5>

                                <div class="row">
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="form-group position-relative">
                                            <label for="roles">Cabang Olahraga<code>*</code></label>
                                            <select class="form-control select2" name="cabor" aria-hidden="true" data-placeholder="Pilih cabang olahraga..." data-scope="<?= $libIonix->Encode('type'); ?>" required>
                                                <option></option>
                                                <?php foreach ($data['modCabor']->fetchData()->get()->getResult() as $row) : ?>
                                                    <option value="<?= $row->sport_cabor_id; ?>"><?= $row->sport_cabor_name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="form-group position-relative">
                                            <label for="type">Jenis<code>*</code></label>
                                            <select class="form-control select2" name="type" aria-hidden="true" data-placeholder="Pilih jenis cabor..." required></select>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="text-center mt-3 mb-3">Informasi Dasar</h5>

                                <div class="form-group position-relative <?= isStakeholder() ? 'd-none' : '' ?>">
                                    <label for="atlet_ownership">Binaan</label>
                                    <input type="text" name="atlet_ownership" class="form-control inputtags">
                                </div>

                                <div class="form-group position-relative">
                                    <label for="fullname">Nama Atlet<code>*</code></label>
                                    <input type="text" name="fullname" class="form-control" placeholder="Masukan nama lengkap atlet" required>
                                </div>

                                <div class="form-group position-relative">
                                    <label for="level">Tingkat<code>*</code></label>
                                    <select class="form-control select2" name="level" aria-hidden="true" data-placeholder="Pilih tingkat..." required>
                                        <option></option>
                                        <option value="Pelajar">Pelajar</option>
                                        <option value="Umum">Umum</option>
                                    </select>
                                </div>

                                <div class="form-group position-relative">
                                    <label for="bio">Biografi</label>
                                    <textarea id="bio" name="bio" class="form-control" placeholder="Tuliskan biografi atlet" rows="5"></textarea>
                                </div>

                                <div class="form-group position-relative">
                                    <label for="explanation">Keterangan</label>
                                    <textarea id="explanation" name="explanation" class="form-control" placeholder="Tuliskan keterangan atlet" rows="5"></textarea>
                                </div>

                                <h5 class="text-center mt-3 mb-3">Informasi Daerah</h5>

                                <div class="form-group position-relative">
                                    <label for="address">Alamat</label>
                                    <input type="text" name="address" class="form-control" placeholder="Masukan alamat atlet">
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="form-group position-relative">
                                            <label for="province">Provinsi<code>*</code></label>
                                            <select class="form-control select2" name="province" aria-hidden="true" data-placeholder="Pilih provinsi..." data-scope="<?= $libIonix->Encode('district'); ?>" required>
                                                <option></option>
                                                <?php foreach ($data['modProvince']->fetchData(['provinces.province_id' => $configIonix->defaultProvince])->get()->getResult() as $row) : ?>
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

                                <div class="row">
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="form-group position-relative">
                                            <label for="subdistrict">Kecamatan</label>
                                            <select class="form-control select2" name="subdistrict" aria-hidden="true" data-placeholder="Pilih kecamatan..." data-scope="<?= $libIonix->Encode('village'); ?>"></select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="form-group position-relative">
                                            <label for="village">Kelurahan/Desa</label>
                                            <select class="form-control select2" name="village" aria-hidden="true" data-placeholder="Pilih kel/desa..."></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="form-group position-relative">
                                            <label for="zipcode">Kode POS</label>
                                            <input type="number" name="zipcode" class="form-control" placeholder="Masukan kode pos" maxlength="5" data-provide="maxlength">
                                        </div>
                                    </div>
                                </div>

                                <h5 class="text-center mt-3 mb-3">Informasi Lainnya</h5>

                                <div class="row">
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="form-group position-relative">
                                            <label for="pob">Tempat Lahir<code>*</code></label>
                                            <input type="text" name="pob" class="form-control" placeholder="Masukan tempat lahir" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="form-group position-relative">
                                            <label for="dob">Tanggal Lahir<code>*</code></label>
                                            <div class="input-group" id="datepicker-dob">
                                                <input type="text" name="dob" class="form-control" placeholder="Pilih tanggal" data-date-container='#datepicker-dob' data-provide="datepicker" data-date-autoclose="true" readonly required>
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="form-group position-relative">
                                            <label for="gender">Jenis Kelamin<code>*</code></label>
                                            <select name="gender" class="form-control select2" data-placeholder="Pilih jenis kelamin..." required>
                                                <option></option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="form-group position-relative">
                                            <label for="religion">Agama<code>*</code></label>
                                            <select name="religion" class="form-control select2" data-placeholder="Pilih agama..." required>
                                                <option></option>
                                                <option value="Islam">Islam</option>
                                                <option value="Kristen">Kristen</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Budha">Budha</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="text-center mt-3 mb-3">Informasi Kontak</h5>

                                <div class="form-group position-relative">
                                    <div class="d-block">
                                        <label for="email">Email</label>
                                        <div class="float-end">
                                            <p class="text-muted mb-0"><code>ex.</code> <i>xxxxx@xxxxx.xxx</i></p>
                                        </div>
                                    </div>
                                    <input type="email" name="email" class="form-control" placeholder="Masukan alamat email">
                                </div>

                                <div class="form-group position-relative">
                                    <div class="d-block">
                                        <label for="phone">No. Telepon</label>
                                        <div class="float-end">
                                            <p class="text-muted mb-0"><code>ex.</code> <i>8xxxxxxxxxx</i></p>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <select name="phoneid" class="form-control">
                                                <option value="62">+62</option>
                                            </select>
                                        </div>
                                        <input type="number" name="phone" class="form-control" placeholder="Masukan nomor telepon" minlength="11" maxlength="11" data-provide="maxlength">
                                    </div>
                                </div>

                                <div class="py-2 border-top">
                                    <div class="text-end">
                                        <button type="reset" class="btn btn-secondary waves-effect waves-light">Batal</button>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light" data-scope="<?= $libIonix->Encode('atlet'); ?>" key="upd-atlet">Simpan</button>
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

    <div class="hidden">
        <?= customFormOpen('image'); ?>
        <input type="file" id="image" name="image" class="form-control" accept="image/x-png, image/jpg, image/jpeg" data-scope="<?= $libIonix->Encode('image'); ?>">
        <?= customFormClose(); ?>
    </div>

    <div class="modal fade" id="modal-social" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tautkan Media Sosial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= customFormOpen('social'); ?>
                    <h3 class="card-caption text-center">Menambah Sosial Media</h3>
                    <p class="card-description text-center">Pilih Provider Media Sosial yang tersedia dan masukkan username Media Sosial yang <strong>Atlet</strong> miliki ke dalam URL di bawah ini.</p>

                    <div class="form-group position-relative">
                        <label for="sosprov">Provider<code>*</code></label>
                        <select name="sosprov" class="form-control select2" data-placeholder="Pilih provider..." data-scope="<?= $libIonix->Encode('sosprov'); ?>" required>
                            <option></option>
                            <?php foreach ($libIonix->getQuery('social_provider')->getResult() as $row) : ?>
                                <option value="<?= $libIonix->Encode($row->sosprov_id); ?>"><?= ucwords($row->sosprov_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group position-relative">
                        <label for="sosmed">Media Sosial URL<code>*</code></label>
                        <div class="input-group">
                            <div class="input-group-text" key="sosprov-url">URL</div>
                            <input type="text" name="sosmed" class="form-control" placeholder="Masukan username media sosial" required>
                        </div>
                    </div>

                    <div class="alert alert-info text-center mt-4 mb-0" role="alert">
                        Dengan menautkan <strong>Media Sosial</strong>, orang-orang akan dengan mudah menemukan <strong>Atlet</strong> ini.
                    </div>
                    <?= customFormClose(); ?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="$('#form-social').submit();">Tautkan</button>
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
    <?= script_tag($configIonix->assetsFolder['panel']['library'] . 'dropify/dist/js/dropify.min.js'); ?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'] .'tagify/dist/tagify.js');?>

    <?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/sports/atlets/atlet-detail.init.js'); ?>

    <?php if ($data['atletData']->sport_atlet_approve == -1 || $data['atletData']->sport_atlet_approve == 0 || $data['atletData']->sport_atlet_approve == 2) : ?>
        <script type="text/javascript">
            $('.form-control').prop('disabled', true),
                $('form button').prop('disabled', true);
        </script>
    <?php endif; ?>
<?= $this->endSection(); ?>
