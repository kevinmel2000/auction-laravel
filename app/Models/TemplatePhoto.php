<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplatePhoto extends Model
{
    use SoftDeletes;
    
    public $table = 'template_photos';
    protected $fillable = [
        'template_id', 'name'
    ];
}