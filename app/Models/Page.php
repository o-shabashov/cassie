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

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'sections' => $this->sections,
        ];
    }
}
