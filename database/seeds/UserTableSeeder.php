<?php

use App\Role;
use App\UserRole;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {


      DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      Role::query()->truncate();
      UserRole::query()->truncate();
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');



    DB::table('users')->insert([
      'name' => 'admin',
      'email' => 'admin@admin.com',
      'password' => bcrypt('admin'),
      'user_state' => 1,
      'avatar' => 'usrx.png',

    ]);
    DB::table('users')->insert([
      'name' => 'usuario',
      'email' => 'usuario@cacao.gt',
      'password' => bcrypt('usuario'),
      'user_state' => 1,
      'avatar' => 'usrx.png',
    ]);
    // DB::table('users')->insert([
    //   'name' => 'Marco Vinicio ',
    //   'last_name'=>'Garcia Gomez',
    //   'email' => 'vinicio@boceto.com',
    //   'password' => bcrypt('boceto'),
    //   'user_state' => 1,
    //   'avatar' => 'usrx.png',
    // ]);

    // DB::table('users')->insert([
    //   'name' => 'Silvia Verónica',
    //   'last_name'=>'Diaz Velásquez',
    //   'email' => 'vero@boceto.com',
    //   'password' => bcrypt('boceto'),
    //   'user_state' => 1,
    //   'avatar' => 'usrx.png',
    // ]);

    // DB::table('users')->insert([
    //   'name' => 'Luis Enrique',
    //   'last_name'=>'Diaz Velásquez',
    //   'email' => 'enrique@boceto.com',
    //   'password' => bcrypt('boceto'),
    //   'user_state' => 1,
    //   'avatar' => 'usrx.png',
    // ]);

    // DB::table('users')->insert([
    //   'name' => 'Gerardo Felipe',
    //   'last_name'=>'Ortíz Velásquez',
    //   'email' => 'gerardo@boceto.com',
    //   'password' => bcrypt('boceto'),
    //   'user_state' => 1,
    //   'avatar' => 'usrx.png',
    // ]);

    // DB::table('users')->insert([
    //   'name' => 'Juan Lorenzo',
    //   'last_name'=>'García Gómez',
    //   'email' => 'juan@boceto.com',
    //   'password' => bcrypt('boceto'),
    //   'user_state' => 1,
    //   'avatar' => 'usrx.png',
    // ]);

    // DB::table('users')->insert([
    //   'name' => 'Willy Estuardo',
    //   'last_name'=>'Ramírez García',
    //   'email' => 'willy@boceto.com',
    //   'password' => bcrypt('boceto'),
    //   'user_state' => 1,
    //   'avatar' => 'usrx.png',
    // ]);

       //VARIABLES DE PESTAÑAS


      //Asignamos los roles al Administrador
      DB::table('user_roles')->insert([
          'user_id' => '1',
          'role_id' => '1',
      ]);

      DB::table('user_roles')->insert([
          'user_id' => '2',
          'role_id' => '1',
      ]);

      DB::table('user_roles')->insert([
          'user_id' => '3',
          'role_id' => '1',
      ]);


  }
}
