<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $fillable = [
        'school_year',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function clubManagers()
    {
        return $this->hasMany(ClubManager::class);
    }

    public static function active(): ?self
    {
        return self::where('status', 'active')->orderByDesc('id')->first()
            ?? self::orderByDesc('id')->first();
    }

    public static function current(): ?self
    {
        if (! app()->runningInConsole()) {
            $sessionSchoolYearId = static::sessionCurrentId();

            if ($sessionSchoolYearId) {
                $schoolYear = self::find($sessionSchoolYearId);

                if ($schoolYear) {
                    return $schoolYear;
                }
            }
        }

        return static::active();
    }

    public static function currentId(): ?int
    {
        return static::current()?->id;
    }

    public static function sessionCurrentId(): ?int
    {
        if (app()->runningInConsole()) {
            return null;
        }

        return (int) (session('school_year_id') ?? data_get(session('sy'), 'id') ?? 0) ?: null;
    }

    public static function syncSession(?self $schoolYear = null): ?self
    {
        $schoolYear ??= static::active();

        if (! app()->runningInConsole() && $schoolYear) {
            session()->put('school_year_id', $schoolYear->id);
            session()->put('sy', $schoolYear);
        }

        return $schoolYear;
    }
}
