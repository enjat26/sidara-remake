<div class="card-body">

    <div align="center">
        <img src="<?= $configIonix->mediaFolder['image'].'guide/thumbnails.png';?>" class="img-thumbnail" alt="Thumbnails">
    </div>

    <div class="my-md-3">
        <h1 align="center">Selamat datang di Aplikasi <?= strtoupper($configIonix->appCode);?> <?= ucwords($configIonix->appType);?></h1>
    </div>

    <p align="center">
        <img src="https://img.shields.io/badge/license-licensed-blue.svg" alt="License: License">
        <img src="https://img.shields.io/badge/version-v.<?= $configIonix->appVersion;?>-green.svg" alt="Version">
        <?php if ($configIonix->appType): ?>
            <img src="https://img.shields.io/badge/type-<?= $configIonix->appType;?>-red.svg" alt="Type">
        <?php endif; ?>
    </p>

    <p align="center"> This guide for your documentation files.</p>

    <h1>Introduction</h1>
    <div class="border-top"><br></div>

    <div style="text-align: justify;margin-bottom: 10px">
        SIDARA hadir untuk menyediakan Data Sektoral Kepemudaan dan Olahraga berbasis Digital di Provinsi Banten.
        Diharapkan akan meningkatkan tingkat pelayanan kepada masyarakat terutama Komunitas Pemuda dan Olahraga dalam mengakses Data Sektoral Kepemudaan dan Olahraga.
    </div>

    <h1>Application</h1>

    <div class="border-top"><br></div>

    <h4>Components</h4>

    <ul>
        <li><a href="https://www.codeigniter.com/" target="_blank">Framework CodeIgniter</a> version 4.x</li>
        <li><a href="https://www.getbootstrap.com/" target="_blank">Bootstrap</a> version 5.x</li>
        <li><a href="https://www.jquery.com/" target="_blank">JQuery</a> version 3.x</li>
    </ul>

    <h4>Requirements</h4>

    <ul>
        <li><a href="https://www.apachefriends.org/" target="_blank">PHP</a> version 7.x or newer</li>
        <li><a href="https://www.apachefriends.org/" target="_blank">MySQL</a> version 5.x or newer</li>
        <li>Web Browser latest version</li>
    </ul>

    <h4>Issues</h4>

    <p>Untuk semua masalah termasuk permintaan penambahan fitur, silahkan <a href="https://wa.me/message/YA32H76WDRDTC1" target="_blank">hubungi kami</a>.</p>

    <h4>Changes</h4>

    <p>Lihat pada tab <kbd>Changelog</kbd> untuk mengetahui seluruh perubahan yang telah dilakukan.</p>

    <h4>Credits</h4>

    <ul>
        <li><a href="https://www.instagram.com/ionixeternalstudio/" target="_blank">CV. Ionix Eternal Studio</a></li>
    </ul>

    <h4>Author</h4>

    <ul>
        <li><a href="https://www.instagram.com/ubenwisnu/" target="_blank">Uben Wisnu</a></li>
    </ul>

    <h4>Trademark</h4>

    <div align="center">
        <img src="<?= $configIonix->mediaFolder['image'].'guide/watermark.png';?>" class="img-thumbnail" alt="Watermark" style="background-color: transparent; border: none" width="80%">
    </div>

</div>
