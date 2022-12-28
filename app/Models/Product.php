<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /*
     * Token permissions
     */
    public static $permissions = [
        'product:create',
        'product:read',
        'product:update',
        'product:delete',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Interact with the product's price.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value / 100,
            set: fn($value) => $value * 100,
        );
    }
}
