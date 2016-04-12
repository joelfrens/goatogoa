<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class article_tags extends Model
{
    protected $fillable = [
        'article_id', 'tag_id'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function articles()
    {
        return $this->belongsTo(Article::class);
    }
}
