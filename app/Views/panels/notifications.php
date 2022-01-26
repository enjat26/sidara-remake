<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>
    <meta name="base-url" content="<?= base_url().'/';?>">
<?= $this->endSection();?>

<?= $this->section('stylesheet');?>

<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Notifikasi</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / <?= ucwords(uri_segment(1));?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="container py-2">
        <div class="d-flex justify-content-end mb-3">
            <div class="button-items">
                <button type="button" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" data-val="<?= $libIonix->Encode('all')?>" key="mark-notification"><i class="mdi mdi-bell-check-outline me-1"></i>Tandai semua dibaca</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-val="all" key="del-notification"><i class="mdi mdi-trash-can-outline me-1"></i>Kosongkan</button>
            </div>
        </div>

        <?php if ($data['modNotification']->fetchData(['user_id' => $userData->user_id])->countAllResults() == false): ?>
                <!-- start row -->
                <div class="row justify-content-center mt-3">
                    <div class="col-md-6 col-xl-6">
                        <img src="<?= $configIonix->mediaFolder['image'].'content/no-result.png';?>" alt="" class="img-thumbnail bg-transparent mx-auto" style="border: none;">
                        <p class="text-muted text-center">Maaf, saat ini <strong>Notifikasi</strong> kosong atau tidak tersedia.</p>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            <?php else: ?>
                <?php foreach ($data['modNotification']->fetchData(['user_id' => $userData->user_id])->get()->getResult() as $row): ?>
                    <!-- timeline item -->
                    <div class="row">
                        <!-- timeline item 1 left dot -->
                        <div class="col-auto text-center flex-column d-none d-sm-flex">
                            <div class="row h-50">
                                <div class="col">&nbsp;</div>
                                <div class="col">&nbsp;</div>
                            </div>
                            <h5 class="m-2"><i class="mdi mdi-arrow-right-bold-hexagon-outline text-<?= $row->notification_status == 'unread' ? 'warning' : $configIonix->colorPrimary ;?>"></i></h5>
                            <div class="row h-50">
                                <div class="col border-end">&nbsp;</div>
                                <div class="col">&nbsp;</div>
                            </div>
                        </div>
                        <!-- timeline item 1 event content -->
                        <div class="col py-2">
                            <div class="alert border-0 border-start border-5 border-<?= $row->notification_status == 'unread' ? 'warning' : $configIonix->colorPrimary ;?> py-2">
                                <div class="float-end">
                                    <div class="dropdown dropstart">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical font-size-18"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?= panel_url($row->notification_slug);?>" data-val="<?= $libIonix->Encode($row->notification_id);?>" key="upd-notification"><i class="mdi mdi-eye align-middle text-<?= $configIonix->colorPrimary;?> me-1"></i>Lihat</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:void(0);" data-val="<?= $libIonix->Encode($row->notification_id);?>" key="del-notification"><i class="mdi mdi-trash-can-outline align-middle text-danger me-1"></i>Hapus</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="font-size-20 text-<?= $row->notification_status == 'unread' ? 'warning' : $configIonix->colorPrimary ;?>"><i class="<?= $row->notification_status == 'unread' ? 'mdi mdi-alert-circle-outline' : 'mdi mdi-check-circle-outline' ;?>"></i></div>
                                    <div class="p-2 ms-3">
                                        <div>
                                            <h6 class="text-<?= $row->notification_status == 'unread' ? 'warning' : $configIonix->colorPrimary ;?>"><?= $row->notification_title;?></h6>
                                            <?php if ($row->notification_content): ?>
                                                    <p class="text-muted text-justify"><?= $row->notification_content;?></p>
                                                <?php else: ?>
                                                    <p class="text-muted mb-0"><i>Tidak ada catatan</i></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <small>
                                        <?= parseDateDiff($row->notification_created_at)->getRelative();?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/row-->
                <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <!--container-->
<?= $this->endSection();?>

<?= $this->section('javascript');?>

<?= $this->endSection();?>
