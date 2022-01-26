<?php if ($libIonix->builderQuery('menu_access')->where(['menu_id' => $libIonix->builderQuery('menu_page')->getWhere(['menu_link' => 'notifications'])->getRow()->menu_id, 'role_access' => $libIonix->getUserData(NULL, 'object')->role_access])->countAllResults() == true): ?>
    <div id="notifications"  class="dropdown d-inline-block" data-scope="<?= $libIonix->Encode('notification');?>">
        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-bell-outline"></i>
            <span class="badge bg-danger rounded-pill">0</span>
        </button>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
            <div class="p-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0">Notifikasi</h6>
                    </div>
                    <div class="col-auto">
                        <a href="javascript:void(0);" class="small" data-val="all" key="mark-notification">Tandai semua dibaca</a>
                    </div>
                </div>
            </div>

            <div data-simplebar style="max-height: 400px;">
                <div class="notifications-list">

                </div>
            </div>
            <div class="p-2 border-top d-grid">
                <a href="<?= panel_url('notifications');?>" class="btn btn-sm btn-link font-size-14 text-center">
                    <i class="mdi mdi-arrow-right-circle me-1"></i><span>Lihat lebih banyak...</span>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>
