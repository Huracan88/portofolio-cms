<?php

namespace App\Models;

use Database\Factories\LanguageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $language_es
 * @property string $language_en
 * @property string $proficiency_es
 * @property string $proficiency_en
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $language
 * @property-read string $proficiency
 */
class Language extends Model
{
    /** @use HasFactory<LanguageFactory> */
    use HasFactory;

    protected $fillable = [
        'language_es', 'language_en',
        'proficiency_es', 'proficiency_en',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function getLanguageAttribute(): string
    {
        return $this->{'language_'.app()->getLocale()} ?? $this->language_en;
    }

    public function getProficiencyAttribute(): string
    {
        return $this->{'proficiency_'.app()->getLocale()} ?? $this->proficiency_en;
    }
}
