
<?php 
	$bentuk = $this->session->userdata('bentuk');
	$pengguna_id = $this->session->userdata('pengguna_id');
	$tahun_ajaran_id = $this->session->userdata('tahun_ajaran_aktif');

	//for consistency
	if(!isset($page)) {
		$page = "";
	}

	$this->load->model(array('Msetting'));
	$waktu_verifikasi = $this->Msetting->tcg_cek_waktuverifikasi();
	$waktu_daftarulang = $this->Msetting->tcg_cek_waktudaftarulang();
?>

<style>
	@media screen and (max-width: 767px) {

		.navbar-collapse.pull-left + .navbar-custom-menu {
			display: block;
			position: absolute;
			top: 0;
			right: 60px !important;
		}

	}

	.navbar-toggle {
		color: #fff;
		border: 0;
		margin: 0;
		padding: 15px 20px;
	}
</style>

<header class="main-header">
	<nav class="navbar navbar-static-top">
		<div class="container">
			<div class="navbar-header">
				<a href="<?php echo base_url();?>" class="navbar-brand"><b>PPDB</b><?php echo $nama_tahun_ajaran_aktif; ?></a>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
					<i class="glyphicon glyphicon-th-list"></i>
				</button>
			</div>
			<?php if ($this->session->userdata('isLogged')) { ?>
			<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
				<ul class="nav navbar-nav">
					<?php if($this->session->userdata('peran_id')==1){?>
						<li <?php if($page=="siswa-profil"){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/siswa/profil"><i class="glyphicon glyphicon-user"></i> Profil Siswa <span class="sr-only">(current)</span></a></li>
						<li <?php if($page=="siswa-pendaftaran"){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/siswa/pendaftaran"><i class="glyphicon glyphicon-registration-mark"></i> Pendaftaran <b>PPDB</b></a></li>
						<li <?php if($page=="siswa-daftarulang"){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/siswa/daftarulang"><i class="glyphicon glyphicon-credit-card"></i> Daftar Ulang</a></li>
					<?php } else if($this->session->userdata('peran_id')==2){?>
						<li <?php if($this->uri->segment(2)==""){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/Csekolah/"><i class="glyphicon glyphicon-home"></i> Beranda <span class="sr-only">(current)</span></a></li>
						<li><a href="<?php echo base_url();?>index.php/home/peringkat"><i class="glyphicon glyphicon-th-list"></i> Peringkat </a></li>
						<?php if ($waktu_verifikasi==1) { ?>
						<li><a href="<?php echo base_url();?>index.php/sekolah/verifikasi"><i class="glyphicon glyphicon-ok"></i> Verifikasi</a></li>
						<?php } ?>
						<?php if ($waktu_daftarulang==1) { ?>
						<li><a href="<?php echo base_url();?>index.php/sekolah/daftarulang"><i class="glyphicon glyphicon-floppy-save"></i> Daftar Ulang</a></li>
						<?php } ?>
						<li class="dropdown <?php if($this->uri->segment(2)=="verifikasiberkas"||$this->uri->segment(2)=="perubahandatasiswa"||$this->uri->segment(2)=="cabutberkas"||$this->uri->segment(2)=="detailverifikasiberkas"||$this->uri->segment(2)=="prosesverifikasiberkas"||$this->uri->segment(2)=="detailperubahandatasiswa"||$this->uri->segment(2)=="prosesperubahandatasiswa"||$this->uri->segment(2)=="hapusperubahandatasiswa"||$this->uri->segment(2)=="proseshapusperubahandatasiswa"||$this->uri->segment(2)=="detailcabutberkas"||$this->uri->segment(2)=="prosescabutberkas"){?>active<?php }?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> Pengelolaan <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?php echo base_url();?>index.php/Csekolah/dashboard"><i class="glyphicon glyphicon-record"></i> Dashboard</a></li>
								<li><a href="<?php echo base_url();?>index.php/Csekolah/pengajuanakun"><i class="glyphicon glyphicon-user"></i> Pengajuan Akun</a></li>
								<li><a href="<?php echo base_url();?>index.php/sekolah/pendaftaran"><i class="glyphicon glyphicon-search"></i> Pencarian Siswa</a></li>
								<li><a href="<?php echo base_url();?>index.php/sekolah/verifikasi"><i class="glyphicon glyphicon-ok"></i> Verifikasi Berkas</a></li>

								<?php if (1==0) { ?>
								<li><a href="<?php echo base_url();?>index.php/Csekolah/perubahandatasiswa"><i class="glyphicon glyphicon-edit"></i> Perubahan Data</a></li>
								<li><a href="<?php echo base_url();?>index.php/Csekolah/cabutberkas"><i class="glyphicon glyphicon-remove"></i> Cabut Berkas</a></li>
								<li><a href="<?php echo base_url();?>index.php/Csekolah/gantiprestasi"><i class="glyphicon glyphicon-education"></i> Ganti Prestasi Siswa</a></li>
								<?php } ?>

								<li><a href="<?php echo base_url();?>index.php/sekolah/daftarulang"><i class="glyphicon glyphicon-floppy-save"></i> Daftar Ulang</a></li>
								<li><a href="<?php echo base_url();?>index.php/sekolah/ubahprofil"><i class="glyphicon glyphicon-home"></i> Ubah Profil Sekolah</a></li>
								<li><a href="<?php echo base_url();?>index.php/sekolah/kandidatswasta"><i class="glyphicon glyphicon-th-list"></i> Kandidat Siswa </a></li>
							</ul>
						</li>
					<?php } else if($this->session->userdata('peran_id')==3){?>
						<li <?php if($this->uri->segment(2)==""){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/Cdinas/"><i class="glyphicon glyphicon-home"></i> Beranda <span class="sr-only">(current)</span></a></li>
						<!--<li <?php if($this->uri->segment(2)=="rekapitulasi"){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/Cdinas/rekapitulasi"><i class="glyphicon glyphicon-th-list"></i> Rekapitulasi</span></a></li>
						<li <?php if($this->uri->segment(2)=="pengajuanperubahandata"){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/Cdinas/pengajuanperubahandata"><i class="glyphicon glyphicon-edit"></i>Pengajuan Perubahan</a></li>!-->
						<li <?php if($this->uri->segment(2)=="pengajuanakun"){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/Cdinas/pengajuanakun"><i class="glyphicon glyphicon-user"></i> Pengajuan Akun</a></li>
						<li><a href="<?php echo base_url();?>index.php/Cdinas/perubahandata"><i class="glyphicon glyphicon-edit"></i> Perubahan Data</a></li>
					<?php } 
					else if($this->session->userdata('peran_id')==4){?>
						<li <?php if($this->uri->segment(2)==""){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/Cadmin/"><i class="glyphicon glyphicon-home"></i> Beranda <span class="sr-only">(current)</span></a></li>
						<li <?php if($this->uri->segment(2)=="rekapitulasi"){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/Cadmin/rekapitulasi"><i class="glyphicon glyphicon-th-list"></i> Rekapitulasi</span></a></li>
						<li <?php if($page=="klarifikasidinas"){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/admin/klarifikasidinas"><i class="glyphicon glyphicon-check"></i> Klarifikasi Dinas</span></a></li>
						<li class="dropdown 
							<?php if(substr($page,0,12) == "pengelolaan-") {?>active<?php } ?>
						">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> Pengelolaan <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?php echo base_url();?>index.php/Cadmin/pengajuanakun"><i class="glyphicon glyphicon-user"></i>Pengajuan Akun</a></li>
								<li><a href="<?php echo base_url();?>index.php/Cadmin/pengajuanperubahandata"><i class="glyphicon glyphicon-edit"></i>Perubahan Data</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/pengguna"><i class="glyphicon glyphicon-user"></i> Pengguna</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/pesertadidik"><i class="glyphicon glyphicon-user"></i> Peserta Didik</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/sekolah"><i class="glyphicon glyphicon-home"></i> Sekolah</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/pendaftaran"><i class="glyphicon glyphicon-home"></i> Pendaftaran</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/dapodik"><i class="glyphicon glyphicon-book"></i> Data DAPODIK</a></li> 
								<li><a href="<?php echo base_url();?>index.php/admin/rekapdaftarulang"><i class="glyphicon glyphicon-book"></i> Rekapitulasi Daftar Ulang</a></li> 
							</ul>
						</li>
						<li class="dropdown 
							<?php if(substr($page,0,12) == "konfigurasi-") {?>active<?php } ?>
						">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-wrench"></i> Konfigurasi <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?php echo base_url();?>index.php/admin/penerimaan"><i class="glyphicon glyphicon-list-alt"></i> Jalur Penerimaan</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/tahapan"><i class="glyphicon glyphicon-calendar"></i> Tahapan Penerimaan</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/skoring"><i class="glyphicon glyphicon-education"></i> Skoring Prestasi</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/kuota"><i class="glyphicon glyphicon-th-large"></i> Kuota Sekolah</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/pengumuman"><i class="glyphicon glyphicon-tag"></i> Pengumuman</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/scheduler"><i class="glyphicon glyphicon-tasks"></i> Job Scheduler</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/batasan"><i class="glyphicon glyphicon-edit"></i> Batasan Perubahan</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/petunjukpelaksanaan"><i class="glyphicon glyphicon-pencil"></i> Petunjuk Pelaksanaan</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/daftarkelengkapan"><i class="glyphicon glyphicon-picture"></i> Daftar Kelengkapan</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/kelengkapanpenerimaan"><i class="glyphicon glyphicon-paperclip"></i> Kelengkapan Jalur Penerimaan</a></li>
								<li><a href="<?php echo base_url();?>index.php/admin/konfigurasi"><i class="glyphicon glyphicon-wrench"></i> Konfigurasi Sistem</a></li>
							</ul>
						</li>
					<?php } else if($this->session->userdata('peran_id')==5){?>
						<li <?php if($page=="beranda"){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/dapodik/beranda"><i class="glyphicon glyphicon-list-alt"></i> Daftar Siswa</a></li>
						<li <?php if($page=="ppdb-sd"){?>class="active"<?php }?>><a href="<?php echo base_url();?>index.php/dapodik/penerimaan"><i class="glyphicon glyphicon-list-alt"></i> Daftar Peserta Didik Baru</a></li>
					<?php } ?>
				</ul>
			</div>
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<?php if($this->session->userdata('isLogged')){?>
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="<?php echo base_url();?>assets/image/user.png" class="user-image" alt="User Image">
								<span class="hidden-xs"><?php echo $this->session->userdata('nama');?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-header">
									<img src="<?php echo base_url();?>assets/image/user.png" class="img-circle" alt="User Image">
									<p><?php echo $this->session->userdata('nama');?> <small><?php echo $this->session->userdata('nama_instansi');?></small></p>
								</li>
								<li class="user-footer">
									<div class="pull-left">
										<a href="<?php echo base_url();?>index.php/akun/password" class="btn btn-danger btn-flat">Ganti PIN</a>
									</div>
									<div class="pull-right">
										<a href="<?php echo base_url();?>index.php/akun/logout" class="btn btn-default btn-flat">Logout</a>
									</div>
								</li>
							</ul>
						</li>
					<?php }else{?>
						<li><a href="<?php echo base_url();?>index.php/Clogin/">Login <span class="sr-only">(current)</span></a></li>
					<?php }?>
				</ul>
			</div>
			<?php } ?>
		</div>
	</nav>
</header>

