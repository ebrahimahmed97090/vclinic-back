<?php

namespace App\Models;

use App\Http\Controllers\Traits\UtilitiesTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Config extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;
    use HasFactory;
    use UtilitiesTrait;

    public const STATUS_SELECT = [
        'active' => 'active',
        'deactive' => 'deactive',
    ];

    public $table = 'configs';

    protected $appends = [
//        'attachment',
        'link'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'details',
        'status',
        'youtube_link',
        'code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getAttachmentAttribute()
    {
        return $this->getMedia('attachment')->last();
    }

    public function getYoutubeLinkAttribute()
    {
        return $this->getYouTubeID($this->attributes['youtube_link']);
    }

    public function getLinkAttribute()
    {
       return ($this->getAttachmentAttribute())?$this->getAttachmentAttribute()->getFullUrl():'';
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
