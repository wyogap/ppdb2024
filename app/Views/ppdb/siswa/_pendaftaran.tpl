{literal}
<script id="tpl-jalur-pendaftaran" type="text/template">

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"> 
        <div class="card box-default box-solid">
            <div class="card-header bg-purple with-border">
                <h3 class="box-title">{{jalur.nama}}</h3>
                <div class="box-tools pull-right">
                    <i class="glyphicon glyphicon-ok"></i>
                </div>
            </div>
            <div class="card-body">
                {{jalur.keterangan}}
            </div>
            <div class="card-footer">
                <button onclick=pilih_sekolah({{idx}}) 
                class="btn btn-primary" {{#tutup_akses}}disabled{{/tutup_akses}}>Klik disini untuk mendaftar</button>
            </div>
        </div>
    </div>

</script>

<script id="tpl-pilih-sekolah" type="text/template">
    <div id="popup-1">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <tr class="alert-secondary">
                <td><b>Jalur</b></td>
                <td>:</td>
                <td>{{jalur.nama}}</td>
            </tr>
        </table>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 8px;">
        <div class="form-group has-feedback">
            <label for="jenis_pilihan"><b>Urutan Pilihan</b></label>
            <select id="jenis_pilihan" name="jenis_pilihan" class="form-control select2" data-validation="required" autofocus>
                <option value="">-- Silahkan Pilih Urutan --</option>
                {{#pilihan}}
                <option value="{{jenis_pilihan}}">{{keterangan}}</option>
                {{/pilihan}}
            </select>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 8px;">
        <div class="form-group has-feedback">
            <label for="sekolah_id"><b>Daftar Sekolah</b></label>
            <select id="sekolah_id" name="sekolah_id" class="form-control select2" data-validation="required" data-validation-error-msg="Belum memilih sekolah">
                <option value="">-- Pilih Urutan Terlebih Dulu --</option>
            </select>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="detailsekolah"></div>
    </div>
    <div id="dokumen_pendukung" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    </div>
    </div>
</script>
{/literal}

<script type="text/javascript">

    function init_pendaftaran() {
        //anything to init?
        //TODO

        //update view
        update_pendaftaran_layout();
    }

    function update_pendaftaran_layout(redraw_jalur = true) {
        //update kelengkapan data
        tags.forEach(function(key) {
            if(!konfirmasi[key]) {
                kelengkapan_data = 0;
            }
        });

        if (!konfirmasi['nomer-hp']) {
            kelengkapan_data = 0;
        }

        //sisa slot pendaftaran
        jumlahpendaftarannegeri = jumlahpendaftaranswasta = 0;
        daftarpendaftaran.forEach(function(p) {
            if (p['pendaftaran'] != 1)  return;
            if (p['status_sekolah'] == 'N') jumlahpendaftarannegeri++;
            else if (p['status_sekolah'] == 'S') jumlahpendaftaranswasta++;
        });

        let slotnegeri = maxpilihannegeri - jumlahpendaftarannegeri;
        let slotswasta = maxpilihanswasta - jumlahpendaftaranswasta;
        $("#slot-negeri").html(slotnegeri);
        $("#slot-swasta").html(slotswasta);

        el = $("#pendaftaran-notif");
        if (slotnegeri==0 && slotswasta==0) {
            el.removeClass('alert-secondary');
            el.addClass("alert-danger");
        }
        else {
            el.addClass('alert-secondary');
            el.removeClass("alert-danger");
        }

        //update layout
        el = $("#kelengkapan-data-notif");
        if (kelengkapan_data) {
            el.hide();
        } else {
            el.show();
        }

        el = $("#kebutuhan-khusus-notif");
        if (!kebutuhankhusus) {
            el.hide();
        } else {
            el.show();
        }
        
        parent = $("#jalur-pendaftaran");
        if (pendaftarandikunci) {
            parent.hide();
        } else {
            parent.show();
        }
        
        if (redraw_jalur) {
            // let parent = $("#jalur-pendaftaran");

            //clear first
            parent.html("");

            //add jalur penerimaan one-by-one
            daftarpenerapan.forEach(function(jalur, idx) {
                //check if we need to disable the button
                flag_negeri = 0;
                if (jalur['sekolah_negeri'] == 1 && slotnegeri > 0) {
                    flag_negeri = 1;
                }
                flag_swasta = 0;
                if (jalur['sekolah_swasta'] == 1 && slotswasta > 0) {
                    flag_swasta = 1;
                }

                tutup_akses = false;
                if (!flag_negeri && !flag_swasta) {
                    tutup_akses = true;
                }

                // render template
                let template = $('#tpl-jalur-pendaftaran').html();
                Mustache.parse(template);

                let dom = Mustache.render(template, {
                    'jalur'      : jalur,
                    'idx'        : idx,
                    'tutup_akses': tutup_akses
                });

                parent.append(dom);
            });
        }

        //kebutuhan khusus
        if (profil['kebutuhan_khusus'] != 'Tidak ada') {
            kebutuhan_khusus = 1;
        }
    }

    function pilih_sekolah(idx) {
        let p = daftarpenerapan[idx];
        let jalur_id = p['jalur_id'];
        let nama_jalur = p['nama'];
        let peserta_didik_id = profil['peserta_didik_id'];
        let penerapan_id = p["penerapan_id"];

        //TODO: get opsi pilihan
        $.ajax({
            type: 'GET',
            url: "{$site_url}ppdb/siswa/pilihan?tipe=jenis_pilihan&peserta_didik_id=" +peserta_didik_id+ "&penerapan_id=" +penerapan_id,
            dataType: 'json',
            async: true,
            cache: false,
            timeout: 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mendapatkan daftar pilihan. " + textStatus)
                return;
            }
        })
        .then(function(json) {
            if (json.error !== undefined && json.error != "" && json.error != null) {
                toastr.error("Tidak berhasil mendapatkan daftar pilihan. " + json.error)
                return;
            }

            // render template
            let template = $('#tpl-pilih-sekolah').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'jalur'     : p,
                'has_dokumen_tambahan' : false,
                'pilihan'        : json.data
            });

            $.confirm({
                title: 'Pilih Sekolah',
                content: dom,
                closeIcon: true,
                columnClass: 'medium',
                // type: 'info',
                // typeAnimated: true,
                closeAnimation: 'none',
                buttons: {
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'DAFTAR',
                        btnClass: 'btn-primary',
                        action: function(){
                            //window.location.href = "#";
                            var jc = this;
                            jenis_pilihan = this.$content.find('#jenis_pilihan').val();
                            sekolah_id = this.$content.find('#sekolah_id').val()

                            if (jenis_pilihan == 0 || jenis_pilihan == "") {
                                return false;
                            }

                            if (sekolah_id == 0 || sekolah_id == "") {
                                return false;
                            }

                            send_pendaftaran(penerapan_id, jenis_pilihan, sekolah_id);
                        }
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('#jenis_pilihan').on('change', function (e) {
                        let val = $(this).val();

                        //get list of sekolah
                        $.ajax({
                            type: 'GET',
                            url: "{$site_url}ppdb/siswa/pilihan?tipe=sekolah&peserta_didik_id=" +peserta_didik_id+ "&penerapan_id=" +penerapan_id+ "&jenis_pilihan=" +val,
                            dataType: 'json',
                            async: true,
                            cache: false,
                            timeout: 60000,
                            error: function(jqXHR, textStatus, errorThrown) {
                                toastr.error("Tidak berhasil mendapatkan daftar sekolah. " + textStatus)
                                return;
                            }
                        })
                        .then(function(json) {
                            if (json.error !== undefined && json.error != "" && json.error != null) {
                                toastr.error("Tidak berhasil mendapatkan daftar sekolah. " + json.error)
                                return;
                            }

                            select = jc.$content.find('#sekolah_id');

                            options = json.data;
                            if (options != null && Array.isArray(options)) {
                                select.empty();

                                //default
                                let option = new Option("-- Silahkan Pilih Sekolah --", '', false, false);
                                select.append(option);

                                //add options one by one
                                for (item of options) {
                                    if (typeof item === "undefined" || item == null ||
                                        typeof item.sekolah_id === "undefined" || item.sekolah_id == null ||
                                        typeof item.nama === "undefined" || item.nama == null) {
                                        continue;
                                    }

                                    let label = item.nama;
                                    let jarak = parseFloat(item.jarak);
                                    if (isNaN(jarak)) {
                                        jarak = 100000;
                                    }

                                    if (item.jarak != '') {
                                        label += ' (' + (jarak/1000).toFixed(2) + 'Km)';
                                    }
                                    let value = item.sekolah_id;

                                    //let _option = $("<option>").val(sekolah_id).text(label);
                                    let option = new Option(label, value, false, false);

                                    select.append(option);

                                };
                            }

                            _parent = $(document.body).find(".jconfirm");
                            select.select2({
                                minimumResultsForSearch: 5,
                                //dropdownParent: $(_input).parent().parent().parent().parent(),
                                dropdownParent: _parent,
                            });                            
                       });
                    });
                }
            });    

        });
  
    }

    function send_pendaftaran(penerapan_id, jenis_pilihan, sekolah_id) {        
        json = {};
        json['peserta_didik_id'] = profil['peserta_didik_id'];
        json['penerapan_id'] = penerapan_id;
        json['jenis_pilihan'] = jenis_pilihan;
        json['sekolah_id'] = sekolah_id;

        $.ajax({
                type: 'POST',
                url: "{$site_url}ppdb/siswa/daftar",
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
                        toastr.error('Tidak berhasil menyimpan data profil. ' +json.error);
                        return;
                    }
                    //tambahkan ke daftar pendaftaran
                    nama_sekolah = ''; jalur = '';
                    for (p of json.data) {
                        if (p['pendaftaran'] == 1) {
                            nama_sekolah = p['sekolah'];
                            jalur = p['jalur'];
                        }
                        daftarpendaftaran.push(p);
                    };
                    update_pendaftaran_layout();
                    update_hasil_layout();

                    toastr.success("Berhasil melakukan pendaftaran di " +nama_sekolah+ " (" +jalur+ ")");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Tidak berhasil menyimpan data profil. ' +textStatus);
                    return;
                }
            });


    }

</script>


<script type="text/javascript" defer>
    var map_sebaran;
    var layer_sebaran_sekolah;

    $(document).ready(function() {
        var greenMarker = new L.Icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        lintang_aktif = {$map_lintang};
        bujur_aktif = {$map_bujur};

        profil['lintang'] = parseFloat(profil['lintang']);
        if (isNaN(profil['lintang']))   profil['lintang'] = null;

        profil['bujur'] = parseFloat(profil['bujur']);
        if (isNaN(profil['bujur']))   profil['bujur'] = null;

        lintang_aktif = profil['lintang'] ? profil['lintang'] : lintang_aktif;
	    bujur_aktif = profil['bujur'] ? profil['bujur'] : bujur_aktif;

        //Peta
        map_sebaran = L.map('peta-sebaran',{ zoomControl:false }).setView([lintang_aktif,bujur_aktif],14);
        L.tileLayer(
        	'{$map_streetmap}',{ maxZoom: 18,attribution: 'PPDB {$nama_wilayah}',id: 'mapbox.streets' }
        ).addTo(map_sebaran);

        if ((profil['lintang'] != null) && (profil['bujur'] != null)) {
            marker = L.marker([profil['lintang'],profil['bujur']], { icon: greenMarker }).bindPopup("Lokasi Rumah").openPopup();
            map_sebaran.addLayer(marker);
        }

        var streetmap   = L.tileLayer('{$map_streetmap}', { id: 'mapbox.light', attribution: '' }),
            satelitemap  = L.tileLayer('{$map_satelitemap}', { id: 'mapbox.streets', attribution: '' });

        var baseLayers = {
            "Streets": streetmap,
            "Satelite": satelitemap
        };

        var overlays = {};
        L.control.layers(baseLayers,overlays).addTo(map_sebaran);

        new L.control.fullscreen({ position:'bottomleft' }).addTo(map_sebaran);
        new L.Control.Zoom({ position:'bottomright' }).addTo(map_sebaran);

        //refresh the size of the map
        $("body").on("shown.bs.tab", "#content-pendaftaran", function() {
            map_sebaran.invalidateSize(false);
        });

        //sebaran sekolah
        layer_sebaran_sekolah = L.layerGroup();
	    map_sebaran.addLayer(layer_sebaran_sekolah);

        //get list of sekolah
        $.ajax({
            type: 'GET',
            url: "{$site_url}ppdb/siswa/sebaransekolah",
            dataType: 'json',
            async: true,
            cache: false,
            timeout: 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mendapatkan daftar sebaran sekolah. " + textStatus)
                return;
            }
        })
        .then(function(json) {
            if (json.error !== undefined && json.error != "" && json.error != null) {
                toastr.error("Tidak berhasil mendapatkan daftar sebaran sekolah. " + json.error)
                return;
            }

            daftarsekolah = json.data;
            if (daftarsekolah != null && Array.isArray(daftarsekolah)) {
                var bounds = [];

                bounds.push([lintang_aktif, bujur_aktif]);

                //add options one by one
                for (item of daftarsekolah) {
                    item.bujur = parseFloat(item.bujur);
                    item.lintang = parseFloat(item.lintang)
                    if (isNaN(item.bujur) || item.bujur == 0 ||
                            isNaN(item.lintang) || item.lintang == 0) {
                        return;
                    }

                    var marker = L.marker([item.lintang,item.bujur]).bindPopup(item.nama);
                    layer_sebaran_sekolah.addLayer(marker);

                    //console.log(item.lintang +";"+ item.bujur);
                    bounds.push([item.lintang,item.bujur]);
                };

                //map_sebaran.fitBounds(bounds);
            }
        })

    });

</script>

