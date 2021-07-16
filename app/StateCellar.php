<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StateCellar extends Model
{

    /**
     * Scope para obtener los Estados Generales
     */
    public function scopeGeneral($query)
    {
        return $query->where('type_number', '=', 1);
    }

    /**
     * Scope para obtener los Estados de transacciones bancarias
     */
    public function scopeBanking($query)
    {
        return $query->where('type_number', '=', 2);
    }
    
    /**
     * Scope para obtener los Estados de documentos de Inventario
     */
    public function scopeInventory($query)
    {
        return $query->where('type_number', '=', 3);
    }
}
