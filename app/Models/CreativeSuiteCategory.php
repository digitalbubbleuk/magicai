<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class CreativeSuiteCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Relationships
    public function templates(): HasMany
    {
        return $this->hasMany(CreativeSuiteTemplate::class, 'category_id');
    }

    public function activeTemplates(): HasMany
    {
        return $this->templates()->where('is_active', true);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeByKey(Builder $query, string $key): Builder
    {
        return $query->where('key', $key);
    }

    // Helper methods
    public function getTemplateCount(): int
    {
        return $this->activeTemplates()->count();
    }

    public function getTranslatedName(): string
    {
        // For now return the name field, but this can be extended for translations
        // e.g., return __('categories.' . $this->key, [], app()->getLocale()) ?: $this->name;
        return $this->name;
    }

    // Accessor for API responses
    public function getFormattedDataAttribute(): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->getTranslatedName(),
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'template_count' => $this->getTemplateCount()
        ];
    }
}
