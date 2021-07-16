<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpPr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $Q_SP= "CREATE PROCEDURE sp_list_permisos_roles(IN Idrol INT)
        BEGIN
        SELECT p.id as IdPermiso, p.descripcion
            ,coalesce(r.id_rol,Idrol) idRol
            ,coalesce(r.estado_permiso,0) Valor
        FROM permissions p LEFT JOIN role_permissions r
          on p.id = r.id_permission
        AND COALESCE(r.id_rol,Idrol)=Idrol where 1=1 and  p.estado=1 ORDER BY  descripcion ASC;
        END;";
        DB::unprepared($Q_SP);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("drop PROCEDURE if exists sp_list_permisos_roles;");
    }
}
