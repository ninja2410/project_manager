<?php

namespace App\Traits;

use App\Account;
use App\Almacen;
use \Auth, \Redirect, \Validator, \Input, \Session;

trait ProjectTrait {
    public function updateProject($project){
        if ($project->create_cellar){
            if (isset($project->cellar_id)){
                $almacen = Almacen::find($project->cellar_id);
                $almacen->id_state = 1;
                $almacen->update();
            }
            else{
                $almacen = new Almacen();
                $almacen->name = "Bodega proyecto:" . $project->name;
                $almacen->id_state = 1;
                $almacen->comentario = $project->description;
                $almacen->categorie_id = 16;
                $almacen->adress = 'CIUDAD';
                $almacen->save();
            }
        }
        else{
            if (isset($project->cellar_id)){
                $almacen = Almacen::find($project->cellar_id);
                $almacen->id_state = 2;
                $almacen->update();
            }
        }

        if ($project->create_account){
            if (isset($project->account_id)){
                $account = Account::find($project->account_id);
                $account->status = 1;
                $account->update();
            }
            else{
                $account = new Account();
                $account->account_name = "Cuenta Interna: " . $project->name;
                $account->bank_name = "Cuenta interna";
                $account->account_type_id = 1;
                $account->opening_balance = 0;
                $account->pago_id = 1;
                $account->account_responsible = Auth::user()->id;
                $account->status = 1;
                $account->user_id = Auth::user()->id;
                $account->categorie_id = 15;
                $account->save();
            }
        }
        else{
            if (isset($project->account_id)){
                $account = Account::find($project->account_id);
                $account->status = 2;
                $account->update();
            }
        }
    }
}