<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class GymScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {

        if ((!method_exists($model, 'gym'))) {
            return $builder;
        }

        if (auth()->hasUser()) {
            if (gym()) {
                $builder->where($model->getTable() . '.gym_id', gym()->id);
            }
        }
    }
} 