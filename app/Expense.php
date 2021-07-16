<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }
    public function route()
    {
        return $this->belongsTo('App\Route');
    }

    public function creditNote(){
        return $this->belongsTo(CreditNote::class, 'credit_note_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function state(){
        return $this->belongsTo(StateCellar::class, 'state_id');
    }

    public function category()
    {
        return $this->belongsTo('App\ExpenseCategory', 'category_id', 'id');
    }

    public function user_assigned()
    {
        return $this->belongsTo('App\User', 'assigned_user_id', 'id');
    }

    public function pago()
    {
        return $this->belongsTo('App\Pago', 'payment_type_id');
    }
    public function setaPaidAtAttribute($date)
    {
        $date_parts = explode('/', $date);
        $this->attributes['paid_at'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
        $this->attributes['expense_date'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }
    public function getPaidAtAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }
}
