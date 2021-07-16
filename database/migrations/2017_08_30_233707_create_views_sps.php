<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewsSps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::Statement("CREATE VIEW v_permisosusuario as
            SELECT  p.id IdPermiso
          ,u.user_id
          ,r.id
          ,p.ruta
          ,p.descripcion
          ,p.ruta_padre
          FROM user_roles u
          JOIN roles r ON u.role_id=r.id
          JOIN role_permissions pr ON r.id=pr.id_rol
          JOIN permissions
        p ON pr.id_permission = p.id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::Statement("drop view if exists v_permisosusuario;");
    }
}
