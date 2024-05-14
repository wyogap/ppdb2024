<?php
namespace App\Libraries;

// require ROOTPATH .'vendor/autoload.php';

class Uploader {
        //disable loading setting from database. not really useful since it hardly changes!
        public static $LOAD_SETTING = 1;
        public static $GENERATE_PDF_THUMBNAIL = 1;

        public static $XSL_ICON_PATH = FCPATH . 'images/icon/xlsx.png';
        public static $XSL_THUMBNAIL_PATH = FCPATH . 'images/icon/xlsx_thumb.png';

        public static $FILE_ICON_PATH = FCPATH . 'images/icon/file.png';
        public static $FILE_THUMBNAIL_PATH = FCPATH . 'images/icon/file_thumb.png';

        public static $PDF_ICON_PATH = FCPATH . 'images/icon/pdf.png';
        public static $PDF_THUMBNAIL_PATH = FCPATH . 'images/icon/pdf_thumb.png';

        public $max_size_mb = 4;              //in MB
        public $max_dimension = 1200;
        public $thumb_dimension = 100;
        public $upload_dir = "upload/";

        public $file_types = array("jpg", "jpeg", "png", "gif", "pdf", "xlsx", "xls", "csv", 'doc', 'docx', 'ppt', 'pptx');
        //public $file_types = array();

        protected $db = null;
        protected $session = null;
    
        public function __construct()
        {
            //init
            $this->db = \Config\Database::connect();
            $this->session = \Config\Services::session();
        }

        function upload($upload, $reftable = null, $refid = null, $refcol = null) {
            $data = array();
            $data["id"] = 0;
            
            //load upload setting
            if (Uploader::$LOAD_SETTING != 1) {
                $this->load_setting();
            }
    
            $maxsize = 1096*1024*$this->max_size_mb;       //4MB
            $maxdim = $this->max_dimension;
            $thumbdim = $this->thumb_dimension;
            $upload_dir = $this->upload_dir;
    
            $filename = "";
            $filesize = 0;
            $webpath = "";
            $thumbpath = "";
            
            do {
                if (!isset($upload) || !is_array($upload)) {
                    $data['error'] = 'Unggahan tidak ditemukan.';
                    break;
                }
    
                if ($upload['error'] != 0) {
                    if ($upload['error'] == 1) {
                        $data['error'] = "Unggahan melebihi maxsize di php.ini";
                    } 
                    else if ($upload['error'] == 3) {
                        $data['error'] = 'Unggahan tidak selesai.';
                    }
                    else if ($upload['error'] == 4) {
                        $data['error'] = 'Unggahan tidak ditemukan.';
                    }
                    else {
                        $data['error'] = 'Kesalahan di konfigurasi sistem. Silahkan hubungi nomor bantuan.';
                    }
                    break;
                }
    
                $file = $upload['tmp_name']; 
                $ext = pathinfo($upload['name'], PATHINFO_EXTENSION);
    
                //for consistency
                $ext = strtolower($ext);
    
                // Allow certain file formats
                if( !empty($this->file_types) && !in_array($ext, $this->file_types) ) {
                    $data['error'] = 'Unggahan yang diperbolehkan: ' .implode(', ', $this->file_types). '.';
                    break;
                }
    
                // Max file size: 2MB. Except images which we will resize automatically
                $filesize = filesize($file);
                if ($ext == "pdf" && $filesize > $maxsize) {
                    $data['error'] = 'Unggahan harus dibawah ' .$this->max_size_mb. 'MB.';
                    break;
                }
    
                $filename = $upload['name'];
                $new_filename = $this->generate_file_name($filename);
                $path = FCPATH . $upload_dir;
                $thumb_filename = "";
    
                if ($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif") {
                    list($width, $height, $type, $attr) = getimagesize($file);
    
                    // Make sure it is true image and not fake image
                    if ($type != IMAGETYPE_PNG && $type != IMAGETYPE_GIF && $type != IMAGETYPE_JPEG) {
                        $data['error'] = 'Tipe gambar tidak dikenal.';
                        break;
                    }
    
                    // Resize if necessary
                    $new_width = $width; $new_height = $height;
                    if ( $width > $maxdim || $height > $maxdim ) {
                        $ratio = $width/$height;
                        if( $ratio > 1) {
                            $new_width = $maxdim;
                            $new_height = $maxdim/$ratio;
                        } else {
                            $new_width = $maxdim*$ratio;
                            $new_height = $maxdim;
                        }
                    }
    
                    $thumb_width = 0; $thumb_height = 0;
                    if ( $width > $thumbdim || $height > $thumbdim ) {
                        $ratio = $width/$height;
                        if( $ratio > 1) {
                            $thumb_width = $thumbdim;
                            $thumb_height = $thumbdim/$ratio;
                        } else {
                            $thumb_width = $thumbdim*$ratio;
                            $thumb_height = $thumbdim;
                        }
                    }
    
                    // $data["error"] = "$width,$height,$thumb_width,$thumb_height";
                    // return $data;

                    // Create thumbnail and resize if necessary
                    switch ($type) {
            
                        case IMAGETYPE_PNG:
                            $imageResourceId = imagecreatefrompng($file); 
                            
                            //create thumbnail
                            if ($thumb_width > 0 && $thumb_height > 0) {
                                $targetLayer = $this->imageresize($imageResourceId,$width,$height,$thumb_width,$thumb_height);
                                imagepng($targetLayer, $path. $new_filename. "_thumb.". $ext);
                                imagedestroy($targetLayer);
                            }
                            else {
                                //just create a copy
                                copy($file, $path. $new_filename. "_thumb.". $ext);
                            }
    
                            if ($new_width != $width || $new_height != $height) {
                                //resize
                                $targetLayer = $this->imageresize($imageResourceId,$width,$height,$new_width,$new_height);
                                imagepng($targetLayer, $file);
                                imagedestroy($targetLayer);
                            }
    
                            imagedestroy($imageResourceId);
                            break;       
            
                        case IMAGETYPE_GIF:
                            $imageResourceId = imagecreatefromgif($file); 
    
                            //create thumbnail
                            if ($thumb_width > 0 && $thumb_height > 0) {
                                $targetLayer = $this->imageresize($imageResourceId,$width,$height,$thumb_width,$thumb_height);
                                imagegif($targetLayer, $path. $new_filename. "_thumb.". $ext);
                                imagedestroy($targetLayer);
                            }
                            else {
                                //just create a copy
                                copy($file, $path. $new_filename. "_thumb.". $ext);
                            }

                            if ($new_width != $width || $new_height != $height) {
                                //resize
                                $targetLayer = $this->imageresize($imageResourceId,$width,$height,$new_width,$new_height);
                                imagegif($targetLayer, $file);
                                imagedestroy($targetLayer);
                            }
    
                            imagedestroy($imageResourceId);
                            break;
            
                        case IMAGETYPE_JPEG:
                            $imageResourceId = imagecreatefromjpeg($file); 
    
                            //create thumbnail
                            if ($thumb_width > 0 && $thumb_height > 0) {
                                $targetLayer = $this->imageresize($imageResourceId,$width,$height,$thumb_width,$thumb_height);
                                imagejpeg($targetLayer, $path. $new_filename. "_thumb.". $ext);
                                imagedestroy($targetLayer);
                            }
                            else {
                                //just create a copy
                                copy($file, $path. $new_filename. "_thumb.". $ext);
                            }

                            if ($new_width != $width || $new_height != $height) {
                                //resize
                                $targetLayer = $this->imageresize($imageResourceId,$width,$height,$new_width,$new_height);
                                imagejpeg($targetLayer, $file);
                                imagedestroy($targetLayer);
                            }
    
                            imagedestroy($imageResourceId);
                            break;
            
                    }
    
                    //get actual file after resize
                    $filesize = filesize($file);   
                    
                    //web file
                    $webpath = $upload_dir. $new_filename. ".". $ext;
    
                    //thumbnail file
                    $thumbpath = $upload_dir. $new_filename. "_thumb.". $ext;
                }
                // else if ($ext == 'pdf' && Uploader::$GENERATE_PDF_THUMBNAIL == 1) {
                //     //copy the actual pdf file for backup
                //     copy($file, $path. $new_filename. ".pdf");
    
                //     //convert 
                //     $img_file = $path. $new_filename. ".png";
    
                //     $ci =& get_instance();
                //     $ci->load->helper("pdfconvert");

                //     $pdflib = new PdfConverter();
                //     $retval = $pdflib->convert($file, $img_file, $maxdim);
                //     if ($retval != 1) {
                //         $data["error"] = $retval;
                //         break;
                //     }
    
                //     //create thumbnail
                //     list($width, $height, $type, $attr) = getimagesize($img_file);
    
                //     // // Make sure it is true image and not fake image
                //     // if ($type != IMAGETYPE_PNG && $type != IMAGETYPE_GIF && $type != IMAGETYPE_JPEG) {
                //     //     $data['error'] = 'Tipe gambar tidak dikenal.';
                //     //     break;
                //     // }
    
                //     $thumb_width = 0; $thumb_height = 0;
                //     if ( $width > $thumbdim || $height > $thumbdim ) {
                //         $ratio = $width/$height;
                //         if( $ratio > 1) {
                //             $thumb_width = $thumbdim;
                //             $thumb_height = $thumbdim/$ratio;
                //         } else {
                //             $thumb_width = $thumbdim*$ratio;
                //             $thumb_height = $thumbdim;
                //         }
                //     }
    
                //     //create the actual thumbnail image
                //     $imageResourceId = imagecreatefrompng($img_file);                         
                //     $targetLayer = $this->imageresize($imageResourceId,$width,$height,$thumb_width,$thumb_height);
                //     imagepng($targetLayer, $path. $new_filename. "_thumb.png");
                //     imagedestroy($targetLayer);
    
                //     //web file
                //     $webpath = $upload_dir. $new_filename. ".png";
    
                //     //thumbnail file
                //     $thumbpath = $upload_dir. $new_filename. "_thumb.png";
                // }
                else if ($ext == 'pdf' && Uploader::$GENERATE_PDF_THUMBNAIL != 1) {  
                    //thumbnail file
                    $path_parts = pathinfo(Uploader::$PDF_THUMBNAIL_PATH);
                    copy(Uploader::$PDF_THUMBNAIL_PATH, $path. $new_filename. "_thumb." .$path_parts['extension']);
                    $thumbpath = $upload_dir. $new_filename. "_thumb." .$path_parts['extension'];
                    $webpath = $upload_dir. $new_filename. "." .$ext;
                }
                else if ($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv') {
                    //thumbnail file
                    $path_parts = pathinfo(Uploader::$XSL_THUMBNAIL_PATH);
                    copy(Uploader::$XSL_THUMBNAIL_PATH, $path. $new_filename. "_thumb." .$path_parts['extension']);
                    $thumbpath = $upload_dir. $new_filename. "_thumb." .$path_parts['extension'];
                    $webpath = $upload_dir. $new_filename. "." .$ext;
                }
                else {
    
                    //thumbnail file
                    $path_parts = pathinfo(Uploader::$FILE_THUMBNAIL_PATH);
                    copy(Uploader::$FILE_THUMBNAIL_PATH, $path. $new_filename. "_thumb." .$path_parts['extension']);
                    $thumbpath = $upload_dir. $new_filename. "_thumb." .$path_parts['extension'];
                    $webpath = $upload_dir. $new_filename. "." .$ext;
                }

                move_uploaded_file($file, $path. $new_filename. ".". $ext);
    
                //uploaded file
                $filepath = $upload_dir. $new_filename. ".". $ext;
    
                $id = $this->simpan($filename, $filesize, $filepath, $webpath, $thumbpath, $reftable, $refid, $refcol);
                if ($id == 0) {
                    $data["error"] = "Tidak berhasil menyimpan unggahan. Silahkan hubungi nomor bantuan.";
                }
                else {
                    $data["id"]= $id;
                    $data["filename"]= $filename;
                    $data["filesize"]= $filesize;
                    $data["web_path"]= base_url(). $webpath;
                    if (!empty($thumbpath)) {
                        $data["thumbnail_path"]= base_url(). $thumbpath;
                    } else {
                        $data["thumbnail_path"]= "";
                    }
                    $data["file_path"]= base_url().  $filepath;
                }

                //var_dump($data);
            }
            while(false);
    
            return $data;
        }

        function remove($id, $reftable = null) {
            $filter = [
                'id'  => $id,
                'is_deleted'  => 0
            ];
    
            if ($reftable != null) {
                $filter['ref_table'] = $reftable;
            }

            $valuepair = [
                'is_deleted'  => 1,
                'updated_on' => date('Y/m/d H:i:s'),
                'updated_by' => $this->session->get('user_id')
            ];
    
            $builder = $this->db->table('dbo_uploads');
            $builder->where($filter);
            $builder->update($valuepair);
        
            $affected = $this->db->affectedRows();
            if ($affected > 0) {
                //audit trail
                return $id;
            }

            return 0;
        }

        function detail($id, $reftable = null) {
            $filter = [
                'id'  => $id,
                'is_deleted'  => 0
            ];
        
            if ($reftable != null) {
                $filter['ref_table'] = $reftable;
            }

            $builder = $this->db->table('dbo_uploads');
            $builder->where($filter);
            $arr = $builder->get()->getRowArray();
            if ($arr == null)   return $arr;

            $arr["web_path"]= base_url(). $arr["web_path"];
            $arr["thumbnail_path"]= base_url(). $arr["thumbnail_path"];

            return $arr;
        }

        function imageresize($imageResourceId,$width,$height,$targetWidth,$targetHeight) {
        
            $targetLayer=imagecreatetruecolor($targetWidth,$targetHeight);
            imagecopyresampled($targetLayer,$imageResourceId,0,0,0,0,$targetWidth,$targetHeight, $width,$height);
            
            return $targetLayer;
        }
            
        function simpan($filename, $filesize, $filepath, $webpath, $thumbpath, $reftable = null, $refid = null, $refcol = null) {
            $valuepair = [
                'filename'  => $filename,
                'filesize'  => $filesize,
                'path'		=> $filepath,
                'web_path'	=> $webpath,
                'thumbnail_path'	=> $thumbpath,
                'ref_table' => $reftable,
                'ref_id'    => $refid,
                'ref_field' => $refcol
            ];
    
            //var_dump($valuepair);

            $builder = $this->db->table('dbo_uploads');
            $result = $builder->insert($valuepair);
            if (!$result) {
                return 0;
            }

            $key = $this->db->insertID();
            return $key;
        }

        function imagerotate($file, $newfile, $degree) {

            list($width, $height, $type, $attr) = getimagesize($file);

            //echo "$file, $width, $height, $type, $attr";

            // Make sure it is true image and not fake image
            if ($type != IMAGETYPE_PNG && $type != IMAGETYPE_GIF && $type != IMAGETYPE_JPEG) {
                return 'Tipe gambar tidak dikenal.';
            }

            switch ($type) {        
                case IMAGETYPE_PNG:
                    $imageResourceId = imagecreatefrompng($file); 
                    
                    // Rotate
                    $rotate = imagerotate($imageResourceId, $degree, 0);
        
                    // simpan
                    imagepng($rotate, $newfile);
                    imagedestroy($rotate);
                    imagedestroy($imageResourceId);
        
                    return '';
        
                case IMAGETYPE_GIF:
                    $imageResourceId = imagecreatefromgif($file); 
        
                    // Rotate
                    $rotate = imagerotate($imageResourceId, $degree, 0);
        
                    // simpan
                    imagegif($rotate, $newfile);
                    imagedestroy($rotate);
                    imagedestroy($imageResourceId);
        
                    return '';
        
                case IMAGETYPE_JPEG:
                    $imageResourceId = imagecreatefromjpeg($file); 
        
                    // Rotate
                    $rotate = imagerotate($imageResourceId, $degree, 0);
        
                    // simpan
                    imagejpeg($rotate, $newfile);
                    imagedestroy($rotate);
                    imagedestroy($imageResourceId);
        
                    return '';
        
                }
                
            return 'Non-specific error.';

        }

        function load_setting() {
            $sql = "select name, value from dbo_settings where `group`='upload' and is_deleted=0";
    
            // public $max_size_mb = 4;              //in MB
            // public $max_dimension = 1200;
            // public $thumb_dimension = 100;
            // public $upload_dir = "upload/";
            // public $file_types = array("jpg", "jpeg", "png", "gif", "pdf", "xlsx", "xls", "csv", 'doc', 'docx', 'ppt', 'pptx');
   
            $query = $this->db->query($sql);
            foreach($query->getResult() as $row) {
                if ($row->name == 'upload_max_size_mb' && !empty($row->value)) {
                    $this->max_size_mb = $row->value;
                }
                else if ($row->name == "upload_img_dim" && !empty($row->value)) {
                    $this->max_dimension = $row->value;
                }
                else if ($row->name == "upload_thumbnail_dim" && !empty($row->value)) {
                    $this->thumb_dimension = $row->value;
                }
                else if ($row->name == "upload_dir" && !empty($row->value)) {
                    $this->upload_dir = $row->value;
                }
                else if ($row->name == "upload_file_types" && !empty($row->value)) {
                    $this->file_types = array_map('trim', explode(",", $row->value));
                }
            }
    
            $_load_setting = 1;
    
            return;
        }

        function crypto_rand_secure($min, $max)
        {
            $range = $max - $min;
            if ($range < 1) return $min; // not so random...
            $log = ceil(log($range, 2));
            $bytes = (int) ($log / 8) + 1; // length in bytes
            $bits = (int) $log + 1; // length in bits
            $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
            do {
                $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
                $rnd = $rnd & $filter; // discard irrelevant bits
            } while ($rnd > $range);
            return $min + $rnd;
        }

        function generate_token($length)
        {
            $token = "";
            $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
            $codeAlphabet.= "0123456789";
            $max = strlen($codeAlphabet); // edited

            for ($i=0; $i < $length; $i++) {
                $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
            }

            return $token;
        }

        function generate_file_name($filename) {
            $user_id = $this->session->get('user_id');

            return $user_id ."_". time() ."_". $this->generate_token(10);
        }
    }