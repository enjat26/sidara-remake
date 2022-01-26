<!-- Right Sidebar -->
<div class="right-bar">
  <div data-simplebar class="h-100">
      <div class="rightbar-title d-flex align-items-center px-3 py-4">
          <h5 class="m-0 me-2">Personalisasi</h5>

          <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
              <i class="mdi mdi-close noti-icon"></i>
          </a>
      </div>

      <!-- Settings -->
      <hr class="mt-0" />
      <h6 class="text-center mb-0">Pilih Tema</h6>

      <div class="p-4">
          <div class="mb-2">
              <img src="<?= $configIonix->mediaFolder['image'].'preview/layout-1.jpg';?>" class="img-fluid img-thumbnail" alt="">
          </div>

          <div class="form-check form-switch mb-3">
              <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" data-bsStyle="<?= $configIonix->assetsFolder['panel']['stylesheet'].'bootstrap.min.css';?>" data-appStyle="<?= $configIonix->assetsFolder['panel']['stylesheet'].'app.min.css';?>" checked>
              <label class="form-check-label" for="light-mode-switch">Mode Terang</label>
          </div>

          <div class="mb-2">
              <img src="<?= $configIonix->mediaFolder['image'].'preview/layout-2.jpg';?>" class="img-fluid img-thumbnail" alt="">
          </div>
          <div class="form-check form-switch mb-3">
              <input class="form-check-input theme-choice" type="checkbox" id="combination-mode-switch" data-bsStyle="<?= $configIonix->assetsFolder['panel']['stylesheet'].'bootstrap.min.css';?>" data-appStyle="<?= $configIonix->assetsFolder['panel']['stylesheet'].'app.min.css';?>">
              <label class="form-check-label" for="combination-mode-switch">Mode Kombinasi</label>
          </div>

          <div class="mb-2">
              <img src="<?= $configIonix->mediaFolder['image'].'preview/layout-3.jpg';?>" class="img-fluid img-thumbnail" alt="">
          </div>
          <div class="form-check form-switch mb-5">
              <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch" data-bsStyle="<?= $configIonix->assetsFolder['panel']['stylesheet'].'bootstrap-dark.min.css';?>" data-appStyle="<?= $configIonix->assetsFolder['panel']['stylesheet'].'app-dark.min.css';?>">
              <label class="form-check-label" for="dark-mode-switch">Mode Gelap</label>
          </div>
      </div>

  </div>
  <!-- end slimscroll-menu-->
</div>
<!-- Right-bar -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>
