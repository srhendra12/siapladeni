<li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=input_perencanaan" title="Input Data">Input Data</a></li>
<li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=output_perencanaan" title="Output Data">Output Data</a></li>
<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false">Laporan <span class="caret"></span></a>
   <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
      <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=laporan_perencanaan" title="Laporan Deteksi Dini">Hasil
            Penilaian</a></li>
      <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=rekap_penilaian" title="Rekapitulasi Hasil Penilaian">Rekapitulasi
            Hasil Penilaian</a></li>
      <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=rekap_potensi_ancaman"
            title="Rekapitulasi Potensi Ancaman">Rekapitulasi Potensi Ancaman</a></li>
   </ul>
</li>
<li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=ragam_dokumen" title="Unduh Ragam Dokumen">Ragam Download</a></li>

<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false">Management Data <span class="caret"></span></a>
   <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
      <li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" href="#">Elemen Assessment</a>
         <ul class="dropdown-menu">
				<?php if($_SESSION['access'] ==  4) : ?>
            <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=param_tahap_perencanaan"
                  title="Instrument Deteksi Dini">Instrument Deteksi Dini</a></li>
            <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=param_range_tahap_perencanaan"
                  title="Parameter Kecenderungan Pemahaman Petugas terhadap Tupoksi Pemasyarakatan">Parameter
                  Kecenderungan Pemahaman Petugas</a></li>
            <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=param_range_perilaku_napi"
                  title="Parameter Kecenderungan perilaku narapidana/tahanan">Parameter Kecenderungan perilaku
                  narapidana/tahanan</a></li>
				<?php endif;?>
            <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=upload_dokumen_pendukung"
                  title="Upload Dokumen Pendukung">Upload Dokumen Pendukung</a></li>
         </ul>
      </li>
		<?php if($_SESSION['access'] ==  4) : ?>
      <li role="separator" class="divider"></li>
      <li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" href="#">Parameter Data</a>
         <ul class="dropdown-menu">
				<li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=param_nilai" title="Nilai">Nilai</a></li>
         </ul>
      </li>
		<?php endif; ?>
      <li role="separator" class="divider"></li>
      <li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" href="#">Pengaturan</a>
         <ul class="dropdown-menu">
            <!-- <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=akes_user" title="UPT Pemasyarakatan">Kelola Akses User</a></li> -->
				<li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=param_upt" title="UPT Pemasyarakatan">UPT Pemasyarakatan</a></li>
            <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=daftar_user" title="Nilai">Kelola Daftar User</a></li>
				<?php if($_SESSION['access'] ==  4) : ?>
            <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=slide-show" title="Image Slide Show"><i>Slide Show
                     Image</i></a></li>
            <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=link_terkait" title="Informasi Link Terkait">Informasi Link
                  Terkait</a></li>
            <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=setting_about_us" title="About Us">About Us</a></li>
            <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=param_setting" title="Web Setting">Web Setting</a></li>
				<?php endif; ?>
         </ul>
      </li>
   </ul>
</li>