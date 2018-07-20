<?php

namespace Ferrl\Paywall\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel
{
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        $prefix = config('paywall.table_prefix');

        return $prefix.parent::getTable();
    }
}
