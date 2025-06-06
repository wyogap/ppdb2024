<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);

/*
|--------------------------------------------------------------------------
| SQL Procedures
|--------------------------------------------------------------------------
|
| Keep it as constant here to provide flexibility to use different versions
|
*/
defined('SQL_PROCESS_PENDAFTARAN')          OR define('SQL_PROCESS_PENDAFTARAN', 'ppdb2025_prosespendaftaran');
defined('SQL_CABUT_BERKAS')                 OR define('SQL_CABUT_BERKAS', 'ppdb2025_cabutberkas');                  //hapus semua pendaftaran untuk satu siswa dan blok untuk daftar lagi
defined('SQL_REGISTRASI')                   OR define('SQL_REGISTRASI', 'ppdb2025_registrasi');                  //hapus semua pendaftaran untuk satu siswa dan blok untuk daftar lagi
//defined('SQL_RESET_PENDAFTARANSISWA')       OR define('SQL_RESET_PENDAFTARANSISWA', 'ppdb2023_resetpendaftaran');   //hapus semua pendaftaran untuk satu siswa
defined('SQL_HAPUS_PENDAFTARAN')            OR define('SQL_HAPUS_PENDAFTARAN', 'ppdb2025_hapuspendaftaran');        //hapur satu pendaftaran
defined('SQL_HITUNGSKOR')                   OR define('SQL_HITUNGSKOR', 'ppdb2025_hitungskor');

defined('SQL_UBAH_JENISPILIHAN')            OR define('SQL_UBAH_JENISPILIHAN', 'ppdb2025_ubah_jenispilihan');
defined('SQL_UBAH_PILIHANSEKOLAH')          OR define('SQL_UBAH_PILIHANSEKOLAH', 'ppdb2025_ubah_pilihansekolah');
defined('SQL_UBAH_JALUR')                   OR define('SQL_UBAH_JALUR', 'ppdb2025_ubah_jalur');

defined('SQL_PILIHSEKOLAH_SEKOLAH')             OR define('SQL_PILIHSEKOLAH_SEKOLAH', 'ppdb2025_pilihsekolah_sekolah');
defined('SQL_PILIHSEKOLAH_JENISPILIHAN')        OR define('SQL_PILIHSEKOLAH_JENISPILIHAN', 'ppdb2025_pilihsekolah_jenispilihan');
defined('SQL_UBAHPILIHAN_JENISPILIHAN')         OR define('SQL_UBAHPILIHAN_JENISPILIHAN', 'ppdb2025_ubahpilihan_jenispilihan');
defined('SQL_SEBARAN_SEKOLAH')                  OR define('SQL_SEBARAN_SEKOLAH', 'ppdb2025_sebaransekolah');

defined('SQL_UBAH_KELENGKAPANBERKAS')       OR define('SQL_UBAH_KELENGKAPANBERKAS', 'ppdb2025_ubah_kelengkapanberkas');     //set status kelengkapan dengan nilai tertentu (lengkap, belum lengkap, belum verifikasi)
defined('SQL_CEK_KELENGKAPANBERKAS')        OR define('SQL_CEK_KELENGKAPANBERKAS', 'ppdb2025_cek_kelengkapanberkas');   //cek dan update status kelengkapan berkas sesuai kelengkapan data pendukung
//defined('SQL_BERKAS_PENDAFTARAN')           OR define('SQL_BERKAS_PENDAFTARAN', 'ppdb_berkas_pendaftaran');             //daftar berkas pendaftaran
//defined('SQL_SIMPAN_DOKUMEN')               OR define('SQL_SIMPAN_DOKUMEN', 'ppdb2024_simpan_dokumen_pendukung');             //simpan berkas pendaftaran

defined('SQL_HAPUS_PENERIMAAN_SD')          OR define('SQL_HAPUS_PENERIMAAN_SD', 'ppdb2025_sd_hapus_penerimaan');
defined('SQL_PENERIMAAN_SD')                OR define('SQL_PENERIMAAN_SD', 'ppdb2025_sd_tambah_penerimaan');
defined('SQL_TAMBAH_SISWA_SD')              OR define('SQL_TAMBAH_SISWA_SD', 'ppdb2025_sd_tambah_siswa');

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

defined('JALURID_INKLUSI')                  OR define('JALURID_INKLUSI', '7');
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

defined('APP_LOCALE')                       OR define('APP_LOCALE', 'IND');
defined('APP_TIMEZONE')                     OR define('APP_TIMEZONE', 'Asia/Jakarta');

defined('COL_SOFT_DELETE')                  OR define('COL_SOFT_DELETE', 'is_deleted');
defined('COL_CREATED_ON')                   OR define('COL_CREATED_ON', 'created_on');
defined('COL_CREATED_BY')                   OR define('COL_CREATED_BY', 'created_by');
defined('COL_UPDATED_ON')                   OR define('COL_UPDATED_ON', 'updated_on');
defined('COL_UPDATED_BY')                   OR define('COL_UPDATED_BY', 'updated_by');
defined('COL_EXPIRED_ON')                   OR define('COL_EXPIRED_ON', 'expired_date');

defined('__DEBUGGING__')                    OR define('__DEBUGGING__', 1);
defined('__USE_CDN__')                      OR define('__USE_CDN__', 1);
