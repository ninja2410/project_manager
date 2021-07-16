<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFelFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parameters', function(Blueprint $table)
        {
            $table->boolean('fel');
            $table->string('fel_username');
            $table->string('fel_cert');
            $table->string('fel_firm');
            $table->string('nit');
        });

        Schema::table('sales', function(Blueprint $table)
        {
            $table->longText('xml_certificado');
            $table->string('api_info');
            $table->string('api_uuid');
            $table->string('fecha_anulacion');
            $table->string('api_serie');
            $table->string('api_fecha');
            $table->string('api_numero');
        });

        Schema::table('credit_notes', function(Blueprint $table)
        {
            $table->longText('xml_certificado');
            $table->string('api_info');
            $table->string('api_uuid');
            $table->string('fecha_anulacion');
            $table->string('api_serie');
            $table->string('api_fecha');
            $table->string('api_numero');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parameters', function($table)
        {
            $table->dropColumn('fel', 'fel_username', 'fel_cert', 'fel_firm', 'nit');
        });
        Schema::table('sales', function($table)
        {
            $table->dropColumn('xml_certificado', 'api_info', 'api_uuid', 'fecha_anulacion',
                'api_serie', 'api_fecha', 'api_numero');
        });
        Schema::table('credit_notes', function($table)
        {
            $table->dropColumn('xml_certificado', 'api_info', 'api_uuid', 'fecha_anulacion',
                'api_serie', 'api_fecha', 'api_numero');
        });
    }
}
