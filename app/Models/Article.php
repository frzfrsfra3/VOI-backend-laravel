<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class Article extends Model
{
    protected $with = ['author', 'visibilityDays', 'ratings'];
    protected $appends = ['average_rating','image_url'];
    protected $fillable= ['title','content','author_id','slug','excerpt','content','image','is_published','published_at','comments_enabled','category_id'];
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }


    public function getImageUrlAttribute()
    {
        return $this->image 
            ? asset('storage/' . $this->image)
            : null;
    }
    
    public function visibilityDays()
    {
        return $this->hasMany(ArticleVisibilityDay::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating');
    }

    public function scopeVisibleToday($query)
    {
        return $query->whereHas('visibilityDays', function($q) {
            $q->where('day_of_week', Carbon::now()->dayOfWeek);
        });
    }
    // public function author() { return $this->belongsTo(User::class, 'user_id'); }
public function categories() { return $this->belongsToMany(Category::class); }
public function comments() { return $this->hasMany(Comment::class); }


public function scopePublished($q) {
return $q->where('is_published', true);
}

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }
}