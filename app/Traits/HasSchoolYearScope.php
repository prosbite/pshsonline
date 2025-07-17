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
        return $query->where('school_year_id', SchoolYear::currentId());
    }

    /**
     * Scope to filter by given school year ID.
     */
    public function scopeForSchoolYear($query, $schoolYearId)
    {
        return $query->where('school_year_id', $schoolYearId);
    }
}
