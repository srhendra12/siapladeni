<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Input Data <span class="caret"></span></a>
    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
        <li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" href="#">Tahap Kesiapan Pelaksanaan</a>
            <ul class="dropdown-menu">
                <?php
                $sql = "select kd_sektor, nm_sektor from p_sektor where is_active = '1' order by  sortBy asc";
                $exe = mysqli_query($connDB, $sql);
                $i=0;
                $kdSektor = array();
                $nmSektor = array();
                while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                   echo '<li><a tabindex="0" href="'.BASE_URL_MENU.'?fl=input_perencanaan&sektor='.$row['kd_sektor'].'" title="Input Data Tahap Kesiapan Pelaksanaan '.$row['nm_sektor'].'">'.$row['nm_sektor'].'</a></li>';
                   $kdSektor[$i] = $row['kd_sektor'];
                   $nmSektor[$i] = $row['nm_sektor'];
                   $i++;
                }
                ?>
            </ul>
        </li> 
        <li role="separator" class="divider"></li>
        <li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" href="#">Tahap Pelaksanaan Konstruksi</a>
            <ul class="dropdown-menu">
                <?php
                for($x=0;$x<$i;$x++){
                    echo '<li><a tabindex="0" href="'.BASE_URL_MENU.'?fl=input_pelaksanaan&sektor='.$kdSektor[$x].'" title="Input Data Tahap Pelaksanaan Konstruksi '.$nmSektor[$x].'">'.$nmSektor[$x].'</a></li>';
                }
                ?>
            </ul>
        </li> 
        <li role="separator" class="divider"></li>
        <li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" href="#">Tahap Pasca Konstruksi</a>
            <ul class="dropdown-menu">
                <?php
                for($x=0;$x<$i;$x++){
                    echo '<li><a tabindex="0" href="'.BASE_URL_MENU.'?fl=input_pascaKonstruksi&sektor='.$kdSektor[$x].'" title="Input Data Tahap Pasca Konstruksi '.$nmSektor[$x].'">'.$nmSektor[$x].'</a></li>';
                }
                ?>
            </ul>
        </li> 
    </ul>
</li>
<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Output Data <span class="caret"></span></a>
    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
        <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=output_perencanaan" title="Output Data Tahap Kesiapan Pelaksanaan">Tahap Kesiapan Pelaksanaan</a></li>
        <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=output_pelaksanaan" title="Output Data Tahap Pelaksanaan Konstruksi">Tahap Pelaksanaan Konstruksi</a></li>
        <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=output_pascaKonstruksi" title="Output Data Tahap Pasca Konstruksi">Tahap Pasca Konstruksi</a></li>
    </ul>
</li>
<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Laporan <span class="caret"></span></a>
    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
        <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=laporan_perencanaan" title="Laporan Tahap Kesiapan Pelaksanaan">Tahap Kesiapan Pelaksanaan</a></li>
        <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=laporan_pelaksanaan" title="Laporan Tahap Pelaksanaan Konstruksi">Tahap Pelaksanaan Konstruksi</a></li>
        <li><a tabindex="0" href="<?=BASE_URL_MENU?>?fl=laporan_pascaKonstruksi" title="Laporan Tahap Pasca Konstruksi">Tahap Pasca Konstruksi</a></li>
    </ul>
</li>