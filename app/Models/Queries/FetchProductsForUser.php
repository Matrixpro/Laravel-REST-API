<?php

namespace App\Models\Queries;

use App\Contracts\FetchProductsForUserContract;
use Illuminate\Database\Eloquent\Builder;

class FetchProductsForUser implements FetchProductsForUserContract {

    public function handle(Builder $builder, int $user_id): Builder
    {
        return $builder->where('user_id', $user_id);
    }
}
