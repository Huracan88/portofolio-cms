<?php

namespace App\Models;

use Database\Factories\EducationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $institution_es
 * @property string $institution_en
 * @property string $degree_es
 * @property string $degree_en
 * @property int $started_at
 * @property int|null $ended_at
 * @property string|null $license_number
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $institution
 * @property-read string $degree
 */
class Education extends Model
{
    /** @use HasFactory<EducationFactory> */
    use HasFactory;

    protected $table = 'educations';

    protected $fillable = [
        'institution_es', 'institution_en',
        'degree_es', 'degree_en',
        'started_at', 'ended_at',
        'license_number', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'integer',
            'ended_at' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function getInstitutionAttribute(): string
    {
        return $this->{'institution_'.app()->getLocale()} ?? $this->institution_en;
    }

    public function getDegreeAttribute(): string
    {
        return $this->{'degree_'.app()->getLocale()} ?? $this->degree_en;
    }
}
