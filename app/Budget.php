<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function status(){
        return $this->belongsTo(StateCellar::class, 'status_id');
    }
    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function details(){
        return $this->hasMany(BudgetHeader::class);
    }
    public function getDateAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }
    public function setDateAttribute($date){
        $date_parts = explode('/', $date);
        $this->attributes['date'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }
}
