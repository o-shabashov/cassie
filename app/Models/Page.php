<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'url',
        'sections',
    ];
    protected $casts = [
        'sections' => 'array',
    ];
    protected $hidden;

    public function toSearchableArray(): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'url'        => $this->url,
            'sections'   => $this->sections,
            'created_at' => $this->created_at,
        ];
    }
}
