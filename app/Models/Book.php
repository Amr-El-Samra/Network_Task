<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'details', 'numberOfPages', 'price', 'cover', 'cat_id', 'author_id'];

    // Book belongs to cat 
    public function cat(){
        return $this->belongsTo(Cat::class);
    }

    // Book belongs to cat 
    public function author(){
        return $this->belongsTo(Author::class);
    }
}
