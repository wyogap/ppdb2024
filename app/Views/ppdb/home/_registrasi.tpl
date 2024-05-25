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

        if (nik != "") {
            $("#nik").val(nik);
        }
        if (nisn != "") {
            $("#nisn").val(nisn);
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

        if (lintang != "") {
            $("#lintang").val(lintang);
        }

        if (bujur != "") {
            $("#bujur").val(bujur);
        }

        if (lintang != "" && bujur != "") {
            layerGroup.clearLayers();
            new L.marker([lintang,bujur]).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
        }

        if (nomor_kontak != "") {
            $("#nomor_kontak").val(nomor_kontak);
        }

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

    {literal}
    var map = L.map('peta',{ zoomControl:false }).setView([map_lintang,map_bujur],10);
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

    var layerGroup = L.layerGroup().addTo(map);
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

</script>