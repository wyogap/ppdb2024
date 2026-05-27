<?php

defined('ERRNO_INVALID_QUERY')                  || define('ERRNO_INVALID_QUERY', 1001);
defined('ERRNO_READONLY_COLUMN')                || define('ERRNO_READONLY_COLUMN', 1002);
defined('ERRNO_MANDATORY_COLUMN')               || define('ERRNO_MANDATORY_COLUMN', 1003);
defined('ERRNO_INVALID_ID')                     || define('ERRNO_INVALID_ID', 1004);
defined('ERRNO_LOCKED_RECORD')                  || define('ERRNO_LOCKED_RECORD', 1005);
defined('ERRNO_NO_UPDATE')                      || define('ERRNO_NO_UPDATE', 1006);
defined('ERRNO_NO_DELETE')                      || define('ERRNO_NO_DELETE', 1007);
defined('ERRNO_NO_INSERT')                      || define('ERRNO_NO_INSERT', 1008);
defined('ERRNO_INSERT_ERROR')                   || define('ERRNO_INSERT_ERROR', 1009);
defined('ERRNO_ATTACHMENT_ERROR')               || define('ERRNO_ATTACHMENT_ERROR', 1010);
defined('ERRNO_NO_IMPORT')                      || define('ERRNO_NO_IMPORT', 1011);
defined('ERRNO_DUPLICATE')                      || define('ERRNO_DUPLICATE', 1012);

defined('ERRNO_UPLOAD_NOTFOUND')                || define('ERRNO_UPLOAD_NOTFOUND', 1101);
defined('ERRNO_UPLOAD_MAXSIZE')                 || define('ERRNO_UPLOAD_MAXSIZE', 1102);
defined('ERRNO_UPLOAD_NOTCOMPLETED')            || define('ERRNO_UPLOAD_NOTCOMPLETED', 1103);
defined('ERRNO_UPLOAD_CONFIG')                  || define('ERRNO_UPLOAD_CONFIG', 1104);
defined('ERRNO_UPLOAD_FILETYPE')                || define('ERRNO_UPLOAD_FILETYPE', 1105);
defined('ERRNO_UPLOAD_SAVING')                  || define('ERRNO_UPLOAD_SAVING', 1106);

defined('ERRMSG_UPLOAD_NOTFOUND')             || define('ERRMSG_UPLOAD_NOTFOUND', 'Unggahan tidak ditemukan');
defined('ERRMSG_UPLOAD_MAXSIZE_PHP')          || define('ERRMSG_UPLOAD_MAXSIZE_PHP', "Unggahan melebihi maxsize di php.ini");
defined('ERRMSG_UPLOAD_NOTCOMPLETED')         || define('ERRMSG_UPLOAD_NOTCOMPLETED', "Unggahan tidak selesai");
defined('ERRMSG_UPLOAD_CONFIG')               || define('ERRMSG_UPLOAD_CONFIG', "Kesalahan di konfigurasi sistem");
defined('ERRMSG_UPLOAD_ALLOWED_FILETYPE')     || define('ERRMSG_UPLOAD_ALLOWED_FILETYPE', "Unggahan yang diperbolehkan: ");
defined('ERRMSG_UPLOAD_MAXSIZE')              || define('ERRMSG_UPLOAD_MAXSIZE', "Unggahan harus dibawah ");
defined('ERRMSG_UPLOAD_FILETYPE')             || define('ERRMSG_UPLOAD_FILETYPE', "Tipe gambar tidak dikenal");
defined('ERRMSG_UPLOAD_SAVING')               || define('ERRMSG_UPLOAD_SAVING', "Tidak berhasil menyimpan unggahan");

?>