<?php

use App\Libraries\Uploader;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('uploader_upload'))
{
    function _uploader_upload($upload) {
        $uploader = new Uploader();
        return $uploader->upload($upload);
    }
}

if ( ! function_exists('uploader_imageresize'))
{
    function _uploader_imageresize($imageResourceId,$width,$height,$targetWidth,$targetHeight) {
        $uploader = new Uploader();
        return $uploader->imageresize($imageResourceId,$width,$height,$targetWidth,$targetHeight);
    }
}

if ( ! function_exists('uploader_simpan'))
{
    function _uploader_simpan($filename, $filesize, $filepath, $webpath, $thumbpath) {
        $uploader = new Uploader();
        return $uploader->simpan($filename, $filesize, $filepath, $webpath, $thumbpath);
    }
}

if ( ! function_exists('uploader_imagerotate'))
{
    function _uploader_imagerotate($file, $newfile, $degree) {
        $uploader = new Uploader();
        return $uploader->imagerotate($file, $newfile, $degree);
    }
}

if ( ! function_exists('uploader_load_setting'))
{
    function _uploader_load_setting() {
        $uploader = new Uploader();
        return $uploader->load_setting();
    }
}

?>        

        