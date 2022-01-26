<?= $this->extend($configIonix->viewLayout['panel']);?>

<?= $this->section('meta');?>

<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'select2/css/select2.min.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'tagify/dist/tagify.css');?>
    <?= link_tag($configIonix->assetsFolder['panel']['library'].'dropify/dist/css/dropify.min.css');?>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Instansi/Badan Usaha</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><?= $userData->role_name;?> / <?= ucwords(uri_segment(1));?></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end page title -->

    <!-- start row -->
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="page-title-box text-center">
                <h3>Instansi/Badan Usaha</h3>
                <p class="text-muted">Informasi <strong>Instansi/Badan Usaha</strong> sangat dibutuhkan untuk melengkapi informasi pada Aplikasi. Informasi yang ada disini akan ditampilkan pada beberapa <strong>Halaman</strong> yang memang membutuhkan identitas <strong>Instansi/Badan Usaha</strong>.</p>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

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

    <!-- start row -->
    <div class="row justify-content-center">
        <div class="col-md-6 col-xl-4">
            <div class="card overflow-hidden">
                <div class="bg-soft bg-<?= $configIonix->colorPrimary;?>">
                    <div class="row">
                        <div class="col-7">
                            <div class="text-<?= $configIonix->colorPrimary;?> p-3">
                                <h5 class="text-<?= $configIonix->colorPrimary;?>">Personalisasi</h5>
                                <p class="text-justify">Sesuaikan informasi <strong>Instansi/Badan Usaha</strong> yang akan Anda gunakan pada Aplikasi.</p>
                            </div>
                        </div>
                        <div class="col-5 align-self-end">
                            <img src="<?= $configIonix->mediaFolder['image'].'content/'.uri_segment(1).'.png'?>" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pt-4">
                                <div class="dropdown float-end">
                                    <a href="javascript:void(0);" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-company" data-scope="<?= $libIonix->Encode('company');?>" key="upd-company"><i class="mdi mdi-circle-edit-outline align-middle text-<?= $configIonix->colorPrimary;?>"></i> Ubah Informasi</a>
                                    </div>
                                </div>
                                <!-- end dropdown -->
                                <div class="float-end ml-2">
                                    <span class="badge rounded-pill badge-soft-<?= $configIonix->colorPrimary;?> font-size-12" key="type"></span>
                                </div>
                            </div>

                            <div class="avatar-md profile-user-wid mb-4">
                                <img src="" alt="" class="img-thumbnail rounded-circle" key="logo|square-dark">
                            </div>
                            <h5 class="font-size-15 text-truncate" key="comname"></h5>
                            <p class="text-muted mb-0 text-truncate" key="email"></p>
                        </div>
                    </div>

                    <div class="alert alert-info text-center mt-4 mb-0" role="alert">
                        <i class="mdi mdi-information-variant font-size-18 align-middle font-size-12 me-1"></i>Informasikan identitas <strong>Instansi/Badan Usaha</strong> Anda pada <strong>Halaman</strong> ini.
                    </div>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-header p-0">
                  <img src="<?= $configIonix->mediaFolder['image'].'content/information.jpg';?>" alt="" class="img-thumbnail p-0">
                </div>
                <div class="card-body">
                    <h4 class="card-title mb-4">Informasi Dasar</h4>
                    <p class="text-muted text-justify mb-4" key="description"></p>
                    <table class="table mb-4" style="table-layout: fixed; width: 100%">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">Alamat</th>
                                <td class="text-justify" style="word-wrap: break-word">: <span class="text-muted" key="address"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">No. Telepon</th>
                                <td>: <span class="text-muted" key="phone"></span></td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 class="card-title mb-4">Tagar</h4>
                    <p class="text-muted text-center" key="tags"></p>

                    <?php if ($configIonix->viewCopyright == true): ?>
                        <h4 class="card-title mb-4">Domain URL</h4>
                        <pre class="text-center" key="domain"></pre>
                        <div class="alert alert-info text-center mb-0" role="alert">
                            <i class="mdi mdi-information-variant font-size-18 align-middle me-1"></i>
                            <strong>Informasi!</strong> Alamat <strong>Domain</strong> digunakan sebagai pengalihan saat <strong>Pengguna</strong> mengklik tulisan <strong><i>Copyright</i></strong>.
                        </div>
                    <?php endif; ?>
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
                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-social" data-scope="<?= $libIonix->Encode('social');?>" data-val="add" key="add-social"><i class="mdi mdi-plus"></i> Tautkan media sosial</a>
                        </div>
                    </div>
                    <!-- end dropdown -->
                    <h4 class="card-title mb-4">Media Sosial</h4>
                    <p class="text-muted text-justify mb-4">Agar orang-orang dapat berinteraksi dan mengenal dengan <strong>Instansi/Badan Usaha</strong> milik Anda, kami menyarankan untuk menyematkan tautan <strong>Media Sosial</strong>.</p>

                    <div class="row social-media"></div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-md-6 col-xl-8">
            <div class="row">
                <div class="col-md-6 col-xl-12">
                    <div class="alert alert-info text-center" role="alert">
                        <i class="mdi mdi-information-variant font-size-18 align-middle me-1"></i>
                        <strong>Informasi!</strong> Bagian ini dimaksudkan untuk merubah gambar-gambar yang digunakan dalam Aplikasi, sesuaikan dengan <strong>Instansi/Badan Usaha</strong> Anda. Format yang didukung hanyalah <strong>PNG</strong> dan untuk merubah gambar, silahkan <strong>Klik</strong> pada gambar yang akan diganti.
                    </div>
                </div>
            </div>

            <div class="row">
              <div class="col-md-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Logo Kotak (Cerah)</h4>
                        <div class="text-center">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-image" data-scope="<?= $libIonix->Encode('image');?>" data-val="<?= $libIonix->Encode('logo|square-light');?>" key="upd-square-light">
                              <img src="" class="img-thumbnail bg-dark" width="200">
                            </a>

                            <div class="clearfix"></div>

                            <p class="text-muted text-justify">Logo ini digunakan pada Menu Sidebar sebagai Header Aplikasi <strong><?= strtoupper($configIonix->appCode);?></strong> saat Siderbar berwarna gelap, mobile mode dan minimize. Sebaiknya gunakan ukuran pixel dengan ratio 1:1 atau kotak serta berformat PNG. Klik pada gambar ini untuk menyetelnya dengan yang baru.</p>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Logo Kotak (Gelap)</h4>
                        <div class="text-center">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-image" data-scope="<?= $libIonix->Encode('image');?>" data-val="<?= $libIonix->Encode('logo|square-dark');?>" key="upd-square-dark">
                              <img src="" class="img-thumbnail" width="200">
                            </a>

                            <div class="clearfix"></div>

                            <p class="text-muted text-justify">Logo ini digunakan pada Halaman Auth, Menu Sidebar dan Laporan. Sama seperti Logo Kotak (Putih) fungsinya namun logo ini sering sekali digunakan. Sebaiknya gunakan ukuran pixel dengan ratio 1:1 atau kotak serta berformat PNG. Klik pada gambar ini untuk menyetelnya dengan yang baru.</p>
                        </div>
                    </div>
                </div>
              </div>
              <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
              <div class="col-md-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Logo Panjang (Cerah)</h4>
                        <div class="text-center">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-image" data-scope="<?= $libIonix->Encode('image');?>" data-val="<?= $libIonix->Encode('logo|landscape-light');?>" key="upd-landscape-light">
                              <img src="" class="img-thumbnail bg-dark" width="200">
                            </a>

                            <div class="clearfix"></div>

                            <p class="text-muted text-justify">Logo ini digunakan pada Menu Sidebar sebagai Header Aplikasi <strong><?= strtoupper($configIonix->appCode);?></strong> saat Siderbar berwarna gelap, mobile mode dan minimize. Sebaiknya gunakan ukuran pixel dengan ratio 20:7 atau kotak serta berformat PNG. Klik pada gambar ini untuk menyetelnya dengan yang baru.</p>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Logo Panjang (Gelap)</h4>
                        <div class="text-center">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-image" data-scope="<?= $libIonix->Encode('image');?>" data-val="<?= $libIonix->Encode('logo|landscape-dark');?>" key="upd-landscape-dark">
                              <img src="" class="img-thumbnail" width="200">
                            </a>

                            <div class="clearfix"></div>

                            <p class="text-muted text-justify">Logo ini digunakan pada Halaman Auth, Menu Sidebar dan Laporan. Sama seperti Logo Kotak (Putih) fungsinya namun logo ini sering sekali digunakan. Sebaiknya gunakan ukuran pixel dengan ratio 20:7 atau kotak serta berformat PNG. Klik pada gambar ini untuk menyetelnya dengan yang baru.</p>
                        </div>
                    </div>
                </div>
              </div>
              <!-- end col -->
            </div>
            <!-- end row -->

            <?php if ($configIonix->allowQRCode === true): ?>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Logo QR</h4>
                                <div class="text-center">
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-image" data-scope="<?= $libIonix->Encode('image');?>" data-val="<?= $libIonix->Encode('logo|qr');?>" key="upd-qr">
                                      <img src="" class="img-thumbnail" width="200">
                                    </a>

                                    <div class="clearfix"></div>

                                    <div class="row justify-content-center">
                                      <div class="col-sm-8">
                                        <p class="text-muted">Logo ini digunakan pada bagian tengah Kode QR, umumnya QR dengan Logo didalamnya banyak digunakan oleh Aplikasi besar lainnya. Kami menyarankan untuk memberikan border juga di sampingnya agar tidak bertabrakan dengan <strong>Algoritma</strong> pada kode QR.</p>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            <?php endif; ?>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Latar Belakang Abstrak</h4>
                            <div class="text-center">
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-image" data-scope="<?= $libIonix->Encode('image');?>" data-val="<?= $libIonix->Encode('background|abstract');?>" key="upd-abstract">
                                    <img src="" class="img-thumbnail" width="500">
                                </a>

                                <div class="clearfix"></div>

                                <div class="row justify-content-center">
                                    <div class="col-sm-8">
                                        <p class="text-muted">Latar belakang dengan gambar <strong>Abstrak</strong> yang halus sangat disarankan, dan gambar ini digunakan pada halaman <strong>Lupa Password</strong> dan <strong>Pemulihan Kata Sandi</strong>. Sebaiknya latar belakang memiliki <strong>Geometric</strong> dan ada ruang kosong pada bagian tengahnya.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-xl-12">
                  <div class="card">
                      <div class="card-body">
                          <h4 class="card-title mb-4">Latar Belakang Hero</h4>
                          <div class="text-center">
                              <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-image"  data-scope="<?= $libIonix->Encode('image');?>" data-val="<?= $libIonix->Encode('background|hero');?>" key="upd-hero">
                                <img src="" class="img-thumbnail" width="500">
                              </a>

                              <div class="clearfix"></div>

                              <div class="row justify-content-center">
                                <div class="col-sm-8">
                                  <p class="text-muted">Latar belakang yang digunakan pada halaman <strong>Awal</strong> saat orang lain pertama kali mengakses Aplikasi ini. Sebaiknya latar belakang pada bagian ini disesuaikan dengan Halaman Awal.</p>
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                <!-- end col -->

                <div class="col-xl-12">
                  <div class="card">
                      <div class="card-body">
                          <h4 class="card-title mb-4">Latar Belakang Halaman</h4>
                          <div class="text-center">
                              <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-image"  data-scope="<?= $libIonix->Encode('image');?>" data-val="<?= $libIonix->Encode('background|page');?>" key="upd-page">
                                <img src="" class="img-thumbnail" width="500">
                              </a>

                              <div class="clearfix"></div>

                              <div class="row justify-content-center">
                                <div class="col-sm-8">
                                  <p class="text-muted">Latar belakang yang digunakan pada setiap halaman yang ada di halaman depan. Sebaiknya latar belakang pada bagian ini disesuaikan dengan Halaman Awal.</p>
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="modal fade" id="modal-company" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Infromasi Instansi/Badan Usaha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                      Informasi mengenai jenis usaha, alamat dan lainnya agar dapat digunakan pada Aplikasi ini.
                      Sesuaikan identitas <strong>Instansi/Badan Usaha</strong> Anda pada bidang-bidang dibawah ini.
                      Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('company');?>
                        <h5 class="text-center my-md-3">Infromasi Dasar</h5>

                        <div class="form-group position-relative">
                            <label for="name">Nama Badan Usaha<code>*</code></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukan nama instansi" required>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="code">Kode Badan Usaha<code>*</code></label>
                                    <input type="text" name="code" class="form-control" placeholder="Masukan kode instansi" maxlength="15" data-provide="maxlength" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="type">Bidang/Jenis Usaha<code>*</code></label>
                                    <input type="text" name="type" class="form-control" placeholder="Masukan bidang/jenis usaha" required>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-center my-md-3">Infromasi Lainnya</h5>

                        <div class="form-group position-relative">
                            <label for="tags">Tagar</label>
                            <input type="text" name="tags" class="form-control inputtags" placeholder="Masukan tagar">
                        </div>

                        <div class="form-group position-relative">
                            <label for="description">Deskripsi Badan Usaha<code>*</code></label>
                            <textarea id="description" name="description" class="form-control" placeholder="Deskripsikan instansi" rows="5" required></textarea>
                        </div>

                        <?php if ($configIonix->viewCopyright == true): ?>
                            <div class="form-group position-relative">
                                <div class="d-block">
                                    <label for="domain">Domain URL<code>*</code></label>
                                    <div class="float-end">
                                        <p class="text-muted mb-0"><code>ex.</code> <i>ionixeternal.co.id</i></p>
                                    </div>
                                </div>
                                <input type="text" name="domain" class="form-control" placeholder="Masukan alamat domain" required>
                            </div>
                        <?php endif; ?>

                        <h5 class="text-center my-md-3">Infromasi Lokasi</h5>

                        <div class="form-group position-relative">
                            <label for="address">Alamat<code>*</code></label>
                            <input type="text" name="address" class="form-control" placeholder="Masukan alamat instansi" required>
                        </div>

                        <div class="form-group position-relative">
                            <label for="country">Negara</label>
                            <select class="form-control select2" name="country" aria-hidden="true" data-placeholder="Pilih negara..." data-scope="<?= $libIonix->Encode('province');?>" required>
                                <option></option>
                                <?php foreach ($data['modCountry']->fetchData(NULL, false, 'CUSTOM')->orderBy('country_name', 'ASC')->get()->getResult() as $row): ?>
                                    <option value="<?= $row->country_id;?>"><?= ucwords($row->country_name);?> (<?= strtoupper($row->country_iso3);?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="province">Provinsi</label>
                                    <select class="form-control select2" name="province" aria-hidden="true" data-placeholder="Pilih provinsi..." data-scope="<?= $libIonix->Encode('district');?>" required></select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="district">Kab/Kota</label>
                                    <select class="form-control select2" name="district" aria-hidden="true" data-placeholder="Pilih kab/kota..." data-scope="<?= $libIonix->Encode('subdistrict');?>" required></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="subdistrict">Kecamatan<code>*</code></label>
                                    <select class="form-control select2" name="subdistrict" aria-hidden="true" data-placeholder="Pilih kecamatan..." data-scope="<?= $libIonix->Encode('village');?>" required></select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="village">Kelurahan/Desa<code>*</code></label>
                                    <select class="form-control select2" name="village" aria-hidden="true" data-placeholder="Pilih kel/desa..." required></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group position-relative">
                                    <label for="zipcode">Kode POS<code>*</code></label>
                                    <input type="number" name="zipcode" class="form-control" placeholder="Masukan kode pos" maxlength="5" data-provide="maxlength" required>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-center my-md-3">Infromasi Kontak</h5>

                        <div class="form-group position-relative">
                            <div class="d-block">
                                <label for="email">Email<code>*</code></label>
                                <div class="float-end">
                                    <p class="text-muted mb-0"><code>ex.</code> <i>xxxxx@xxxxx.xxx</i></p>
                                </div>
                            </div>
                            <input type="email" name="email" class="form-control" placeholder="Masukan alamat email" required>
                        </div>

                        <div class="form-group position-relative">
                            <div class="d-block">
                              <label for="phone">No. Telepon<code>*</code></label>
                                  <div class="float-end">
                                      <p class="text-muted mb-0"><code>ex.</code> <i>08xxxxxxxxxx</i> <code>atau</code> <i>02xx-xxxxxxxx</i></p>
                                  </div>
                            </div>
                            <input type="text" name="phone" class="form-control" placeholder="Masukan nomor telepon" onkeypress="return isNumberKey(event);" required>
                        </div>
                    <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" onclick="$('#form-company').submit();">Simpan</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <div class="modal fade" id="modal-social" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tautkan Media Sosial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                      Pilih Provider <strong>Media Sosial</strong> yang tersedia dan masukkan <strong>username</strong> Media Sosial yang <strong>Instansi/Badan Usaha</strong> miliki ke dalam URL di bawah ini.
                      Jika ada bidang yang bertanda <code>*</code>, maka bidang tersebut wajib diisi.
                    </p>

                    <?= customFormOpen('social');?>
                        <div class="form-group position-relative">
                            <label for="sosprov">Provider<code>*</code></label>
                            <select name="sosprov" class="form-control select2" data-placeholder="Pilih provider..." data-scope="<?= $libIonix->Encode('sosprov');?>" required>
                                <option></option>
                                <?php foreach ($libIonix->getQuery('social_provider')->getResult() as $row): ?>
                                    <option value="<?= $libIonix->Encode($row->sosprov_id);?>"><?= ucwords($row->sosprov_name);?></option>
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
                            Dengan menautkan <strong>Media Sosial</strong>, orang-orang akan dengan mudah menemukan <strong>Instansi/Badan Usaha</strong> Anda.
                        </div>
                    <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                  <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                  <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" onclick="$('#form-social').submit();">Tautkan</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->

    <div class="modal fade" id="modal-image" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ganti Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="card-description text-center">
                      Pilih atau letakan gambar disini dan Anda dapat mulai mengunggahnya, pastikan format gambar telah sesuai dengan aturan yang ditetapkan.
                    </p>

                    <?= customFormOpen('image');?>
                    <div class="form-group position-relative">
                      <label>Unggah gambar disini <code>(Max. <?= $configIonix->maximumSize['image'];?>B)</code></label>
                      <input id="image" type="file" name="image" class="dropify" accept="image/x-png" data-max-file-size="<?= $configIonix->maximumSize['image'];?>" data-show-errors="true" data-allowed-formats="square landscape" data-allowed-file-extensions="png" required>
                    </div>
                    <?= customFormClose();?>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    <button type="submit" class="btn btn-<?= $configIonix->colorPrimary;?> waves-effect waves-light" onclick="$('#form-image').submit();">Unggah</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- end modal-dialog -->
    </div>
    <!-- end modal -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'select2/js/select2.min.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'tagify/dist/tagify.js');?>
    <?= script_tag($configIonix->assetsFolder['panel']['library'].'dropify/dist/js/dropify.min.js');?>

    <?= script_tag($configIonix->assetsFolder['local'].'js/panel/company.init.js');?>
<?= $this->endSection();?>
