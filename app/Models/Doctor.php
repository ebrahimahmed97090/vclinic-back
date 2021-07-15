<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Doctor extends Model implements HasMedia
{
    use SoftDeletes;
    use HasFactory;
    use HasMediaTrait;

    public $table = 'doctors';

    protected $hidden = [
        'password',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $appends = ['picture', 'ratings_avg', 'hours'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'specialization',
        'is_first_period',
        'is_second_period',
        'start',
        'end',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getPictureAttribute()
    {
        return $this->getMedia('doctors')->last();
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }

    public function getRatingsAvgAttribute()
    {
        $sum = $this->ratings()->sum('rating');
        $count = $this->ratings()->count('rating');

        return ['sum' => $sum, 'count' => $count];
    }

    public function getHoursAttribute()
    {
        $hours = DoctorAppointment::with([])->where('doctor_id', '=', $this->attributes['id'])->count();
        $hours = (float)(($hours / 3) / 30) * 100;
        $hours = number_format($hours, 1);
        return $hours;
    }

    public function appointments()
    {
        return $this->hasMany(DoctorAppointment::class, 'doctor_id', 'id');
    }


}
