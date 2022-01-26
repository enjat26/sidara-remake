<?= $this->extend($configIonix->viewLayout['pages']);?>

<?= $this->section('meta');?>

<?= $this->endSection();?>

<?= $this->section('stylesheet');?>
    <style media="screen">
        .highlighted-word.highlighted-word-animation-1:after {
          background: url(<?= $configIonix->mediaFolder['image'].'shape/pencil-green-line.png';?>)!important;
        }

        html section.section-primary p {
          color: #FFFFFF !important;
        }
    </style>
<?= $this->endSection();?>

<?= $this->section('content');?>
    <section class="section section-overlay-opacity section-overlay-opacity-scale-3 border-0 m-0" style="background-image: url(<?= core_url('image/background/hero.png');?>); background-size: cover; background-position: center;">
        <div class="container py-5">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 text-center mb-5 mb-lg-0">
                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                        <h3 class="position-relative text-color-light text-5 line-height-5 font-weight-medium px-4 mb-2 appear-animation" data-appear-animation="fadeInDownShorterPlus" data-plugin-options="{'minWindowWidth': 0}">
                            <span class="position-absolute right-100pct top-50pct transform3dy-n50 opacity-3">
                                <img src="<?= $configIonix->mediaFolder['image'].'shape/slide-title-border.png';?>" class="w-auto appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="250" data-plugin-options="{'minWindowWidth': 0}" alt="" />
                            </span>
                            Selamat
                            <span class="position-relative">
                                Datang
                                <span class="position-absolute left-50pct transform3dx-n50 top-0 mt-3">
                                    <img src="<?= $configIonix->mediaFolder['image'].'shape/slide-green-line.png';?>" class="w-auto appear-animation"data-appear-animation="fadeInLeftShorterPlus" data-appear-animation-delay="2500" data-plugin-options="{'minWindowWidth': 0}" alt="" />
                                </span>
                            </span>
                            di
                            <span class="position-absolute left-100pct top-50pct transform3dy-n50 opacity-3">
                                <img src="<?= $configIonix->mediaFolder['image'].'shape/slide-title-border.png';?>" class="w-auto appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="250" data-plugin-options="{'minWindowWidth': 0}" alt="" />
                            </span>
                        </h3>
                        <h1 class="text-color-light font-weight-extra-bold text-12 mb-2 appear-animation" data-appear-animation="blurIn" data-appear-animation-delay="1300" data-plugin-options="{'minWindowWidth': 0}"><?= strtoupper($configIonix->appCode);?></h1>
                        <p class="text-4 text-color-light font-weight-light opacity-7 mb-0" data-plugin-animated-letters data-plugin-options="{'startDelay': 3500, 'minWindowWidth': 0}">Sistem Informasi Data <?= strtoupper($companyData->code);?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="home-intro mb-0" id="home-intro">
  			<div class="container">
    				<div class="row align-items-center">
      					<div class="col text-center">
        						<p class="mb-0">
          							<?= strtoupper($configIonix->appCode);?> hadir untuk menyediakan <span class="highlighted-word highlighted-word-animation-1 text-color-primary font-weight-semibold text-5">Data Sektoral</span> Kepemudaan dan Olahraga di Provinsi Banten.
        						</p>
      					</div>
    				</div>
  			</div>
		</div>

    <!-- cabor dan Atlet Start -->
    <section id="intro" class="section section-no-border section-angled bg-light pt-5 pb-5 m-0">
        <div class="section-angled-layer-bottom section-angled-layer-increase-angle bg-color-light-scale-1 mt-3" style="padding: 21rem 0;"></div>

        <div class="container pb-5">
            <div class="row mb-5 pb-lg-3 counters appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400" style="animation-delay: 400ms;">
                <div class="col-lg-10 text-center offset-lg-1">
                    <p class="text-1rem text-color-default negative-ls-05 pt-3 pb-4 mb-5">
                        <strong><?= strtoupper($configIonix->appCode);?></strong> terbagi menjadi 2 Data Sektoral yang dapat ditayangkan untuk konsumsi Publik diantaranya
                    </p>
                </div>

                <div class="col-sm-6 col-lg-4 offset-lg-2 counter mb-5 mb-md-0">
                    <div class="m-0 featured-box featured-box-primary featured-box-effect-4 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="1600">
                        <div class="box-content text-center">
                            <span class="d-inline-block font-weight-extra-bold font-italic line-height-1 text-18 ls-0 mb-2"><i class="fas fa-users text-primary"></i></span>
                            <h4 class="font-weight-bold text-color-dark"><a href="<?= core_url('youths');?>">Kepemudaan</a></h4>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-4 counter divider-left-border">
                    <div class="m-0 featured-box featured-box-primary featured-box-effect-4 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="1600">
                        <div class="box-content text-center">
                            <span class="d-inline-block font-weight-extra-bold font-italic line-height-1 text-18 ls-0 mb-2"><i class="fas fa-running text-primary"></i></span>
                            <h4 class="font-weight-bold text-color-dark"><a href="<?= core_url('sports');?>">Olahraga</a></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Cabor dan Atlet -->
<?= $this->endSection();?>

<?= $this->section('javascript');?>
    <?= script_tag($configIonix->assetsFolder['local'].'js/pages/home.init.js');?>
<?= $this->endSection();?>
