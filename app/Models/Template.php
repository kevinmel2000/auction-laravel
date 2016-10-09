<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;
    
    public $table = 'templates';
    protected $fillable = [
        'name', 'description'
    ];
    
    public function photos()
    {
        return $this->hasMany('App\Models\TemplatePhoto');
    }
}
