<html>
	<head>
		<style>
			body {
				Bookman Old Style; font-size: 16px;
				align: right;
			}
			@page {
				margin-top: 1cm;
				margin-bottom: 1cm;
			}
			h3 { position: fixed; top: -40px; left: 0px; right: 0px;}.page-number:after { content: counter(page); }
		</style>
	</head>
	<body>
		<?php
			function tanggal_indo($tanggal)
			{
				$bulan = array (1 =>   'Januari',
					'Februari',
					'Maret',
					'April',
					'Mei',
					'Juni',
					'Juli',
					'Agustus',
					'September',
					'Oktober',
					'November',
					'Desember'
				);
				$split = explode('-', $tanggal);
				return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
			}

			function mask ( $str, $start = 0, $length = null ) {
				$mask = preg_replace ( "/\S/", "*", $str );
				if( is_null ( $length )) {
					$mask = substr ( $mask, $start );
					$str = substr_replace ( $str, $mask, $start );
				}else{
					$mask = substr ( $mask, $start, $length );
					$str = substr_replace ( $str, $mask, $start, $length );
				}
				return $str;
			}			

            $session = \Config\Services::session();
            $wilayah_aktif = $session->get('nama_wilayah_aktif');
            $nama_tahun_ajaran_aktif = $session->get('nama_tahun_ajaran_aktif');
            $tahun_ajaran_aktif = $session->get('tahun_ajaran_aktif');

            $path = WRITEPATH ."/qrcode/" .$peserta_didik_id. ".png";
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		?>

        <img src="<?php echo $base64; ?>" height="50px" width="50px">
		<div align="center">
			<b>TANDA BUKTI PENDAFTARAN DAN DAFTAR ULANG<br>PENERIMAAN PESERTA DIDIK BARU (PPDB) ONLINE <?php echo strtoupper($wilayah_aktif);?><br>TAHUN <?php echo $nama_tahun_ajaran_aktif; ?></b>
		</div><br>
		<p>Identitas siswa :</p>
		<table width="100%">
			<tr>
				<td valign="top" width="25%" style="padding-left: 5px; padding right: 5px;">NIK</td>
				<td valign="top">:</td>
				<td width="75%"><?php echo $profilsiswa['nik'];?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 5px; padding right: 5px;">NISN</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['nisn'];?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 5px; padding right: 5px;">Nama</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['nama'];?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 5px; padding right: 5px;">Jenis Kelamin</td>
				<td valign="top">:</td>
				<td><?php if($profilsiswa['jenis_kelamin']=="L"){echo "Laki-laki";}else{echo "Perempuan";}?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 5px; padding right: 5px;">Tempat / Tanggal Lahir</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['tempat_lahir'];?>, <?php echo tanggal_indo($profilsiswa['tanggal_lahir']);?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 5px; padding right: 5px;">Nama Ibu Kandung</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['nama_ibu_kandung'];?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 5px; padding right: 5px;">Alamat</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['desa_kelurahan'];?>, <?php echo $profilsiswa['kecamatan'];?>, <?php echo $profilsiswa['kabupaten'];?>, <?php echo $profilsiswa['provinsi'];?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 5px; padding right: 5px;">Nomor Kontak</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['nomor_kontak'];?></td>
			</tr>
		</table>
		<p>Pilihan sekolah PPDB :</p>
		<table width="100%" style="border-collapse: collapse;" border=1>
			<tr>
				<th align="center">Pilihan</th>
				<th align="center">Nama Sekolah</th>
				<th align="center">Jalur</th>
				<th align="center">Skor</th>
				<th align="center">Status</th>
			</tr>
			<?php foreach($pendaftaran as $row):?>
			<tr>
				<td valign="top" align="center"><?php echo $row['jenis_pilihan'];?></td>
				<td valign="top" style="padding-left: 5px; padding right: 5px;"><?php echo $row['sekolah'];?></td>
				<td valign="top" align="center"><?php echo $row['jalur'];?></td>
				<td valign="top" align="center"><?php echo $row['skor'];?></td>
				<td valign="top" align="center">
				<?php if($row['status_penerimaan_final']==0){?>Dalam Proses Seleksi
				<?php }else if($row['status_penerimaan_final']==1){?>Diterima
				<?php }else if($row['status_penerimaan_final']==2){?>Tidak Diterima
				<!-- Pada saat daftar ulang Daftar Tunggu == Masuk Kuota !-->
				<?php }else if($row['status_penerimaan_final']==3){?>Diterima
				<?php }else if($row['status_penerimaan_final']==4){?>Diterima Pilihan <?php echo $row['masuk_jenis_pilihan'] ?>
				<?php }else {?>Tidak Diterima
				<?php }?>

				</td>
			</tr>
			<?php endforeach;?>
		</table>
		<p>Dokumen pendukung yang diverifikasi dan diserahkan :</p>
		<table width="100%" style="border-collapse: collapse;" border=1>
			<tr>
				<th align="center">Nama Dokumen</th>
				<th align="center">Verifikasi</th>
				<th align="center">Dokumen Fisik</th>
			</tr>
			<?php foreach($dokumenpendukung as $row):
			?>
			<tr>
				<td valign="top" align="left" style="padding-left: 5px; padding right: 5px;">
					<?php echo $row['nama'];?> 
					<?php if($row['placeholder']==1) {?>*<?php }?>
				</td>
				<td valign="top" align="center">
					<?php if($row['verifikasi']==1){?>Benar
					<?php }else if($row['verifikasi']==2){?>Tidak Benar</i>
					<?php }else if($row['verifikasi']==3){?>Tidak Ada
					<?php }else{?>Dalam Proses<?php }?>
				</td>
				<td valign="top" align="center">
					<?php if($row['verifikasi']==3){?>Tidak Ada
					<?php }else if($row['berkas_fisik']!=1){?>Tidak Ada
					<?php }else if($row['berkas_fisik']==1){?>Asli
					<?php }else if($row['berkas_fisik']==2){?>Fotokopi Dilegalisir</i>
					<?php }else{?>Fotokopi<?php }?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
		&nbsp;&nbsp;* Coret nama dokumen yang tidak benar<br>
		<table width="100%">
			<tr>
				<td colspan="3">&nbsp; <td>
			</tr>
			<tr>
				<td valign="top" width="25%">Lokasi Verifikasi</td>
				<td valign="top">:</td>
				<td width="75%"><?php echo $profilsiswa['lokasi_berkas'];?></td>
			</tr>
		</table>
		<table width="100%">
			<tr>
				<td colspan="2">&nbsp; <td>
			</tr>
			<tr>
				<td width="75%"><br><br><br><br><br><br><br><br></td>
				<td>Kebumen, <?php echo tanggal_indo(date('Y-m-d'));?><br>Yang Menerima Berkas,<br><br><br><br><br><br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>NIP: </td>
			</tr>
		</table>

	</body>
</html>