<?php

namespace App\Models\Core;

use App\Models\Core\BaseController;

/**
 * Class CrudController
 *
 */
interface ICrudModel
{
    function list($filter = null, $limit = null, $offset = null, $orderby = null);

    function detail($id, $filter = null);

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true);

    function delete($id, $filter = null);

    function add($valuepair, $enforce_edit_columns = true);

    function lookup($filter = null);

    function import($file, $filters = null);
}


