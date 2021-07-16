<?php

use Illuminate\Database\Seeder;
use App\GeneralParameter;
class GeneralParametersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GeneralParameter::query()->truncate();
        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Mostrar campo de pedido',
            'description'   => 'Mostrar campo de pedido',
            'text_value'    => '0',
            'active'        => '0',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Mostrar campo de transporte',
            'description'   => 'Mostrar campo de transporte',
            'text_value'    => '0',
            'active'        => '0',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Mostrar campo de Comentario imprimible',
            'description'   => 'Mostrar campo de Comentario imprimible',
            'text_value'    => '0',
            'active'        => '0',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_parameters')->insert([
            'type'          => 'Cuentas por pagar',
            'name'          => 'Recordatorio de cuentas por pagar próximas a vencer',
            'description'   => 'Recordatorio de cuentas por pagar próximas a vencer',
            'text_value'    => '1',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_parameters')->insert([
            'type'          => 'Cuentas por pagar',
            'name'          => 'Número de días anticipados para mostrar notificaciones.',
            'description'   => 'Número de días anticipados para mostrar notificaciones.',
            'text_value'    => '5',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Validar precio mínimo.',
            'description'   => 'Validar precio mínimo al vender productos.',
            'text_value'    => '1',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Permitir varias veces el mismo item.',
            'description'   => 'Permitir varias veces el mismo item al agregar productos.',
            'text_value'    => '0',
            'active'        => '0',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Dias de pago por defecto.',
            'description'   => 'Setear automáticamente la fecha de pago al crédito según días de crédito que tenga configurados el cliente.',
            'text_value'    => '0',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'ID Documento por defecto.',
            'description'   => 'ID del documento por defecto en las ventas.',
            'text_value'    => '2',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        /**nuevos */

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Imprimir código de cliente.',
            'description'   => 'Imprimir código de cliente.',
            'text_value'    => '0',
            'active'        => '0',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_parameters')->insert([
            'type'          => 'Inventario',
            'name'          => 'Período (meses) de cierre de inventario.',
            'description'   => 'Frecuencia de días en la que se solicita realizar cierres de inventario para efectos contables.',
            'text_value'    => '60',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_parameters')->insert([
            'type'          => 'Inventario',
            'name'          => 'Días de retraso para cerrar inventario.',
            'description'   => 'Días de retraso aceptado para poder cerrar el inventario sin restringir transacciones.',
            'text_value'    => '3',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_parameters')->insert([
            'type'          => 'Inventario',
            'name'          => 'Mes inicial cierres de inventario.',
            'description'   => 'Mes inicial de referencia para el calculo de cierres de inventario.',
            'text_value'    => date("Y-m-1"),
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Descuento en venta.',
            'description'   => 'Descuento máximo en ventas.',
            'text_value'    => '1',
            'max_amount'    => '5',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_parameters')->insert([
            'type'          => 'Rutas',
            'name'          => 'Campo ruta requerido.',
            'description'   => 'La ruta es obligatoria para crear un cliente.',
            'text_value'    => '0',
            'max_amount'    => '0',
            'active'        => '0',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Precio default.',
            'description'   => 'Tipo de precio default en la venta.',
            'text_value'    => '2',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);


        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Cuenta default.',
            'description'   => 'Cuenta bancaria seleccionada por default en la venta.',
            'text_value'    => '1',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Cambia precios solo Admin.',
            'description'   => 'Solo administradores pueden cambiar de precios.',
            'text_value'    => '1',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Número máximo de produtos en venta.',
            'description'   => 'Número máximo de produtos en venta (Campo maximo).',
            'text_value'    => '1',
            'active'        => '1',
            'max_amount'    => '20',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);


        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Imprimir propietario y negocio en proforma.',
            'description'   => 'Imprimir propietario y negocio en proforma.',
            // 'text_value'    => '1',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Versión de proforma',
            'description'   => '1 Versión completa, 2 Versión reducida',
            'text_value'    => '2',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Aplica descuentos solo Admin.',
            'description'   => 'Solo administradores pueden utilizar los descuentos.',
            'text_value'    => '1',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_parameters')->insert([
            'type'          => 'Cierre de caja',
            'name'          => 'Tipos de pago no coinciden.',
            'description'   => 'Permitir guardar un cierre de caja cuando el monto del efectivo no cuadre con el de sistema pero el balance general si coincide.',
            'text_value'    => '1',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            
        ]);
        DB::table('general_parameters')->insert([
            'type'          => 'Ventas',
            'name'          => 'Imprimir ticket',
            'description'   => 'Opción para imprimir ticket además de factura o proforma',
            'text_value'    => '1',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Inventario',
            'name'          => 'Transferencia bancaria por traslados',
            'description'   => 'Cada vez que se realice un traslado de bodega se realizará una transferencia bancaria entre cuentas seleccionando el tipo d eprecio para determinar el costo del mismo.',
            'text_value'    => '0',
            'active'        => '0',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Otros',
            'name'          => 'Imagen para exportar',
            'description'   => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAAUCAYAAAAa2LrXAAAAAXNSR0IArs4c6QAAAIRlWElmTU0AKgAAAAgABQESAAMAAAABAAEAAAEaAAUAAAABAAAASgEbAAUAAAABAAAAUgEoAAMAAAABAAIAAIdpAAQAAAABAAAAWgAAAAAAAABgAAAAAQAAAGAAAAABAAOgAQADAAAAAQABAACgAgAEAAAAAQAAAFCgAwAEAAAAAQAAABQAAAAAgPQzAgAAAAlwSFlzAAAOxAAADsQBlSsOGwAAAVlpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IlhNUCBDb3JlIDUuNC4wIj4KICAgPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6dGlmZj0iaHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KTMInWQAADmRJREFUWAl9WAt4VcW1XvuEKAha8VlK0YLiVbBiEfWKookoSGu5/T5Jbq9axaqgVai9iHrxtVOIIFZ5yCuIIBAQkvCOIRDgJCE8AgmvEiCEgCAQ3q+ACTlnz3//NfvseGpp5/tmz2utf9asWbNmzXaEyRUJdcjKclJTU70LIq2u+KjNM7jrf3tIy1vaS5NmTaWh9qxztHyLV5iemzBOshyRBqRIQnZKiqSmZnt4Ubqb+37XK/Ttwq0yXDIdQFzHcYiLsCsJya5EdR6I3G1G3PVYqG2PDtK81bUSuVAvNRt2S2ZugbNWii2N4rKSmi1eQzfpnNjzlkelunqXM01yddylrMwIJxG30MdtEOmUOKpbb7Tvfb9cdXMbMZ7nnK7aJ9uHFsv7kXmU96AAITiOsE4x/ilpH4dsjh+Mp9Xx+LZP56pABNcW3pA/Yu28o4iexyXT2UNA3uebI/dKV59bJDpA+mBXiU9+Zj8w8oE0HQuH3SZl/e5J1HqdSFtkvpmNnSUNMHWk9WLwhmUU2FsOzHnvG0p3q9JrQrI8iNLFSgwcqQQmPjva9lPWsn5icWtFrvemvDzNVBQ3ABct6T98Gs7BlC48ibTmgy0oP2GRJiwCZem6ta5lfA7G/1X5Ax8n1IZg2NVpOLIrmF+lCSQyXCYFtDliCdYtrENfuTtLJAErplTHmL7X0mxaFqEiOiqmJtwiXVE081iMRgvFrWeOELeeWduqSZjCmccbnpJfWb6FI9dqH5OvxD2lQEd50ILyUy/SHvkTdtsN8OlUxkYs1oM2cL4GmNJvFuVShagZ2TXHsH6spEA58f1BX4ylcQMsnlpRX9Ts0FUEyuLSzgBV6+uw/++UxbcYjkeZffOc8MJ7+Iukou648kUQjfql0k57dYbOdFHkdlOUedJfHxqU39YbaoFTtGZrjVZ7invBjs3763j8SnrjxF5tRk0jLluzBnPPRM6ItMQ3Y3YoAflo1SZm0gbm6D7PHKo8ie8pvz+u2BFE2B7be5zys/vfKTBQnJIGqbEPWSkJyOiX6AabwN24ESVZ3KKYMFpZm3MU/ycDcZd0xm8kGZOe/sSsX3AYdTFdHK0CesnLKP6aZ498XADdDvXsUVa2K4ovEvcuMz99oW371uDB0PCWT97uvSOv4AV50ku/eRBWfLkNtUeUjIkG9MmDnyM/Y422CEYefgmvbWwv8oh7EzL+MEyNmJ0NnDsYq0PG0595/WS4Sb9/Hoa2GGeWTdoMzxqwKtjDga3Ab6WXaoW+OTjKgXU1KonDWtcUlLbBuf+5jWFN3kSk1t9JFbJ0wQkS3m054j7nRK7DZ8mDsSAtD+mdnsUQeQ11J5SDVmUXYRery4WJwExIqTT7tukx0kQarmHO+2Fit4iDle9EmsGV17FsVKE3+61R6C/v4cxB5SGeNSz9qJKIS7gRd4SxaZnd8EbLK559jrj98Yo8it/JL5A5cJjZFT6GLHcaZg4qUjAmPd4wCz5aoPNnZWUlxBQSKFAvp1DYTWqCcLhJWDPrtk2ry+LlpnwY2e0pjH38Awy+96faFuSNXanATBftkUpv867tn9a3KRcWYiagq7vVmKzvWz1nr88GWh/1U1VCvfn6skqspduLsh0cr41LTlPgmxRkn/uLpnrBVLgdL2sEZYXjibTqSsVVq9YCu3lBnbeWb20RRzl8/pRqVJUKs2/LeTP+92u8ktnTMaLVUEzosxRz3k3D10New+l9vHxSZmDnmsCNwGxYepTz/ETndbk+FtaqVJmqVO3/dwkrZ61FLXFFOls6sy7PbrdKY/ZvA/4gj+qAaj4GpBNoZOLso1K1D0OvfRv1p8hB32L5tl/0fi2TzIEKvRygfovJ09NFXNtAzkezLC/CwdHxcYndiDviziFoOKM8iuvhAtfdT6Ziy4rDCsiNUjGtYmMKhpnvFprNyzfi1MFaDG82CF89vwQ7C2u95RnpCH85CqXzt2PFjD3Kb1NFYR0X397K4rqqQM1Upq0LkuQhLBwz1sufOh1/69oHD8lj0eHX9oU74Co8KUlm/aJqnDsOjOk5AZ899ssm4jiB1o3TrEXIu0yaK6BIof3yw/lsctq+8FU9Gy0lacKf5PKWOgC7fWWLwgl59GvlS9pJmw6PS0KCHmcfl7GX4Htem4f3K0p29vEAzy9pBTHcG+Rhd4Ak0jiM4bJCIVm/eK8zmaHVAzmjpdPDf3ZCiXqsuQGM50IhErF27OARdLmhtbRs3ULaPOPhsitDzrFD60Jd/2uIN+PFviHH3CEd+3a1cpKek6pEgXEoBPRicFJdD8PavSFPfz1KJCLOicMR6fX+c5JyoyRE6iV6a9f9CTNee9u5s3M7NL/uopOS9qqZO6SFmPyJ1mFzd6yPwBd9J1rUiqzLOFlg4k7V2AGX2/5hP3tLrU+tRM0BuxledJJkHYskS0/s3aS9ND01wDgLXPBJjuUvK9MYLsCViizXHmN82v1jeDYSUtwoTn4LMDpQHt7mHWhl9vbnZWVxOYdv2dMHzkTZgrk6KVbS4opmZJqFQwvw3Y56VJdP4xo+wCFeHsFpKV9+hn3XKa7rSogBuVUm+zqhch2wc62OJzNf771Po9i/rRbHDxh0l27sa401i3fgAi3wJXkHbspNglHd/hrTne/Adq6OkrCHTqCpjAuuyMqyizwv8lOUzA2uTJ9+9pv6cNDQwAa3dNyLVVomxjWqx9gNunWFHp07lbYqb+zl6qi1tLwi92DrisCB+m5h0cgSjtF1wF/gFy9OiOksohvDpEcZKM/bz2P3PM4eieDYtxeQkfI6whN2ebmjJ5v5H+eaGW8EPt7HL/hytc6pyeVGljEk0bo37U/DgXOMF18equ0gIX9qgW5m5HF5RPuwdtFKnDsMvn66WBou6g5sLfCDVWP8XS3LPcfn2UscswtUQgau/4F5aav9iCTm+7aFVdl+4OuKVXKkjTyMqg1qoeq0Aiu0izV543aQvjHIVlyNFZE/bqeSU+Hq5Ayqy4AHpLuOVw3wZfDlXGlNVG8Y3Rwmykt9zx68GM8wrNq9ZgcWD1+J1xgh9JbBGJ9agIazlo7UEeu30+TPiquXmEsLdGMxoVmTnYsLjE37yxM6jvA0398X5yzD6QPg7Z6k/aZkQRin+OJqIt20bRNG/+Zv8PxQhhbj7+yp74DimZX4JmMp8iYWYdsKRr90Qfwwczep8zG9Y88rPyTIgn+LMVwJ4r+otRZdb2AxW3gU5w6b7U17/UPMGTrDlOVx2/1xHzcKb0r/mSpY7FZ09FmobXzxUoY1bHUfqkBe1D4uY+nSRdVmyus5mDFwk8l0S8zm/H0qpo4zW6tGQcYubsSVFst1bbhC7dpLBOsX5aOW92mf2Mbl5fmno2jecpw/Cr79H7d8q3PyrUJvi1cgLQ3ZbnGccBFq0Vek7l8sURB9TfjCzEtfT2GusKDq1ZnUGduyGd+xlaUqvNHQRs2QdSoz5rcUL8r9iCWOquXZI2aWjqsirvVRLP3F0VJi89xutvhWaKLUoS+jyuMfT1ZM3dlGuTmu/tTHXT+/AffLQ/Fyaj1IWJg+ns8jYFTPN7SPUHZNZtXsUpyj1+rlRyemOCsXtQwKesYU2BggilyNzEFLoD8MVBD7tcpSAVQQXzANnrNdDYhvsBPFAkzWHZcLJq2/2MxB2T5E7HKKNYijbuIiyyD7bkOtJW+cWkj7OFy7CNumz9TSy3hukoqi/DHIoPCfbLpR/ib7G61k4ekHeQnYiy6IaTHhuQGYyx8cw++1Rzb6P/IU6hm7bsxVN9PazsnjjppKoKba4AlJsn1Fcxai/jj4e+kpbWtygptIGxhM37dq6kZUbjA4VgWc4VHW4LVyA/hTYKv3AUFjYUCMTxepWRXn6G8ulhou3IZV0w/h3EHwmB5G7se52LLqFE7Tf8SM2D7tTuzjRbDsjDe+zxjyXGN5GbwrFrNi2hyH28aspELqeKwKM3fg04dGYE1OJWp2EZbeQJPHZ/WxPcCG3Bp8/ttPiHujj5vUJAWw8pnlmfrIB77q94WOubr5OemzUE/r2lQQQWneaWycDWxdFcXBnYY+sLvFmPrKaJjTQDX1MXXwukBIoTJCyWEYRkmmjC+Ce2gJ3ltym1yVeKWcipz1PpNq3hJ7yHCR9u2Ekx39H2djMQWOTxaL/+oY/f282avSpW6ilPGsH+RCfi7v8I9Ku7/caZpe0zx08fhZb9fYioZPZR3HDymGbgpxPVZVNrL8UPK0OPqfEE2lrbwi/ymjpYBEJ06KXHVNqtxnHunbJXRF659I/cnzsn3S1vPjpZQO7zgx7Ns32RWTlZJCDP7DHP18b7m6zRPRw6u/ThxSxMuR14Lj3CQf//q/0fXZHk406kj2c+PkyUlvy309ukRvbZeUeEaKGInc2HzigIGmXed2oT2l3yp2kBxK6+CHF0jQ/w9lWb9+iUrHziBby4u1tW4Tn4CNde2ggEr/L5NeFDGeADe+VD7LH7icAEj/jAT1S5V6ZN1YrBeMX0oWvCm/x/ocg8mvpjXSidyM6o0X8PdVtWoMQX98aYVih5aNu+1y8R+qUXeocAp3dORYoSTxFSMfuvpTt5EuxseisU9xAqt0uNjQ9R2TnCQpNGmukJ2jHVKc8tOPhZq1rHHqTrdCbU0NksQ1nC7QcIBP4ksnuK7OE8qucGEtks/MwrSkhKRHktj9CHMR7a4CkpJt9NkRS4qrSXvs60MoR3n5ZOkyuTzCcOqXiZuXb3Ha3hKS0oIlgsQTuKFVb6dD52tl+rsjnf5fvk3Fh2iloDknyJWtHKGHsIgxUK0reJCDtlqS5qA/KHX8x/3a1qQ08XzxdMG40gT1YPzHZYAT0AVzx5c+ij9fUA/GL8UXzBGMhXisfb99O3/+Lh2/iv6PfjAfWDP/AEZ2eYtaUh61EsXVFOA7/w/NUTiqOF8xPwAAAABJRU5ErkJggg==',
            'text_value'    => 'https://www.base64-image.de',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('general_parameters')->insert([
            'type'          => 'Articulos',
            'name'          => 'Validez de precios de presupuesto',
            'description'   => 'Meses de validez de precios de presupuesto para los articulos y servicios',
            'text_value'    => '6',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('general_parameters')->insert([
            'type'          => 'Articulos',
            'name'          => 'Precios de presupuestos en articulos',
            'description'   => 'Mostrar la seccion de modificacion de precios utilizados en los presupuestos de proyectos',
            'text_value'    => '1',
            'active'        => '1',
            'system'        => '1',
            'created_by'    => '1',
            'updated_by'    => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
