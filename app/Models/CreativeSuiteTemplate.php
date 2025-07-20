<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreativeSuiteTemplate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'category', // Keep temporarily for migration
        'category_id', // New foreign key
        'preview_image',
        'stage_width',
        'stage_height',
        'template_data',
        'tags',
        'is_active',
        'usage_count'
    ];
    
    protected $casts = [
        'template_data' => 'array',
        'tags' => 'array',
        'is_active' => 'boolean',
        'usage_count' => 'integer',
        'stage_width' => 'integer',
        'stage_height' => 'integer'
    ];
    
    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(CreativeSuiteCategory::class, 'category_id');
    }

    // Accessor to ensure category returns relationship object, not string field
    public function getCategoryAttribute()
    {
        // If we have a category_id, return the relationship
        if ($this->category_id) {
            return $this->getRelationValue('category');
        }
        // Otherwise return null (old string field is deprecated)
        return null;
    }
    
    // Scopes for filtering
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
    
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
    
    public function scopePopular(Builder $query): Builder
    {
        return $query->orderBy('usage_count', 'desc');
    }
    
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%")
              ->orWhereJsonContains('tags', $search);
        });
    }
    
    // Increment usage count
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
    
    // Get formatted template data for frontend
    public function getFormattedDataAttribute(): array
    {
        return [
            'id' => $this->id,
            'preview' => $this->preview_image,
            'category' => $this->category?->getTranslatedName(),
            'category_id' => $this->category_id,
            'category_key' => $this->category?->key,
            'name' => $this->name,
            'data' => [
                'name' => $this->template_data['name'] ?? $this->name,
                'stage' => [
                    'width' => $this->template_data['stage']['width'] ?? $this->stage_width ?? 1080,
                    'height' => $this->template_data['stage']['height'] ?? $this->stage_height ?? 1080
                ],
                'nodes' => $this->template_data['nodes'] ?? []
            ],
            'tags' => $this->tags,
            'usage_count' => $this->usage_count
        ];
    }
}
