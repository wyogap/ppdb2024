<script>
    function update_hasil_layout() {
        if (daftarpendaftaran == null || daftarpendaftaran.length == 0) {
            $('#daftar-pendaftaran-notif').show();
            let parent = $("#daftar-pendaftaran");
            parent.html(''); 

            //tidak ada pendaftaran aktif -> reenable edit of profil (buka kunci)
            if (profildikunci) {
                profildikunci = 0;
                update_profile_layout();
            }
            return;
        }

        $('#daftar-pendaftaran-notif').hide();
        
        //clear first
        let parent = $("#daftar-pendaftaran");
        parent.html('');

        //add pendaftaran one-by-one
        daftarpendaftaran.forEach(function(pendaftaran, idx) {
            // render template
            let template = $('#detail-pendaftaran').html();
            Mustache.parse(template);

            //batasan
            pendaftaran['ubah_pilihan'] = pendaftaran['batasan_ubah_pilihan'] > 0;
            pendaftaran['ubah_jalur'] = pendaftaran['batasan_ubah_jalur'] > 0;
            pendaftaran['ubah_sekolah'] = pendaftaran['batasan_ubah_sekolah'] > 0;
            pendaftaran['hapus_pendaftaran'] = pendaftaran['batasan_hapus'] > 0;

            let dom = Mustache.render(template, {
                'allow_edit': !pendaftarandikunci,
                'kelengkapan_data' : kelengkapan_data,
                'item'      : pendaftaran,
                //'batasan'   : batasanperubahan,
                'idx'       : idx
            });

            parent.append(dom);
        });
        
        //kunci profil
        if (!profildikunci) {
            profildikunci = 1;
            update_profile_layout();
        }

    }

    {if !$is_public|default: FALSE}
    function hapus_pendaftaran(idx) {
        let p = daftarpendaftaran[idx];
        let pendaftaran_id = p['pendaftaran_id'];

        // render template
        let template = $('#hapus-pendaftaran').html();
        Mustache.parse(template);

        let dom = Mustache.render(template, {
            'batasan'   : p['batasan_hapus'],
            'item'      : p,
        });

        $.confirm({
                title: 'Hapus Pendaftaran?',
                content: dom,
                closeIcon: true,
                columnClass: 'medium',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    // confirm: function () {
                    //     $.alert('Confirmed!');
                    // },
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'Hapus',
                        btnClass: 'btn-danger',
                        action: function(){
                            let el = this.$content.find('#keterangan');
                            let catatan = el.val().trim();
                            if(!catatan){
                                el.addClass("border-red");
                                toastr.error("Alasan penghapusan pendaftaran harus diisi.");
                                return false;
                            }   
                                               
                            send_hapus_pendaftaran(pendaftaran_id, catatan);
                        }
                    },
                }
            });      
    }

    function ubah_pilihan(idx) {
        let p = daftarpendaftaran[idx];
        let pendaftaran_id = p['pendaftaran_id'];
        let peserta_didik_id = profil['peserta_didik_id'];

        //get opsi pilihan
        $.ajax({
            type: 'GET',
            url: "{$site_url}ppdb/siswa/pilihan?tipe=ubahpilihan&peserta_didik_id=" +peserta_didik_id+ "&pendaftaran_id=" +pendaftaran_id,
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
            let template = $('#ubah-pilihan').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'batasan'   : p['batasan_ubah_pilihan'],
                'item'      : p,
                'pilihan'   : json.data
            });

            $.confirm({
                    title: 'Ubah Jenis Pilihan?',
                    content: dom,
                    closeIcon: true,
                    columnClass: 'medium',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        // confirm: function () {
                        //     $.alert('Confirmed!');
                        // },
                        cancel: {
                            text: 'Batal',
                            keys: ['enter', 'shift'],
                            action: function(){
                                //do nothing
                            }
                        },
                        confirm: {
                            text: 'Ubah Pilihan',
                            btnClass: 'btn-orange',
                            action: function(){
                                let el = this.$content.find('#jenis_pilihan_baru');
                                let pilihan_baru = el.val();
                                if(!pilihan_baru){
                                    el.addClass("border-red");
                                    this.$content.find('.select2-selection').addClass("border-red");
                                    toastr.error("Urutan pilihan baru harus diisi.");
                                    return false;
                                }   

                                send_ubah_pilihan(pendaftaran_id, pilihan_baru);
                            }
                        },
                    }
                });      
        })
    }

    function ubah_jalur(idx) {
        let p = daftarpendaftaran[idx];
        let pendaftaran_id = p['pendaftaran_id'];
        let peserta_didik_id = profil['peserta_didik_id'];

        //get opsi pilihan
        $.ajax({
            type: 'GET',
            url: "{$site_url}ppdb/siswa/pilihan?tipe=ubahjalur&peserta_didik_id=" +peserta_didik_id+ "&pendaftaran_id=" +pendaftaran_id,
            dataType: 'json',
            async: true,
            cache: false,
            timeout: 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mendapatkan daftar jalur. " + textStatus)
                return;
            }
        })
        .then(function(json) {
            if (json.error !== undefined && json.error != "" && json.error != null) {
                toastr.error("Tidak berhasil mendapatkan daftar jalur. " + json.error)
                return;
            }

            // render template
            let template = $('#ubah-jalur').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'batasan'   : p['batasan_ubah_jalur'],
                'item'      : p,
                'pilihan'   : json.data
            });

            $.confirm({
                title: 'Ubah Jalur?',
                content: dom,
                closeIcon: true,
                columnClass: 'medium',
                type: 'blue',
                typeAnimated: true,
                buttons: {
                    // confirm: function () {
                    //     $.alert('Confirmed!');
                    // },
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'Ubah Jalur',
                        btnClass: 'btn-blue',
                        action: function(){
                            let el = this.$content.find('#jalur_penerapan_baru');
                            let pilihan_baru = el.val();
                            if(!pilihan_baru){
                                el.addClass("border-red");
                                this.$content.find('.select2-selection').addClass("border-red");
                                toastr.error("Pilihan jalur penerimaan baru harus diisi.");
                                return false;
                            }   

                            send_ubah_jalur(pendaftaran_id, pilihan_baru);
                        }
                    },
                }
            });      
        });
    }

    function ubah_sekolah(idx) {
        let p = daftarpendaftaran[idx];
        let pendaftaran_id = p['pendaftaran_id'];
        let peserta_didik_id = profil['peserta_didik_id'];

        //get opsi pilihan
        $.ajax({
            type: 'GET',
            url: "{$site_url}ppdb/siswa/pilihan?tipe=ubahsekolah&peserta_didik_id=" +peserta_didik_id+ "&pendaftaran_id=" +pendaftaran_id,
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

            //update the label display
            options = json.data;
            options.forEach(function(item, idx) {
                if (typeof item === "undefined" || item == null ||
                    typeof item.sekolah_id === "undefined" || item.sekolah_id == null ||
                    typeof item.nama === "undefined" || item.nama == null) {
                    return;
                }

                let label = item.nama;
                let jarak = parseFloat(item.jarak);
                if (isNaN(jarak)) {
                    jarak = 100000;
                }

                if (item.jarak != '') {
                    label += ' (' + (jarak/1000).toFixed(2) + 'Km)';
                }
                
                //update label
                options[idx]['nama'] = label;
            })
             
            // render template
            let template = $('#ubah-sekolah').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'batasan'   : p['batasan_ubah_sekolah'],
                'item'      : p,
                'pilihan'   : options
            });

            $.confirm({
                title: 'Ubah Pilihan Sekolah?',
                content: dom,
                closeIcon: true,
                columnClass: 'medium',
                type: 'purple',
                typeAnimated: true,
                buttons: {
                    // confirm: function () {
                    //     $.alert('Confirmed!');
                    // },
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'Ubah Sekolah',
                        btnClass: 'btn-purple',
                        action: function(){
                            let el = this.$content.find('#sekolah_id');
                            let pilihan_baru = el.val();
                            if(!pilihan_baru){
                                el.addClass("border-red");
                                this.$content.find('.select2-selection').addClass("border-red");
                                toastr.error("Pilihan sekolah baru harus diisi.");
                                return false;
                            }   

                            send_ubah_sekolah(pendaftaran_id, pilihan_baru);
                        }
                    },
                },
                onContentReady: function () {
                    var jc = this;

                    parent = $(document.body).find(".jconfirm");
                    select = this.$content.find('#sekolah_id');
                    select.select2({
                        minimumResultsForSearch: 5,
                        //dropdownParent: $(_input).parent().parent().parent().parent(),
                        dropdownParent: parent,
                    });     
                }
            });      
        });
    }

    function send_hapus_pendaftaran(pendaftaran_id, catatan) {
        let item;

        //get item to delete
        for (i of daftarpendaftaran) {
            if (i['pendaftaran_id'] == pendaftaran_id) {
                item = i;
                break;
            }
        }

        $("#loader").show();

        //build json
        let json = {};
        json["pendaftaran_id"] = pendaftaran_id;
        json['keterangan'] = catatan;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/siswa/hapus",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil menghapus pendaftaran. " + json.error)
                    return;
                }

                //for logging
                sekolah = item['sekolah'];
                jalur = item['jalur'];

                //get the return value and re-set the field 
                if (json.data === undefined || json.data == null) {
                    daftarpendaftaran = [];
                }
                else {
                    daftarpendaftaran = json.data;
                }
                update_hasil_layout();
                update_pendaftaran_layout();

                toastr.success("Berhasil menghapus pendaftaran di " +sekolah+ " (" +jalur+ ")");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil menghapus pendaftaran. " + textStatus);
                $("#loader").hide();
                return;
            }
        })
        .done(function(json) {
            $("#loader").hide();
        });

    }

    function send_ubah_pilihan(pendaftaran_id, jenis_pilihan) {
        let item;

        //get item to delete
        for (i of daftarpendaftaran) {
            if (i['pendaftaran_id'] == pendaftaran_id) {
                item = i;
                break;
            }
        }

        $("#loader").show();

        //build json
        let json = {};
        json["tipe"] = 'ubahpilihan';
        json["pendaftaran_id"] = pendaftaran_id;
        json['jenis_pilihan_baru'] = jenis_pilihan;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/siswa/ubah",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil mengubah urutan pilihan. " + json.error)
                    return;
                }

                //for logging
                sekolah = item['sekolah'];
                pilihan = item['label_jenis_pilihan'];

                //get the return value and re-set the field
                daftarpendaftaran = json.data;
                update_hasil_layout();
                update_pendaftaran_layout();

                pilihan_baru = '';
                for (i of daftarpendaftaran) {
                    if (i['pendaftaran_id'] == pendaftaran_id) {
                        pilihan_baru = i['label_jenis_pilihan'];
                        break;
                    }
                }

                toastr.success("Berhasil mengubah urutan pilihan di " +sekolah+ " (" +pilihan+ ") menjadi " +pilihan_baru);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mengubah urutan pilihan. " + textStatus);
                $("#loader").hide();
                return;
            }
        })
        .done(function(json) {
            $("#loader").hide();
        });
    }

    function send_ubah_jalur(pendaftaran_id, penerapan_id) {
        let item;

        //get item to delete
        for (i of daftarpendaftaran) {
            if (i['pendaftaran_id'] == pendaftaran_id) {
                item = i;
                break;
            }
        }

        $("#loader").show();

        //build json
        let json = {};
        json["tipe"] = 'ubahjalur';
        json["pendaftaran_id"] = pendaftaran_id;
        json['penerapan_id_baru'] = penerapan_id;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/siswa/ubah",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil mengubah jalur pendaftaran. " + json.error)
                    return;
                }

                //for logging
                sekolah = item['sekolah'];
                jalur = item['penerapan'];

                //get the return value and re-set the field
                daftarpendaftaran = json.data;
                update_hasil_layout();
                update_pendaftaran_layout();

                jalur_baru = '';
                for (item of daftarpendaftaran) {
                    if (item['tag'] == pendaftaran_id && item['pendaftaran'] == 1) {
                        jalur_baru = item['penerapan'];
                        break;
                    }
                }

                toastr.success("Berhasil mengubah jalur pendaftaran di " +sekolah+ " (" +jalur+ ") menjadi " +jalur_baru);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mengubah jalur pendaftaran. " + textStatus);
                $("#loader").hide();
                return;
            }
        })
        .done(function(json) {
            $("#loader").hide();
        });
    }

    function send_ubah_sekolah(pendaftaran_id, sekolah_id) {
        let item;

        //get item to delete
        for (i of daftarpendaftaran) {
            if (i['pendaftaran_id'] == pendaftaran_id) {
                item = i;
                break;
            }
        }

        $("#loader").show();

        //build json
        let json = {};
        json["tipe"] = 'ubahsekolah';
        json["pendaftaran_id"] = pendaftaran_id;
        json['sekolah_id_baru'] = sekolah_id;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/siswa/ubah",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil mengubah pilihan sekolah. " + json.error)
                    return;
                }

                //for logging
                sekolah = item['sekolah'];
                jalur = item['jalur'];

                //get the return value and re-set the field
                daftarpendaftaran = json.data;
                update_hasil_layout();
                update_pendaftaran_layout();

                jalur_baru = '';
                for (i of daftarpendaftaran) {
                    if (i['pendaftaran_id'] == pendaftaran_id) {
                        jalur_baru = i['jalur'];
                        break;
                    }
                }

                toastr.success("Berhasil mengubah pilihan sekolah dari " +sekolah+ " (" +jalur+ ") menjadi " +sekolah+ " (" +jalur_baru+ ")");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mengubah pilihan sekolah. " + textStatus);
                $("#loader").hide();
                return;
            }
        })
        .done(function(json) {
            $("#loader").hide();
        });
    }
    {/if}
    
</script>