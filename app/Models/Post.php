<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'user_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        // conversion for image of post item
        $this
        ->addMediaConversion('preview')
        ->width(400);

        // conversion for image of post page
        // but i will use the original image there
        $this
            ->addMediaConversion('large')
            ->width(1200);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')
            ->singleFile();
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function claps()
    {
        return $this->hasMany(Clap::class);
    }

    public function readTime($wordsPerMinute = 100)
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / $wordsPerMinute);

        return $minutes;
    }

    public function imageUrl($conversionName = '')
    {
        $media = $this->getFirstMedia();

        // if ready so display the image
        if ($media->hasGeneratedConversion($conversionName)) {
            return $media->getUrl($conversionName);
        }
        // if not ready yet display the original one
        return $media->getUrl();
    }

    public function getFormattedPublishedAt()
    {
        return $this->published_at->format('M d, Y');
    }
}
