<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewNamedocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::Statement("CREATE VIEW v_namedocument as
          SELECT s.id,
          CONCAT(d.name , ' ',r.name,'-',s.correlative) AS Documento
          from sales s
          LEFT JOIN series r
          on s.id_serie = r.id
          LEFT JOIN documents d
          on r.id_document = d.id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::Statement("drop view if exists v_namedocument;");
    }
}
