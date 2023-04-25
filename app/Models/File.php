<?php

namespace App\Models;

use App\Traits\HasCreatorAndUpdater;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class File extends Model
{
    use HasFactory, HasSlug, HasCreatorAndUpdater, NodeTrait;

    protected $fillable = [
        'name',
        'slug',
        'is_folder',
        'mime',
        'size',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function owner(): Attribute
    {
        return Attribute::make(
            get: function(mixed $value, array $attributes) {
                return $attributes['created_by'] === request()->user()->id ? "me" : $this->user->name;
            }
        );
    }

    public function isOwnedBy($userId): bool
    {
        return $this->created_by == $userId;
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }
}
