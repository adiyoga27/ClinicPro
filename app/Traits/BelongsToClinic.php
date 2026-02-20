<?php

namespace App\Traits;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToClinic
{
    protected static function bootBelongsToClinic(): void
    {
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->clinic_id && empty($model->clinic_id)) {
                $model->clinic_id = Auth::user()->clinic_id;
            }
        });

        // Auto-scope queries to the current user's clinic unless superadmin
        static::addGlobalScope('clinic', function (Builder $builder) {
            if (Auth::check() && Auth::user()->clinic_id) {
                $builder->where($builder->getModel()->getTable() . '.clinic_id', Auth::user()->clinic_id);
            }
        });
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
