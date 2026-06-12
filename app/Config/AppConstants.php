<?php

defined('APP_NAME')             || define('APP_NAME', 'System Penerimaan Murid Baru Kabupaten Kebumen');   
defined('APP_SHORT_NAME')       || define('APP_SHORT_NAME', "SPMB");   
defined('APP_LOGO')             || define('APP_LOGO', "images/checklist.png");   
defined('APP_ICON')             || define('APP_ICON', "images/checklist.png");   
defined('APP_VERSION')          || define('APP_VERSION', "1.0"); 

defined('APP_LOCALE')                       OR define('APP_LOCALE', 'IND');
defined('APP_TIMEZONE')                     OR define('APP_TIMEZONE', 'Asia/Jakarta');
defined('APP_GMTOFFSET')                    OR define('APP_GMTOFFSET', '07:00');

defined('UPLOAD_MAX_SIZE_MB')               OR define('UPLOAD_MAX_SIZE_MB', '20');
defined('UPLOAD_FILE_TYPES')                OR define('UPLOAD_FILE_TYPES', 'xlsx, xls');

defined('CURRENCY_PREFIX')                  OR define('CURRENCY_PREFIX', 'SGD');
defined('CURRENCY_PRECISION')               OR define('CURRENCY_PRECISION', '2');
defined('THOUSAND_SEPARATOR')               OR define('THOUSAND_SEPARATOR', ',');
defined('DECIMAL_SEPARATOR')                OR define('DECIMAL_SEPARATOR', '.');

defined('COOKIE_USER_ID')                   OR define('COOKIE_USER_ID', 'c_user');
defined('COOKIE_USER_TOKEN')                OR define('COOKIE_USER_TOKEN', 'xs');
defined('COOKIE_USER_REFERRER')             OR define('COOKIE_USER_REFERRER', 'ref');

defined('BRUTE_FORCE_DETECTION')            OR define('BRUTE_FORCE_DETECTION', 1);

defined('XLSX_FILE_TYPE')             || define('XLSX_FILE_TYPE', 'Xlsx');
defined('XLS_FILE_TYPE')              || define('XLS_FILE_TYPE', 'Xls');

//log level in monolog
defined('APP_LOGDEBUG')                || define('APP_LOGDEBUG', 100);
defined('APP_LOGINFO')                 || define('APP_LOGINFO', 200);
defined('APP_LOGNOTICE')               || define('APP_LOGNOTICE', 250);
defined('APP_LOGWARNING')              || define('APP_LOGWARNING', 300);
defined('APP_LOGERROR')                || define('APP_LOGERROR', 400);
defined('APP_LOGCRITICAL')             || define('APP_LOGCRITICAL', 500);
defined('APP_LOGALERT')                || define('APP_LOGALERT', 550);
defined('APP_LOGEMERGENCY')            || define('APP_LOGEMERGENCY', 600);

//application log level
defined('APP_LOGLEVEL')                || define('APP_LOGLEVEL', APP_LOGDEBUG);

//DEBUGGING SESSION?
defined('APP_DEBUGGING')               || define('APP_DEBUGGING', 0);

/**
 * ------------------------------------------------------------------------
 * Tahun ajaran aktif
 * ------------------------------------------------------------------------
 */
defined('TAHUN_AJARAN_ID') || define('TAHUN_AJARAN_ID', 2026);
defined('NAMA_TAHUN_AJARAN') || define('NAMA_TAHUN_AJARAN', '2026/2027');

/*
|--------------------------------------------------------------------------
| SQL Procedures
|--------------------------------------------------------------------------
|
| Keep it as constant here to provide flexibility to use different versions
|
*/
defined('SQL_PROCESS_PENDAFTARAN')          OR define('SQL_PROCESS_PENDAFTARAN', 'ppdb2026_prosespendaftaran');
defined('SQL_CABUT_BERKAS')                 OR define('SQL_CABUT_BERKAS', 'ppdb2026_cabutberkas');                  //hapus semua pendaftaran untuk satu siswa dan blok untuk daftar lagi
defined('SQL_REGISTRASI')                   OR define('SQL_REGISTRASI', 'ppdb2026_registrasi');                  //hapus semua pendaftaran untuk satu siswa dan blok untuk daftar lagi
//defined('SQL_RESET_PENDAFTARANSISWA')       OR define('SQL_RESET_PENDAFTARANSISWA', 'ppdb2023_resetpendaftaran');   //hapus semua pendaftaran untuk satu siswa
defined('SQL_HAPUS_PENDAFTARAN')            OR define('SQL_HAPUS_PENDAFTARAN', 'ppdb2026_hapuspendaftaran');        //hapur satu pendaftaran
defined('SQL_HITUNGSKOR')                   OR define('SQL_HITUNGSKOR', 'ppdb2026_hitungskor');

defined('SQL_UBAH_JENISPILIHAN')            OR define('SQL_UBAH_JENISPILIHAN', 'ppdb2026_ubah_jenispilihan');
defined('SQL_UBAH_PILIHANSEKOLAH')          OR define('SQL_UBAH_PILIHANSEKOLAH', 'ppdb2026_ubah_pilihansekolah');
defined('SQL_UBAH_JALUR')                   OR define('SQL_UBAH_JALUR', 'ppdb2026_ubah_jalur');

defined('SQL_PILIHSEKOLAH_SEKOLAH')             OR define('SQL_PILIHSEKOLAH_SEKOLAH', 'ppdb2026_pilihsekolah_sekolah');
defined('SQL_PILIHSEKOLAH_JENISPILIHAN')        OR define('SQL_PILIHSEKOLAH_JENISPILIHAN', 'ppdb2026_pilihsekolah_jenispilihan');
defined('SQL_UBAHPILIHAN_JENISPILIHAN')         OR define('SQL_UBAHPILIHAN_JENISPILIHAN', 'ppdb2026_ubahpilihan_jenispilihan');
defined('SQL_SEBARAN_SEKOLAH')                  OR define('SQL_SEBARAN_SEKOLAH', 'ppdb2026_sebaransekolah');

//defined('SQL_UBAH_KELENGKAPANBERKAS')       OR define('SQL_UBAH_KELENGKAPANBERKAS', 'ppdb2026_ubah_kelengkapanberkas');     //set status kelengkapan dengan nilai tertentu (lengkap, belum lengkap, belum verifikasi)
defined('SQL_CEK_KELENGKAPANBERKAS')        OR define('SQL_CEK_KELENGKAPANBERKAS', 'ppdb2026_cek_kelengkapanberkas');   //cek dan update status kelengkapan berkas sesuai kelengkapan data pendukung
//defined('SQL_BERKAS_PENDAFTARAN')           OR define('SQL_BERKAS_PENDAFTARAN', 'ppdb_berkas_pendaftaran');             //daftar berkas pendaftaran
//defined('SQL_SIMPAN_DOKUMEN')               OR define('SQL_SIMPAN_DOKUMEN', 'ppdb2024_simpan_dokumen_pendukung');             //simpan berkas pendaftaran
defined('SQL_GENERATE_DOK_PENDUKUNG')       OR define('SQL_GENERATE_DOK_PENDUKUNG', 'ppdb2026_generate_dokumen_pendukung');     //generate dok pendukung based on profil and pilihan pendaftaran

defined('SQL_HAPUS_PENERIMAAN_SD')          OR define('SQL_HAPUS_PENERIMAAN_SD', 'ppdb2026_sd_hapus_pendaftaran');
defined('SQL_PENERIMAAN_SD')                OR define('SQL_PENERIMAAN_SD', 'ppdb2026_sd_tambah_pendaftaran');
defined('SQL_TAMBAH_SISWA_SD')              OR define('SQL_TAMBAH_SISWA_SD', 'ppdb2026_sd_tambah_siswa');
defined('SQL_HITUNGSKOR_SD')                OR define('SQL_HITUNGSKOR_SD', 'ppdb2026_sd_hitungskor');

// defined('SQL_UBAH_DATA')                    OR define('SQL_UBAH_DATA', 'ppdb2023_ubah_data');
// defined('SQL_UBAH_NILAIKELULUSAN')          OR define('SQL_UBAH_NILAIKELULUSAN', 'ppdb2022_ubah_nilaikelulusan');
// defined('SQL_UBAH_NILAISEMESTER')           OR define('SQL_UBAH_NILAISEMESTER', 'ppdb2022_ubah_nilaisemester');
// defined('SQL_UBAH_NILAIUSBN')               OR define('SQL_UBAH_NILAIUSBN', 'ppdb2022_ubah_nilaiusbn');
// defined('SQL_UBAH_PRESTASI')                OR define('SQL_UBAH_PRESTASI', 'ppdb2022_ubah_prestasi');

defined('ROLEID_SYSADMIN')                  OR define('ROLEID_SYSADMIN', '1');
defined('ROLEID_ADMIN')                     OR define('ROLEID_ADMIN', '2');

defined('ROLEID_SISWA')                     OR define('ROLEID_SISWA', '11');
defined('ROLEID_SEKOLAH')                   OR define('ROLEID_SEKOLAH', '12');
defined('ROLEID_DINAS')                     OR define('ROLEID_DINAS', '13');
defined('ROLEID_DAPODIK')                   OR define('ROLEID_DAPODIK', '14');

//defined('JALURID_INKLUSI')                  OR define('JALURID_INKLUSI', '7');
defined('JALURID_INKLUSI')                  OR define('JALURID_INKLUSI', '9');      //inklusi digabung dengan jalur afirmasi karena banyak kesamaan persyaratan dan prosesnya
defined('JALURID_ZONASI')                   OR define('JALURID_ZONASI', '1');
defined('JALURID_PRESTASI')                 OR define('JALURID_PRESTASI', '2');
defined('JALURID_AFIRMASI')                 OR define('JALURID_AFIRMASI', '9');
defined('JALURID_MUTASI')                   OR define('JALURID_MUTASI', '3');

defined('DOCID_AKTE')                       OR define('DOCID_AKTE', '5');
defined('DOCID_KK')                         OR define('DOCID_KK', '6');
defined('DOCID_SUKET_DOMISILI')             OR define('DOCID_SUKET_DOMISILI', '19');
defined('DOCID_IJAZAH_SKL')                 OR define('DOCID_IJAZAH_SKL', '2');
defined('DOCID_HASIL_UN')                   OR define('DOCID_HASIL_UN', '3');
defined('DOCID_PRESTASI')                   OR define('DOCID_PRESTASI', '8');
defined('DOCID_KIP')                        OR define('DOCID_KIP', '16');
defined('DOCID_SUKET_BDT')                  OR define('DOCID_SUKET_BDT', '20');
defined('DOCID_SUKET_INKLUSI')              OR define('DOCID_SUKET_INKLUSI', '9');
defined('DOCID_SUKET_KEBENARAN_DOK')        OR define('DOCID_SUKET_KEBENARAN_DOK', '21');
defined('DOCID_RAPOR_KELAS6')               OR define('DOCID_RAPOR_KELAS6', '26');
defined('DOCID_RAPOR_5SEMESTER')            OR define('DOCID_RAPOR_5SEMESTER', '27');
defined('DOCID_AKADEMIK')                   OR define('DOCID_AKADEMIK', '28');
defined('DOCID_ORGANISASI')                 OR define('DOCID_ORGANISASI', '29');

defined('TAHAPANID_SOSIALISASI')            OR define('TAHAPANID_SOSIALISASI', '1');
defined('TAHAPANID_REGISTRASI')             OR define('TAHAPANID_REGISTRASI', '2');
defined('TAHAPANID_PENDAFTARAN')            OR define('TAHAPANID_PENDAFTARAN', '3');
defined('TAHAPANID_VERIFIKASI')             OR define('TAHAPANID_VERIFIKASI', '4');
defined('TAHAPANID_PENGUMUMAN')             OR define('TAHAPANID_PENGUMUMAN', '4');
defined('TAHAPANID_DAFTARULANG')            OR define('TAHAPANID_DAFTARULANG', '6');
defined('TAHAPANID_SUSULAN')                OR define('TAHAPANID_SUSULAN', '7');
defined('TAHAPANID_PERBAIKANDATA')          OR define('TAHAPANID_PERBAIKANDATA', '8');

//defined('PUTARAN_SD')               OR define('PUTARAN_SD', '4');
//defined('PENERAPANID_SD')           OR define('PENERAPANID_SD', '301');
defined('JENJANGID_SD')             OR define('JENJANGID_SD', '2');
defined('JENJANGID_SMP')            OR define('JENJANGID_SMP', '3');
defined('JENJANGID_TK')             OR define('JENJANGID_TK', '5');

defined('ASALDATA_REGISTRASI')      OR define('ASALDATA_REGISTRASI', '1');
defined('ASALDATA_DAPODIK')         OR define('ASALDATA_DAPODIK', '2');
defined('ASALDATA_PENERIMAANSD')    OR define('ASALDATA_PENERIMAANSD', '4');

defined('TIPESKORING_AKADEMIK')             OR define('TIPESKORING_AKADEMIK', '3');
defined('TIPESKORING_PRESTASI')             OR define('TIPESKORING_PRESTASI', '8');
defined('TIPESKORING_ORGANISASI')           OR define('TIPESKORING_ORGANISASI', '9');

defined('COL_SOFT_DELETE')                  OR define('COL_SOFT_DELETE', 'is_deleted');
defined('COL_CREATED_ON')                   OR define('COL_CREATED_ON', 'created_on');
defined('COL_CREATED_BY')                   OR define('COL_CREATED_BY', 'created_by');
defined('COL_UPDATED_ON')                   OR define('COL_UPDATED_ON', 'updated_on');
defined('COL_UPDATED_BY')                   OR define('COL_UPDATED_BY', 'updated_by');
defined('COL_EXPIRED_ON')                   OR define('COL_EXPIRED_ON', 'expired_date');

defined('__DEBUGGING__')                    OR define('__DEBUGGING__', 0);
defined('__USE_CDN__')                      OR define('__USE_CDN__', 1);


defined('WA_COUNTRYCODE')                   OR define('WA_COUNTRYCODE', '62');
defined('WA_APIKEY')                        OR define('WA_APIKEY', 'T3DOAJFMEU9YNUWE');
defined('WA_NUMBERKEY')                     OR define('WA_NUMBERKEY', 'PLDkC7q7bF7ff9fD');

defined('DEFAULT_NPSN_SEKOLAH')             OR define('DEFAULT_NPSN_SEKOLAH', '00000000');


?>