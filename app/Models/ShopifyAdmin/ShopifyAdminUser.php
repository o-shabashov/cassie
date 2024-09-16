<?php

namespace App\Models\ShopifyAdmin;

use Illuminate\Database\Eloquent\Model;

class ShopifyAdminUser extends Model
{
    protected $connection = 'shopify_admin';
    protected $table      = 'users';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'cassie_id',
        'cassie_access_token',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'cassie_access_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
