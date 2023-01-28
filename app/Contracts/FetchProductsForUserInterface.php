<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface FetchProductsForUserInterface
{
    public function handle(Builder $builder, int $user_id): Builder;
}
