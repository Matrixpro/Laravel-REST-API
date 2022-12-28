<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function read(User $user, Request $request)
    {
        return $request->user()->tokenCan('product:read');
    }

    /**
     * Determine whether the user can view a specific model.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function show(Request $request, Product $product)
    {
        return $request->user()->id === $product->user_id &&
            $request->user()->tokenCan('product:read');
    }
}
