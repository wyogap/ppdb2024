<?php

/**
 * PPDB Common Functions
 *
 */

if ( ! function_exists('audit_siswa'))
{

    function audit_siswa($profil, $action_type, $action_description, $columns=null, $new_values=null, $old_values=null) {
        $module = 'ppdb';
        $table = 'tcg_peserta_didik';
        $key = $profil['peserta_didik_id'];
        $key2 = $profil['nisn'];

        if ($columns == null) {
            $column_text = null;
            $new_values_text = null;
            $old_values_text = null;
        }
        else {
            $column_text = json_encode($columns, JSON_INVALID_UTF8_IGNORE);
        
            $arr1 = $arr2 = array();
            foreach($columns as $k) {
                $arr1[$k] = empty($new_values[$k]) ? '' : $new_values[$k];
                if (!empty($old_values))
                    $arr2[$k] = empty($old_values[$k]) ? '' : $old_values[$k];
            }

            $new_values_text = json_encode($arr1, JSON_INVALID_UTF8_IGNORE);
            $old_values_text = json_encode($arr2, JSON_INVALID_UTF8_IGNORE);
        }

        $session = \Config\Services::session();
        $db = \Config\Database::connect();

        $valuepair = array (
            'module' => $module,
            'table_name' => $table,
            'reference_id' => $key,
            'reference_id2' => $key2,
            'action_name' => $action_type,
            'long_description' => $action_description,
            'col_names' => $column_text,
            'col_values' => $new_values_text,
            'old_values' => $old_values_text,
            'created_by' => $session->get("user_id")
        );

        $builder = $db->table('dbo_audit_trails');
        $builder->insert($valuepair);
    }

    function audit_pendaftaran($pendaftaran, $action_type, $action_description, $columns=null, $new_values=null, $old_values=null) {
        $module = 'ppdb';
        $table = 'tcg_pendaftaran';
        $key = $pendaftaran['peserta_didik_id'];
        if (!empty($pendaftaran['nisn'])) {
            $key2 = $pendaftaran['nisn'];
        } else {
            $msiswa = new \App\Models\Ppdb\Siswa\Mprofilsiswa();
            $profil = $msiswa->tcg_profilsiswa($key);
            $key2 = $profil['nisn'];
        }

        if ($columns == null) {
            $column_text = null;
            $new_values_text = null;
            $old_values_text = null;
        }
        else {
            $column_text = json_encode($columns, JSON_INVALID_UTF8_IGNORE);
        
            $arr1 = $arr2 = array();
            foreach($columns as $k) {
                $arr1[$k] = empty($new_values[$k]) ? '' : $new_values[$k];
                if (!empty($old_values))
                    $arr2[$k] = empty($old_values[$k]) ? '' : $old_values[$k];
            }

            $new_values_text = json_encode($arr1, JSON_INVALID_UTF8_IGNORE);
            $old_values_text = json_encode($arr2, JSON_INVALID_UTF8_IGNORE);
        }

        $session = \Config\Services::session();
        $db = \Config\Database::connect();

        $valuepair = array (
            'module' => $module,
            'table_name' => $table,
            'reference_id' => $key,
            'reference_id2' => $key2,
            'action_name' => $action_type,
            'long_description' => $action_description,
            'col_names' => $column_text,
            'col_values' => $new_values_text,
            'old_values' => $old_values_text,
            'created_by' => $session->get("user_id")
        );

        $builder = $db->table('dbo_audit_trails');
        $builder->insert($valuepair);
    }

    function audit_trail($table, $key, $action_type, $action_description, $columns=null, $new_values=null, $old_values=null, $additional_key = null) {
        if ($columns == null) {
            $column_text = null;
            $new_values_text = null;
            $old_values_text = null;
        }
        else {
            $column_text = implode(', ', $columns);
        
            $arr1 = $arr2 = array();
            foreach($columns as $k) {
                $arr1[$k] = empty($new_values[$k]) ? '' : $new_values[$k];
                if (!empty($old_values))
                    $arr2[$k] = empty($old_values[$k]) ? '' : $old_values[$k];
             }

            $new_values_text = implode(', ', $arr1);
            $old_values_text = implode(', ', $arr2);
        }        

        $session = \Config\Services::session();
        $db = \Config\Database::connect();

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $key,
            'reference_id2' => $additional_key,
            'action_name' => $action_type,
            'long_description' => $action_description,
            'col_names' => $column_text,
            'col_values' => $new_values_text,
            'old_values' => $old_values_text,
            'created_by' => $session->get("user_id")
        );

        $builder = $db->table('dbo_audit_trails');
        $builder->insert($valuepair);
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

    function print_json_output($data, $enforce = 0) {
        $json = array();
        $json["status"] = 1;
        if (!empty($data) || $enforce) {
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE); 
        exit;

        // //TODO: output properly
        // if (empty($data) || count($data) <= 5000) {
        //     echo json_encode($json, JSON_INVALID_UTF8_IGNORE); 
        //     exit;
        // }
        // else {
        //     //manual echo json file to avoid memory exhausted
        //     echo '{"status":"1", "data":[';
        //     $first = true;
        //     foreach($data as $row)
        //     {
        //         if ($first) {
        //             echo json_encode($row, JSON_INVALID_UTF8_IGNORE);
        //             $first = false;
        //         } else {
        //             echo ",". json_encode($row, JSON_INVALID_UTF8_IGNORE);
        //         }
        //     }
        //     echo ']}';
        //     exit;
        // }
        
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