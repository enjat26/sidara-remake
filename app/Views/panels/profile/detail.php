<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>

<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <style media="screen">
        .activity-list {
          width: 100%!important;
          max-height: 1150px!important
        }
    </style>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Profil Pengguna</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / Profile / <?= ucwords(uri_segment(2));?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">
        <div class="col-md-12 col-xl-12">
            <div class="card overflow-hidden">
                <div class="bg-<?= $configIonix->colorPrimary;?> bg-soft">
                  <?php if ($data['clientData']->cover): ?>
                          <img src="<?= core_url('content/user/'.$libIonix->Encode($data['clientData']->uuid).'/'.$libIonix->Encode($data['clientData']->cover));?>" alt="<?= $data['clientData']->name;?>" class="img-fluid">
                      <?php else: ?>
                          <img src="<?= $configIonix->mediaFolder['image'].'default/cover.jpg';?>" alt="<?= $data['clientData']->name;?>" class="img-fluid">
                  <?php endif; ?>
                </div>

                <div class="card-body pt-0">
                    <div class="row justify-content-center">
                        <div class="col-md-12 col-xl-12">
                            <div class="text-center">
                                <div class="avatar-xl bg-white mx-auto mb-4" <?= $data['clientData']->avatar ? 'style="height: auto!important;"' : '' ;?>>
                                  <?php if ($data['clientData']->avatar): ?>
                                        <div class="user-avatar">
                                            <img src="<?= core_url('content/user/'.$libIonix->Encode($data['clientData']->uuid).'/'.$libIonix->Encode($data['clientData']->avatar));?>" alt="<?= $data['clientData']->name;?>" class="img-thumbnail rounded">
                                        </div>
                                      <?php else: ?>
                                        <span class="avatar-title user-avatar rounded font-size-24" style="background-color: <?= hexToRGB($data['clientData']->role_color, 18);?>;color: #<?= $data['clientData']->role_color;?>;">
                                          <?= substr($data['clientData']->name, 0, 1);?>
                                        </span>
                                  <?php endif; ?>
                                </div>

                                <h3 class="text-truncate"><?= parseFullName($data['clientData']->name, $data['clientData']->role_access);?></h3>
                                <p class="text-muted">
                                    <span class="badge rounded-pill font-size-12" style="background-color: <?= hexToRGB($data['clientData']->role_color, 18);?>;color: #<?= $data['clientData']->role_color;?>"><?= $data['clientData']->role_name;?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="row justify-content-center">
        <div class="col-md-6 col-xl-5">
            <?php if ($data['clientData']->safe_mode == false): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Informasi Akun</h4>

                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row" width="30%">Username</th>
                                    <td>: <?= $data['clientData']->username;?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end card -->
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Biografi</h5>
                    <p class="text-muted text-justify mb-0"><?= $data['clientData']->bio ? $data['clientData']->bio : '<i>Belum menambahkan biografi</i>' ;?></p>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informasi Kantor</h4>

                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">Cabang</th>
                                <td>: <?= $data['clientData']->branch_name;?> (<strong><?= strtoupper($data['clientData']->branch_code);?></strong>) - <?= strtoupper($data['clientData']->branch_type);?></td>
                            </tr>
                            <tr>
                                <th scope="row">Unit Kerja</th>
                                <td>: <?= $data['clientData']->workunit_name;?> (<strong><?= strtoupper($data['clientData']->workunit_code);?></strong>)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card -->

            <?php if ($data['clientData']->safe_mode == false): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Informasi Lainnya</h4>

                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row" width="30%">Alamat</th>
                                    <td class="text-justify" style="word-wrap: break-word">: <?= $data['clientData']->safe_mode == true ? parseAddress($data['clientData'], false) : parseAddress($data['clientData']) ;?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Tempat / Tanggal Lahir</th>
                                    <td>: <?= $data['clientData']->pob && $data['clientData']->dob ? $data['clientData']->pob.', '.parseDate($data['clientData']->dob) : '-';?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Jenis Kelamin</th>
                                    <td>: <?= parseGender($data['clientData']->gender);?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Agama</th>
                                    <td>: <?= $data['clientData']->religion ? $data['clientData']->religion : '-';?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end card -->
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informasi Kontak</h4>

                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">Email</th>
                                <td>: <?= $data['clientData']->email ? $data['clientData']->email : '-';?></td>
                            </tr>
                            <?php if ($data['clientData']->safe_mode == false): ?>
                              <tr>
                                  <th scope="row">No. Telepon</th>
                                  <td>: <?= $data['clientData']->phone_number ? parsePhoneNumber($data['clientData']->phone_code ,$data['clientData']->phone_number) : '-';?></td>
                              </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Media Sosial</h4>

                    <div class="row">
                        <?php if ($libIonix->getQuery('social_media', NULL, ['user_id' => $data['clientData']->user_id])->getNumRows() > 0): ?>
                              <?php foreach ($libIonix->getQuery('social_media', ['social_provider' => 'social_provider.sosprov_id = social_media.sosprov_id'], ['user_id' => $data['clientData']->user_id])->getResult() as $row): ?>
                                  <div class="col-4">
                                      <div class="social-source text-center mt-3">
                                          <div class="avatar-xs mx-auto mb-3">
                                              <span class="avatar-title rounded-circle font-size-16" style="background-color: #<?= $row->sosprov_color;?>">
                                                  <i class="mdi mdi-<?= $row->sosprov_name;?> text-white"></i>
                                              </span>
                                          </div>
                                          <a href="<?= $row->sosprov_url.$row->sosmed_key;?>" target="_blank">
                                            <h5 class="font-size-15 mb-0"><?= ucwords($row->sosprov_name);?></h5>
                                            <p class="text-muted mb-0">@<?= $row->sosmed_key;?></p>
                                          </a>
                                      </div>
                                  </div>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <p class="text-muted text-center mb-0"><i>Tidak menautkan media sosial</i></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-md-6 col-xl-7 <?= $data['clientData']->safe_mode == true ? 'my-auto' : '' ;?>">
            <?php if ($data['clientData']->safe_mode == false): ?>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-5">Aktivitas Login</h4>

                            <div class="activity-list" data-simplebar>
                                <ul class="verti-timeline list-unstyled">
                                    <?php if ($libIonix->getQuery('auth_login', NULL, ['user_id' => $data['clientData']->user_id])->getNumRows() > 0): ?>
                                            <?php foreach ($libIonix->getQuery('auth_login', NULL, ['user_id' => $data['clientData']->user_id])->getResult() as $row): ?>
                                                <li class="event-list">
                                                    <div class="event-timeline-dot">
                                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline font-size-18"></i>
                                                    </div>
                                                    <div class="media">
                                                        <div class="me-3">
                                                            <h5 class="font-size-14 mb-0"><?= parseDate($row->login_created_at, 'dS F Y - g:i A');?> <i class="mdi mdi-arrow-right font-size-16 text-<?= $configIonix->colorPrimary;?> align-middle ms-2"></i></h5>
                                                        </div>
                                                        <div class="media-body">
                                                            <?php if ($row->login_success == true): ?>
                                                                    <p class="text-muted mb-0"><?= $row->login_message.' dengan menggunakan '.explode('|', $row->login_browser)[0].' pada perangkat <strong>'.$row->login_os.'.</strong>';?></p>
                                                                <?php else: ?>
                                                                    <p class="text-muted mb-0"><?= $row->login_message;?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-muted text-center mb-0"><i>Tidak ada riwayat masuk</i></p>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end-card -->
                <?php else: ?>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-xl-8">
                                <img src="<?= $configIonix->mediaFolder['image'].'content/secure.png';?>" alt="" class="img-thumbnail">

                                <div class="alert alert-warning text-center" role="alert">
                                    <i class="mdi mdi-alert-outline align-middle font-size-18 me-1"></i>
                                    <strong>Peringatan!</strong> Beberapa informasi mungkin tidak dapat ditampilkan karena <strong>Penggguna</strong> mengaktifkan <strong>Privasi Akun</strong>.
                                </div>
                            </div>
                        </div>
                    </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- end row -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>

<?= $this->endSection();?>
