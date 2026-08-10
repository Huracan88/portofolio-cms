<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $company_es
 * @property string $company_en
 * @property string $position_es
 * @property string $position_en
 * @property string|null $description_es
 * @property string|null $description_en
 * @property Carbon $started_at
 * @property Carbon|null $ended_at
 * @property bool $is_current
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $company
 * @property-read string $position
 * @property-read string|null $description
 */
class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_es', 'company_en', 'position_es', 'position_en',
        'description_es', 'description_en', 'started_at', 'ended_at',
        'is_current', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'ended_at' => 'date',
            'is_current' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getCompanyAttribute(): string
    {
        return $this->{'company_'.app()->getLocale()} ?? $this->company_en;
    }

    public function getPositionAttribute(): string
    {
        return $this->{'position_'.app()->getLocale()} ?? $this->position_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->{'description_'.app()->getLocale()} ?? $this->description_en;
    }
}
