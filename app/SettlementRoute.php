<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettlementRoute extends Model
{
    use SoftDeletes; //Implementamos

    protected $dates = ['deleted_at']; //Registramos la nueva columna

    public function user_assigned()
    {
        return $this->belongsTo('App\User', 'user_asigned');
    }
    public function route(){
        return $this->belongsTo('App\Route');
    }
    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function updated_by()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
    public function setDateBeginAttribute($date){
        $date_parts = explode('/', $date);
        $this->attributes['date_begin'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }
    public function setDateEndAttribute($date){
        $date_parts = explode('/', $date);
        $this->attributes['date_end'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }
    public function getDateBeginAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }
    public function getDateEndAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }
}
