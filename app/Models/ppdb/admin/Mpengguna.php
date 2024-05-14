<?php 
Class Mpengguna 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function ubahpassword($pengguna_id, $username)
	{
		// $pengguna_id = $_POST["pengguna_id"] ?? null; 
		// $username = $_POST["username"] ?? null; 

		$password = str_replace("@ppdb.go.id", "", $username);
		$data = array(
			'password' => md5($password),
			'updated_on' => date("Y/m/d H:i:s")
		);

		$builder = $this->db->table('dbo_users');
		$builder->where(array('pengguna_id'=>$pengguna_id,'role_id'=>1,'is_deleted'=>0));
		return $builder->update($data);
	}

	function tcg_cari_pengguna($nama, $username, $sekolah_id, $peran_id){
		$query = "select a.*, a.user_name as username, b.nama as sekolah, c.role as peran
		from dbo_users a
		left outer join ref_sekolah b on a.sekolah_id=b.sekolah_id 
		left join dbo_roles c on a.role_id=c.role_id and c.is_deleted=0
		";

		$where = "a.is_deleted = 0 and a.role_id != 1 and a.nama like '%" . $nama . "%'";
		if (!empty($sekolah_id))
			$where .= " AND a.sekolah_id='" . $sekolah_id . "'";
		if (!empty($username))
			$where .= " AND a.user_name='" . $username . "'";
		if (!empty($peran_id))
			$where .= " AND a.role_id=" . $peran_id;

		$query .= " WHERE " . $where;

		return $this->db->query($query);
	}

	function tcg_detil_pengguna($key) {
		$query = "select a.*, a.user_name as username, b.nama as sekolah, c.role as peran
		from dbo_users a
		left outer join ref_sekolah b on a.sekolah_id=b.sekolah_id 
		left join dbo_roles c on a.role_id=c.role_id and c.is_deleted=0
		WHERE a.role_id != 1 and a.pengguna_id = '$key' and a.is_deleted=0
		";

		return $this->db->query($query);
   }

    function tcg_ubah_pengguna($key, $valuepair) {
		$builder = $this->db->table('dbo_users');
        $builder->where('pengguna_id', $key);
        $builder->update($valuepair);

        return 1;
    }

    function tcg_hapus_pengguna($key) {
        // $builder->where('pengguna_id', $key);
        // $builder->delete('dbo_users');

		$valuepair = array (
			'is_deleted' => 1,
			'updated_on' => date("Y/m/d H:i:s")
		);

		$builder = $this->db->table('dbo_users');
        $builder->where('pengguna_id', $key);
        $builder->update($valuepair);

        return $this->db->affectedRows();
    }

    function tcg_pengguna_baru($valuepair) {
		$uuid = self::uuid();

        if (!empty($valuepair['username'])) {
            $valuepair['user_name'] = $valuepair['username'];
            unset($valuepair['username']);
        }

		//inject password and user id
		$valuepair['password'] = md5($valuepair['user_name']);
		$valuepair['pengguna_id'] = $uuid;
		$valuepair['approval'] = 1;
		$builder = $this->db->table('dbo_users');
        if ($builder->insert($valuepair)) {
            //return the id
            return $uuid;
        } else {
            return "";
        }
    }

	function tcg_pengguna_id_from_username($username) {
		$sql = "select pengguna_id from dbo_users where user_name='$username'";

		$pengguna_id = "";
		foreach($this->db->query($sql)->getResult() as $row) {
			$pengguna_id = $row->pengguna_id;
		}

		return $pengguna_id;
	}

	function tcg_cek_username($pengguna_id, $username) {
		$sql = "select count(*) cnt from dbo_users where user_name='$username' and pengguna_id!='$pengguna_id' and is_deleted=0";

		$cnt = 0;
		foreach($this->db->query($sql)->getResult() as $row) {
			$cnt = $row->cnt;
		}

		return $cnt;
	}

    /**
     * Generate name based md5 UUID (version 3).
     *
     * @example '7e57d004-2b97-0e7a-b45f-5387367791cd'
     *
     * @return string
     */
    public static function uuid()
    {
        // fix for compatibility with 32bit architecture; each mt_rand call is restricted to 32bit
        // two such calls will cause 64bits of randomness regardless of architecture
        $seed = self::numberBetween(0, 2147483647) . '#' . self::numberBetween(0, 2147483647);

        // Hash the seed and convert to a byte array
        $val = md5($seed, true);
        $byte = array_values(unpack('C16', $val));

        // extract fields from byte array
        $tLo = ($byte[0] << 24) | ($byte[1] << 16) | ($byte[2] << 8) | $byte[3];
        $tMi = ($byte[4] << 8) | $byte[5];
        $tHi = ($byte[6] << 8) | $byte[7];
        $csLo = $byte[9];
        $csHi = $byte[8] & 0x3f | (1 << 7);

        // correct byte order for big edian architecture
        if (pack('L', 0x6162797A) == pack('N', 0x6162797A)) {
            $tLo = (($tLo & 0x000000ff) << 24) | (($tLo & 0x0000ff00) << 8)
                | (($tLo & 0x00ff0000) >> 8) | (($tLo & 0xff000000) >> 24);
            $tMi = (($tMi & 0x00ff) << 8) | (($tMi & 0xff00) >> 8);
            $tHi = (($tHi & 0x00ff) << 8) | (($tHi & 0xff00) >> 8);
        }

        // apply version number
        $tHi &= 0x0fff;
        $tHi |= (3 << 12);

        // cast to string
        return sprintf(
            '%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
            $tLo,
            $tMi,
            $tHi,
            $csHi,
            $csLo,
            $byte[10],
            $byte[11],
            $byte[12],
            $byte[13],
            $byte[14],
            $byte[15],
        );
    }
	
    public static function numberBetween($int1 = 0, $int2 = 2147483647)
    {
        $min = $int1 < $int2 ? $int1 : $int2;
        $max = $int1 < $int2 ? $int2 : $int1;

        return mt_rand($min, $max);
    }
}