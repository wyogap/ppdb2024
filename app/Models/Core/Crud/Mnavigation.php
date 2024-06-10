<?php 

namespace App\Models\Core\Crud;

class Mnavigation
{
    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    
    public function get_navigation($role_id = null, $page_group = null) {
        if ($role_id == null) {
            $role_id = $this->session->get('role_id');
        }

        //table metas
        $builder = $this->db->table('dbo_crud_navigations a');
        $builder->select('a.*, b.name as page_name');
        $builder->orderBy('a.order_no asc');
        $builder->join('dbo_crud_pages b', 'b.id=a.page_id and b.is_deleted=0', 'LEFT OUTER');

        $builder->where('a.role_id', $role_id);
        $builder->where('a.is_deleted', 0);

        if ($page_group != null) {
            $builder->groupStart();
            $builder->where('a.page_group', $page_group);
            $builder->orWhere('a.page_group', NULL);
            $builder->groupEnd();
        }

        $arr = $builder->get()->getResultArray();
        if ($arr == null) {
            return false;
        }

        $cur_navitem = null;
        foreach($arr as $key => $row) {
            if ($row['nav_type'] == 'item') {
                $cur_navitem = $key;

                $arr[$key]['pages'] = array();
                $arr[$key]['tags'] = array();
                if ($row['action_type'] == 'page' && !empty($row['page_name'])) {
                    $arr[$key]['pages'][] = $row['page_name'];
                    if (!empty($row['nav_tag'])) {
                        $arr[$key]['tags'][] = $row['nav_tag'];
                    }
                }
                $arr[$key]['subitems'] = array();
            }
            else if ($row['nav_type'] == 'subitem'){
                //add as subitem in the current navitem
                if (isset($arr[$cur_navitem])) {
                    $arr[$cur_navitem]['subitems'][] = $row;
                    $arr[$cur_navitem]['pages'][] = $row['page_name'];
                    if (!empty($row['nav_tag'])) {
                        $arr[$cur_navitem]['tags'][] = $row['nav_tag'];
                    }
                }
                //remove
                unset($arr[$key]);
            }
        }

        return $arr;
    }

}