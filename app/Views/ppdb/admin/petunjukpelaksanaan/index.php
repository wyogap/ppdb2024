<!DOCTYPE html>
<html>
	<?php view('head');?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>
<script src="<?php echo base_url();?>assets/adminlte/plugins/moment/moment-with-locales.min.js"></script>

<script src="<?php echo base_url();?>assets/adminlte/plugins/ckeditor/ckeditor.js"></script>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">

<style>
/* body {
    font: 16px/27px "Poppins", sans-serif;
    font-weight: 300;
    background-color: #fff;
    color: #717779;
} */

a, abbr, acronym, address, applet, article, aside, audio, b, big, blockquote, body, caption, canvas, center, cite, code, dd, del, details, dfn, dialog, div, dl, dt, em, embed, fieldset, figcaption, figure, form, footer, header, hgroup, h1, h2, h3, h4, h5, h6, html, i, iframe, img, ins, kbd, label, legend, li, mark, menu, nav, object, ol, output, p, pre, q, ruby, s, samp, section, small, span, strike, strong, sub, summary, sup, tt, table, tbody, textarea, tfoot, thead, time, tr, th, td, u, ul, var, video {
    vertical-align: baseline;
    margin: 0;
    padding: 0;
    border: 0;
    outline: 0;
}

.flat-accordion ul, .flat-accordion ul {
    padding: 0px !important;
	padding-inline-start: 15px !important;
}

.flat-accordion li {
    padding: 0px !important;
	padding-inline-start: 10px !important;
}

.flat-accordion .toggle-title {
    font-weight: 500;
	background-color: #fff;
    color: #2b2e2f;
    cursor: pointer;
    padding: 10px 30px 11px;
    letter-spacing: 0.2px;
	position: relative;

	-webkit-transition: all 0.3s ease-in-out;
    -moz-transition: all 0.3s ease-in-out;
    -ms-transition: all 0.3s ease-in-out;
    -o-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.flat-accordion .flat-toggle {
    border: 1px solid #e5e5e5;
    margin-top: 10px;
    margin-bottom: 10px;
}

.flat-accordion .toggle-title.active {
    background-color: #66bfd7;
	color: #fff;
    /* padding: 10px 30px 12px; */
}

.flat-accordion {
    font: 16px/27px "Poppins", sans-serif;
    font-weight: 300;
    background-color: #fff;
    color: #717779;
}

.flat-accordion .toggle-content {
    padding: 10px 30px 11px;
    letter-spacing: 0.2px;
	position: relative;
}

.flat-accordion .toggle-title:before {
    left: 96%;
	box-sizing: border-box;
    /* font-family: "Themify";
    content: "\e61a"; */
	content: "\002B";
    left: 96%;
    bottom: 11px;
    color: #66bfd7;
	position: absolute;
	box-sizing: border-box;
}

.flat-accordion .toggle-title.active:before {
    content: "\002D";
	color: #fff;
}

.flat-accordion .toggle-title:after {
	box-sizing: border-box;
	content: none;
}

</style>

<?php 
	$this->load->helper('url');
?>

	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<!-- <div class="row"> -->
						<h1 class="text-white">
							<i class="glyphicon glyphicon-edit"></i> Petunjuk Pelaksanaan</small>
						</h1>
					<!-- <ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-th-list"></i> Peringkat Pendaftaran</a></li>
						</ol> -->
						<div class="tahun-selection">
							<div class="tahun-selection-label">
							Tahun Ajaran: 
							</div>
							<select id="tahunajaran" name="tahunajaran" class="tahun-selection-control" data-validation="required">
							<?php foreach($tahun_ajaran->getResult() as $row2): ?>
								<option value="<?php echo $row2->tahun_ajaran_id; ?>" <?php if($row2->tahun_ajaran_id==$tahun_ajaran_aktif){?>selected="true"<?php }?>><?php echo $row2->tahun_ajaran_id; ?></option>
							<?php endforeach;?>
							</select>
						</div>
						<!-- </div> -->
					</section>
					<section class="content">

					<span><?php if(isset($info)){echo $info;}?></span>
					<?php
						$error = $this->session->flashdata('error');
						$success = $this->session->flashdata('success');
					?>

					<div id="alert-error" class="alert alert-danger alert-dismissable" <?php if(empty($error)) { echo "style='display:none;'"; } ?>>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
							<?php echo $error; ?>                    
					</div>
					<div id="alert-success" class="alert alert-success alert-dismissable" <?php if(empty($success)) { echo "style='display:none;'"; } ?>>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
							<?php echo $success; ?>                    
					</div>


<div class="box box-solid">
	<!-- <div class="box-header with-border">
		<i class="glyphicon glyphicon-edit text-info"></i>
		<h3 class="box-title text-info"><b>Kuota Sekolah</b></h3>
	</div> -->
	<div class="box-body">

		<?php foreach($petunjuk_pelaksanaan->getResult() as $row) :
		?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div data-sample="2" class="flat-accordion">
						<div class="flat-toggle">
							<div class="toggle-title">JADWAL PELAKSANAAN</div>
							<div id="jadwal" class="toggle-content" contenteditable="true">
								<?php echo $row->jadwal_pelaksanaan; ?>
							</div>
						</div><!-- /toggle -->
						<div class="flat-toggle">
							<div class="toggle-title">PERSYARATAN</div>
							<div id="persyaratan" class="toggle-content" contenteditable="true">
								<?php echo $row->persyaratan; ?>
							</div>
						</div><!-- /toggle -->
						<div class="flat-toggle">
							<div class="toggle-title">TATA CARA PENDAFTARAN</div>
							<div id="tata-cara" class="toggle-content" contenteditable="true">
								<?php echo $row->tata_cara_pendaftaran; ?>
							</div>
						</div>
						<div class="flat-toggle">
							<div class="toggle-title">JALUR PENDAFTARAN</div>
							<div id="jalur" class="toggle-content" contenteditable="true">
								<?php echo $row->jalur_pendaftaran; ?>
							</div>
						</div>
						<div class="flat-toggle">
							<div class="toggle-title">SELEKSI</div>
							<div id="seleksi" class="toggle-content" contenteditable="true">
								<?php echo $row->proses_seleksi; ?>
							</div>
						</div>
						<div class="flat-toggle">
							<div class="toggle-title">KONVERSI NILAI</div>
							<div id="nilai" class="toggle-content" contenteditable="true">
								<?php echo $row->konversi_nilai; ?>
							</div>
						</div>
					</div>
			</div>
		</div>
		<?php endforeach; ?>

	</div>
	<div class="box-footer">
		<a class="btn btn-primary" href="#" onclick="simpan(); return false;">Simpan Petunjuk Pelaksanaan</a>
	</div>
</div>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>

<script type="text/javascript">

// Tabel
var editor; // use a global for the submit and return data rendering in the examples
 
$(document).ready(function() {

	$('select[name="tahunajaran"]').on('change', function() {
		window.location.replace("<?php echo site_url('admin/petunjukpelaksanaan'); ?>?tahun_ajaran=" + $("#tahunajaran").val());
	});

	flatAccordion();

});

function simpan() {
	$.post( "<?php echo site_url('admin/petunjukpelaksanaan/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>", 
		{ 
			action: "edit",
			jadwal: btoa($("#jadwal").html()), 
			persyaratan: btoa($("#persyaratan").html()), 
			tatacara: btoa($("#tata-cara").html()), 
			jalur: btoa($("#jalur").html()), 
			seleksi: btoa($("#seleksi").html()), 
			nilai: btoa($("#nilai").html()), 
		}, 
		function( data ) {
			if (data.status == 0) {
				if (typeof data.error !== 'undefined' && data.error != null) {
					$('#alert-error').html(data.error);
				}
				else {
					$('#alert-error').html('Data tidak berhasil disimpan. Silahkan coba lagi.');
				}
				$('#alert-error').show();
			} 
			else {
				$('#alert-success').html('Petunjuk Pelaksanaan berhasil disimpan.');
				$('#alert-success').show();
			}
			document.body.scrollTop = 0; // For Safari
  			document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
		}, 
		"json");
}

var flatAccordion = function() {
        var args = {duration: 600};
        $('.flat-toggle .toggle-title').siblings('.toggle-content').hide();
        $('.flat-toggle .toggle-title.active').siblings('.toggle-content').show();

        $('.flat-toggle.enable .toggle-title').on('click', function() {
            $(this).closest('.flat-toggle').find('.toggle-content').slideToggle(args);
            $(this).toggleClass('active');
        }); // toggle 

        $('.flat-accordion .toggle-title').on('click', function () {
            if( !$(this).is('.active') ) {
                $(this).closest('.flat-accordion').find('.toggle-title.active').toggleClass('active').next().slideToggle(args);
                $(this).toggleClass('active');
                $(this).next().slideToggle(args);
            } else {
                $(this).toggleClass('active');
                $(this).next().slideToggle(args);
            }     
        }); // accordion
    }; 

</script>

</html>
