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

		<?php 
			$img = base64_encode(file_get_contents("./qrcode/images/" .$this->session->userdata("pengguna_id"). ".png"));
		?>

		<img src="data:image/png;base64, <?php echo $img; ?>" height="50px" width="50px">
		<div align="center">
			<b>SURAT PERNYATAAN KESANGGUPAN MELENGKAPI AKTA KELAHIRAN<br>PENERIMAAN PESERTA DIDIK BARU (PPDB) ONLINE <?php echo strtoupper($wilayah_aktif);?><br>TAHUN <?php echo $nama_tahun_ajaran_aktif; ?></b></b>
		</div><br>
		<p>Yang bertanda tangan di bawah ini :</p>
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
				<td valign="top" style="padding-left: 5px; padding right: 5px;">Nama Ibu Kandung</td>
				<td valign="top">:</td>
				<td><?php echo $row->nama_ibu_kandung;?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 5px; padding right: 5px;">Alamat</td>
				<td valign="top">:</td>
				<td><?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 5px; padding right: 5px;">Nomor Kontak</td>
				<td valign="top">:</td>
				<td><?php echo $row->nomor_kontak;?></td>
			</tr>
			<?php endforeach;?>
		</table>
		<p>Dengan sungguh-sungguh menyatakan bahwa akan melengkapi berkas Akta Kelahiran paling lambat pada semester 2 (dua) Tahun Ajaran ini.</p>
		<p>Demikian pernyataan ini saya buat dengan sebenarnya dan penuh rasa tanggung jawab. Apabila dikemudian hari ditemukan bahwa saya tidak melengkapi berkas Akta Kelahiran pada semester 2 (dua), maka saya bersedia dikenakan sanksi sesuai ketentuan peraturan yang berlaku.</p>
		<br>
		<table width="100%">
			<?php foreach($profilsiswa->getResult() as $row):?>
			<tr>
				<td width="50%"><?php echo $row->kabupaten;?>, <?php echo tanggal_indo($tanggal_pernyataan);?><br>Mengetahui Orang Tua/Wali,<br><br><br>[Materai]<br><br><br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
				<td><br>Pendaftar,<br><br><br><br><br><br>(<?php echo $row->nama;?>)</td>
			</tr>
			<?php endforeach;?>
		</table>
	</body>
</html>