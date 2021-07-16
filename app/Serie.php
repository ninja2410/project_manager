<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    public function document()
    {
        return $this->belongsTo('App\Document','id_document');
    }
}
