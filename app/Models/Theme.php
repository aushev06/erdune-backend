<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Theme
 * @property int $id
 * @property string $name
 */
class Theme extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name'];

}
