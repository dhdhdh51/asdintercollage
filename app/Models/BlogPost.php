<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'image',
        'meta_title', 'meta_description', 'meta_keywords',
        'status', 'author_id', 'published_at'
    ];
    protected $casts = ['published_at' => 'datetime'];

    public function author() { return $this->belongsTo(User::class, 'author_id'); }

    public function getRouteKeyName() { return 'slug'; }
}
