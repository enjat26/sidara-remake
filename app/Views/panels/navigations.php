<?= $this->extend($configIonix->viewLayout['panel']); ?>

<?= $this->section('meta'); ?>
    <meta name="scope" content="<?= $libIonix->Encode('navigation'); ?>">
<?= $this->endSection(); ?>

<?= $this->section('stylesheet'); ?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'] . 'select2/css/select2.min.css'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Navigasi</h4>

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
                <h4 class="card-title mb-3">Data Navigasi</h4>
                <p class="card-title-desc text-justify">
                    Navigasi digunakan dalam Aplikasi <strong><?= strtoupper($configIonix->appCode); ?></strong> sebagai pengatur akses <strong>Menu Halaman</strong>.
                    Anda dapat mengkonfigurasi <strong>Navigasi</strong> pada bagian ini sesuai kebutuhan.
                </p>

                <div class="button-items mb-4">
                    <button type="button" class="btn btn-success waves-effect waves-light" key="rfs-navigation"><i class="mdi mdi-refresh me-1"></i>Segarkan</button>
                    <button type="button" class="btn btn-light waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-navigations" key="add-navigation"><i class="mdi mdi-plus me-1"></i>Tambah navigasi baru</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-9">
        <div class="card">
            <div class="card-body">
                <table id="dt_navigations" class="table table-striped table-borderless align-middle w-100 mt-2">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" width="5%">ID</th>
                            <th scope="col" class="">Grup</th>
                            <th scope="col" class="">Nama Navigasi</th>
                            <th scope="col" class="">Link</th>
                            <th scope="col" class="">Icon</th>
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

<div class="modal fade" id="modal-navigations" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="card-description text-center">
                    Sesuaikan informasi <strong>Navigasi</strong> pada bidang-bidang dibawah ini.
                    Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                </p>

                <?= customFormOpen('navigations'); ?>
                    <h5 class="text-center my-md-3">Informasi Dasar</h5>

                    <div class="row">
                        <div class="col-sm-6 col-lg-6">
                            <div class="form-group position-relative">
                                <label for="group">Grup Navigasi<code>*</code></label>
                                <select class="form-control select2" name="group" aria-hidden="true" data-placeholder="Pilih grup..." required>
                                    <option></option>
                                    <?php foreach ($data['modNavigationGroup']->fetchData(NULL, false, 'CUSTOM')->orderBy('group_id', 'ASC')->get()->getResult() as $row) : ?>
                                        <option value="<?= $row->group_id ?>"><?= $row->group_title ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="form-group position-relative">
                                <label for="parent">Induk Navigasi</label>
                                <select class="form-control select2" name="parent" aria-hidden="true" data-placeholder="Pilih induk...">
                                    <option></option>
                                    <?php foreach ($data['modNavigation']->fetchData()->get()->getResult() as $row) : ?>
                                        <option value="<?= $row->menu_id ?>"><?= $row->menu_title;?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group position-relative">
                        <label for="title">Nama Navigasi<code>*</code></label>
                        <input type="text" name="title" class="form-control" placeholder="Masukan nama navigasi" required>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-lg-6">
                            <div class="form-group position-relative">
                                <label for="link">Link</label>
                                <input type="text" name="link" class="form-control" placeholder="Masukan link" maxlength="30" data-provide="maxlength">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="form-group position-relative">
                                <label for="icon">Icon<code>*</code></label>
                                <input type="text" name="icon" class="form-control" placeholder="Masukan icon" maxlength="30" required>
                            </div>
                        </div>
                    </div>

                    <div class="alert border-0 border-start border-5 border-info py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-size-18 text-info"><i class="mdi mdi-information-variant"></i></div>
                            <div class="ms-3">
                                <div>
                                    Gunakan <strong><i>Material Design Icons</i></strong> untuk <i>icon</i> Navigasi.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-lg-6">
                            <div class="form-group position-relative">
                                <label for="order">Urutan Navigasi</label>
                                <input type="number" name="order" class="form-control" placeholder="Masukan urutan" required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="form-group position-relative">
                                <label for="previlege">Keistimewaan<code>*</code></label>
                                <select class="form-control select2" name="previlege" aria-hidden="true" data-placeholder="Pilih keistimewaan..." required>
                                    <option value="0" selected>Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?= customFormClose(); ?>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                <button type="submit" class="btn btn-<?= $configIonix->colorPrimary; ?> waves-effect waves-light" onclick="$('#form-navigations').submit();">Button</button>
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

    <?= script_tag($configIonix->assetsFolder['local'] . 'js/panel/navigations.init.js'); ?>
<?= $this->endSection(); ?>
