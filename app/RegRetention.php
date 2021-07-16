<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegRetention extends Model
{
    public function retention()
    {
        return $this->belongsTo('App\Retention');
    }

    public function revenue()
    {
        return $this->belongsTo('App\Revenue');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function revenue_origin()
    {
        return $this->belongsTo('App\Revenue', 'revenue_origin_id');
    }

    public function setDateAttribute($date)
    {
        $date_parts = explode('/', $date);
        $this->attributes['date'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }

    public function getDateAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }
}
