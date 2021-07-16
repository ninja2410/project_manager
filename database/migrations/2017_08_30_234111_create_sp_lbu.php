<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpLbu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sp_query1= "CREATE PROCEDURE sp_list_bodega_users(IN IdBodega INT)
      BEGIN
        SELECT u.id as idUser
      , u.name
      , COALESCE(a.id_bodega,IdBodega) id_bodega
      ,COALESCE(a.estado_user,0) NueValor
    From users  u
      LEFT JOIN almacen_users a on  a.id_usuario=u.id
    and  COALESCE(a.id_bodega,IdBodega)=IdBodega where  u.user_state=1;
    END;";

        DB::unprepared($sp_query1);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("drop PROCEDURE if exists sp_list_bodega_users;");
    }
}
