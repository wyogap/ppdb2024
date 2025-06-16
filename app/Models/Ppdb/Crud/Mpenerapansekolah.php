<?php 

namespace App\Models\Ppdb\Crud;

use App\Libraries\AuditTrail;
use App\Models\Core\Mcrud_ext;

class Mpenerapansekolah extends Mcrud_ext
{
    protected static $TABLE_ID = 203;
    protected static $TABLE_NAME = "cfg_penerapan_sekolah";
    protected static $PRIMARY_KEY = "penerapan_sekolah_id";
    protected static $LOOKUP_KEY = "penerapan_sekolah_id";

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        //enforce jalur_id
        if (isset($valuepair['penerapan_id'])) {
            $penerimaan_id = $valuepair['penerapan_id'];
            $penerapan = $this->get_penerapan($penerimaan_id);
            if (empty($penerapan)) {
                return 0;
            }
            $valuepair['jalur_id'] = $penerapan['jalur_id'];
        }

        //enforce tahun_ajaran_id, putaran, sekolah_id, sekolah_dapodik_id
        if (isset($valuepair['kuota_sekolah_id'])) {
            $kuota_sekolah_id = $valuepair['kuota_sekolah_id'];
            $kuota = $this->get_kuota_sekolah($kuota_sekolah_id);
            if (empty($kuota)) {
                return 0;
            }
            $valuepair['tahun_ajaran_id'] = $kuota['tahun_ajaran_id'];
            $valuepair['putaran'] = $kuota['putaran'];
            $valuepair['sekolah_id'] = $kuota['sekolah_id'];
            $valuepair['sekolah_dapodik_id'] = $kuota['sekolah_dapodik_id'];
        }
        
        //enforce kuota_original
        if (isset($valuepair['kuota'])) {
            $valuepair['kuota_orig'] = $valuepair['kuota'];
        }

        //var_dump($valuepair); exit;

        return parent::update($id, $valuepair, $filter, $enforce_edit_columns);
    }

    function add($valuepair, $enforce_edit_columns = true) {
        $penerimaan_id = $valuepair['penerapan_id'];
        $kuota_sekolah_id = $valuepair['kuota_sekolah_id'];

        if (empty($penerimaan_id) || empty($kuota_sekolah_id))  return 0;

        //enforce tahun_ajaran_id, putaran, sekolah_id, sekolah_dapodik_id
        $kuota = $this->get_kuota_sekolah($kuota_sekolah_id);
        if (empty($kuota)) {
            return 0;
        }

        $valuepair['tahun_ajaran_id'] = $kuota['tahun_ajaran_id'];
        $valuepair['putaran'] = $kuota['putaran'];
        $valuepair['sekolah_id'] = $kuota['sekolah_id'];
        $valuepair['sekolah_dapodik_id'] = $kuota['sekolah_dapodik_id'];

        //enforce jalur_id
        $penerapan = $this->get_penerapan($penerimaan_id);
        if (empty($penerapan)) {
            return 0;
        }

        $valuepair['jalur_id'] = $penerapan['jalur_id'];

        //enforce kuota_original
        $valuepair['kuota_orig'] = $valuepair['kuota'];

        return parent::add($valuepair, $enforce_edit_columns);
    }

    private function get_kuota_sekolah($kuota_sekolah_id) {
		$builder = $this->db->table('cfg_kuota_sekolah a');
		$builder->select('*');
		$builder->where(array('a.kuota_sekolah_id'=>$kuota_sekolah_id));

		$result = $builder->get()->getRowArray();
		if ($result == null) return null;
        
        return $result;
    }

    private function get_penerapan($penerimaan_id) {
		$builder = $this->db->table('cfg_penerapan a');
		$builder->select('*');
		$builder->where(array('a.penerapan_id'=>$penerimaan_id));

		$result = $builder->get()->getRowArray();
		if ($result == null) return null;
        
        return $result;
    }    

    function generate_penerapan($kuota_sekolah_id) {
        $sql = "
        INSERT INTO `cfg_penerapan_sekolah`
        (
            `kuota_sekolah_id`, `penerapan_id`, `tahun_ajaran_id`, `putaran`, `jalur_id`,
            `sekolah_id`, `sekolah_dapodik_id`, `kuota`, `kuota_orig`
        )
        select a.kuota_sekolah_id, c.penerapan_id, a.tahun_ajaran_id, a.putaran, c.jalur_id,
            b.sekolah_id, b.dapodik_id as sekolah_dapodik_id, a.kuota_total*c.persen_kuota/100 as kuota, a.kuota_total*c.persen_kuota/100 as kuota_orig
        from cfg_kuota_sekolah a 
        join ref_sekolah b on b.sekolah_id=a.sekolah_id and b.is_deleted=0
        join cfg_penerapan c on c.jenjang_id=b.jenjang_id 
            and ((c.sekolah_negeri=1 and b.status='N') or (c.sekolah_swasta=1 and b.status='S'))
            and c.is_deleted=0
        left join cfg_penerapan_sekolah x on x.sekolah_id=b.sekolah_id and x.penerapan_id=c.penerapan_id and x.is_deleted=0
        where a.kuota_sekolah_id = ? and x.penerapan_sekolah_id is null
        order by c.urutan";

        $this->db->query($sql, array($kuota_sekolah_id));
        $affected = $this->db->affectedRows();
        
        return $affected;
    }

    // public function get_penerapan_lookup($kuota_sekolah_id) {

    // }
}

?>