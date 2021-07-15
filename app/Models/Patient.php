<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'patients';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'email', 'mobile', 'national_id',
        'first_name', 'second_name', 'last_name', 'province',
        'district', 'age', 'gender', 'start_age', 'tobaco_type',
        'daily_cigarettes', 'weekly_hookah,marital_status',
        'education', 'job', 'step',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = ['name'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['second_name'] . ' ' . $this->attributes['last_name'];
    }
}
