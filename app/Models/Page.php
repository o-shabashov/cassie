<?php

namespace App\Models;

use App\Models\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $title
 * @property string $url
 * @property array $sections
 */
class Page extends Model
{
    use HasFactory, HasTenant, SoftDeletes;

    protected $table = 'pages';

    protected $fillable = [
        'title',
        'url',
        'sections',
    ];

    protected $casts = [
        'sections' => 'array',
    ];

    public function getConnection()
    {
        return parent::getConnection(); // TODO: Change the autogenerated stub
    }


}
