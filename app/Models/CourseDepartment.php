<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseDepartment extends Pivot
{
    protected $table = 'course_departments';

    public $timestamps = false;

    protected $fillable = ['course_id', 'department_id'];
}
