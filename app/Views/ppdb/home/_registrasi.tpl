<script>
    //Date Picker
    $("#tanggal_lahir").datepicker({ 
        format: 'yyyy-mm-dd',
        {if !empty($maxtgllahir)}startDate: new Date("{$maxtgllahir}"),{/if}
        {if !empty($mintgllahir)}endDate: new Date("{$mintgllahir}"){/if}
    });
</script>

<script>
    //Dropdown Select
    $(function () {
        $(".select2").select2();
    });
    
    var kode_kabupaten_sekolah = "{$kode_kabupaten_sekolah|default:''}";
    var sekolah_id = "{$sekolah_id|default:''}";
    var bentuk_sekolah = "{$bentuk_sekolah|default:''}";
    var nik = "{$nik|default:''}";
    var nisn = "{$nisn|default:''}";
    var nomor_ujian = "{$nomor_ujian|default:''}";
    var nama = "{$nama|default:''}";
    var jenis_kelamin = "{$jenis_kelamin|default:''}";
    var tempat_lahir = "{$tempat_lahir|default:''}";
    var tanggal_lahir = "{$tanggal_lahir|default:''}";
    var nama_ibu_kandung = "{$nama_ibu_kandung|default:''}";
    var kebutuhan_khusus = "{$kebutuhan_khusus|default:''}";
    var alamat = "{$alamat|default:''}";
    var kode_kabupaten = "{$kode_kabupaten|default:''}";
    var kode_kecamatan = "{$kode_kecamatan|default:''}";
    var kode_desa = "{$kode_desa|default:''}";
    var kode_wilayah = "{$kode_wilayah|default:''}";
    var lintang = "{$lintang|default:''}";
    var bujur = "{$bujur|default:''}";
    var nama_sekolah = "{$nama_sekolah|default:''}";
    var npsn = "{$npsn_sekolah|default:''}";
    var nomor_kontak = "{$nomor_kontak|default:''}";

    //Event On Change Dropdown
    $(document).ready(function () {

        $('#kode_kabupaten_sekolah').on('change', function() {
            let val1 = $("#kode_kabupaten_sekolah").val();
            if (val1 == kode_kabupaten_sekolah && bentuk_sekolah != "") {
                $("#bentuk").val(bentuk_sekolah).change();
            }

            kode_kabupaten_sekolah = val1;
        });

        $('select[name="bentuk"]').on('change', function() {
            let val1 = $("#kode_kabupaten_sekolah").val();
            let val2 = $("#bentuk").val();
            var data = { kode_wilayah:val1, bentuk:val2 };
            $.ajax({
                type: "POST",
                url : "{$site_url}home/ddsekolah",
                data: data,
                success: function(msg){
                    $('#sekolah_id').html(msg);
                    if (val1 == kode_kabupaten_sekolah && val2 == bentuk_sekolah && sekolah_id != "") {
                        $('#sekolah_id').val(sekolah_id).change();
                    }
                    else {
                        $('#sekolah_id').val("").change();
                    }

                    kode_kabupaten_sekolah = val1;
                    bentuk_sekolah = val2;
                }
            });
        });

        $('select[name="sekolah_id"]').on('change', function() {
            var val = $("#sekolah_id").val();
            var txt = $( "#sekolah_id option:selected" ).text();
            if (val == "") {
                el = $('#nama-sekolah-manual');
                el.find("#nama_sekolah").attr("data-validation", "required");
                el.find("#npsn_sekolah").attr("data-validation", "required");
                el.show();
                $('#sekolah_id').attr("data-validation", "");
                //cache value
                if (nama_sekolah != "") {
                    $('#nama_sekolah').val(nama_sekolah);
                }
            }
            else {
                el = $('#nama-sekolah-manual');
                el.find("#nama_sekolah").attr("data-validation", "");
                el.find("#npsn_sekolah").attr("data-validation", "");
                el.hide();
                $('#sekolah_id').attr("data-validation", "required");
            }

            sekolah_id = val;
        });

        $('#nama_sekolah').on('change', function() {
            nama_sekolah = $('#nama_sekolah').val();
        });

        $('select[name="kode_kabupaten"]').on('change', function() {
            let val = $("#kode_kabupaten").val();
            var data = { kode_wilayah:val };
            $.ajax({
                type: "POST",
                url : "{$site_url}home/ddkecamatan",
                data: data,
                success: function(msg){
                    $('#kode_kecamatan').html(msg);
                    if (val == kode_kabupaten && kode_kecamatan != "") {
                        $('#kode_kecamatan').val(kode_kecamatan).change();
                    }
                    kode_kabupaten = val;
                }
            });
        });

        $('select[name="kode_kecamatan"]').on('change', function() {
            let val = $("#kode_kecamatan").val();
            var data = { kode_wilayah:val };
            $.ajax({
                type: "POST",
                url : "{$site_url}home/dddesa",
                data: data,
                success: function(msg){
                    $('#kode_wilayah').html(msg);
                    if (val == kode_kecamatan && kode_wilayah != "") {
                        $('#kode_wilayah').val(kode_wilayah).change();
                    }
                    kode_kecamatan = val;
                }
            });
        });

        $('select[name="kode_wilayah"]').on('change', function() {
            kode_wilayah = $("#kode_wilayah").val();
        });

        if (kode_kabupaten_sekolah != "") {
            $("#kode_kabupaten_sekolah").val(kode_kabupaten_sekolah).change();
        }

        // if (bentuk_sekolah != "") {
        // 	$("#bentuk_sekolah").val(bentuk_sekolah).change();
        // }

        // if (sekolah_id != "") {
        // 	$("#sekolah_id").val(sekolah_id).change();
        // }

        // if (nama_sekolah != "") {
        // 	$("#nama_sekolah").val(nama_sekolah);
        // }

        $(".btn-tarik-data").on("click", function() {
            tarik_dapodik();
        })
 
        $(".btn-registrasi").on("click", function(e) {
            e.preventDefault();
            send_registrasi();
        })

        dom = document.getElementById("peta");

        if (dom) {
            {literal}
            map = L.map('peta',{ zoomControl:false }).setView([map_lintang,map_bujur],10);
            var tile = L.tileLayer(
                map_streetmap,{ maxZoom: 18,attribution: 'PPDB ' +map_namawilayah,id: 'mapbox.streets' }
            ).addTo(map);

            var streetmap   = L.tileLayer(map_streetmap, { id: 'mapbox.light', attribution: '' }),
                satelitemap  = L.tileLayer(map_satelitemap, { id: 'mapbox.streets',   attribution: '' });

            var baseLayers = {
                "Streets": streetmap,
                "Satelite": satelitemap
            };

            var overlays = {};
            L.control.layers(baseLayers,overlays).addTo(map);

            layerGroup = L.layerGroup().addTo(map);
            function onMapClick(e) {
                layerGroup.clearLayers();
                let lintang = e.latlng.lat;
                let bujur = e.latlng.lng;
                new L.marker(e.latlng).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
                document.getElementById("lintang").value=lintang;
                document.getElementById("bujur").value=bujur;
            }
            map.on('click', onMapClick);

            //not very useful. use google better?
            // var searchControl = L.esri.Geocoding.geosearch().addTo(map);
            // searchControl.on('layerGroup', function(data){
            //     layerGroup.clearLayers();
            // });

            new L.Control.Fullscreen({ position:'bottomleft' }).addTo(map);
            new L.Control.Zoom({ position:'bottomright' }).addTo(map);

            new L.Control.EasyButton( '<span class="map-button" style="font-size: 30px;">&curren;</span>', function(){
                map.setView([map_lintang,map_bujur],10);;
            }, { position: 'topleft' }).addTo(map);

            streetmap.addTo(map);
            {/literal}  
        }
  
        show_profil();
 
    });

    //Validasi
    var myLanguage = {
        errorTitle: 'Gagal mengirim data. Belum mengisi semua data wajib:',
        requiredFields: 'Belum mengisi semua data wajib',
    };
    
    // var $messages = $('#error-message-wrapper');
    // $.validate({
    //     language : myLanguage,
    //     ignore: [],
    //     modules: 'security',
    //     errorMessagePosition: "top",
    //     scrollToTopOnError: true,
    //     validateHiddenInputs: true
    // });

    //Peta
    var map_lintang = "{$map_lintang}";
    var map_bujur = "{$map_bujur}";
    var map_namawilayah = "{$nama_wilayah}";
    var map_streetmap = '{$map_streetmap}';
    var map_satelitemap = '{$map_satelitemap}';

    var map = null;
    var layerGroup = null;

    function show_profil() {
        if (nik != "") {
            $("#nik").val(nik);
        }
        if (nisn != "") {
            $("#nisn").val(nisn);
            // $("#cari_nisn").val(nisn);
        }
        if (nomor_ujian != "") {
            $("#nomor_ujian").val(nomor_ujian);
        }
        if (nama != "") {
            $("#nama").val(nama);
        }

        if (jenis_kelamin != "") {
            $("#jenis_kelamin").val(jenis_kelamin).change();
        }

        if (tempat_lahir != "") {
            $("#tempat_lahir").val(tempat_lahir);
        }
        if (tanggal_lahir != "") {
            $("#tanggal_lahir").val(tanggal_lahir);
        }
        if (nama_ibu_kandung != "") {
            $("#nama_ibu_kandung").val(nama_ibu_kandung);
        }

        if (kebutuhan_khusus != "") {
            $("#kebutuhan_khusus").val(kebutuhan_khusus).change();
        }

        if (alamat != "") {
            $("#alamat").val(alamat);
        }

        if (kode_kabupaten != "") {
            $("#kode_kabupaten").val(kode_kabupaten).change();
        }

        if (lintang != null && lintang != "") {
            $("#lintang").val(lintang);
        }

        if (bujur != null && bujur != "") {
            $("#bujur").val(bujur);
        }

        if (nama_sekolah != "") {
            $("#nama_sekolah").val(nama_sekolah);
        }

        if (npsn != "") {
            $("#npsn_sekolah").val(npsn);
            // $("#cari_npsn").val(npsn);
        }

        if (map != null) {
            map.invalidateSize(false);
            if (lintang != null && lintang != "" && bujur != null && bujur != "") {
                layerGroup.clearLayers();
                new L.marker([lintang,bujur]).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
            }
        }

        if (nomor_kontak != "") {
            $("#nomor_kontak").val(nomor_kontak);
        }

    }

    function reset_profil() {
        $("#nik").val('');
        $("#nisn").val('');
        $("#nomor_ujian").val('');
        $("#nama").val('');
        $("#jenis_kelamin").val('').change();
        $("#tempat_lahir").val('');
        $("#tanggal_lahir").val('');
        $("#nama_ibu_kandung").val('');
        $("#kebutuhan_khusus").val('').change();
        $("#alamat").val('');
        $("#kode_kabupaten").val('').change();
        $("#lintang").val('');
        $("#bujur").val('');
        $("#nama_sekolah").val('');
        $("#npsn_sekolah").val('');
        $("#nomor_kontak").val('');

        if (map != null) {
            layerGroup.clearLayers();
            map.invalidateSize(false);
        }
    }

    function tarik_dapodik() {  

        let cari_nisn = $('#cari_nisn').val();
        let cari_npsn = $('#cari_npsn').val();

        if (cari_nisn == '' || cari_npsn == '') {
            $("#status-tarik-data").removeClass('alert-danger');
            $("#status-tarik-data").addClass('alert-secondary');
            $("#status-tarik-data").html("Silahkan masukkan no NISN kamu dan no NPSN sekolah asal kamu. ATAU masukkan data secara manual di bawah ini.");
            $("#status-tarik-data").show();

            $("#formulir").show();
            reset_profil();

            return;
        }

        let loader = $('#loader');
        loader.show();

        $.ajax({
            "url": "{$site_url}home/cekdapodik",
            "dataType": "json",
            "type": "POST",
            "data": {
                nisn: $('#cari_nisn').val(),
                npsn: $('#cari_npsn').val()
            },
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type",
                    "application/x-www-form-urlencoded; charset=UTF-8");
            },
            success: function(response) {
                let data = [];
                let message = '';

                if (response.errorno == -90) {
                    $("#status-tarik-data").removeClass('alert-danger');
                    $("#status-tarik-data").addClass('alert-secondary');
                    $("#status-tarik-data").html('Data siswa dengan NISN di atas sudah ada di sistem. Silahkan koordinasi dengan Sekolah Tujuan atau Sekolah Tempat Registrasi Akun untuk pengelolaan akun siswa dengan membawa berkas pendukung.');
                    $("#status-tarik-data").show();
                    $("#formulir").hide();
                    loader.hide();
                    return;
                }

                if (response.data === null) {
                    toastr.error("Gagal mengambil data via ajax");
                    data = null;
                } else if (typeof response.error !== 'undefined' && response.error !== null && response.error != "") {
                    toastr.error("Gagal mengambil data via ajax: " +response.error);
                    data = null;
                } else {
                    data = response.data;
                }

                if (data != null) {
                    kode_kabupaten_sekolah = data['kode_wilayah_sekolah'];
                    sekolah_id = data['sekolah_id'];
                    bentuk_sekolah = data['bentuk'];
                    nik = data['nik'];
                    nisn = data['nisn'];
                    nomor_ujian = "";
                    nama = data['nama'];
                    jenis_kelamin = data['jenis_kelamin'];
                    tempat_lahir = data['tempat_lahir'];
                    tanggal_lahir = data['tanggal_lahir'];
                    nama_ibu_kandung = data['nama_ibu_kandung'];
                    kebutuhan_khusus = data['kebutuhan_khusus'];
                    alamat = data['alamat'];
                    kode_kabupaten = data['kode_wilayah'].substring(0, 4) +'00';
                    kode_kecamatan = data['kode_wilayah'].substring(0, 6);
                    kode_desa = data['kode_wilayah'];
                    kode_wilayah = data['kode_wilayah'];
                    lintang = data['lintang'] == null ? '' : data['lintang'];
                    bujur = data['bujur'] == null ? '' : data['bujur'];
                    nama_sekolah = data['nama_sekolah'];
                    npsn = data['npsn_sekolah'];
                    nomor_kontak = "";    
                    
                    $("#status-tarik-data").removeClass('alert-danger');
                    $("#status-tarik-data").addClass('alert-secondary');
                    $("#status-tarik-data").html("BERHASIL! Silahkan review data hasil penarikan dari DAPODIK di bawah ini. Setelah itu, tekan tombol Registrasi di bawah.");
                    $("#status-tarik-data").show();

                    $("#formulir").show();
                    show_profil();
                    loader.hide();

                }
                else {
                    $("#status-tarik-data").removeClass('alert-secondary');
                    $("#status-tarik-data").addClass('alert-danger');
                    $("#status-tarik-data").html("GAGAL! Tidak berhasil menarik data dari DAPODIK. Silahkan coba lagi. ATAU masukkan data secara manual di bawah ini.");
                    $("#status-tarik-data").show();

                    $("#formulir").show();
                    reset_profil();
                    loader.hide();

                }

            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                        (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}auth";
                }
                //send toastr message
                toastr.error("Gagal mengambil data via ajax");

                $("#status-tarik-data").removeClass('alert-success');
                $("#status-tarik-data").addClass('alert-danger');
                $("#status-tarik-data").html("GAGAL! Tidak berhasil menarik data dari DAPODIK di bawah ini. Silahkan coba lagi. ATAU masukkan data secara manual di bawah ini.");
                $("#status-tarik-data").show();

                $("#formulir").show();
                reset_profil();
                loader.hide();

            }
        });

    }

    function send_registrasi() {
        let frm = $("#registrasi");

        let tosubmit = 1;
        els = frm.find("input");
        for(el of els) {
            dom = $(el);
            val = dom.val();
            field = dom.attr("name");
            validation = dom.attr('data-validation');

            if (validation == 'required' && (val == null || val == "")) {
                tosubmit = 0;
                label = dom.prev('label');
                if (label.length > 0)   text = label[0].innerText;
                else text = field;
                toastr.error("Field " +text+ " harus diisi.");
                dom.addClass("border-red");
            }
            else {
                dom.removeClass("border-red");
            }
        };

        els = frm.find("select");
        for(el of els) {
            dom = $(el);
            val = dom.val();
            field = dom.attr("name");
            validation = dom.attr('data-validation');

            if (validation == 'required' && (val == null || val == "")) {
                tosubmit = 0;
                label = dom.prev('label');
                if (label.length > 0)   text = label[0].innerText;
                else text = field;
                toastr.error("Field " +text+ " harus diisi.");
                select2 = dom.next(".select2");
                select2.find(".select2-selection").addClass("border-red")
            }
            else {
                select2 = dom.next(".select2");
                select2.find(".select2-selection").removeClass("border-red")
            }
        };

        if (tosubmit == 0) {
            return false;
        }

        //send the form
        document.getElementById('registrasi').submit();
    }
    
</script>