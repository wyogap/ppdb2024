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
			$cekprestasi = 0;
			foreach($prestasi->getResult() as $row):
				$cekprestasi++;
			endforeach;
			$cekperpindahanorangtua = 0;
			foreach($perpindahanorangtua->getResult() as $row):
				$cekperpindahanorangtua++;
			endforeach;
			$cekkhusus = 0;
			foreach($khusus->getResult() as $row):
				$cekkhusus++;
			endforeach;
		?>
		<?php foreach($profilsekolah->getResult() as $row):?>
		<div align="center">
			<table>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td align="left"><img class="text-center" src="assets/image/logodinas.png" height="90px" width="80px"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>
						<div align="center">
							<b>PEMERINTAH <?php echo strtoupper($nama_wilayah_aktif);?></b><br>
							<b>DINAS PENDIDIKAN, KEBUDAYAAN,</b><br>
							<b>KEPEMUDAAN, DAN OLAHRAGA</b><br>
							<b>UPTD SATUAN PENDIDIKAN FORMAL</b><br>
							<!---b><?php echo $alamat;?></b><br--->
							<!---b><?php echo $no_telp;?></b><br-->
							<font size="30px"><b><?php echo $row->nama;?></b></font>
						</div>
					</td>
					<td width="10%">&nbsp;</td>
				</tr>
			</table>
		</div>
		<hr>
		<p></p>
		<?php endforeach;?>
		<div align="center"><b>PENGUMUMAN</b><br><b>HASIL SELEKSI PENERIMAAN PESERTA DIDIK BARU (PPDB)</b><br>
			<b><?php echo $row->nama;?></b><br>
			<b>TAHUN PELAJARAN 2019/2020 </b></div>
		<b>Jalur Zonasi</b>
		<table width="100%" style="border-collapse: collapse;" border=1>
			<tr>
				<th align="center">Peringkat</th>
				<th align="center">Nama</th>
				<th align="center">Jenis Kelamin</th>
				<th align="center">Asal Sekolah</th>
				<th align="center">Skor</th>
			</tr>
			<?php $i=1; foreach($zonasi->getResult() as $row):?>
			<tr>
				<td valign="top" align="center"><?php echo $i;?></td>
				<td valign="top"><?php echo ucwords(strtolower($row->nama));?></td>
				<td valign="top" align="center"><?php echo $row->jenis_kelamin;?></td>
				<td valign="top"><?php echo ucwords(strtolower($row->asal_sekolah));?></td>
				<td valign="top" align="right"><?php echo number_format($row->skor);?></td>
			</tr>
			<?php $i++; endforeach;?>
		</table>
		<?php if($cekprestasi>0){?>
		<h4>Jalur Prestasi</h4>
		<table width="100%" style="border-collapse: collapse;" border=1>
			<tr>
				<th align="center">Peringkat</th>
				<th align="center">Nama</th>
				<th align="center">Jenis Kelamin</th>
				<th align="center">Skor</th>
			</tr>
			<?php $i=1; foreach($prestasi->getResult() as $row):?>
			<tr>
				<td valign="top" align="center"><?php echo $i;?></td>
				<td valign="top"><?php echo ucwords(strtolower($row->nama));?></td>
				<td valign="top" align="center"><?php echo $row->jenis_kelamin;?></td>
				<td valign="top" align="right"><?php echo number_format($row->skor);?></td>
			</tr>
			<?php $i++; endforeach;?>
		</table>
		<?php }?>
		<?php if($cekperpindahanorangtua>0){?>
		<h4>Jalur Perpindahan Orang Tua</h4>
		<table width="100%" style="border-collapse: collapse;" border=1>
			<tr>
				<th align="center">Peringkat</th>
				<th align="center">Nama</th>
				<th align="center">Jenis Kelamin</th>
				<th align="center">Skor</th>
			</tr>
			<?php $i=0; foreach($perpindahanorangtua->getResult() as $row):?>
			<tr>
				<td valign="top" align="center"><?php echo $i;?></td>
				<td valign="top"><?php echo ucwords(strtolower($row->nama));?></td>
				<td valign="top" align="center"><?php echo $row->jenis_kelamin;?></td>
				<td valign="top" align="right"><?php echo number_format($row->skor);?></td>
			</tr>
			<?php $i++; endforeach;?>
		</table>
		<?php }?>
		<?php if($cekkhusus>0){?>
		<h4>Jalur Berkebutuhan Khusus</h4>
		<table width="100%" style="border-collapse: collapse;" border=1>
			<tr>
				<th align="center">Peringkat</th>
				<th align="center">Nama</th>
				<th align="center">Jenis Kelamin</th>
				<th align="center">Skor</th>
			</tr>
			<?php $i=1; foreach($khusus->getResult() as $row):?>
			<tr>
				<td valign="top" align="center"><?php echo $i;?></td>
				<td valign="top"><?php echo ucwords(strtolower($row->nama));?></td>
				<td valign="top" align="center"><?php echo $row->jenis_kelamin;?></td>
				<td valign="top" align="right"><?php echo number_format($row->skor);?></td>
			</tr>
			<?php $i++; endforeach;?>
		</table>
		<?php }?>
		<p style="page-break-before: always">
		<h4>Tabel Rekapitulasi</h4>
		<table style="border-collapse: collapse;" border=1>
			<tr>
				<th align="center">&nbsp;Jalur&nbsp;</th>
				<th align="center">&nbsp;Laki-laki&nbsp;</th>
				<th align="center">&nbsp;Perempuan&nbsp;</th>
				<th align="center">&nbsp;Jumlah&nbsp;</th>
			</tr>
			<?php $l=0;$p=0; foreach($rekapitulasi->getResult() as $row):?>
			<tr>
				<td valign="top"><?php echo $row->jalur;?></td>
				<td valign="top" align="right"><?php echo $row->lakilaki;?></td>
				<td valign="top" align="right"><?php echo $row->perempuan;?></td>
				<td valign="top" align="right"><?php echo $row->lakilaki+$row->perempuan;?></td>
			</tr>
			<?php $l=$l+$row->lakilaki;$p=$p+$row->perempuan; endforeach;?>
			<tr>
				<td><b>Total</b></td>
				<td align="right"><?php echo number_format($l);?></td>
				<td align="right"><?php echo number_format($p);?></td>
				<td align="right"><?php echo number_format($l+$p);?></td>
			</tr>
		</table>
		<br><br>
		<table width="100%">
			<?php foreach($profilsekolah->getResult() as $row):?>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td width="50%"><br>Kepala Sekolah,<br><br><br><br><br><br><br>............................................................<p>NIP .....................................................</p>
				</td>
				<td>..............................., 23 Mei 2019
					<br>Ketua Panitia,<br><br><br><br><br><br><br>............................................................<p>NIP .....................................................</p>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</body>
</html>