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

		<img src="qrcode/images/<?php echo $this->session->userdata("pengguna_id");?>.png" height="40px" width="40px">
		<div align="center">
			<b>TANDA BUKTI PENDAFTARAN DAN SURAT PERNYATAAN KEBENARAN DOKUMEN<br>PENERIMAAN PESERTA DIDIK BARU (PPDB) ONLINE <?php echo strtoupper($wilayah_aktif);?><br>TAHUN <?php echo $tahun_ajaran_aktif; ?></b>
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
				<td valign="top">Nama Ibu Kandung</td>
				<td valign="top">:</td>
				<td><?php echo $row->nama_ibu_kandung;?></td>
			</tr>
			<tr>
				<td valign="top">Alamat</td>
				<td valign="top">:</td>
				<td><?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?></td>
			</tr>
			<tr>
				<td valign="top">Lintang</td>
				<td valign="top">:</td>
				<td><?php echo $row->lintang;?></td>
			</tr>
			<tr>
				<td valign="top">Bujur</td>
				<td valign="top">:</td>
				<td><?php echo $row->bujur;?></td>
			</tr>
			<tr>
				<td valign="top">Nomor HP</td>
				<td valign="top">:</td>
				<td><?php echo $row->nomor_kontak;?></td>
			</tr>
			<?php endforeach;?>
		</table>
		<p>Dengan pilihan sekolah untuk PPDB sebagai berikut :</p>
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
		</table>
		<p>Dengan sungguh-sungguh menyatakan bahwa : </p>
		<ol>
			<li>Seluruh pernyataan data dan informasi beserta seluruh dokumen yang saya lampirkan dalam berkas pendaftaran Seleksi Penerimaan Peserta Didik Baru (PPDB) tahun <?php echo $tahun_ajaran_aktif; ?> adalah benar.</li>
			<li>Apabila diperlukan, saya bersedia memberikan informasi lebih lanjut untuk melengkapi dokumen pendaftaran ini.</li>
		</ol>
		<p>Demikian pernyataan ini saya buat dengan sebenarnya dan penuh rasa tanggung jawab. Apabila dikemudian hari ditemukan bahwa data/dokumen yang saya sampaikan tidak benar dan/atau ada  pemalsuan, maka seluruh keputusan yang telah ditetapkan berdasarkan berkas  tersebut  batal  berdasarkan hukum dan saya bersedia dikenakan sanksi sesuai ketentuan peraturan yang berlaku.</p>
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