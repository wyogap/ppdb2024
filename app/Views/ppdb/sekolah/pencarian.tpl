<style>
    .adv-search .form-control {
        height: 42px;
        margin-bottom: 0px;
    }

    .adv-search .btn-search {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .adv-search-btn .form-control {
        display: inline-block;
    }

    .btn .caret {
        margin-left: 0;
    }

    .caret {
        display: inline-block;
        width: 0;
        height: 0;
        margin-left: 2px;
        vertical-align: middle;
        border-top: 4px dashed;
        /* border-top: 4px solid; */
        border-right: 4px solid transparent;
        border-left: 4px solid transparent;
    }
</style>

<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Pencarian Siswa</a></li>
    </ol>
</div>

<div class="card box-solid" style="margin-bottom: 16px;">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group adv-search"><input type="text" name="search" id="search" class="form-control" placeholder="Pencarian">
                    <div class="input-group-btn cust-input-grp">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary btn-search"><i class="fa fa-search"></i></button>
                            <a class="btn btn-secondary adv-search-btn" href="#"><span class="d-none d-md-inline">Filter </span><span class="caret"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="adv-search-box row" style="margin-top: 16px; display:none;">

            <div class="form-group col-4 mb-0 mt-1 col-12 col-md-3 col-sm-6">
                <select id="f_jenjang" name="jenjang" class="form-control" placeholder="Jenjang Sekolah">
                    <option value="">-- Jenjang Sekolah --</option>
                    {foreach $daftarjenjang as $jenjang}
                    <option value="{$jenjang.label}">{$jenjang.label}</option>
                    {/foreach}
                </select>
            </div>

            <div class="form-group col-4 mb-0 mt-1 col-12 col-md-3">
                <select id="f_asaldata" name="asaldata" class="form-control" placeholder="Asal Data">
                    <option value="">-- Asal Data --</option>
                    {foreach $daftarasaldata as $asaldata}
                    <option value="{$asaldata.value}">{$asaldata.label}</option>
                    {/foreach}
                </select>
            </div>

            <div class="form-group col-4 mb-0 mt-1 col-12 col-md-3 col-sm-6">
                <select id="f_inklusi" name="inklusi" class="form-control" placeholder="Kebutuhan Khusus">
                    <option value="">-- Kebutuhan Khusus --</option>
                    YA
                    <option value="1">YA</option>
                    TIDAK
                    <option value="0">TIDAK</option>
                </select>
            </div>

            <div class="form-group col-4 mb-0 mt-1 col-12 col-md-3 col-sm-6">
                <select id="f_afirmasi" name="afirmasi" class="form-control" placeholder="Afirmasi">
                    <option value="">-- Afirmasi --</option>
                    YA
                    <option value="1">YA</option>
                    TIDAK
                    <option value="0">TIDAK</option>
                </select>
            </div>

        </div>
    </div>
</div>

<div class="card box-solid">
    <div class="card-body">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="display" id="tnegeri" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="1">Nama</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center">Jenis Kelamin</th>
                            <th class="text-center">Tempat Lahir</th>
                            <th class="text-center">Tanggal Lahir</th>
                            <th class="text-center">Asal Sekolah</th>
                            <th class="text-center" data-priority="2">Status Pendaftaran</th>
                            <th class="text-center">Kelengkapan Berkas</th>
                            <th class="text-center" data-priority="3">Lokasi Berkas</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>