<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>

<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'magnific-popup/magnific-popup.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'select2/css/select2.min.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'dropify/dist/css/dropify.min.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'tagify/dist/tagify.css');?>
<?= $this->endSection();?>

<?= $this->section('app-search');?>
    <form class="app-search d-none d-lg-block" action="<?= panel_url('gallerys')?>" method="GET" novalidate>
        <div class="position-relative">
            <input type="text" name="search[value]" class="form-control" placeholder="Cari foto/vidio..." value="<?= !empty($request->getGet('search')['value']) ? $request->getGet('search')['value'] : '';?>">
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
            <form class="p-3" action="<?= panel_url('gallerys')?>" method="GET" novalidate>
                <div class="form-group m-0">
                    <div class="input-group">
                        <input type="text" name="search[value]" class="form-control" placeholder="Cari foto/vidio..." value="<?= !empty($request->getGet('search')['value']) ? $request->getGet('search')['value'] : '';?>">
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
                <h4 class="mb-sm-0 font-size-18">Galeri</h4>

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

    <div class="d-flex justify-content-start mb-3">
        <div class="btn-group">
            <button type="button" class="btn btn-<?= $configIonix->colorPrimary;?>"><i class="mdi mdi-upload me-1"></i>Unggah</button>
            <button type="button" class="btn btn-<?= $configIonix->colorPrimary;?> dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-chevron-down"></i>
            </button>
            <div class="dropdown-menu" style="margin: 0px;">
                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-image" data-scope="<?= $libIonix->Encode('image');?>" key="add-image"><i class="mdi mdi-image-outline align-middle me-1"></i>Foto/Gambar</a>
                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-video" data-scope="<?= $libIonix->Encode('video');?>" key="add-video"><i class="mdi mdi-video align-middle me-1"></i>Vidio/Youtube</a>
            </div>
        </div>
    </div>
    <!-- end row -->

    <?php if (!$data['resultGallery']): ?>
            <!-- start row -->
            <div class="row justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <img src="<?= $configIonix->mediaFolder['image'].'content/no-result.png';?>" alt="" class="img-thumbnail bg-transparent mx-auto" style="border: none;">
                    <p class="text-muted text-center">Maaf, saat ini <strong>Galeri</strong> kosong atau tidak tersedia.</p>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        <?php else: ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 product-grid">
                <?php foreach ($data['resultGallery'] as $row): ?>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="product-img position-relative">
                                    <div class="avatar-sm product-ribbon">
                                        <span class="avatar-title rounded-circle bg-<?= $configIonix->colorPrimary;?>"><?= $row->gallery_type == 'image' ? 'Foto' : 'Vidio';?></span>
                                    </div>

                                    <?php if ($row->gallery_type == 'image'): ?>
                                          <div class="zoom-gallery">
                                              <a href="<?= core_url('content/gallery/'.$libIonix->Encode($row->gallery_id).'/'.$libIonix->Encode($row->gallery_source));?>" title="<?= $row->gallery_title;?>" data-scope="<?= $libIonix->Encode('gallery');?>" data-val="<?= $libIonix->Encode($row->gallery_id);?>" key="view-gallery">
                                                  <img src="<?= core_url('content/gallery/'.$libIonix->Encode($row->gallery_id).'/'.$libIonix->Encode($row->gallery_source));?>" alt="<?= $row->gallery_title;?>" class="img-fluid mx-auto d-block">
                                              </a>
                                          </div>
                                       <?php else: ?>
                                           <?php if ($row->gallery_link == 'local'): ?>
                                                 <video class="ratio ratio-16x9 card-img-top cursor-pointer" controls poster="<?= core_url('content/gallery/'.$libIonix->Encode($row->gallery_id).'/'.$libIonix->Encode($row->gallery_thumbnails));?>" data-scope="<?= $libIonix->Encode('gallery');?>" data-val="<?= $libIonix->Encode($row->gallery_id);?>" key="view-video">
                                                     <source src="<?= core_url('content/gallery/'.$libIonix->Encode($row->gallery_id).'/'.$libIonix->Encode($row->gallery_source));?>" type="video/mp4">
                                                 </video>
                                              <?php else: ?>
                                                  <div class="ratio ratio-16x9">
                                                      <iframe src="<?= $libIonix->Encode($row->gallery_source);?>" title="<?= $row->gallery_title;?>" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                                  </div>
                                           <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-4 text-center">
                                    <h5 class="text-truncate mb-0"><?= $row->gallery_title;?></h5>

                                    <div class="clearfix">
                                        <ul class="list-inline mb-0">
                                            <?php foreach (explode(', ', $row->gallery_tags) as $tags): ?>
                                                <li class="list-inline-item me-1">
                                                    <a href="<?= panel_url('gallerys?search[value]='.$tags);?>">#<?= $tags;?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <span class="float-end fw-bold"><?= ucwords($row->gallery_link);?></span>
                                    </div>

                                    <div class="d-flex align-items-center mt-3 fs-6">
                                        <?php if ($row->user_id == $userData->user_id): ?>
                                            <div class="d-flex list-inline tooltip-container">
                                                <a href="javascript:void(0);" class="list-inline-item text-danger bg-light-danger border-0" data-bs-container=".tooltip-container" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hapus" data-scope="<?= $libIonix->Encode('gallery');?>" data-val="<?= $libIonix->Encode($row->gallery_id);?>" key="del-gallery">
                                                    <i class="mdi mdi-trash-can-outline"></i>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($row->gallery_link != 'youtube'): ?>
                                            <p class="mb-0 ms-auto">
                                                <small><i class="mdi mdi-eye align-middle me-1"></i><?= $row->gallery_views;?>x Dilihat</small>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <hr>

                                    <small>
                                       Diunggah oleh <a href="<?= panel_url('u/'.$libIonix->getUserData(NULL, 'object')->username);?>" target="_blank"><strong><?= parseFullName($libIonix->getUserData(NULL, 'object')->name, $libIonix->getUserData(NULL, 'object')->role_access, 12);?></strong></a>
                                    </small>
                                </div>
                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    </div>
                <?php endforeach; ?>
            </div>
            <!--end row-->

            <div class="row justify-content-center">
                <div class="col-md-6 col-xl-8">
                    <?= $data['pageGallery']->links('gallerys', 'rounded_pagination');?>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
    <?php endif; ?>

    <div class="modal fade" id="modal-image" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Unggah <strong>Foto/Gambar</strong> pada bagian ini sebagai <strong>Media</strong> yang akan ditampilkan pada <strong>Halaman Galeri</strong>.
                        Ektensi file yang didukung hanya <code>jpg</code>, <code>jpeg</code>, dan <code>png</code>.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <form id="form-image" class="needs-validation" action="javascript:void(0);" method="POST" novalidate>
                        <h5 class="text-center my-md-3">Informasi Dasar</h5>

                        <div class="form-group position-relative">
                            <label class="form-label" for="title">Judul<code>*</code></label>
                            <input type="text" name="title" class="form-control" placeholder="Masukan judul" required>
                        </div>

                        <div class="form-group position-relative">
                            <label class="form-label" for="tags">Tagar<code>*</code></label>
                            <input type="text" name="tags" class="form-control inputtags" placeholder="Masukan tagar" required>
                        </div>

                        <h5 class="text-center my-md-3">Media</h5>

                        <div class="form-group position-relative">
                          <label class="form-label">Unggah foto/gambar disini<code>*</code> <code>(Max. <?= $configIonix->maximumSize['image'];?>B)</code></label>
                          <input type="file" name="image" class="dropify" accept="image/x-png, image/jpeg" data-max-file-size="<?= $configIonix->maximumSize['image'];?>" data-show-errors="true" data-allowed-formats="square landscape" data-allowed-file-extensions="jpg jpeg png" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?>" onclick="$('#form-image').submit();">Button</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <div class="modal fade" id="modal-video" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                        Unggah <strong>Vidio/Youtube</strong> pada bagian ini sebagai <strong>Media</strong> yang akan ditampilkan pada <strong>Halaman Galeri</strong>.
                        Ektensi file yang didukung hanya <code>mp4</code> dan <code>ID Vidio</code> pada Youtube.
                        Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <form id="form-video" class="needs-validation" action="javascript:void(0);" method="POST" novalidate>
                        <h5 class="text-center my-md-3">Informasi Dasar</h5>

                        <div class="form-group position-relative">
                            <label class="form-label" for="title">Judul<code>*</code></label>
                            <input type="text" name="title" class="form-control" placeholder="Masukan judul" required>
                        </div>

                        <div class="form-group position-relative">
                            <label class="form-label" class="form-label" for="tags">Tagar<code>*</code></label>
                            <input type="text" name="tags" class="form-control inputtags" placeholder="Masukan tagar" required>
                        </div>

                        <h5 class="text-center my-md-3">Media</h5>

                        <!-- Nav tabs -->
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link active" data-bs-toggle="tab" href="#local" role="tab" key="local">
                                    <span class="d-block d-sm-none"><i class="fa fa-video"></i></span>
                                    <span class="d-none d-sm-block">Lokal</span>
                                </a>
                            </li>
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link" data-bs-toggle="tab" href="#youtube" role="tab" key="youtube">
                                    <span class="d-block d-sm-none"><i class="fab fa-youtube"></i></span>
                                    <span class="d-none d-sm-block">Youtube</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="local" role="tabpanel">
                                <div class="form-group position-relative">
                                    <label class="form-label" for="image">Unggah <code>thumbnails</code> disini<code>*</code> <code>(Max. <?= $configIonix->maximumSize['image'];?>B)</code></label>
                                    <input type="file" name="image" class="dropify" accept="image/x-png, image/jpeg" data-max-file-size="<?= $configIonix->maximumSize['image'];?>" data-show-errors="true" data-allowed-formats="landscape" data-allowed-file-extensions="jpg jpeg png" required>
                                </div>

                                <div class="form-group position-relative">
                                    <label class="form-label" for="video">Unggah vidio disini<code>*</code> <code>(Max. <?= $configIonix->maximumSize['video'];?>B)</code></label>
                                    <input type="file" name="video" class="dropify" accept="video/mp4" data-max-file-size="<?= $configIonix->maximumSize['video'];?>" data-show-errors="true" data-allowed-formats="landscape" data-allowed-file-extensions="mp4" required>
                                </div>
                            </div>

                            <div class="tab-pane" id="youtube" role="tabpanel">
                                <div class="form-group position-relative">
                                    <label class="form-label" for="url">ID Vidio Youtube<code>*</code></label>
                                    <input type="text" name="url" class="form-control" placeholder="Masukan id vidio">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?>" onclick="$('#form-video').submit();">Button</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'magnific-popup/jquery.magnific-popup.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'select2/js/select2.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'dropify/dist/js/dropify.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'tagify/dist/tagify.js');?>

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/gallerys.init.js');?>
<?= $this->endSection();?>
