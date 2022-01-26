<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>
    <meta name="scope" content="<?= $libIonix->Encode('role');?>">
    <?php if ($data['params']['manage'] == true): ?>
        <meta name="params" content="<?= $data['params']['role'];?>">
    <?php endif; ?>
<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'spectrum-colorpicker2/spectrum.min.css');?>
<?= $this->endSection();?>

<?= $this->section('app-search');?>
    <form class="app-search d-none d-lg-block" action="<?= panel_url('roles')?>" method="GET" novalidate>
        <div class="position-relative">
            <input type="text" name="search[value]" class="form-control" placeholder="Cari hak akses..." value="<?= !empty($request->getGet('search')['value']) ? $request->getGet('search')['value'] : '';?>">
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
            <form class="p-3" action="<?= panel_url('roles')?>" method="GET" novalidate>
                <div class="form-group m-0">
                    <div class="input-group">
                        <input type="text" name="search[value]" class="form-control" placeholder="Cari hak akses..." value="<?= !empty($request->getGet('search')['value']) ? $request->getGet('search')['value'] : '';?>">
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
                <h4 class="mb-sm-0 font-size-18">Hak Akses</h4>

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

    <div class="container">
        <?php if (ENVIRONMENT == 'production'): ?>
            <!-- start row -->
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        <i class="mdi mdi-alert-outline align-middle me-1"></i>
                        <strong>Peringatan!</strong> Kami menyarankan untuk tidak menambah atau menghapus <strong>Hak Akses</strong> karena dapat merubah <strong>validasi</strong> yang telah ditetapkan.
                        Silahkan <strong>konsultasikan</strong> terlebih dahulu pada kami jika ingin menambah atau menghapus <strong>Hak Akses</strong>.
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-sm-8">
                        <h4 class="card-title">Manajemen Hak Akses</h4>
                        <p class="card-title-desc text-justify">
                            <strong>Hak Akses</strong> (<i>Access Rights</i>) adalah izin atau hak istimewa yang diberikan kepada <strong>Pengguna</strong> di dalam aplikasi,
                            sebagaimana ditetapkan oleh aturan yang dibuat oleh pemilik data dan sesuai kebijakan keamanan informasi di dalam aplikasi.
                            Kami telah menentukan <strong><?= $data['modRole']->fetchData()->countAllResults();?> Hak Akses</strong> yang telah disesuaikan secara <strong>Umum</strong> dengan <strong>Filter</strong> dan <strong>Validasi</strong>.
                        </p>
                    </div>

                    <div class="col-lg-4 col-sm-4 align-self-center">
                        <img src="<?= $configIonix->mediaFolder['image'].'content/roles.png';?>" alt="" class="img-fluid d-block mx-auto" width="150">
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->

        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-role" key="add-role"><i class="mdi mdi-plus me-1"></i> Tambah hak akses baru</button>
        </div>

        <?php if (!$data['resultRole']): ?>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-xl-4">
                        <img src="<?= $configIonix->mediaFolder['image'].'content/no-result.png';?>" alt="" class="img-thumbnail mx-auto">
                        <p class="text-muted text-center">Maaf, saat ini <strong>Hak Akses</strong> kosong atau tidak tersedia.</p>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            <?php else: ?>
              <div class="row">
                  <?php foreach ($data['resultRole'] as $row): ?>
                      <div class="col-xl-4 col-sm-6">
                          <div class="card">
                              <div class="card-body">
                                  <div class="float-end">
                                      <div class="dropdown dropstart">
                                          <a href="javascript:void(0);" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                              <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                          </a>
                                          <div class="dropdown-menu">
                                              <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-role" data-val="<?= $libIonix->Encode($row->role_id);?>" key="upd-role"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-<?= $configIonix->colorPrimary;?> me-1"></i>Ubah Informasi</a>
                                              <div class="dropdown-divider"></div>
                                              <a class="dropdown-item" href="javascript:void(0);" data-scope="<?= $libIonix->Encode('role');?>" data-val="<?= $libIonix->Encode($row->role_id);?>" key="del-role"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
                                          </div>
                                      </div>
                                      <!-- end dropdown -->
                                  </div>

                                  <div class="media">
                                      <div class="media-body overflow-hidden">
                                          <h5 class="text-truncate font-size-15"><?= $row->role_name;?> (<strong><?= strtoupper($row->role_code);?></strong>) <?= parseRoleIcon($row->role_access);?></h5>
                                          <p class="text-muted mb-4"><?= $row->role_description ? $row->role_description : '<i>Tidak ada deskripsi</i>' ;;?></p>

                                          <?php if ($data['modUser']->fetchData(['roles.role_code' => $row->role_code])->countAllResults() > 0): ?>
                                              <div class="avatar-group tooltip-container">
                                                  <?php foreach ($data['modUser']->fetchData(['roles.role_code' => $row->role_code], false, 'CUSTOM')->orderBy('role_id', 'RANDOM')->get(9, 0)->getResult() as $sub): ?>
                                                      <?php if ($sub->avatar): ?>
                                                              <div class="avatar-group-item">
                                                                  <a href="<?= panel_url('u/'.$sub->username);?>" target="_blank" class="d-inline-block">
                                                                      <img src="<?= core_url('content/user/'.$libIonix->Encode($sub->uuid).'/'.$libIonix->Encode($sub->avatar));?>" alt="" class="rounded-circle avatar-sm" data-bs-container=".tooltip-container" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?= $sub->name;?>">
                                                                  </a>
                                                              </div>
                                                          <?php else: ?>
                                                              <div class="avatar-group-item">
                                                                  <a href="<?= panel_url('u/'.$sub->username);?>" target="_blank" class="d-inline-block">
                                                                      <div class="avatar-sm">
                                                                          <span class="avatar-title rounded-circle bg-light font-size-16" data-bs-container=".tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="<?= $sub->name;?>" style="color: #<?= $row->role_color;?>;">
                                                                              <?= substr($sub->name, 0, 1);?>
                                                                          </span>
                                                                      </div>
                                                                  </a>
                                                              </div>
                                                      <?php endif; ?>
                                                  <?php endforeach; ?>
                                              </div>
                                              <!-- end avatar-group -->
                                              <?php else: ?>
                                                  <p class="text-muted text-center mb-0"><i>Tidak ada pengguna disini</i></p>
                                          <?php endif; ?>
                                      </div>
                                  </div>
                              </div>
                              <!-- end card-body -->
                              <div class="px-4 py-3 border-top">
                                  <div class="float-end">
                                      <li class="list-inline-item me-3">
                                          <i class="mdi mdi-account-multiple align-middle me-1"></i> <?= $data['modUser']->fetchData(['roles.role_code' => $row->role_code])->countAllResults();?> Pengguna
                                      </li>
                                  </div>
                                  <ul class="list-inline mb-0">
                                      <li class="list-inline-item me-3">
                                          <span class="badge font-size-12" style="background-color: #<?= $row->role_color;?>;">#<?= $row->role_color;?></span>
                                      </li>
                                  </ul>
                              </div>

                              <div class="text-center px-4 py-3 border-top">
                                  <small><i class="mdi mdi-clock-outline align-middle me-1"></i>Dibuat pada <?= parseDate($row->role_created_at, 'dS F Y - g:i A T');?></small>
                              </div>

                              <div class="px-4 py-3 border-top">
                                  <div class="d-grid">
                                      <a href="<?= panel_url('roles/'.$libIonix->Encode($row->role_code).'/manage?scope=access');?>" class="btn btn-info waves-effect waves-light"><i class="mdi mdi-near-me me-1"></i>Kelola Akses</a>
                                  </div>
                              </div>
                          </div>
                          <!-- end card -->
                      </div>
                      <!-- end col -->
                  <?php endforeach; ?>
              </div>
              <!-- end row -->

              <div class="row justify-content-center">
                  <div class="col-md-6 col-xl-8">
                      <?= $data['pageRole']->links('roles', 'rounded_pagination');?>
                  </div>
                  <!-- end col -->
              </div>
              <!-- end row -->
        <?php endif; ?>
    </div>
    <!-- end container -->

    <div class="modal fade" id="modal-role" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Hak Akses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p class="card-description text-center">
                    Sesuaikan identitas <strong>Hak Akses</strong> pada bidang-bidang dibawah ini.
                    Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                  </p>

                  <?= customFormOpen('role');?>
                      <h5 class="text-center my-md-3">Informasi Dasar</h5>

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
                                  <input type="text" name="name" class="form-control" placeholder="Masukan nama hak akses" required>
                              </div>
                          </div>
                      </div>

                      <div class="form-group position-relative">
                          <label for="description">Deskripsi</label>
                          <textarea id="description" name="description" class="form-control" placeholder="Deskripsikan hak akses" rows="5"></textarea>
                      </div>

                      <h5 class="text-center my-md-3">Informasi Lainnya</h5>

                      <div class="row">
                          <div class="col-sm-6 col-lg-6">
                              <div class="form-group position-relative">
                                  <label for="access">Tipe Akses<code>*</code></label>
                                  <input type="number" name="access" class="form-control" placeholder="Masukan tipe akses" min="1" max="90" maxlength="3" data-provide="maxlength" required>
                              </div>
                          </div>
                          <div class="col-sm-6 col-lg-6">
                              <div class="form-group position-relative">
                                  <label for="color">Warna<code>*</code></label>
                                  <input type="text" name="color" class="form-control colorpicker" placeholder="Pilih warna" readonly required>
                              </div>
                          </div>
                      </div>

                      <div class="alert alert-info text-center" role="alert">
                          Tipe Akses hanya diizinkan menggunakan angka dari <strong>1-90</strong>.
                      </div>
                  <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?>" onclick="$('#form-role').submit();">Button</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <?php if ($data['params']['manage'] == true): ?>
        <!-- Static Backdrop Modal -->
        <div class="modal fade" id="modal-menu" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Kelola Akses Navigasi pada <?= $data['params']['roleData']->role_name;?></h5>
                        <a href="<?= panel_url('roles');?>" class="btn-close"></a>
                    </div>
                    <div class="modal-body">
                        <p class="card-description text-center">
                          Kelola batasan-batasan jalur akses navigasi pada <strong><?= $data['params']['roleData']->role_name;?></strong>, dengan begitu <strong>Hak Akses</strong> tersebut dapat melihat navigasi yang diberikan.
                        </p>

                        <h5 class="text-center my-md-3">Daftar Navigasi</h5>

                        <?php $i = 0;$k= 0;$n = 0;$t = 0;?>
                        <?php foreach ($libIonix->getQuery('menu_group')->getResult() as $group): ?>
                          <?php $i++;?>
                          <div class="d-flex align-items-center mb-2">
                              <div class="features-number font-weight-semibold display-4 me-3"><?= $i < 10 ? '0'.$i : $i ;?></div>
                              <h4 class="mb-0"><?= $group->group_title;?></h4>
                          </div>
                          <p class="text-muted"><?= $group->group_description;?></p>

                          <div class="text-muted mt-4">
                              <?php $menuParams = $userData->role_access == 100 ? ['group_id' => $group->group_id, 'menu_parent' => false] : ['group_id' => $group->group_id, 'menu_parent' => false, 'menu_previlege' => false] ;?>
                              <?php foreach ($libIonix->getQuery('menu_page', NULL, $menuParams)->getResult() as $menu): ?>
                                  <?php $t++;?>
                                  <?php if ($libIonix->getQuery('menu_page', NULL, ['menu_parent' => $menu->menu_id])->getNumRows() > 0): ?>
                                      <?php $k++;?>
                                      <div class="row">
                                          <div class="col-md-8">
                                              <p class="text-muted"><i class="mdi mdi-circle-medium text-success me-1"></i><?= $menu->menu_title;?></p>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="form-check form-switch mb-0">
                                                  <?php if ($libIonix->getQuery('menu_access', NULL, ['role_access' => $data['params']['roleData']->role_access, 'menu_id' => $menu->menu_id])->getNumRows() > 0): ?>
                                                          <input type="checkbox" class="form-check-input" id="leadSwitch<?= $k;?>" onclick="updateStatus('<?= $libIonix->Encode('access');?>', '<?= $libIonix->Encode($menu->menu_id);?>')" checked>
                                                      <?php else: ?>
                                                          <input type="checkbox" class="form-check-input" id="leadSwitch<?= $k;?>" onclick="updateStatus('<?= $libIonix->Encode('access');?>', '<?= $libIonix->Encode($menu->menu_id);?>')">
                                                  <?php endif; ?>
                                                  <label for="leadSwitch<?= $k;?>">Click to switch</label>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="container">
                                        <?php foreach ($libIonix->getQuery('menu_page', NULL, ['menu_parent' => $menu->menu_id])->getResult() as $submenu): ?>
                                          <?php $n++;?>
                                          <div class="row">
                                              <div class="col-md-7">
                                                  <p class="text-muted"><i class="mdi mdi-chevron-right text-info me-1"></i><?= $submenu->menu_title;?></p>
                                              </div>
                                              <div class="col-md-5">
                                                  <div class="form-check form-switch mb-0">
                                                      <?php if ($libIonix->getQuery('menu_access', NULL, ['role_access' => $data['params']['roleData']->role_access, 'menu_id' => $menu->menu_id])->getNumRows() > 0): ?>
                                                            <input type="checkbox" class="form-check-input" id="subSwitch<?= $n;?>" onclick="updateStatus('<?= $libIonix->Encode('access');?>', '<?= $libIonix->Encode($submenu->menu_id);?>')" checked>
                                                         <?php else: ?>
                                                            <input type="checkbox" class="form-check-input" id="subSwitch<?= $n;?>" onclick="updateStatus('<?= $libIonix->Encode('access');?>', '<?= $libIonix->Encode($submenu->menu_id);?>')">
                                                      <?php endif; ?>
                                                      <label for="subSwitch<?= $n;?>">Click to switch</label>
                                                  </div>
                                              </div>
                                          </div>
                                        <?php endforeach; ?>
                                      </div>
                                    <?php else: ?>
                                      <div class="row">
                                          <div class="col-md-8">
                                              <p class="text-muted"><i class="mdi mdi-circle-medium text-success me-1"></i><?= $menu->menu_title;?></p>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="form-check form-switch mb-0">
                                                  <?php if ($libIonix->getQuery('menu_access', NULL, ['role_access' => $data['params']['roleData']->role_access, 'menu_id' => $menu->menu_id])->getNumRows() > 0): ?>
                                                          <input type="checkbox" class="form-check-input" id="parentSwitch<?= $t;?>" onclick="updateStatus('<?= $libIonix->Encode('access');?>', '<?= $libIonix->Encode($menu->menu_id);?>')" checked>
                                                      <?php else: ?>
                                                          <input type="checkbox" class="form-check-input" id="parentSwitch<?= $t;?>" onclick="updateStatus('<?= $libIonix->Encode('access');?>', '<?= $libIonix->Encode($menu->menu_id);?>')">
                                                  <?php endif; ?>
                                                  <label for="parentSwitch<?= $t;?>">Click to switch</label>
                                              </div>
                                          </div>
                                      </div>
                                  <?php endif; ?>
                              <?php endforeach; ?>
                          </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <div class="alert alert-info text-center" role="alert">
                            Klik pada tombol <strong><i>Toogle Switch</i></strong> untuk merubahnya.
                        </div>
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
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'spectrum-colorpicker2/spectrum.min.js');?>

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/roles.init.js');?>

    <?php if ($data['params']['manage'] == true): ?>
        <script type="text/javascript">
            $( document ).ready(function() {
              $('#modal-menu').modal('show');
            });

            function updateStatus(scope, value) {
              $.ajax({
                url:  $('meta[name=site-url]').attr("content")+'roles/update?scope='+scope+"&id="+$('meta[name=params]').attr("content"),
                data: "value="+value,
                type: "POST",
                success: function(response) {
                  pushToastr(response.type, response.header, response.message.success);
                },
              });
            }
        </script>
    <?php endif; ?>
<?= $this->endSection();?>
