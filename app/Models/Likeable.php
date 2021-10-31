<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likeable extends BaseModel
{
    use HasFactory;

    public function likeable()
    {
        return $this->morphTo();
    }
}