<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
<meta name="scope" content="<?= $libIonix->Encode('navigation_group'); ?>">
<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>

<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Grup Navigasi</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active"><?= $userData->role_name; ?> / <?= ucwords(uri_segment(1));?></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<?php if (ENVIRONMENT == 'demo') : ?>
    <!-- start row -->
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="alert alert-danger text-center" role="alert">
                <i class="mdi mdi-alert-circle align-middle me-1"></i>
                Dalam mode <strong><?= ucwords(ENVIRONMENT); ?></strong>, beberapa fungsi dalam halaman ini telah dinonaktifkan.
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Data Grup Navigasi</h4>
                <p class="card-title-desc text-justify">
                    Grup Navigasi digunakan dalam Aplikasi <strong><?= strtoupper($configIonix->appCode);?></strong> sebagai <strong>Data Master</strong> untuk pengelompokan <strong>Navigasi</strong> pada <strong><i>Sidebar</i></strong>.
                    Menghapus data grup akan berdampak pada <strong>Navigasi</strong> lainnya.
                </p>

                <div class="button-items mb-4">
                    <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-navigation-group"><i class="mdi mdi-refresh me-1"></i>Segarkan</button>
                    <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-navigation-groups" key="add-navigation-group"><i class="mdi mdi-plus me-1"></i>Tambah grup navigasi</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-9">
        <div class="card">
            <div class="card-body">
                <table id="dt_navigation_groups" class="table table-striped table-borderless align-middle w-100 mt-2">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" width="5%">No</th>
                            <th scope="col" class="">Kode</th>
                            <th scope="col" class="">Nama Grup</th>
                            <th scope="col" class="">Deskripsi</th>
                            <th scope="col" class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                </table>
                <!-- end table -->
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<div class="modal fade" id="modal-navigation-groups" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="card-description text-center">
                    Sesuaikan informasi <strong>Grup Navigasi</strong> pada bidang-bidang dibawah ini.
                    Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                </p>

                <?= customFormOpen('navigation-groups'); ?>
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
                                <label for="title">Nama Group<code>*</code></label>
                                <input type="text" name="title" class="form-control" placeholder="Masukan nama grup navigasi" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group position-relative">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Deskripsikan grup navigasi" rows="5" maxlength="500" data-provide="maxlength"></textarea>
                    </div>
                <?= customFormClose(); ?>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                <button type="submit" class="btn btn-<?= $configIonix->colorPrimary; ?> waves-effect waves-light" onclick="$('#form-navigation-groups').submit();">Button</button>
            </div>
        </div>
        <!-- end modal-content -->
    </div>
    <!-- end modal-dialog -->
</div>
<!-- end modal -->
<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/navigation-groups.init.js'); ?>
<?= $this->endSection(); ?>
