<script>
    var map;
    var layerGroup;
    var profil;

	//Event On Change Dropdown
	$(document).ready(function () {
        //Dropdown Select
        $(function () {
            $(".select2").select2();
        });

        //Datepicker
        $("#tanggal_lahir").datepicker({ 
            format: 'yyyy-mm-dd'
        });

		$('select[name="kode_kabupaten"]').on('change', function() {
			var data = { "kode_wilayah":$("#kode_kabupaten").val() };
			$.ajax({
				type: "POST",
				url : "{$site_url}home/ddkecamatan",
				data: data,
				success: function(msg){
					$('#kode_kecamatan').html(msg);
                    $('#kode_kecamatan').val(profil['kode_kecamatan']).trigger("change");
				}
			});
		});

		$('select[name="kode_kecamatan"]').on('change', function() {
			var data = { "kode_wilayah":$("#kode_kecamatan").val() };
			$.ajax({
				type: "POST",
				url : "{$site_url}home/dddesa",
				data: data,
				success: function(msg){
					$('#kode_wilayah').html(msg);
                    $('#kode_wilayah').val(profil['kode_wilayah']).trigger("change");
				}
			});
		});

		// $('select[name="kode_desa"]').on('change', function() {
		// 	var data = { "kode_wilayah":$("#kode_desa").val() };
		// 	$.ajax({
		// 		type: "POST",
		// 		url : "{$site_url}home/ddpadukuhan",
		// 		data: data,
		// 		success: function(msg){
		// 			$('#kode_wilayah').html(msg);
        //             $('#kode_wilayah').val(profil['kode_wilayah']).trigger("change");
		// 		}
		// 	});
		// });

		$('#lintang').on('change', onChangeCoordinate);
		$('#bujur').on('change', onChangeCoordinate);

        map = L.map('peta',{ zoomControl:false }).setView([{$map_lintang},{$map_bujur}],10);

        L.tileLayer(
            '{$map_streetmap}',{ maxZoom: 18,attribution: 'PPDB {$nama_wilayah}',id: 'mapbox.streets' }
        ).addTo(map);

        var streetmap   = L.tileLayer('{$map_streetmap}', { id: 'mapbox.light', attribution: '' }),
            satelitemap  = L.tileLayer('{$map_satelitemap}', { id: 'mapbox.streets', attribution: '' });

        var baseLayers = {
            "Streets": streetmap,
            "Satelite": satelitemap
        };

        var overlays = {};
        L.control.layers(baseLayers,overlays).addTo(map);

        new L.control.fullscreen({ position:'bottomleft' }).addTo(map);
        new L.Control.Zoom({ position:'bottomright' }).addTo(map);

        // new L.Control.EasyButton( '<span class="map-button" style="font-size: 30px;">&curren;</span>', function(){
        //     map.setView([lintang, bujur],10);
        // }, { position: 'topleft' }).addTo(map);

        var greenMarker = new L.Icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        layerGroup = L.layerGroup().addTo(map);
        function onMapClick(e) {
            if (confirm("Ubah lokasi rumah siswa?")) {
                layerGroup.clearLayers();
                var lintang = e.latlng.lat;
                var bujur = e.latlng.lng;
                new L.marker(e.latlng).addTo(layerGroup).bindPopup("Lokasi Baru:<br>"+lintang+" , "+bujur).openPopup();
                document.getElementById("lintang").value=lintang;
                document.getElementById("bujur").value=bujur;
            }
        }
        map.on('click', onMapClick);

        // $("body").on("shown.bs.collapse", "#lokasi-content", function() {
        //     map.invalidateSize(false);
        // });

        $(".ctx-batal").on("click", function(e) {
            close_profil();
        });

        $(".ctx-simpan").on("click", function(e) {
            simpan_profil();
        });

	});
    
	function onChangeCoordinate() {
		layerGroup.clearLayers();
		var lintang = document.getElementById("lintang").value;
		var bujur = document.getElementById("bujur").value;
		new L.marker([lintang,bujur]).addTo(layerGroup).bindPopup("Lokasi Baru:<br>"+lintang+" , "+bujur).openPopup();
	}

    function show_profile(profil) {
 
        elements = $("[tcg-field-type='input']");
        elements.each(function(idx) {
            el = $(this);
            key = el.attr('name');
            val = profil[key];
            if (val === undefined || val == null) {
                val = "";
            }

            el.val(val);
        });
        
        $('#jenis_kelamin').trigger("change");
        $('#kebutuhan_khusus').trigger("change");
        $('#kode_kabupaten').trigger("change");
        $('#nama-siswa').html(profil['nama']);

        //layout
        $("#daftarsiswa-wrapper").hide();

        verifikasi = $("#ubahdata-wrapper");
        verifikasi.show();

        let width = $(window).width();
        let header_offset = 230;
        if (width < 785) {
            header_offset = 100;
        }
        else if (width < 1040) {
            header_offset = 100;
        }

        $('html,body').animate({
            scrollTop: verifikasi.offset().top - header_offset
        }, 100);

        map.invalidateSize(false);
        layerGroup.clearLayers();

        lintang = parseFloat(profil['lintang']);
        bujur = parseFloat(profil['bujur']);
        if (!isNaN(lintang) && lintang != 0 && !isNaN(bujur) && bujur != 0) {
            map.setView([lintang,bujur],10);
 
            L.marker([lintang, bujur]).addTo(layerGroup)
            .bindPopup(profil['desa_kelurahan']+ ", " +profil['kecamatan']+ ", " +profil['kabupaten']+ ", " +profil['provinsi']).openPopup();
        }

    };

    function ubah_data(peserta_didik_id) {
        //show loading
        loader = $("#loader");
        loader.show();

        //get profil data
        let formdata = new FormData();
        formdata.append("peserta_didik_id", peserta_didik_id)

        $.ajax({
            type: "POST",
            url: "{$site_url}ppdb/dapodik/daftarsiswa/profilsiswa",
            async: true,
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000,
            dataType: 'json',
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error(json.error);
                    return;
                }
                //simpan for later
                profil = json.data.profil;

                //reset the content
                show_profile(json.data.profil);
            
                loader.hide();

            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error(textStatus);
                loader.hide();
                return;
            }
        });
       
    }

    function close_profil() {
        $("#ubahdata-wrapper").hide();

        tabs = $("#daftarsiswa-wrapper");
        tabs.show();

        let width = $(window).width();
        let header_offset = 230;
        if (width < 785) {
            header_offset = 100;
        }
        else if (width < 1040) {
            header_offset = 100;
        }

        $('html,body').animate({
            scrollTop: tabs.offset().top - header_offset
        }, 100);
    }

    function simpan_profil() {

        var cnt = 0;
        var updated = {};

        //show loading
        loader = $("#loader");
        loader.show();
        
        elements = $("[tcg-field-type='input']");
        elements.each(function(idx) {
            el = $(this);
            key = el.attr('name');
            val = el.val();

            //ignore some fields
            if (key == 'kode_kecamatan' || key == 'kode_kabupaten') {
                return;
            }

            //check for changed value
            oldval = profil[key];
            if (val != oldval && !(val == '' && oldval === undefined)) {
                updated[key] = val;       
                cnt++;     
            }
        });
        
        if (cnt == 0) {
            toastr.info("Tidak ada data yang berubah.");
            close_profil();
            loader.hide();
            return;
        }

        json = {};
        json['peserta_didik_id'] = profil['peserta_didik_id'];
        json['data'] = {};
        json['data']['profil'] = updated;

        status = $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/dapodik/daftarsiswa/simpan",
            dataType: 'json',
            data: json,
            async: false,
            cache: false,
            //if we use formData, set processData = false. if we use json, set processData = true!
            //contentType: true,
            //processData: true,      
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil menyimpan data profl siswa: " +json.error);
                    loader.hide();
                    return false;
                }
                toastr.success("Data profil siswa an. " +profil['nama']+ " berhasil disimpan.");

                //reload
                dt_siswa_kls6.ajax.reload();

                //close the window
                loader.hide();
                close_profil();
                return true;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //TODO
                toastr.error("Tidak berhasil menyimpan data profil siswa: " +textStatus);
                verifikasi_siswa = 0;
                loader.hide();
                return false;
            }
        });

        return false;
    }
</script>
