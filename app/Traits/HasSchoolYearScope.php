<?php

namespace App\Traits;

use App\Models\SchoolYear;

trait HasSchoolYearScope
{
    /**
     * Scope to filter by current school year.
     */
    public function scopeCurrentSchoolYear($query)
    {
        $schoolYearId = SchoolYear::currentId();

        return $schoolYearId
            ? $query->where('school_year_id', $schoolYearId)
            : $query;
    }

    /**
     * Scope to filter by given school year ID.
     */
    public function scopeForSchoolYear($query, $schoolYearId)
    {
        return $query->where('school_year_id', $schoolYearId);
    }
}
