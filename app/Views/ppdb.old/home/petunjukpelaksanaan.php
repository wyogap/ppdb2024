<?php foreach($petunjuk_pelaksanaan->getResult() as $row) :
?>
<div class="container">
    <div class="title-call-action another text-center">
        Petunjuk Pelaksanaan
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
             <div class="flat-accordion style2">
                <div class="flat-toggle">
                    <div class="toggle-title">JADWAL PELAKSANAAN</div>
                    <div class="toggle-content">
                        <?php echo $row->jadwal_pelaksanaan; ?>
                    </div>
                </div><!-- /toggle -->
                <div class="flat-toggle">
                    <div class="toggle-title">PERSYARATAN</div>
                    <div class="toggle-content">
                        <?php echo $row->persyaratan; ?>
                    </div>
                </div><!-- /toggle -->
                <div class="flat-toggle">
                    <div class="toggle-title">TATA CARA PENDAFTARAN</div>
                    <div class="toggle-content">
                        <?php echo $row->tata_cara_pendaftaran; ?>
                     </div>
                </div>
                <div class="flat-toggle">
                    <div class="toggle-title">JALUR PENDAFTARAN</div>
                    <div class="toggle-content">
                        <?php echo $row->jalur_pendaftaran; ?>
                    </div>
                </div>
                <div class="flat-toggle">
                    <div class="toggle-title">SELEKSI</div>
                    <div class="toggle-content">
                        <?php echo $row->proses_seleksi; ?>
                    </div>
                </div>
                <div class="flat-toggle">
                    <div class="toggle-title">KONVERSI NILAI</div>
                    <div class="toggle-content">
                        <?php echo $row->konversi_nilai; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>