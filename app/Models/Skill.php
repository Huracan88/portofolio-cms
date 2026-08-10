<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name_es
 * @property string $name_en
 * @property int $level
 * @property int $sort_order
 * @property bool $is_visible
 * @property string|null $icon
 * @property string|null $group
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $name
 */
class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_es', 'name_en', 'level', 'sort_order', 'is_visible', 'icon', 'group',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function getNameAttribute(): string
    {
        return $this->{'name_'.app()->getLocale()} ?? $this->name_en;
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_skill');
    }
}
