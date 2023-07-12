<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KbItem extends BaseModel
{
    use HasAdvancedFilter;
    use SoftDeletes;

    public const PUBLIC_RADIO = [
        'N' => 'No',
        'Y' => 'Yes',
    ];

    public static $search = [
        'question',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $orderable = [
        'id',
        'question',
        'quality.rating',
        'status.status',
        'public',
        'category.category',
    ];

    public $filterable = [
        'id',
        'question',
        'quality.rating',
        'status.status',
        'public',
        'category.category',
    ];

    protected $fillable = [
        'question',
        'quality_id',
        'status_id',
        'public',
        'category_id',
        'solution',
        'notes',
    ];

    public function quality(): BelongsTo
    {
        return $this->belongsTo(KbItemQuality::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(KbItemStatus::class);
    }

    public function getPublicLabelAttribute($value)
    {
        return static::PUBLIC_RADIO[$this->public] ?? null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(KbItemCategory::class);
    }

    public function institution(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function getDeletedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
