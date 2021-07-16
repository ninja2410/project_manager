<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model {

	public function setBirthdateAttribute($date)
    {
		$date_parts = explode('/', $date);
		$fecha=  $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
        $this->attributes['birthdate'] = (new Carbon($fecha))->format('Y-m-d');
    }

    public function getBirthdateAttribute($date)
    {
        if(strtotime($date) !=null)
            return date('d/m/Y', strtotime($date));

        return '01/01/1900';
    }
    public function routes()
    {        
        return $this->belongsToMany('App\Route','route_costumers','customer_id','route_id');
    }


}
