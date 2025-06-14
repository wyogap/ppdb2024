<?php

namespace App\Models\Core\Ext;

use App\Libraries\AuditTrail;

defined('FIELD_STATUS_NOTOK')           OR define('FIELD_STATUS_NOTOK', 0);
defined('FIELD_STATUS_OK')              OR define('FIELD_STATUS_OK', 1);
defined('FIELD_STATUS_SEARCH')          OR define('FIELD_STATUS_SEARCH', 2);
defined('FIELD_STATUS_PICK')            OR define('FIELD_STATUS_PICK', 3);

use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;

class MWhatsApp
{
    protected $db;
    protected $session;
    protected $audittrail;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->audittrail = new AuditTrail();
    }

    function get_user($handphone) {
        $sql = "select * from dbo_users where handphone=? and is_deleted=1";

        return $this->db->query($sql, array($handphone))->getRowArray();
    }

    function start_session($userdata) {
        //close existing (if any)
        $sql = "update dbo_wa_session set is_deleted=1 where wa_number=?";
        $this->db->query($sql, array($userdata['handphone']));

        //create a new one
        $valuepair = array (
            'wa_number' => $userdata['handphone'],
            'user_id' => $userdata['user_id'],
            'name' => $userdata['nama'],
            "created_on" => date('Y-m-d H:i:s'),
            'created_by' => $userdata['user_id']
        );


        $builder = $this->db->table('dbo_wa_session');
        $result = $builder->insert($valuepair);
        if ($result == null)    return null;

        $res = $this->db->query("SELECT LAST_INSERT_ID(session_id) as insert_id from dbo_wa_session order by LAST_INSERT_ID(session_id) desc limit 1;")->getRowArray();
        $id_session = $res['insert_id']; 

        $sql = "select * from dbo_wa_session where session_id=? and is_active=1";
        return $this->db->query($sql, array($id_session))->getRowArray();;
    }

    function get_session($userdata) {
        $sql = "select * from dbo_wa_session where wa_number=? and is_active=1";

        return $this->db->query($sql, array($userdata['handphone']))->getRowArray();
    }

    function close_session($id_session) {
        $sql = "update dbo_wa_session set is_deleted=1 where session_id=?";
        $this->db->query($sql, array($id_session));
    }

    function save_incoming($sessiondata, $msg, $timestamp) {
        //create a new one
        $valuepair = array (
            'wa_number' => $sessiondata['wa_number'],
            'user_id' => $sessiondata['user_id'],
            'name' => $sessiondata['name'],
            'session_id' => $sessiondata['session_id'],
            'outgoing_id' => $sessiondata['last_outgoing_id'],
            'message' => $msg,
            'timestamp' => $timestamp,
            "created_on" => date('Y-m-d H:i:s'),
            'created_by' => $sessiondata['id_user']
        );

        $builder = $this->db->table('dbo_wa_incoming');
        $result = $builder->insert($valuepair);
        if ($result == null)    return 0;

        //get id
        $res = $this->db->query("SELECT LAST_INSERT_ID(incoming_id) as insert_id from dbo_wa_incoming order by LAST_INSERT_ID(incoming_id) desc limit 1;")->getRowArray();
        $id = $res['insert_id']; 

        //update session
        $sql = "update dbo_wa_session set last_incoming_id=?, first_incoming_id=(case when first_incoming_id is null then ? else first_incoming_id end) where session_id=?";
        $this->db->query($sql, array($id, $id, $sessiondata['session_id']));

        return $id;
    }
  
    function save_outgoing($sessiondata, $msg) {
        //create a new one
        $valuepair = array (
            'wa_number' => $sessiondata['wa_number'],
            'user_id' => $sessiondata['user_id'],
            'name' => $sessiondata['name'],
            'session_id' => $sessiondata['session_id'],
            'incoming_id' => $sessiondata['last_incoming_id'],
            'message' => $msg,
            'timestamp' => date('Y-m-d H:i:s'),
            "created_on" => date('Y-m-d H:i:s'),
            'created_by' => $sessiondata['id_user']
        );

        $builder = $this->db->table('dbo_wa_outgoing');
        $result = $builder->insert($valuepair);
        if ($result == null)    return 0;

        //get session
        $res = $this->db->query("SELECT LAST_INSERT_ID(outgoing_id) as insert_id from dbo_wa_outgoing order by LAST_INSERT_ID(outgoing_id) desc limit 1;")->getRowArray();
        $id = $res['insert_id']; 

        //update session
        $sql = "update dbo_wa_session set last_outgoing_id=? where session_id=?";
        $this->db->query($sql, array($id, $sessiondata['session_id']));

        return $id;
    }

    function log_message($msg) {
        $valuepair = array(
            'message' => $msg
        );

        $builder = $this->db->table('dbo_wa_audit');
        $builder->insert($valuepair);
    }

    function process_message($user, $message) {
        $name = $user['name'];

        $outmsg = "Hello, " +$name+ "!";
        return $outmsg;
    }

    function get_new_line() {
        return PHP_EOL;
    }

    // function get_report_list_message($prefix = null) {
    //     //list of possible reports
    //     $sql = "select * from wa_template where is_active=1";
    //     $result = $this->db->query($sql)->result_array();

    //     $msg = "";
    //     if ($prefix != null) {
    //         $msg .= $prefix .$this->get_new_line() .$this->get_new_line();
    //     }

    //     $msg .= "JENIS REPORT?" .$this->get_new_line();
    //     foreach($result as $r) {
    //         $msg .= "  [" .$r['template']. "] " .$r['label']. $this->get_new_line();
    //     }
    //     $msg .= $this->get_new_line();
    //     $msg .= "Pilih salah satu (contoh: " .$result[0]['template']. ").";
        
    //     return $msg;
    // }

    // function get_completed_message() {
    //     $msg = "Pelaporan berhasil dibuat. Terima kasih.";
    //     return $msg;
    // }

    // function parse($sessiondata, $message) {
    //     $id_satker = $sessiondata['id_satker'];
    //     $id_session = $sessiondata['id_session'];

    //     //var_dump($message);

    //     //get message type
    //     $segment = "";
    //     $lastpos = 2;
    //     $pos = strpos($message, '#', $lastpos);
    //     if ($pos > 0) {
    //         $segment = substr($message, $lastpos, $pos-$lastpos);
    //     }
    //     else {
    //         $segment = substr($message, $lastpos);
    //     }

    //     //trim
    //     $namatemplate = trim($segment);
    //     $keyword = "#!" .$namatemplate;

    //     if (policy(strtoupper($namatemplate),'read')){
    //         $id_satker = $sessiondata['id_satker'];
    //     }
    //     else if (policy(strtoupper($namatemplate),'read_all')){
    //         $id_satker = null;
    //     }

    //     //get template
    //     $sql = "select * from wa_template where template=? and is_active=1";
    //     $template = $this->db->query($sql, array($namatemplate))->row_array();
    //     if ($template == null) {
    //         return 0;
    //     }

    //     //get fields
    //     $sql = "select * from wa_template_field where template=? and coalesce(field,'')!='' and is_active=1";
    //     $result = $this->db->query($sql, array($namatemplate))->result_array();
    //     if (count($result) == 0) {
    //         return 0;
    //     }

    //     $fields = array();
    //     foreach($result as $r) {
    //         $fields[ strtoupper($r['field']) ] = $r;
    //     }

    //     //parse the message
    //     $lastpos = $pos+1;
    //     $idx = 0;
    //     $name = null;
    //     $value = null;
    //     $lastfield = null;
    //     $lastvalue = null;
    //     $cnt = 0;
    //     $geocode = null;
    //     do {
    //         //get next segment
    //         $pos = strpos($message, '#', $lastpos);
    //         if ($pos > 0) {
    //             $segment = substr($message, $lastpos, $pos-$lastpos);
    //         }
    //         else {
    //             $segment = substr($message, $lastpos);
    //         }
            
    //         //get field name
    //         $idx = strpos($segment, ':', 1);
    //         if ($idx < 0) {
    //             //no field -> maybe part of prev message?
    //             if ($lastfield != null) {
    //                 $lastvalue .= " #" .$segment;

    //                 //update last field
    //                 $fields[ $lastfield ] = $lastvalue;
    //             }

    //             if ($pos === FALSE) break;

    //             continue;
    //         }

    //         $name = strtoupper(trim(substr($segment, 0, $idx)));
    //         $value = trim(substr($segment, $idx+1));
            
    //         do {
    //             if (empty($fields[ $name ])) break;

    //             $fields[ $name ]['value'] = $value;
    //             $fields[ $name ]['actual_value'] = $value;

    //             //parse date
    //             if ($fields[ $name ]['field_type'] == 'date') {
    //                 $arr = date_parse_from_format('Y/m/d', $value);
    //                 $actual_value = $arr['year'] .'-'. str_pad($arr['month'],2,'0', STR_PAD_LEFT) .'-'. str_pad($arr['day'],2,'0', STR_PAD_LEFT);
    //                 $actual_value .= ' 00:00:00';

    //                 $fields[ $name ]['actual_value'] = $actual_value;
    //                 if ($arr['year'] < 2000 || $arr['year'] > date('Y') || $arr['month'] < 1 || $arr['month'] > 12 || $arr['day'] < 0 || $arr['day'] > 31) {
    //                     $fields[ $name ]['status'] = FIELD_STATUS_NOTOK;
    //                 }
    //                 break;
    //             }
    //             else if ($fields[ $name ]['field_type'] == 'datetime') {
    //                 $arr = date_parse_from_format('Y/m/d H:i:s', $value);
    //                 $actual_value = $arr['year'] .'-'. str_pad($arr['month'],2,'0', STR_PAD_LEFT) .'-'. str_pad($arr['day'],2,'0', STR_PAD_LEFT);
    //                 $actual_value .= ' ' .($arr['hour'] ? str_pad($arr['hour'],2,'0', STR_PAD_LEFT) : "00");
    //                 $actual_value .= ':' .($arr['minute'] ? str_pad($arr['minute'],2,'0', STR_PAD_LEFT) : "00");
    //                 $actual_value .= ':' .($arr['second'] ? str_pad($arr['second'],2,'0', STR_PAD_LEFT) : "00");

    //                 $fields[ $name ]['actual_value'] = $actual_value;
    //                 if ($arr['year'] < 2000 || $arr['year'] > date('Y') || $arr['month'] < 1 || $arr['month'] > 12 || $arr['day'] < 0 || $arr['day'] > 31) {
    //                     $fields[ $name ]['status'] = FIELD_STATUS_NOTOK;
    //                 }
    //                 break;
    //             }

    //             if ($fields[ $name ]['column_name'] == 'tags') {
    //                 if (!empty($value)) {
    //                     $fields[ $name ]['actual_value'] = strtolower($value);
    //                     $fields[ $name ]['status'] = FIELD_STATUS_OK;
    //                 }
    //                 else {
    //                     $fields[ $name ]['actual_value'] = null;
    //                     $fields[ $name ]['status'] = FIELD_STATUS_PICK;
    //                 }
    //                 break;
    //             }
                
    //             if ($fields[ $name ]['column_name'] == 'where') {
    //                 if (!empty($value)) {
    //                     $geocode = $this->get_geocode($value);
    //                     if ($geocode == null) {
    //                         $fields[ $name ]['error_message'] = "Alamat tidak ditemukan.";
    //                         $fields[ $name ]['status'] = FIELD_STATUS_NOTOK;
    //                     }
    //                     else {
    //                         $fields[ $name ]['status'] = FIELD_STATUS_OK;
    //                     }
    //                 }
    //                 else {
    //                     $actual_value = null;
    //                     $fields[ $name ]['status'] = FIELD_STATUS_NOTOK;
    //                 }
    //                 break;
    //             }
    
    //             //check for actual value
    //             if (!empty($fields[ $name ]['lookup_table'])) {
    //                 $sql = "select " .$fields[ $name ]['lookup_col_value']. " as value from " .$fields[ $name ]['lookup_table']. 
    //                         " where is_active=1 and " .$fields[ $name ]['lookup_col_label']. " like '%" .$value. "%'";

    //                 //limit by satker
    //                 if (!empty($id_satker) && !empty($fields[ $name ]['lookup_has_satker'])) {
    //                     $sql .= " AND id_satker=" .$this->db->escape($id_satker);
    //                 }

    //                 $sql .= " order by length(" .$fields[ $name ]['lookup_col_label']. ")";

    //                 $result = $this->db->query($sql)->result_array();
    //                 if ($result == null) {
    //                     $fields[ $name ]['actual_value'] = null;
    //                     $fields[ $name ]['status'] = FIELD_STATUS_NOTOK;
    //                 }
    //                 else if (count($result) == 1) {
    //                     $fields[ $name ]['actual_value'] = $result[0]['value'];
    //                     $fields[ $name ]['status'] = FIELD_STATUS_OK;
    //                 }
    //                 else if (count($result) <= 10) {
    //                     //multiple matches => pick list
    //                     $fields[ $name ]['actual_value'] = null;
    //                     $fields[ $name ]['status'] = FIELD_STATUS_PICK;
    //                 }
    //                 else {
    //                     //multiple matches => search
    //                     $fields[ $name ]['actual_value'] = null;
    //                     $fields[ $name ]['status'] = FIELD_STATUS_SEARCH;
    //                 }
    //             }
    //             else {
    //                 $fields[ $name ]['status'] = 1;
    //             }

    //             //if it satker, use for limiting the options
    //             if ($fields[ $name ]['column_name'] == 'id_satker') {
    //                 if (!empty($fields[ $name ]['actual_value'])) {
    //                     $id_satker = $fields[ $name ]['actual_value'];
    //                     //update session
    //                     $sql = "update wa_session set id_satker=? where id_session=?";
    //                     $this->db->query($sql, array($id_satker, $id_session));
    //                 }
    //                 else if (!empty($id_satker)) {
    //                     $fields[ $name ]['actual_value'] = $id_satker;
    //                 }
    //             }
    //         }
    //         while (false);

    //         // if ($pos === FALSE || $cnt > 20) {
    //         //     //all done
    //         //     break;
    //         // }

    //         $lastpos = $pos+1;
    //         $lastfield = $name;
    //         $lastvalue = $value;
    //         $cnt++;
    //     }
    //     while ($pos !== FALSE && $cnt <= 20);

    //     //create the draft pelaporan
    //     $valuepair = array (
    //         'nomor_wa' => $sessiondata['nomor_wa'],
    //         'id_user' => $sessiondata['id_user'],
    //         'nama_pegawai' => $sessiondata['nama_pegawai'],
    //         'id_satker' => $id_satker,
    //         'id_session' => $id_session,
    //         'template' => $namatemplate,
    //         'id_activity_jenis' => $template['id_activity_jenis'],
    //         "created_date" => date('Y-m-d H:i:s'),
    //         'created_by' => $sessiondata['id_user']
    //     );

    //     if ($geocode != null) {
    //         $valuepair['id_geografi'] = $geocode['id_geografi'];
    //         $valuepair['latitude'] = $geocode['latitude'];
    //         $valuepair['longitude'] = $geocode['longitude'];
    //     }

    //     $result = $this->db->insert('wa_draft_pelaporan', $valuepair);
    //     if ($result == null)    return 0;

    //     $res = $this->db->query("SELECT LAST_INSERT_ID(id_draft_pelaporan) as insert_id from wa_draft_pelaporan order by LAST_INSERT_ID(id_draft_pelaporan) desc limit 1;")->row_array();
    //     $id_draft = $res['insert_id']; 
    //     //$id_draft = $this->db->insert_id();

    //     //update session
    //     $sql = "update wa_session set id_draft_pelaporan=? where is_active=1 and id_session=?";
    //     $this->db->query($sql, array($id_draft, $id_session));

    //     //store the fields
    //     foreach($fields as $f) {
    //         $f[ 'id_draft_pelaporan' ] = $id_draft;

    //         unset( $f['is_active'] );
    //         unset( $f['created_by'] );
    //         unset( $f['created_date'] );
    //         unset( $f['updated_by'] );
    //         unset( $f['updated_date'] );

    //         $this->db->insert('wa_draft_field', $f);
    //     }

    //     //process the fields
    //     //mandatory
    //     $sql = "select * from wa_draft_field where id_draft_pelaporan=? and is_active=1 and is_mandatory=1 and actual_value is null";
    //     $result = $this->db->query($sql, array($id_draft))->result_array();
    //     if (count($result) > 0) {
    //         $updated = array();
    //         foreach($result as $r) {
    //             $id_draft_field = $r['id_draft_field'];
    //             $updated[ $id_draft_field ] = array();
    //             $updated[ $id_draft_field ]['status'] = FIELD_STATUS_NOTOK;

    //             //if it satker, use for limiting the options
    //             if ($r['column_name'] == 'id_satker' && !empty($id_satker)) {
    //                 $updated[ $id_draft_field ]['actual_value'] = $id_satker;
    //                 $updated[ $id_draft_field ]['status'] = FIELD_STATUS_OK;
    //                 continue;
    //             }
    
    //             if (!empty($r['lookup_table'])) {
    //                 $sql = "select count(*) as cnt from " .$r['lookup_table']. " where is_active=1";
    
    //                 //limit by satker
    //                 if (!empty($id_satker) && !empty($r['lookup_has_satker'])) {
    //                     $sql .= " AND id_satker=" .$this->db->escape($id_satker);
    //                 }
        
    //                 $result = $this->db->query($sql)->row_array();
    //                 if ($result['cnt'] <= 10) {
    //                     //$updated[ $id_draft_field ]['actual_value'] = null;
    //                     $updated[ $id_draft_field ]['status'] = FIELD_STATUS_PICK;
    //                 }
    //                 else {
    //                     //$updated[ $id_draft_field ]['actual_value'] = null;
    //                     $updated[ $id_draft_field ]['status'] = FIELD_STATUS_SEARCH;
    //                 }
    
    //                 continue;
    //             }
    //         }
        
    //         foreach ($updated as $k => $v) {
    //             if (empty($v))  continue;
    //             $this->db->update('wa_draft_field', $v, ['id_draft_field' => $k]);
    //         }
            
    //     }
        
    //     //not-mandatory -> set status = 1 if no value
    //     $sql = "update wa_draft_field set status=1 where is_active=1 and id_draft_pelaporan=? and is_mandatory=0";
    //     $this->db->query($sql, array($id_draft));

    //     //update draft status
    //     $sql = "select count(*) as cnt from wa_draft_field where is_active=1 and id_draft_pelaporan=? and status!=" .$this->db->escape(FIELD_STATUS_OK);
    //     $result = $this->db->query($sql, array($id_draft))->row_array();

    //     if ($result['cnt'] == 0) {
    //         $valuepair = array(
    //             "current_field" => null,
    //             "confirmed_at" => date('Y-m-d H:i:s'),
    //             "completed_at" => date('Y-m-d H:i:s'),
    //             "updated_date" => date('Y-m-d H:i:s'),
    //             "updated_by" => $sessiondata['id_user']
    //         );

    //         $result = $this->db->update("wa_draft_pelaporan", $valuepair, ['id_draft_pelaporan' => $id_draft]);

    //         //create pelaporan
    //         $report_id = $this->create_pelaporan($id_draft);

    //         $this->close_session($id_session);
    //     }
    //     else {
    //         //TODO: set current_field to work on
    //     }

    //     return $id_draft;
    // }

    // function create_draft($sessiondata, $message) {
    //     $namatemplate = trim($message);
    //     $namatemplate = str_replace('[', '', $namatemplate);
    //     $namatemplate = str_replace(']', '', $namatemplate);

    //     $id_satker = $sessiondata['id_satker'];
    //     $id_session = $sessiondata['id_session'];

    //     if (policy(strtoupper($namatemplate),'read')){
    //         $id_satker = $sessiondata['id_satker'];
    //     }
    //     else if (policy(strtoupper($namatemplate),'read_all')){
    //         $id_satker = null;
    //     }

    //     //get template
    //     $sql = "select * from wa_template where template=? and is_active=1";
    //     $template = $this->db->query($sql, array($namatemplate))->row_array();
    //     if ($template == null) {
    //         return 0;
    //     }

    //     //get fields
    //     $sql = "select * from wa_template_field where template=? and is_active=1";
    //     $result = $this->db->query($sql, array($namatemplate))->result_array();
    //     if (count($result) == 0) {
    //         return 0;
    //     }

    //     $fields = array();
    //     foreach($result as $r) {
    //         $name = strtoupper($r['field']);
    //         $id_draft_field = $r['id_template_field'];

    //         $fields[ $id_draft_field ] = $r;

    //         $fields[ $id_draft_field ]['status'] = FIELD_STATUS_NOTOK;

    //         //if it satker, use for limiting the options
    //         if ($r['column_name'] == 'id_satker' && !empty($id_satker)) {
    //             $sql = "select nama_satker as label from org_satker where id_satker=?";
    //             $result = $this->db->query($sql, $id_satker)->row_array();
    //             if ($result != null) {
    //                 $fields[ $id_draft_field ]['value'] = $result['label'];
    //                 $fields[ $id_draft_field ]['actual_value'] = $id_satker;
    //                 $fields[ $id_draft_field ]['status'] = FIELD_STATUS_OK;
    //             }
    //             continue;
    //         }

    //         if (!empty($r['lookup_table'])) {
    //             if ($fields[ $id_draft_field ]['lookup_search'] == 1) {
    //                 $fields[ $id_draft_field ]['actual_value'] = null;
    //                 $fields[ $id_draft_field ]['status'] = FIELD_STATUS_SEARCH;
    //             }
    //             else {
    //                 $fields[ $id_draft_field ]['actual_value'] = null;
    //                 $fields[ $id_draft_field ]['status'] = FIELD_STATUS_PICK;
    //             }

    //             continue;
    //         }

    //     }

    //     //create the draft pelaporan
    //     $valuepair = array (
    //         'nomor_wa' => $sessiondata['nomor_wa'],
    //         'id_user' => $sessiondata['id_user'],
    //         'nama_pegawai' => $sessiondata['nama_pegawai'],
    //         'id_satker' => $id_satker,
    //         'id_session' => $id_session,
    //         'template' => $namatemplate,
    //         'id_activity_jenis' => $template['id_activity_jenis'],
    //         'created_date' => date('Y-m-d H:i:s'),
    //         'created_by' => $sessiondata['id_user']
    //     );

    //     $result = $this->db->insert('wa_draft_pelaporan', $valuepair);
    //     if ($result == null)    return 0;

    //     $res = $this->db->query("SELECT LAST_INSERT_ID(id_draft_pelaporan) as insert_id from wa_draft_pelaporan order by LAST_INSERT_ID(id_draft_pelaporan) desc limit 1;")->row_array();
    //     $id_draft = $res['insert_id']; 
    //     //$id_draft = $this->db->insert_id();

    //     //store the fields
    //     foreach($fields as $f) {
    //         $f[ 'id_draft_pelaporan' ] = $id_draft;

    //         //unset mandatory
    //         if (empty($f['field'])) {
    //             $f['is_mandatory'] = 0;
    //             $f['status'] = 1;
    //         }

    //         unset( $f['is_active'] );
    //         unset( $f['created_by'] );
    //         unset( $f['created_date'] );
    //         unset( $f['updated_by'] );
    //         unset( $f['updated_date'] );

    //         $this->db->insert('wa_draft_field', $f);
    //     }

    //     //process the fields
    //     //no need => assume every fields needs imput

    //     //update session
    //     $valuepair = array (
    //         'id_draft_pelaporan' => $id_draft,
    //         'updated_date' => date('Y-m-d H:i:s'),
    //         'updated_by' => $sessiondata['id_user']
    //     );

    //     $result = $this->db->update('wa_session', $valuepair, ['id_session' => $id_session]);

    //     return $id_draft;
    // }

    // function repeat_last_message($sessiondata, $repeat = false) {
    //     //get last outgoing message
    //     $outgoing_id = $sessiondata['last_outgoing_id'];

    //     $sql = "select * from dbo_wa_pesan_keluar where outgoing_id=?";
    //     $message = $this->db->query($sql, array($outgoing_id))->getRowArray();

    //     $str = '';
    //     if ($repeat) {
    //         $str = "Jawaban tidak valid. Silahkan coba lagi. " .$this->get_new_line() .$this->get_new_line(); 
    //     }

    //     if (strpos($message['pesan'], $str, 0) < 0) {
    //         $str .= $message['pesan'];
    //     }
    //     else {
    //         $str = $message['pesan'];
    //     }

    //     return $str;
    // }

    // function process_message($draft_id, $message) {
    //     //var_dump($draft_id); var_dump($message); 
    //     //get draft info
    //     $sql = "select * from wa_draft_pelaporan where is_active=1 and id_draft_pelaporan=?";
    //     $draft = $this->db->query($sql, array($draft_id))->row_array();
    //     if ($draft == null) return 0;

    //     $id_session = $draft['id_session'];
       
    //     $value = trim($message);
    //     if(substr($value, 0, 1) == '[') {
    //         $value = str_replace('[', '', $value);
    //         $value = str_replace(']', '', $value);
    //     }

    //     if (!empty($draft['completed_at']) && !empty($draft['confirmed_at'])) {
    //         $this->close_session($id_session);
    //         return 1;
    //     }

    //     //completed but not confirmed => waiting for confirmation
    //     if (!empty($draft['completed_at']) && empty($draft['confirmed_at'])) {
    //         if (strtolower($value) == 'ya') {
    //             //update confirmed at
    //             $valuepair = array(
    //                 "confirmed_at" => date('Y-m-d H:i:s'),
    //                 "updated_date" => date('Y-m-d H:i:s'),
    //                 "updated_by" => $draft('id_user')
    //             );
    
    //             $result = $this->db->update("wa_draft_pelaporan", $valuepair, ['id_draft_pelaporan' => $draft_id]);

    //             //create pelaporan
    //             $report_id = $this->create_pelaporan($draft_id);

    //             //close session
    //             $this->close_session($id_session);

    //             return 1;
    //         }
    //         else if (strtolower($value) == 'tidak'){
    //             //redo the whole process
    //             $this->close_session($id_session);

    //             $session = $this->start_session($draft);

    //             $this->create_draft($session, $draft['template']);

    //             return 1;
    //         }
    //         else {
    //             //reset this field status only
    //             $name = strtoupper($value);
    //             $sql = "select * from wa_draft_field where is_active=1 and id_draft_pelaporan=? and field=?";
    //             $field = $this->db->query($sql, array($draft_id, $name))->row_array();
    //             if ($field == null) {
    //                 //what to do?
    //                 return 1;
    //             }

    //             $id_draft_field = $field['id_draft_field'];
    //             $updated = array();
    //             $updated['status'] = FIELD_STATUS_NOTOK;

    //             if (!empty($field['lookup_table'])) {
    //                 $sql = "select count(*) as cnt from " .$field['lookup_table']. " where is_active=1";
    
    //                 //limit by satker
    //                 if (!empty($id_satker) && !empty($field['lookup_has_satker'])) {
    //                     $sql .= " AND id_satker=" .$this->db->escape($id_satker);
    //                 }
        
    //                 $result = $this->db->query($sql)->row_array();
    //                 if ($result['cnt'] <= 10) {
    //                     $updated['actual_value'] = null;
    //                     $updated['status'] = FIELD_STATUS_PICK;
    //                 }
    //                 else {
    //                     $updated['actual_value'] = null;
    //                     $updated['status'] = FIELD_STATUS_SEARCH;
    //                 }
    //             }

    //             $result = $this->db->update("wa_draft_field", $updated, ['id_draft_field' => $id_draft_field]);   
                
    //             //reset completed column
    //             $valuepair = array(
    //                 'completed_at' => null,
    //                 "updated_date" => date('Y-m-d H:i:s'),
    //                 "updated_by" => $draft('id_user')
    //             );

    //             $result = $this->db->update("wa_draft_pelaporan", $valuepair, ['id_draft_pelaporan' => $draft_id]);

    //             return 1;
    //         }
    //         return 1;
    //     }

    //     //current field
    //     $name = $draft['current_field'];
    //     if (empty($name)) {
    //         //get the first invalid field
    //         $sql = "select * from wa_draft_field where is_active=1 and id_draft_pelaporan=? and status!=1 and coalesce(field,'')!='' order by order_no asc limit 1";
    //         $field = $this->db->query($sql, array($draft_id))->row_array();
    //         if ($field == null) {
    //             //what to do?
    //             return 0;
    //         }
    //         else {
    //             $name = $field ['field'];
    //         }
    //     }
    //     else {
    //         $sql = "select * from wa_draft_field where is_active=1 and id_draft_pelaporan=? and field=?";
    //         $field = $this->db->query($sql, array($draft_id, $name))->row_array();
    //         if ($field == null) {
    //             //what to do?
    //             return 0;
    //         }
    //     }

    //     $id_draft_field = $field['id_draft_field'];

    //     $actual_value = null;
    //     $status = 0;
    //     $error_message = null;

    //     do {
    //         //check optional field
    //         if ($field['is_mandatory'] == 0) {
    //             if (!empty($value))     $value = strtolower($value);
    //             if (empty($value) || $value == 'tidak ada' || $value == 'na') {
    //                 $value = null;
    //                 $status = FIELD_STATUS_OK;
    //                 break;
    //             }
    //         }
    //         else if (empty($value)) {
    //             $error_message = "Data harus diisi.";
    //             $status = $field['status'];
    //             break;
    //         }

    //         //parse date
    //         if ($field['field_type'] == 'date') {
    //             $arr = date_parse_from_format('Y/m/d', $value);
    //             $actual_value = $arr['year'] .'-'. str_pad($arr['month'],2,'0', STR_PAD_LEFT) .'-'. str_pad($arr['day'],2,'0', STR_PAD_LEFT);
    //             $actual_value .= ' 00:00:00';
    //             break;
    //         }
    //         else if ($field['field_type'] == 'datetime') {
    //             $arr = date_parse_from_format('Y/m/d H:i:s', $value);
    //             $actual_value = $arr['year'] .'-'. str_pad($arr['month'],2,'0', STR_PAD_LEFT) .'-'. str_pad($arr['day'],2,'0', STR_PAD_LEFT);
    //             $actual_value .= ' ' .($arr['hour'] ? str_pad($arr['hour'],2,'0', STR_PAD_LEFT) : "00");
    //             $actual_value .= ':' .($arr['minute'] ? str_pad($arr['minute'],2,'0', STR_PAD_LEFT) : "00");
    //             $actual_value .= ':' .($arr['second'] ? str_pad($arr['second'],2,'0', STR_PAD_LEFT) : "00");
    //             break;
    //         }

    //         if ($field['column_name'] == 'tags') {
    //             if (!empty($value)) {
    //                 $actual_value = strtolower($value);
    //                 $status = FIELD_STATUS_OK;
    //             }
    //             else {
    //                 $actual_value = null;
    //                 $status = FIELD_STATUS_PICK;
    //             }
    //             break;
    //         }

    //         if ($field['column_name'] == 'where') {
    //             if (!empty($value)) {
    //                 $geocode = $this->get_geocode($value);
    //                 if ($geocode == null) {
    //                     $error_message = "Alamat tidak ditemukan.";
    //                     $status = FIELD_STATUS_NOTOK;
    //                 }
    //                 else {
    //                     $status = FIELD_STATUS_OK;
    //                 }
    //             }
    //             else {
    //                 $actual_value = null;
    //                 $status = FIELD_STATUS_NOTOK;
    //             }
    //             break;
    //         }
            
    //         //check for lookup
    //         if (!empty($field['lookup_table'])) {
    //             //pick list
    //             if ($field['status'] != FIELD_STATUS_SEARCH) {
    //                 $sql = "select " .$field['lookup_col_label']. " as label from " .$field['lookup_table']. 
    //                 " where is_active=1 and " .$field['lookup_col_value']. " = " .$this->db->escape($value);
    
    //                 //limit by satker
    //                 if (!empty($id_satker) && !empty($fields[ $name ]['lookup_has_satker'])) {
    //                     $sql .= " AND id_satker=" .$this->db->escape($id_satker);
    //                 }
    
    //                 $result = $this->db->query($sql)->row_array();
    //                 if ($result != null) {
    //                     $actual_value = $value;
    //                     $value = $result['label'];
    //                     $status = FIELD_STATUS_OK;
    //                     break;
    //                 }
    //             }

    //             //search string
    //             $sql = "select " .$field['lookup_col_value']. " as value from " .$field['lookup_table']. 
    //             " where is_active=1 and " .$field['lookup_col_label']. " like '%" .$value. "%'";
    
    //             //limit by satker
    //             if (!empty($id_satker) && !empty($field['lookup_has_satker'])) {
    //                 $sql .= " AND id_satker=" .$this->db->escape($id_satker);
    //             }
    
    //             $sql .= " order by length(" .$field['lookup_col_label']. ")";
    
    //             $result = $this->db->query($sql)->result_array();
    //             if ($result == null) {
    //                 $actual_value = null;
    //                 $value = null;
    //                 if ($field['lookup_search'] == 1) {
    //                     $status = FIELD_STATUS_SEARCH;
    //                 } 
    //                 else {
    //                     $status = FIELD_STATUS_PICK;
    //                 }
    //                 $error_message = "Jawaban tidak valid.";
    //             }
    //             else if (count($result) == 1) {
    //                 $actual_value = $result[0]['value'];
    //                 $status = FIELD_STATUS_OK;
    //             }
    //             else if (count($result) <= 10) {
    //                 //multiple matches => pick list
    //                 $actual_value = null;
    //                 $status = FIELD_STATUS_PICK;
    //             }
    //             else {
    //                 //multiple matches => search
    //                 $actual_value = null;
    //                 $status = FIELD_STATUS_SEARCH;
    //                 $error_message = "Terlalu banyak hasil yang mirip.";
    //             }         

    //             break;
    //         }
            
    //         $status = FIELD_STATUS_OK;
    //     }
    //     while (false);

    //     //if it is satker, use for limiting the options
    //     if ($field['column_name'] == 'id_satker') {
    //         if (!empty($actual_value)) {
    //             //update session
    //             $sql = "update wa_session set id_satker=? where id_session=?";
    //             $this->db->query($sql, array($actual_value, $id_session));
    //         }
    //     }           

    //     //update field
    //     $valuepair = array(
    //         "value" => $value,
    //         "actual_value" => $actual_value,
    //         "status" => $status,
    //         "error_message" => $error_message,
    //         "updated_date" => date('Y-m-d H:i:s'),
    //         "updated_by" => $draft('id_user')
    //     );

    //     $result = $this->db->update("wa_draft_field", $valuepair, ['id_draft_field' => $id_draft_field]);
    //     if (!$result)   return 0;

    //     //update draft status
    //     $sql = "select count(*) as cnt from wa_draft_field where is_active=1 and id_draft_pelaporan=? and status!=" .$this->db->escape(FIELD_STATUS_OK);
    //     $result = $this->db->query($sql, array($draft_id))->row_array();

    //     if ($result['cnt'] == 0) {
    //         $valuepair = array(
    //             "current_field" => null,
    //             "completed_at" => date('Y-m-d H:i:s'),
    //             "updated_date" => date('Y-m-d H:i:s'),
    //             "updated_by" => $draft('id_user')
    //         );

    //         $result = $this->db->update("wa_draft_pelaporan", $valuepair, ['id_draft_pelaporan' => $draft_id]);
    //     }

    //     return 1;
    // }

    // function get_response($draft_id) {
    //     //get draft info
    //     $sql = "select * from wa_draft_pelaporan where is_active=1 and id_draft_pelaporan=?";
    //     $draft = $this->db->query($sql, array($draft_id))->row_array();
    //     if ($draft == null) return 0;

    //     $id_session = $draft['id_session'];
    //     $id_satker = $draft['id_satker'];

    //     $message = '';

    //     if (!empty($draft['completed_at']) && !empty($draft['confirmed_at'])) {

    //         return $message;
    //     }

    //     if (!empty($draft['completed_at']) && empty($draft['confirmed_at'])) {
    //         //header
    //         $template = $draft['template'];
    //         $message .= "#!" .$template. $this->get_new_line();
            
    //         //field
    //         $sql = "select * from wa_draft_field where is_active=1 and id_draft_pelaporan=? and coalesce(field,'')!='' order by order_no asc";
    //         $fields = $this->db->query($sql, array($draft_id))->result_array();
    //         foreach($fields as $f) {
    //             $message .= "#" .strtoupper($f['field']). ": " .$f['value']. $this->get_new_line();
    //         }

    //         $message .= "#!" .$template. $this->get_new_line();

    //         $message .= $this->get_new_line();
    //         $message .= "Ketik YA untuk menyimpan, TIDAK untuk memulai ulang. " .$this->get_new_line(). "Untuk memperbaiki salah satu data, ketik nama kolom (contoh: " .strtoupper($fields[0]['field']). ").";

    //         return $message;
    //     }

    //     //current field
    //     $name = $draft['current_field'];
    //     if (empty($name)) {
    //         //get the first invalid field
    //         $sql = "select * from wa_draft_field where is_active=1 and id_draft_pelaporan=? and status!=1 and coalesce(field,'')!='' order by order_no asc limit 1";
    //         $field = $this->db->query($sql, array($draft_id))->row_array();
    //         if ($field == null) {
    //             //what to do?
    //             return null;
    //         }
    //         else {
    //             $name = $field ['field'];
    //         }
    //     }
    //     else {
    //         $sql = "select * from wa_draft_field where is_active=1 and id_draft_pelaporan=? and field=?";
    //         $field = $this->db->query($sql, array($draft_id, $name))->row_array();
    //         if ($field == null) {
    //             //what to do?
    //             return null;
    //         }
    //     }

    //     if ($field['status'] == 1) {
    //         //get next invalid field
    //         $sql = "select * from wa_draft_field where is_active=1 and id_draft_pelaporan=? and status!=1 and coalesce(field,'')!='' order by order_no asc limit 1";
    //         $field = $this->db->query($sql, array($draft_id))->row_array();
    //         if ($field == null) {
    //             //what to do?
    //             return null;
    //         }
    //         else {
    //             $name = $field ['field'];
    //         }
    //     }

    //     if (!empty($field['error_message'])) {
    //         $message .= $field['error_message'] .$this->get_new_line() .$this->get_new_line();
    //     }
        
    //     do {
    //         if ($field['column_name'] == 'tags') {
    //             $id_activity_jenis = $draft['id_activity_jenis'];

    //             $category = "";
    //             $sql = "select * from wa_draft_field where is_active=1 and id_draft_pelaporan=? and column_name='id_activity_jenis2' order by order_no asc limit 1";
    //             $field2 = $this->db->query($sql, array($draft_id))->row_array();
    //             if ($field2 != null) {
    //                 $category = $field2['actual_value'];
    //             }

    //             //pick list
    //             $message .= $field['field'] ."?". $this->get_new_line();

    //             $sql = "select " .$field['lookup_col_value']. " as value, " .$field['lookup_col_label']. " as label from " .$field['lookup_table']. 
    //                     " where is_active=1";
    
    //             // if (!empty($field['value'])) {
    //             //     $sql .= " and " .$field['lookup_col_label']. " like '%" .$field['value']. "%'";
    //             // }
    
    //             //limit by satker
    //             if (!empty($id_satker) && !empty($field['lookup_has_satker'])) {
    //                 $sql .= " AND id_satker=" .$this->db->escape($id_satker);
    //             }
    
    //             //limit by jenis dan kategory
    //             if (!empty($category)) {
    //                 $sql .= " AND (id_activity_jenis=" .$this->db->escape($id_activity_jenis). " OR id_activity_jenis=" .$this->db->escape($category). ")";
    //             }
    //             else {
    //                 $sql .= " AND id_activity_jenis=" .$this->db->escape($id_activity_jenis);
    //             }

    //             //$sql .= " order by length(" .$field['lookup_col_label']. ")";
    
    //             $result = $this->db->query($sql)->result_array();
    //             if (!empty($result)) {
    //                 foreach($result as $r) {
    //                     $message .= "  [" .$r['value']. "] " .$r['label']. $this->get_new_line();
    //                 }
    //             }
    
    //             $message .= $this->get_new_line();
    //             if (count($result) > 1) {
    //                 $message .= "Pilih satu atau lebih (contoh: " .$result[0]['value']. "," .$result[1]['value']. ").";
    //             }
    //             else {
    //                 $message .= "Pilih satu atau lebih.";
    //             }
                
    //             break;
    //         }

    //         if (!empty($field['lookup_table'])) {
    //             if ($field['status'] == FIELD_STATUS_SEARCH) {
    //                 //search
    //                 $message .= $field['field'] ."?". $this->get_new_line();
                    
    //                 $message .= $this->get_new_line();
    //                 if (!empty($field['prompt'])) {
    //                     $message .= $field['prompt'];
    //                 }
    //                 else {
    //                     $message .= "Ketik bebas.";
    //                 }
    //             }
    //             else if ($field['status'] == FIELD_STATUS_PICK) {
    //                 //pick list
    //                 $message .= $field['field'] ."?". $this->get_new_line();
        
    //                 $sql = "select " .$field['lookup_col_value']. " as value, " .$field['lookup_col_label']. " as label from " .$field['lookup_table']. 
    //                         " where is_active=1";
        
    //                 if (!empty($field['value'])) {
    //                     $sql .= " and " .$field['lookup_col_label']. " like '%" .$field['value']. "%'";
    //                 }
        
    //                 //limit by satker
    //                 if (!empty($id_satker) && !empty($field['lookup_has_satker'])) {
    //                     $sql .= " AND id_satker=" .$this->db->escape($id_satker);
    //                 }
        
    //                 //$sql .= " order by length(" .$field['lookup_col_label']. ")";
        
    //                 $result = $this->db->query($sql)->result_array();
    //                 if (!empty($result)) {
    //                     foreach($result as $r) {
    //                         $message .= "  [" .$r['value']. "] " .$r['label']. $this->get_new_line();
    //                     }
    //                 }
        
    //                 $message .= $this->get_new_line();
    //                 if (!empty($field['prompt'])) {
    //                     $message .= $field['prompt'];
    //                 }
    //                 else if (!empty($field['lookup_multiple'])) {
    //                     if (count($result) > 1) {
    //                         $message .= "Pilih satu atau lebih (contoh: " .$result[0]['value']. "," .$result[1]['value']. ").";
    //                     }
    //                     else {
    //                         $message .= "Pilih satu atau lebih.";
    //                     }
    //                 }
    //                 else {
    //                     if (count($result) >= 1) {
    //                         $message .= "Pilih salah satu (contoh: " .$result[0]['value']. ").";
    //                     }
    //                     else {
    //                         $message .= "Pilih salah satu.";
    //                     }
    //                 }

    //             }
    //             else {
    //                 //prompt
    //                 $message .= $field['field'] ."?". $this->get_new_line();
        
    //                 $message .= $this->get_new_line();
    //                 if (!empty($field['prompt'])) {
    //                     $message .= $field['prompt'];
    //                 }
    //                 else {
    //                     $message .= "Ketik bebas.";
    //                 }
    //             }

    //             break;
    //         }

    //         //prompt
    //         $message .= $field['field'] ."?". $this->get_new_line();

    //         $message .= $this->get_new_line();
    //         if (!empty($field['prompt'])) {
    //             $message .= $field['prompt'];
    //         }
    //         else {
    //             $message .= "Ketik bebas.";
    //         }
    //     }
    //     while (false);

    //     //update current field in draft-pelaporan
    //     $valuepair = array(
    //         "current_field" => $field['field'],
    //         "updated_date" => date('Y-m-d H:i:s'),
    //         "updated_by" => $draft('id_user')
    //     );

    //     $result = $this->db->update("wa_draft_pelaporan", $valuepair, ['id_draft_pelaporan' => $draft_id]);

    //     return $message;
    // }

    // function check_draft_status($draft_id) {        
    //     $sql = "select * from wa_draft_pelaporan where is_active=1 and id_draft_pelaporan=?";
    //     $draft = $this->db->query($sql, array($draft_id))->row_array();

    //     if ($draft == null) return 1;

    //     if (!empty($draft['completed_at']) || !empty($draft['autoclosed_at'])) return 1;
        
    //     return 0;
    // }

    // function create_pelaporan($draft_id) {
    //     //get draft info
    //     $sql = "select * from wa_draft_pelaporan where is_active=1 and id_draft_pelaporan=?";
    //     $draft = $this->db->query($sql, array($draft_id))->row_array();
    //     if ($draft == null) return 0;

    //     $template = $draft['template'];

    //     //get field info
    //     $sql = "select * from wa_draft_field where is_active=1 and id_draft_pelaporan=? and coalesce(field,'')!='' order by order_no asc";
    //     $fields = $this->db->query($sql, array($draft_id))->result_array();
    //     if ($fields == null) {
    //         //what to do?
    //         return 0;
    //     }

    //     //insert
    //     $valuepair = array();
    //     foreach($fields as $f) {
    //         $valuepair[ $f['column_name'] ] = (!empty($f['actual_value'])) ? $f['actual_value'] : $f['value'];
    //     }

    //     // $sql = "select * from wa_template where is_active=1 and template=?";
    //     // $result = $this->db->query($sql, array($template))->row_array();
    //     // if ($result == null) {
    //     //     return 0;
    //     // }

    //     //$valuepair[ 'id_satker' ] = $draft['id_activity_jenis'];
    //     $valuepair[ 'id_activity_jenis' ] = $draft['id_activity_jenis'];

    //     $alamat = '';
    //     foreach($fields as $f) {
    //         if ($f['column_name'] == 'where') {
    //             $alamat = $f['value'];
    //             break;
    //         }
    //     }

    //     $geocode = $this->get_geocode($alamat);
    //     if ($geocode != null) {
    //         $valuepair['id_geografi'] = $geocode['id_geografi'];
    //         $valuepair['latitude'] = $geocode['latitude'];
    //         $valuepair['longitude'] = $geocode['longitude'];
    //     }

    //     $valuepair['is_active'] = 1;
    //     $valuepair['created_date'] = date('Y-m-d H:i:s');
    //     $valuepair['created_by'] = $draft['id_user'];

    //     $this->log_message(json_encode($valuepair));

    //     $result = $this->db->insert('rekap_activity_sosial', $valuepair);
    //     if ($result == null)    return 0;

    //     //get session
    //     $res = $this->db->query("SELECT LAST_INSERT_ID(id_activity_sosial) as insert_id from rekap_activity_sosial order by LAST_INSERT_ID(id_activity_sosial) desc limit 1;")->row_array();
    //     $id = $res['insert_id']; 

    //     //update link
    //     $sql = "update wa_draft_pelaporan set id_activity_sosial=" .$id. " where is_active=1 and id_draft_pelaporan=?";
    //     $draft = $this->db->query($sql, array($draft_id));
         
    //     return $id;
    // }

}
