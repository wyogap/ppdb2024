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
				<a href="{$base_url}" class="navbar-brand"><b>PPDB</b>{$nama_tahun_ajaran} {$nama_putaran}</a>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
					<i class="glyphicon glyphicon-th-list"></i>
				</button>
			</div>
            {if !empty($pengguna_id)}
			<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
				<ul class="nav navbar-nav">
                {if ($is_siswa)}
                    <li {if $page=="siswa-profil"}class="active"{/if}><a href="{$base_url}siswa/profil"><i class="glyphicon glyphicon-user"></i> Profil Siswa</a></li>
                    <li {if $page=="siswa-pendaftaran"}class="active"{/if}><a href="{$base_url}siswa/pendaftaran"><i class="glyphicon glyphicon-registration-mark"></i> Pendaftaran <b>PPDB</b></a></li>
                    <li {if $page=="siswa-daftarulang"}class="active"{/if}><a href="{$base_url}siswa/daftarulang"><i class="glyphicon glyphicon-credit-card"></i> Daftar Ulang</a></li>
				{elseif $is_sekolah}
                    <li {if $page=="beranda"}class="active"{/if}><a href="{$base_url}sekolah/beranda"><i class="glyphicon glyphicon-home"></i> Beranda</a></li>
                    <li {if $page=="peringkat"}class="active"{/if}><a href="{$base_url}home/peringkat"><i class="glyphicon glyphicon-th-list"></i> Peringkat </a></li>
                    {if ($waktu_verifikasi==1)}
                    <li {if $page=="verifikasi"}class="active"{/if}><a href="{$base_url}sekolah/verifikasi"><i class="glyphicon glyphicon-ok"></i> Verifikasi</a></li>
                    {/if}
                    {if ($waktu_daftarulang==1)}
                    <li {if $page=="daftarulang"}class="active"{/if}><a href="{$base_url}sekolah/daftarulang"><i class="glyphicon glyphicon-floppy-save"></i> Daftar Ulang</a></li>
                    {/if}
                    <li class="dropdown {if substr($page,0,2) == 's-'}active{/if}">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> Pengelolaan <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li {if $page=="s-dashboard"}class="active"{/if}><a href="{$base_url}sekolah/dashboard"><i class="glyphicon glyphicon-record"></i> Dashboard</a></li>
                            <li {if $page=="s-pengajuanakun"}class="active"{/if}><a href="{$base_url}sekolah/pengajuanakun"><i class="glyphicon glyphicon-user"></i> Pengajuan Akun</a></li>
                            <li {if $page=="s-pencarian"}class="active"{/if}><a href="{$base_url}sekolah/pencarian"><i class="glyphicon glyphicon-search"></i> Pencarian Siswa</a></li>
                            <li {if $page=="s-ubahprofil"}class="active"{/if}><a href="{$base_url}sekolah/ubahprofil"><i class="glyphicon glyphicon-home"></i> Ubah Profil Sekolah</a></li>
                            <li {if $page=="s-kandidatswasta"}class="active"{/if}><a href="{$base_url}sekolah/kandidatswasta"><i class="glyphicon glyphicon-th-list"></i> Kandidat Siswa </a></li>
                        </ul>
                    </li>
				{elseif $is_dinas}
                    <li {if $page=="beranda"}class="active"{/if}><a href="{$base_url}admin/beranda"><i class="glyphicon glyphicon-home"></i> Beranda <span class="sr-only">(current)</span></a></li>
                    <li {if $page=="rekapitulasi"}class="active"{/if}><a href="{$base_url}admin/rekapitulasi"><i class="glyphicon glyphicon-th-list"></i> Rekapitulasi</a></li>
                    <li {if $page=="klarifikasidinas"}class="active"{/if}><a href="{$base_url}admin/klarifikasidinas"><i class="glyphicon glyphicon-check"></i> Klarifikasi Dinas</a></li>
                    <li class="dropdown {if substr($page,0,2) == 'p-'}active{/if}">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> Pengelolaan <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li {if $page=="p-akun"}class="active"{/if}><a href="{$base_url}admin/pengajuanakun"><i class="glyphicon glyphicon-user"></i>Pengajuan Akun</a></li>
                            <li {if $page=="p-data"}class="active"{/if}><a href="{$base_url}admin/perubahandata"><i class="glyphicon glyphicon-edit"></i>Perubahan Data</a></li>
                            <li {if $page=="p-pengguna"}class="active"{/if}><a href="{$base_url}admin/pengguna"><i class="glyphicon glyphicon-user"></i> Pengguna</a></li>
                            <li {if $page=="p-pesertadidik"}class="active"{/if}><a href="{$base_url}admin/pesertadidik"><i class="glyphicon glyphicon-user"></i> Peserta Didik</a></li>
                            <li {if $page=="p-sekolah"}class="active"{/if}><a href="{$base_url}admin/sekolah"><i class="glyphicon glyphicon-home"></i> Sekolah</a></li>
                            <li {if $page=="p-pendaftaran"}class="active"{/if}><a href="{$base_url}admin/pendaftaran"><i class="glyphicon glyphicon-home"></i> Pendaftaran</a></li>
                            <li {if $page=="p-dapodik"}class="active"{/if}><a href="{$base_url}admin/dapodik"><i class="glyphicon glyphicon-book"></i> Data DAPODIK</a></li> 
                            <li {if $page=="p-daftarulang"}class="active"{/if}><a href="{$base_url}admin/rekapdaftarulang"><i class="glyphicon glyphicon-book"></i> Rekapitulasi Daftar Ulang</a></li> 
                        </ul>
                    </li>
                    <li class="dropdown {if substr($page,0,2) == 'k-'}active{/if}">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-wrench"></i> Konfigurasi <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li {if $page=="k-penerimaan"}class="active"{/if}><a href="{$base_url}admin/penerimaan"><i class="glyphicon glyphicon-list-alt"></i> Jalur Penerimaan</a></li>
                            <li {if $page=="k-tahapan"}class="active"{/if}><a href="{$base_url}admin/tahapan"><i class="glyphicon glyphicon-calendar"></i> Tahapan Penerimaan</a></li>
                            <li {if $page=="k-skoring"}class="active"{/if}><a href="{$base_url}admin/skoring"><i class="glyphicon glyphicon-education"></i> Skoring Prestasi</a></li>
                            <li {if $page=="k-kuota"}class="active"{/if}><a href="{$base_url}admin/kuota"><i class="glyphicon glyphicon-th-large"></i> Kuota Sekolah</a></li>
                            <li {if $page=="k-pengumuman"}class="active"{/if}><a href="{$base_url}admin/pengumuman"><i class="glyphicon glyphicon-tag"></i> Pengumuman</a></li>
                            <li {if $page=="k-scheduler"}class="active"{/if}><a href="{$base_url}admin/scheduler"><i class="glyphicon glyphicon-tasks"></i> Job Scheduler</a></li>
                            <li {if $page=="k-batasan"}class="active"{/if}><a href="{$base_url}admin/batasan"><i class="glyphicon glyphicon-edit"></i> Batasan Perubahan</a></li>
                            <li {if $page=="k-petunjukpelaksanaan"}class="active"{/if}><a href="{$base_url}admin/petunjukpelaksanaan"><i class="glyphicon glyphicon-pencil"></i> Petunjuk Pelaksanaan</a></li>
                            <li {if $page=="k-daftarkelengkapan"}class="active"{/if}><a href="{$base_url}admin/daftarkelengkapan"><i class="glyphicon glyphicon-picture"></i> Daftar Kelengkapan</a></li>
                            <li {if $page=="k-kelengkapanpenerimaan"}class="active"{/if}><a href="{$base_url}admin/kelengkapanpenerimaan"><i class="glyphicon glyphicon-paperclip"></i> Kelengkapan Jalur Penerimaan</a></li>
                            <li {if $page=="k-konfigurasi"}class="active"{/if}><a href="{$base_url}admin/konfigurasi"><i class="glyphicon glyphicon-wrench"></i> Konfigurasi Sistem</a></li>
                        </ul>
                    </li>
                {elseif $is_dapodik}
                    <li {if $page=="beranda"}class="active"{/if}><a href="{$base_url}dapodik/beranda"><i class="glyphicon glyphicon-list-alt"></i> Daftar Siswa</a></li>
                    <li {if $page=="ppdb-sd"}class="active"{/if}><a href="{$base_url}dapodik/penerimaan"><i class="glyphicon glyphicon-list-alt"></i> Daftar Peserta Didik Baru</a></li>
                {/if}
				</ul>
			</div>
            {/if}
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
                {if !empty($pengguna_id)}
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{$base_url}assets/image/user.png" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo $this->session->userdata('nama');?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{$base_url}assets/image/user.png" class="img-circle" alt="User Image">
                                <p><?php echo $this->session->userdata('nama');?> <small><?php echo $this->session->userdata('nama_instansi');?></small></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{$base_url}akun/password" class="btn btn-danger btn-flat">Ganti PIN</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{$base_url}akun/logout" class="btn btn-default btn-flat">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </li>
				{else}
                    <li><a href="{$base_url}home/login">Login <span class="sr-only">(current)</span></a></li>
				{/if}
				</ul>
			</div>
		</div>
	</nav>
</header>

