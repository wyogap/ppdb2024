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

		?>
		<img src="qrcode/images/<?php echo $this->session->userdata("pengguna_id");?>.png" height="50px" width="50px">
		<div align="center">
			<img class="text-center" src="assets/image/logodinas.png" height="60px" width="60px"><br><br>
			<b>TANDA BUKTI VERIFIKASI<br>PPDB ONLINE <?php echo strtoupper($wilayah_aktif);?><br><?php echo $this->session->userdata("nama");?></b>
		</div><br><br>
		<table>
			<?php foreach($profilsiswa->getResult() as $row):?>
			<tr>
				<td valign="top">NIK</td>
				<td valign="top">:</td>
				<td><?php echo $row->nik;?></td>
			</tr>
			<tr>
				<td valign="top">NISN</td>
				<td valign="top">:</td>
				<td><?php echo $row->nisn;?></td>
			</tr>
			<tr>
				<td valign="top">Nama</td>
				<td valign="top">:</td>
				<td><?php echo $row->nama;?></td>
			</tr>
			<tr>
				<td valign="top">Jenis Kelamin</td>
				<td valign="top">:</td>
				<td><?php if($row->jenis_kelamin=="L"){echo "Laki-laki";}else{echo "Perempuan";}?></td>
			</tr>
			<tr>
				<td valign="top">Tempat dan Tanggal Lahir</td>
				<td valign="top">:</td>
				<td><?php echo $row->tempat_lahir;?>, <?php echo tanggal_indo($row->tanggal_lahir);?></td>
			</tr>
			<tr>
				<td valign="top">Nama Ibu Kandung</td>
				<td valign="top">:</td>
				<td><?php echo $row->nama_ibu_kandung;?></td>
			</tr>
			<tr>
				<td valign="top">Alamat</td>
				<td valign="top">:</td>
				<td><?php echo $row->desa_kelurahan;?></td>
			</tr>
			<tr>
				<td valign="top">&nbsp;</td>
				<td valign="top">&nbsp;</td>
				<td><?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?></td>
			</tr>
			<tr>
				<td valign="top"><b>Waktu Verifikasi</b></td>
				<td valign="top">:</td>
				<td><b><?php echo $row->waktu_kelengkapan_berkas;?></b></td>
			</tr>
			<?php endforeach;?>
		</table>
		<br><br>
		<!--<table width="100%" style="border-collapse: collapse;" border=1>
			<tr>
				<td align="center"><b>Daftar Kelengkapan</b></td>
				<td align="center"><b>Status Verifikasi</b></td>
			</tr>
			<?php foreach($daftarberkasverifikasi->getResult() as $row):?>
			<tr>
				<td><?php echo $row->kelengkapan;?></td>
				<td align="center">
					<?php if($row->verifikasi==2){?>Tidak Ada<?php }else if($row->verifikasi==1){?>Ada<?php }else{?>Dalam Proses<?php }?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
		<br>!-->
		<table width="100%" style="border-collapse: collapse;" border=1>
			<tr>
				<th align="center">Pilihan</th>
				<th align="center">Nama Sekolah</th>
				<th align="center">Jalur</th>
				<th align="center">Skor</th>
			</tr>
			<?php foreach($daftarpendaftaran->getResult() as $row):?>
			<tr>
				<td valign="top" align="center"><?php echo $row->jenis_pilihan;?></td>
				<td valign="top"><?php echo $row->sekolah;?></td>
				<td valign="top" align="center"><?php echo $row->jalur;?></td>
				<td valign="top" align="center"><?php echo $row->skor;?></td>
			</tr>
			<?php endforeach;?>
		</table><br>
		<table width="100%">
			<tr>
				<td width="50%"><br><br>Mengetahui Orang Tua/Wali,<br><br><br><br><br><br><br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
				<td><br><?php echo $wilayah_aktif;?>, <?php echo tanggal_indo(date("Y-m-d"));?><br>Verifikator,<br><br><br><br><br><br><br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
			</tr>
		</table>
	</body>
</html>