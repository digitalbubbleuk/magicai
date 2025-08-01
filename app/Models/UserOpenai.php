<?php

namespace App\Models;

use App\Domains\Engine\Enums\EngineEnum;
use App\Domains\Entity\Enums\EntityEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserOpenai extends Model
{
    /**
     * @var \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|\Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    protected $table = 'user_openai';

    protected $guarded = [];

    protected $fillable = [
        'is_demo',
        'request_id',
        'team_id',
        'title',
        'slug',
        'user_id',
        'openai_id',
        'input',
        'response',
        'output',
        'hash',
        'credits',
        'words',
        'payload',
        'storage',
        'status',
        'request_id',
        'is_advanced_image',
        'engine',
        'model',
    ];

    protected $casts = [
        'payload' => 'array',
        'engine'  => EngineEnum::class,
        'model'   => EntityEnum::class,
    ];

    protected $appends = [
        'format_date',
        'generator_type',
        'output_url',
    ];

    // STORAGE
    public const STORAGE_LOCAL = 'public';

    public const STORAGE_AWS = 's3';

    public function outputUrl(): Attribute
    {
        if (Str::contains($this->output, 'loading.svg')) {
            return Attribute::make(function () {
                return url('themes/default/assets/img/loading.svg');
            });
        }

        if ($this->output) {
            if (Str::contains($this->output, 'https://') || Str::contains($this->output, 'http://')) {
                return Attribute::make(function () {
                    return $this->output;
                });
            }

            if ($this->storage === 'uploads' || $this->storage === 'public') {
                return Attribute::make(function () {
                    return Storage::disk('uploads')->url(str_replace('uploads', '', $this->output));
                });
            }

            if ($this->storage === 's3') {
                return Attribute::make(function () {
                    return Storage::disk('s3')->url($this->output);
                });
            }

            if ($this->storage === 'r2') {
                return Attribute::make(function () {
                    return Storage::disk('r2')->url($this->output);
                });
            }
        }

        return Attribute::make(function () {
            return url('themes/default/assets/img/loading.svg');
        });
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(OpenAIGenerator::class, 'openai_id', 'id');
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folders::class);
    }

    public function getFormatDateAttribute()
    {
        if ($this?->created_at) {
            return $this?->created_at?->format('M d, Y');
        } else {
            return null;
        }
    }

    public function generatorWithType(): BelongsTo
    {
        return $this->belongsTo(OpenAIGenerator::class, 'openai_id', 'id')->select(['id', 'type']);
    }

    public function isFavoriteDoc(): bool
    {
        return (bool) $this->isFavoriteDocRelation;
    }

    public function isFavoriteDocRelation(): HasOne
    {
        return $this->hasOne(UserDocsFavorite::class, 'user_openai_id', 'id')
            ->where('user_id', auth()->id());
    }

    public function getGeneratorTypeAttribute()
    {
        return $this->generator?->type;
    }
}
