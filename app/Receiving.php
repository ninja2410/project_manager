<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Receiving extends Model {

	public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function storageOrigin()
    {
        return $this->belongsTo('App\Almacen', 'storage_origins');
    }

    public function pago()
    {
        return $this->belongsTo('App\Pago','id_pago');
    }

    public function price()
    {
        return $this->belongsTo('App\Price','price_id');
    }

    public function modify_user()
    {
        return $this->belongsTo('App\User','updated_by');
    }

    public function transfer_status(){
        return $this->belongsTo('App\StateCellar','status_transfer_id');
    }

    public function expenses()
    {
        return $this->hasOne('App\Payment','bill_id');
    }


    public function serie()
    {
        return $this->belongsTo('App\Serie','id_serie');
    }

    public function account_origin()
    {
        return $this->belongsTo('App\Account','id_account_origin');
    }
    public function account_destination()
    {
        return $this->belongsTo('App\Account','id_account_destination');
    }

    public function payment()
    {
        return $this->hasOne('App\Payment','bill_id');
    }
    public function getDateAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
        // return date('d/m/Y H:i', strtotime($date));
        // return $date;
    }
}
