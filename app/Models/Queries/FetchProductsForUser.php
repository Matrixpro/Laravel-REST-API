<?php

namespace App\Models\Queries;

use App\Contracts\FetchProductsForUserInterface;
use Illuminate\Database\Eloquent\Builder;

class FetchProductsForUser implements FetchProductsForUserInterface
{

    public function handle(Builder $builder, int $user_id): Builder
    {
        $filters = request('filter', []);

        foreach ($filters as $filter => $value)
            match ($filter) {
                'name' => $builder->where('name', 'LIKE', "%{$value}%")
            };

        return $builder->where('user_id', $user_id);
    }
}
