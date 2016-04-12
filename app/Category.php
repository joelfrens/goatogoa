<?php

namespace App;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Illuminate\Database\Eloquent\Model;

class Category extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug',
    ];

    protected $fillable = [
    	'name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
    	return $this->belongsTo(Article::class);
    }
    
}
