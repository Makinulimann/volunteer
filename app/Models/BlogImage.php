<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'blog_id',
        'image_url',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}