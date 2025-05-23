<?php

namespace App\Models;

use App\Models\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $title
 * @property string $url
 * @property array $fields
 * @property int $shopify_id
 */
class Product extends Model
{
    use HasFactory, HasTenant, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'fields',
        'url',
        'shopify_id',
    ];

    protected $casts = [
        'fields' => 'array',
    ];
}
