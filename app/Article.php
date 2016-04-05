<?php

namespace App;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Illuminate\Database\Eloquent\Model;

class Article extends Model implements SluggableInterface
{
    //
	use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $fillable = [
    	'title', 'content', 'active', 'scheduled_on', 'what_you_can_do', 'xcoordinate', 'ycoordinate', 'category_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article_images()
    {
        return $this->hasMany(article_images::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function tag()
    {
        return $this->hasMany(Article::class);
    }
    
}
