<?php

namespace App;

use App\Traits\LineTemplateTrait;
use Illuminate\Database\Eloquent\Model;

class LineTemplate extends Model
{
    use LineTemplateTrait;
    public function category(){
        return $this->belongsTo('App\Categorie', 'categorie_id');
    }

    public function getPrice(){
        return $this->getNewPrice($this);
    }

    public function details(){
        return $this->hasMany('App\DetailLineTemplate', 'lineTemplate_id');
    }
}
