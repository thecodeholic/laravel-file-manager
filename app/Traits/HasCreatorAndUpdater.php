<?php
/**
 * User: Zura
 * Date: 5/13/2023
 * Time: 6:20 PM
 */

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasCreatorAndUpdater
{
    protected static function bootHasCreatorAndUpdater()
    {
        static::creating(function($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function($model) {
            $model->updated_by = Auth::id();
        });
    }
}
