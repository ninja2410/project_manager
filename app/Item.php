<?php namespace App;

use App\Traits\ItemsTrait;
use Illuminate\Database\Eloquent\Model;
use DB;
class Item extends Model {

    use ItemsTrait;

	public function inventory()
    {
        return $this->hasMany('App\Inventory')->orderBy('id', 'DESC');
    }

    public function receivingtemp()
    {
        return $this->hasMany('App\ReceivingTemp')->orderBy('id', 'DESC');
    }

    public function itemType()
    {
        return $this->belongsTo('App\ItemType', 'type_id', 'id');
    }

    public function prices()
    {        
        return $this->belongsToMany('App\Price','item_prices','item_id','price_id');
    }
    public function price()
    {        
        return $this->belongsToMany('App\Price','item_prices','item_id','price_id')->select(['prices.name','item_prices.selling_price',DB::raw('item_prices.pct * 100 as pct')])->orderBy('prices.order','asc');
    }
    public function itemCategory()
    {
        return $this->belongsTo('App\ItemCategory', 'id_categorie', 'id');
    }

    public function verifyExpire(){
	    return $this->verifyBudgetCost($this->id);
    }

    protected $dates=[
        'expiration_date'
    ];
    public function setExpirationDateAttribute($date){
        $date_parts = explode('/', $date);
        $anio = explode(' ',$date_parts[2]);
        $this->attributes['expiration_date']=$anio[0].'-'.$date_parts[1].'-'.$date_parts[0];
    }

    public function getExpirationDateAttribute($date)
    {
        return date('d/m/Y ', strtotime($date));
        return $date;
    }

    public function getCodigoAttribute()
    {
        return $this->upc_ean_isbn;
    }

    public function scopeWildcard($query){
        return $query->whereWildcard(0);
    }
}
