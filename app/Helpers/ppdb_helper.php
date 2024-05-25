<?php

/**
 * PPDB Common Functions
 *
 */

if ( ! function_exists('audit_siswa'))
{

    function audit_siswa($peserta_didik_id, $action_type, $action_description, $columns, $new_values, $old_values) {

    }

    function audit_pendaftaran($pendaftaran_id, $action_type, $action_description, $columns, $new_values, $old_values) {
        
    }

    function print_json_error($error_message, $error_no = -1) { 
        $json = array();
        $json["status"] = 0;
        $json["error"] = $error_message;
        if (!empty($error_no)) {
            $json["errorno"] = $error_no;
        }

        //TODO: output properly
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE); 
        exit;
    }

    function print_json_output($data) {
        $json = array();
        $json["status"] = 1;
        if (!empty($data)) {
            $json['data'] = $data;
        }

        //TODO: output properly
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE); 
        exit;
    }

    function update_daftarpendaftaran($pendaftaran, $batasan_perubahan = null, $batasan_siswa = null) {

        $msiswa = new \App\Models\Ppdb\Siswa\Mprofilsiswa();

        foreach($pendaftaran as $k => $p) {
            $pendaftaran_id = $p['pendaftaran_id'];
            $penerapan_id = $p['penerapan_id'];

            //data for view
            if ($batasan_perubahan != null && $batasan_siswa != null) {
                $pendaftaran[$k]['url_ubah_pilihan'] = base_url() ."siswa/pendaftaran/ubahjenispilihan?pendaftaran_id=". $pendaftaran_id;
                $pendaftaran[$k]['url_ubah_jalur'] = base_url() ."siswa/pendaftaran/ubahjalur?pendaftaran_id==". $pendaftaran_id;
                $pendaftaran[$k]['url_ubah_sekolah'] = base_url() ."siswa/pendaftaran/ubahsekolah?pendaftaran_id=". $pendaftaran_id ."&penerapan_id=". $penerapan_id;
                $pendaftaran[$k]['url_hapus'] = base_url() ."siswa/pendaftaran/hapus?pendaftaran_id=". $pendaftaran_id;

                $pendaftaran[$k]['ubah_pilihan'] = ($batasan_perubahan['ubah_pilihan'] > 0);
                $pendaftaran[$k]['ubah_jalur'] = ($batasan_perubahan['ubah_jalur'] > 0);
                $pendaftaran[$k]['ubah_sekolah'] = ($batasan_perubahan['ubah_sekolah'] > 0);
                $pendaftaran[$k]['hapus_pendaftaran'] = ($batasan_perubahan['hapus_pendaftaran'] > 0);

                $pendaftaran[$k]['allow_ubah_pilihan'] = !(($batasan_siswa['ubah_pilihan']>=$batasan_perubahan['ubah_pilihan']&&$p['jenis_pilihan']!=0)||$p['pendaftaran']!=1);
                $pendaftaran[$k]['allow_ubah_jalur'] = !(($batasan_siswa['ubah_jalur']>=$batasan_perubahan['ubah_jalur'])||$p['pendaftaran']!=1);
                $pendaftaran[$k]['allow_ubah_sekolah'] = !(($batasan_siswa['ubah_sekolah']>=$batasan_perubahan['ubah_sekolah'])||$p['pendaftaran']!=1);
                $pendaftaran[$k]['allow_hapus'] = !(($batasan_siswa['hapus_pendaftaran']>=$batasan_perubahan['hapus_pendaftaran'])||$p['pendaftaran']!=1);
            }
            else {
                $pendaftaran[$k]['allow_edit'] = 0;
            }

            //perankingan
            if ($p['peringkat'] = 0) $pendaftaran[$k]['label_peringkat'] = "Belum Ada";
            else if ($p['peringkat'] == -1) $pendaftaran[$k]['label_peringkat'] = "Tidak Ada";
            else if ($p['status_penerimaan_final'] == 2 || $p['status_penerimaan_final'] == 4) $pendaftaran[$k]['label_peringkat'] = "Tidak Dihitung";
            else if ($p['status_penerimaan_final'] == 1 || $p['status_penerimaan_final'] == 3) $pendaftaran[$k]['label_peringkat'] = $p['peringkat_final'];
            else $pendaftaran[$k]['label_peringkat'] = "Belum Ada";

            $pendaftaran[$k]['url_perankingan'] = base_url() ."home/peringkat?sekolah_id=" .$p['sekolah_id']. "&bentuk=" .$p['bentuk'];

            //status penerimaan
            if ($p['status_penerimaan'] == 0) $pendaftaran[$k]['class_status_penerimaan'] = "status-tidak-dihitung";
            else if ($p['status_penerimaan'] == 1) $pendaftaran[$k]['class_status_penerimaan'] = "status-masuk-kuota";
            else if ($p['status_penerimaan'] == 2) $pendaftaran[$k]['class_status_penerimaan'] = "status-tidak-masuk-kuota";
            else if ($p['status_penerimaan'] == 3) $pendaftaran[$k]['class_status_penerimaan'] = "status-daftar-tunggu";
            else if ($p['status_penerimaan'] == 4) $pendaftaran[$k]['class_status_penerimaan'] = "status-tidak-dihitung";
            else $pendaftaran[$k]['class_status_penerimaan'] = "status-tidak-dihitung";

            if ($p['status_penerimaan'] == 0) $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-search";
            else if ($p['status_penerimaan'] == 1) $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-check";
            else if ($p['status_penerimaan'] == 2) $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-info-sign";
            else if ($p['status_penerimaan'] == 3) $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-check";
            else if ($p['status_penerimaan'] == 4) $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-info-sign";
            else $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-search";

            if ($p['status_penerimaan'] == 0) $pendaftaran[$k]['label_status_penerimaan'] = "Dalam Proses Verifikasi";
            else if ($p['status_penerimaan'] == 1) $pendaftaran[$k]['label_status_penerimaan'] = "Masuk Kuota";
            else if ($p['status_penerimaan'] == 2) $pendaftaran[$k]['label_status_penerimaan'] = "Tidak Masuk Kuota";
            else if ($p['status_penerimaan'] == 3) $pendaftaran[$k]['label_status_penerimaan'] = "Daftar Tunggu";
            else if ($p['status_penerimaan'] == 4) $pendaftaran[$k]['label_status_penerimaan'] = "Masuk Kuota " .$p['label_masuk_pilihan'];
            else $pendaftaran[$k]['label_status_penerimaan'] = "Dalam Proses Seleksi";

            //kelengkapan
            $kelengkapan = $msiswa->tcg_kelengkapanpendaftaran($pendaftaran_id);
            foreach ($kelengkapan as $k2 => $i) {
                $kelengkapan[$k2]['status_ok'] = ($i['verifikasi']==1);
                $kelengkapan[$k2]['status_notok'] = ($i['verifikasi']==2);
                $kelengkapan[$k2]['status_tidakada'] = ($i['verifikasi']==3 || ($i['verifikasi']==0 && $i['wajib']==0));
                $kelengkapan[$k2]['status_dalamproses'] = (!$kelengkapan[$k2]['status_ok'] && !$kelengkapan[$k2]['status_notok'] && !$kelengkapan[$k2]['status_tidakada']);
                $kelengkapan[$k2]['kondisi_khusus'] = ($i['kondisi_khusus']!=0);
            }
            $pendaftaran[$k]['kelengkapan'] = $kelengkapan;
            
            // //berkas fisik
            // $berkas = $this->Msiswa->tcg_kelengkapanpendaftaran_berkasfisik($pendaftaran_id)->getResultArray();
            // foreach ($berkas as $k2 => $i) {
            //     $berkas[$k2]['status_ok'] = ($i['berkas_fisik']==1);
            //     $berkas[$k2]['status_notok'] = ($i['berkas_fisik']!=1);
            //     $berkas[$k2]['kondisi_khusus'] = ($i['kondisi_khusus']!=0);
            // }
            // $pendaftaran[$k]['berkasfisik'] = $berkas;

            //skoring
            $skoring = $msiswa->tcg_nilaiskoring($pendaftaran_id);
            $pendaftaran[$k]['skoring'] = $skoring;

            //total skoring
            $totalskoring = 0;
            foreach($skoring as $k2 => $s) {
                $val = floatval($s['nilai']);
                $totalskoring += $val;
            }
            $pendaftaran[$k]['totalskoring'] = number_format($totalskoring,2,".","");
        }

        return $pendaftaran;
    }    

}