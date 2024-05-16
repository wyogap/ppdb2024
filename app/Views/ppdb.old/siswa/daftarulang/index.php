<!DOCTYPE html>
<html lang="en">
	<?php view('head');?>
	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-credit-card"></i> Daftar Ulang</small>
						</h1>
					</section>
					<section id="content" class="content">
						<?php if($cek_waktudaftarulang == 0) { ?>
							<?php view('siswa/daftarulang/belumdimulai');?>
						<?php } else if ($diterima == 1) { ?>
							<?php view('siswa/daftarulang/diterima');?>
						<?php } else { ?>
							<?php view('siswa/daftarulang/tidakditerima');?>
						<?php } ?>

					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
