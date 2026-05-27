<?php 

namespace App\Models\Core\Crud;

use Exception;
use App\Models\Core\MCrud5;

class MSetting extends MCrud5
{
    protected $TABLE_NAME = "dbo_settings";
    protected $COLUMNS = array('id', 'group', 'name', 'value');
    protected $INSERT_COLUMNS = array('group', 'name', 'value');
    protected $COMPULSORY_COLUMNS = array('name', 'value');

    function __construct() {
        parent::__construct();
    }

    function get_setting($group, $name) {
        $filters = array (
            'GROUP' => $group,
            'NAME' => $name
        );

        $result = $this->list(0, null, $filters);
        if (empty($result)) return null;

        return $result[0]['VALUE'];
    }
}

  