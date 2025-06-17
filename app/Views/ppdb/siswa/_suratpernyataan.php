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
			<b>SURAT PERNYATAAN KEBENARAN DOKUMEN<br>SISTEM PENERIMAAN MURID BARU (SPMB) ONLINE <?php echo strtoupper($wilayah_aktif);?><br>TAHUN <?php echo $nama_tahun_ajaran_aktif; ?></b>
		</div><br>
		<p>Yang bertanda tangan di bawah ini :</p>
		<table>
			<tr>
				<td valign="top">NIK</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['nik'];?></td>
			</tr>
			<tr>
				<td valign="top">NISN</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['nisn'];?></td>
			</tr>
			<tr>
				<td valign="top">Nama</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['nama'];?></td>
			</tr>
			<tr>
				<td valign="top">Jenis Kelamin</td>
				<td valign="top">:</td>
				<td><?php if($profilsiswa['jenis_kelamin']=="L"){echo "Laki-laki";}else{echo "Perempuan";}?></td>
			</tr>
			<tr>
				<td valign="top">Tempat dan Tanggal Lahir</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['tempat_lahir'];?>, <?php echo tanggal_indo($profilsiswa['tanggal_lahir']);?></td>
			</tr>
			<tr>
				<td valign="top">Nama Ibu Kandung</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['nama_ibu_kandung'];?></td>
			</tr>
			<tr>
				<td valign="top">Alamat</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['desa_kelurahan'];?>, <?php echo $profilsiswa['kecamatan'];?>, <?php echo $profilsiswa['kabupaten'];?>, <?php echo $profilsiswa['provinsi'];?></td>
			</tr>
			<tr>
				<td valign="top">Lintang</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['lintang'];?></td>
			</tr>
			<tr>
				<td valign="top">Bujur</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['bujur'];?></td>
			</tr>
			<tr>
				<td valign="top">Nomor HP</td>
				<td valign="top">:</td>
				<td><?php echo $profilsiswa['nomor_kontak'];?></td>
			</tr>
		</table>
		<p>Dengan sungguh-sungguh menyatakan bahwa : </p>
		<ol>
			<li>Seluruh pernyataan data dan informasi beserta seluruh dokumen yang saya lampirkan dalam berkas pendaftaran Seleksi Penerimaan Peserta Didik Baru (PPDB) tahun <?php echo $tahun_ajaran_aktif; ?> adalah benar.</li>
			<li>Apabila diperlukan, saya bersedia memberikan informasi lebih lanjut untuk melengkapi dokumen pendaftaran ini.</li>
		</ol>
		<p>Demikian pernyataan ini saya buat dengan sebenarnya dan penuh rasa tanggung jawab. Apabila dikemudian hari ditemukan bahwa data/dokumen yang saya sampaikan tidak benar dan/atau ada  pemalsuan, maka seluruh keputusan yang telah ditetapkan berdasarkan berkas  tersebut  batal  berdasarkan hukum dan saya bersedia dikenakan sanksi sesuai ketentuan peraturan yang berlaku.</p>
		<table width="100%">
			<tr>
				<td width="50%"><?php echo $profilsiswa['kabupaten'];?>, <?php echo tanggal_indo($tanggal_pernyataan);?><br>Mengetahui Orang Tua/Wali,<br><br><br>[Materai]<br><br><br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
				<td><br>Pendaftar,<br><br><br><br><br><br>(<?php echo $profilsiswa['nama'];?>)</td>
			</tr>
		</table>
	</body>
</html>