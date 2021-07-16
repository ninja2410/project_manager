<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model {

	public function item()
  {
      return $this->belongsTo('App\Item');
  }
	public function unit(){
		return $this->belongsTo(UnitMeasure::class, 'unit_id');
	}
}
