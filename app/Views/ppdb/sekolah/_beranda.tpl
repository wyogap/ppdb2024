


<script>
		//Peta
		var map = L.map('peta',{ zoomControl:false }).setView([{$map_lintang},{$map_bujur}],16);
		L.tileLayer(
			'{$map_streetmap}',{ maxZoom: 18,attribution: 'PPDB {$nama_wilayah}',id: 'mapbox.streets' }
		).addTo(map);

		L.marker([{$map_lintang},{$map_bujur}]).addTo(map)
            .bindPopup("{$profil.alamat_jalan}, {$profil.desa_kelurahan}, {$profil.kecamatan}");

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

</script>