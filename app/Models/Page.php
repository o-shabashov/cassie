<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Page extends Model
{
    use SoftDeletes, HasFactory, Searchable;

    protected $fillable = [
        'title',
        'url',
        'sections',
    ];

    protected $casts = [
        'sections' => 'array',
    ];

    public function toSearchableArray(): array
    {
        return $this->toArray();
    }
}
