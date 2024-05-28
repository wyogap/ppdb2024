<?php
namespace App\Libraries;

require_once ROOTPATH .'vendor/autoload.php';
   
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class S3Uploader
{
    public static $CREDENTIAL_KEY = 'd53b114f9becbcf00EWH';
    public static $CREDENTIAL_SECRET = 'nWJFFZBf7PcwU77fnLGVBbaBq6QAeyG6Cg2XFtIk';
    public static $BUCKET = 'ppdb2022';

    // protected static $SETTING_TABLE = 'dbo_settings';

    // protected $db = null;
    // protected $session = null;

    // public function __construct()
    // {
    //     //init
    //     $this->db = \Config\Database::connect();
    //     $this->session = \Config\Services::session();
    // }

    // public function get($name, $default="", $group=null) {
    //     $ci =& get_instance();

    //     $filters = array (
    //         'name'          => $name,
    //         'is_deleted'    => 0
    //     );
    //     if ($group != null)     $filters['group'] = $group;

    //     $ci->db->select('value');
    //     $arr = $ci->db->get_where(static::$SETTING_TABLE, $filters)->getRowArray();
    //     if ($arr == null) {
    //         return $default;
    //     }

    //     return $arr['value'];
    // }

    public function store($upload)
    {  
  
           // Instantiate an Amazon S3 client.
           $s3Client = new S3Client([
                'version' => 'latest',
                //'region'  => 'YOUR_AWS_REGION',
                'credentials' => [
                    'key'    => S3Uploader::$CREDENTIAL_KEY,
                    'secret' => S3Uploader::$CREDENTIAL_SECRET
                ]
           ]);
  
           $bucket = S3Uploader::$BUCKET;
           //$file_Path = FCPATH . 'images/icon/pdf_'. time().'_'. rand(). '.png';
           $file_Path = $upload;
           $key = mb_basename($file_Path);
  
           try {
               $result = $s3Client->putObject([
                   'Bucket' => $bucket,
                   'Key'    => $key,
                   'Body'   => fopen($file_Path, 'r'),
                   'ACL'    => 'public-read', // make file 'public'
               ]);
             $msg = 'File has been uploaded';
           } catch (S3Exception $e) {
               //$msg = 'File has been uploaded';
               echo $e->getMessage();
           }

           $msg = 'File has been uploaded';
  
    }

    // public function set($name, $value, $group=null) {
    //     $ci =& get_instance();
        
    //     $values = array (
    //         'value'         => $value,
    //         'updated_on'    => date('Y/m/d H:i:s'),     //utc
    //         'updated_by'    => $ci->session->userdata('user_id')
    //     );

    //     $filters = array (
    //         'name'          => $name,
    //         'is_deleted'    => 0
    //     );
    //     if ($group != null)     $filters['group'] = $group;

    //     $ci->db->update(static::$SETTING_TABLE, $values, $filters);
    // }

    // public function list() {
    //     $ci =& get_instance();
        
    //     $filters = array (
    //         'is_deleted'    => 0
    //     );

    //     $ci->db->select('name, group, value, description');
    //     $arr = $ci->db->get_where(static::$SETTING_TABLE, $filters)->getResultArray();

    //     return $arr;
    // }

    // public function list_group($group) {
    //     $ci =& get_instance();
        
    //     $filters = array (
    //         'is_deleted'    => 0,
    //         'group'         => $group
    //     );

    //     $ci->db->select('name, group, value, description');
    //     $arr = $ci->db->get_where(static::$SETTING_TABLE, $filters)->getResultArray();

    //     return $arr;
    // }
}