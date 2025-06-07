<script type="text/javascript">

    var map_profil;

    $(document).ready(function() {
		//Peta
		var map_profil = L.map('peta-profil',{ zoomControl:false }).setView([{$profilsekolah.lintang},{$profilsekolah.bujur}],16);
		L.tileLayer(
			'{$map_streetmap}',{ maxZoom: 18,attribution: '{$app_short_name} {$nama_wilayah}',id: 'mapbox.streets' }
		).addTo(map_profil);

		L.marker([{$profilsekolah.lintang},{$profilsekolah.bujur}]).addTo(map_profil)
            .bindPopup("{$profilsekolah.alamat_jalan}, {$profilsekolah.desa_kelurahan}, {$profilsekolah.kecamatan}");

		var streetmap   = L.tileLayer('{$map_streetmap}', { id: 'mapbox.light', attribution: '' }),
			satelitemap  = L.tileLayer('{$map_satelitemap}', { id: 'mapbox.streets', attribution: '' });

		var baseLayers = {
			"Streets": streetmap,
			"Satelite": satelitemap
		};

		var overlays = {};
		L.control.layers(baseLayers,overlays).addTo(map_profil);

		new L.control.fullscreen({ position:'bottomleft' }).addTo(map_profil);
		new L.Control.Zoom({ position:'bottomright' }).addTo(map_profil);

        new L.Control.EasyButton( '<span class="map-button" style="font-size: 30px;">&curren;</span>', function(){
            map_profil.setView([{$profilsekolah.lintang},{$profilsekolah.bujur}],10);
        }, { position: 'topleft' }).addTo(map_profil);

        var greenMarker = new L.Icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var layerGroup = L.layerGroup().addTo(map_profil);
        function onMapClick(e) {
            layerGroup.clearLayers();
            let lintang = e.latlng.lat;
            let bujur = e.latlng.lng;
            new L.marker(e.latlng, { icon: greenMarker }).addTo(layerGroup).bindPopup("Koordinat Baru:<br>"+lintang+" , "+bujur).openPopup();
            document.getElementById("lintang").value=lintang;
            document.getElementById("bujur").value=bujur;
        }
        map_profil.on('click', onMapClick);

    });

    function simpan_profil() {
        let json = {};

        json['inklusi'] = $("#inklusi").val();
        json['alamat_jalan'] = $("#alamat_jalan").val();
        json['lintang'] = $("#lintang").val();
        json['bujur'] = $("#bujur").val();

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/sekolah/ubahprofil/simpan",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            //if we use formData, set processData = false. if we use json, set processData = true!
            //contentType: true,
            //processData: true,      
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil menyimpan perubahan profil sekolah. " + json.error);
                    return;
                }
                toastr.success("Perubahan profil sekolah berhasil disimpan.");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil menyimpan perubahan profil sekolah. " + textStatus);
                return;
            }
        });

    }
</script>