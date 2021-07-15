<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorAppointment extends Model
{
    use SoftDeletes;
    use HasFactory;

    public const STATUS_SELECT = [
        'active' => 'Active',
        'deactive' => 'Deactive',
    ];

    public $table = 'doctor_appointments';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'date_time', 'zoom_id', 'doctor_id', 'patient_id', 'sms', 'done',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $appends = ['meeting_link', 'date_time_ts', 'passed', 'created_day', 'day_name', 'week_number'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getMeetingLinkAttribute()
    {
        return "https://zoom.us/s/{$this->attributes['zoom_id']}";
    }

    public function getDateTimeTsAttribute()
    {
        return strtotime($this->attributes['date_time']);
    }

    public function getPassedAttribute()
    {

        return ($this->attributes['done']);
    }

    public function getCreatedDayAttribute()
    {
        return date('Y-m-d', strtotime($this->attributes['created_at']));
    }

    public function getDayNameAttribute()
    {
        return date('N', strtotime($this->attributes['created_at'])) - 1;
    }

    public function getWeekNumberAttribute()
    {

        $month_first_day = date('Y-m-01', strtotime($this->attributes['created_at']));
        $month_first_day_week = date('W', strtotime($month_first_day));
        $created_week_number = date('W', strtotime($this->attributes['created_at']));
//        return [$month_first_day, $month_first_day_week, $created_week_number, (1 + $created_week_number - $month_first_day_week)];
        return (1 + $created_week_number - $month_first_day_week);
    }

}
