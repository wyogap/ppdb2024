<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Penerimaan Siswa Baru</a></li>
    </ol>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center justify-content-center" id="loading2" style="position: absolute; margin-top: 24px; margin-left: -12px;">
    <div class="loader" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="card box-solid">
    <div class="card-body">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="display" id="tditerima" style="width:100%;">
                    <thead>
                        <tr>
                            <!-- <td class="text-center" data-priority="1">#</td> -->
                            <td class="text-center" data-priority="1">Nama</td>
                            <td class="text-center">Jenis Kelamin</td>
                            <td class="text-center" data-priority="3">NISN</td>
                            <td class="text-center">NIK</td>
                            <td class="text-center">Tempat Lahir</td>
                            <td class="text-center">Tanggal Lahir</td>
                            <td class="text-center">Nama Ibu Kandung</td>
                            <td class="text-center">NPSN Sekolah Asal</td>
                            <td class="text-center" data-priority="4">Sekolah Asal</td>
                            <td class="text-center" data-priority="2"></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>


<div class="card box-solid">
    <div class="card-header">
    <h3 class="box-title">Pencarian</h3>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" minlength="3" maxlength="100">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN" minlength="3" maxlength="64">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group has-feedback">
                    <select id="sekolah_id" name="sekolah_id" class="form-control select2" style="width:100%;">
                        <option value="">-- Asal Sekolah --</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <a id='btn_search' href="javascript:void(0)" onclick="cari_peserta_didik(); return false;" class="btn btn-sm btn-primary btn-flat" style=" float: right;">Cari Peserta Didik</a>
            </div>
        </div>
        <div class="row" style="margin-top: 16px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center justify-content-center" id="loading" style="position: absolute; margin-top: 24px; margin-left: -12px; display: none;">
                <div class="loader" role="status">
                    <span class="sr-only">Loading...</span>
                </div> 
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="display" id="tsearch" style="width:100%;">
                    <thead>
                        <tr>
                            <!-- <td class="text-center" data-priority="1">#</td> -->
                            <td class="text-center" data-priority="1">Nama</td>
                            <td class="text-center">Jenis Kelamin</td>
                            <td class="text-center" data-priority="3">NISN</td>
                            <td class="text-center">NIK</td>
                            <td class="text-center" data-priority="4">Tanggal Lahir</td>
                            <td class="text-center">Asal Sekolah</td>
                            <td class="text-center">Diterima Di</td>
                            <td class="text-center" data-priority="2"></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
    <!-- <div class="card-body" style="border-top: solid 1px #f5f5f5;">
    </div> -->
    <!-- <div class="card-footer">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <a id='btn_search' href="javascript:void(0)" onclick="cari_peserta_didik(); return false;" class="btn btn-sm btn-primary btn-flat">Cari Peserta Didik</a>
            </div>
        </div>
    </div> -->
</div>
