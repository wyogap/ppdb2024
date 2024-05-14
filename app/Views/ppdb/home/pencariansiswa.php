<div class="container">
    <div class="title-call-action another text-center">
        Pencarian PPDB
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-header bg-blue with-border">
                <i class="glyphicon glyphicon-search"></i>
                <h3 class="box-title text-white"><b>Formulir Pencarian</b></h3>
            </div>
            <div class="box-body">
                <h4>Silahkan cari data pendaftaran berdasarkan NISN.</h4>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN" minlength="10" maxlength="10" data-validation="required">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" minlength="3" maxlength="100">
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <a href="javascript:void(0)" onclick="carisiswa()" class="btn btn-warning btn-lg btn-flat">Cari Siswa</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="daftarpencarian"></div>
    </div>
</div>

<script>
        $(function () {
            $(".select2").select2();
        });

        function carisiswa(){
            var nama = $("#nama").val();
            if(nama.length<=5){
                alert('Variabel nama minimal 5 karakter');
            }else{
                var data = {nisn:$("#nisn").val(),nama:nama};
                $.ajax({
                    type: "POST",
                    url : "<?php echo site_url('Chome/carisiswa')?>",
                    data: data,
                    success: function(msg){
                        $('#daftarpencarian').html(msg);
                    }
                });
            }
        }

</script>