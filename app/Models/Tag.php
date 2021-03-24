<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    use HasFactory;

    protected $guarded = [];

    public function clips()
    {
        return $this->belongsToMany(Clip::class, 'clip_tag')->withTimestamps();
    }
}
