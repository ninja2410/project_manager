<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Sale extends Model {

	public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id','id');
    }
    public function serie()
    {
        return $this->belongsTo('App\Serie','id_serie');
    }

    public function pago()
    {
        return $this->belongsTo('App\Pago','id_pago');
    }

    public function sale_items()
    {
        return $this->hasMany('App\SaleItem');
    }

    public function revenues()
    {
        return $this->hasOne('App\Revenue','invoice_id');
    }

    public function credits()
    {
        return $this->hasOne('App\Credit','id_factura');
    }


    public function almacen(){
	    return $this->belongsTo(Almacen::class, 'almacen_id');
    }

    
    protected $table ='sales';

    protected $dates=[
        'sale_date',
    ];
   	public function setSaleDateAttribute($date){
        $date_parts = explode('/', $date);
        $this->attributes['sale_date'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];

        // $date_parts = explode('/', $date);
        // $anio = explode(' ',$date_parts[2]);
        // $this->attributes['sale_date'] = $anio[0].'-'.$date_parts[1].'-'.$date_parts[0].' '.$anio[1];
    }
    public function getSaleDateAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function scopeAlmacen($query)
    {
        if (!Session::get('administrador')) {
            //BUSCAR EL ALMACEN ASIGNADO AL USUARIO
            $almacens = AlmacenUser::whereId_usuario(Auth::user()->id)->lists('id_bodega');
            return $query->whereIn('almacen_id', $almacens);
        } else {
            return $query;
        }
    }
}
