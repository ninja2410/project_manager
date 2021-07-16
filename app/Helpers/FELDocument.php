<?php
namespace App\Helpers;
use App\Parameter;
use App\Traits\FELDocumentsTrait;
use Doctrine\DBAL\Driver\IBMDB2\DB2Driver;
use Illuminate\Support\Facades\DB;


class FELDocument {
    public static function __callStatic($method, $args)
    {
        $obj = new BackendFacade;
        return $obj->$method(...$args);
    }
}

class BackendFacade {
    use FELDocumentsTrait;

    /**
     * @param $typeDocument: FACT, FCAM, etc.
     * @param $header : json con los datos del emisor
                     * - date
                    - email
                    - nit
                    - nombre_comercial
                    - nombre_emisor
                    - address
                    - postal_code
                    - municipality
                    - departament
     * @param $receiver: json con los datos del receptor
                     * - email
                    - nit
                    - name
                    - address
                    - postal_code
                    - municipality
                    - departament
     * @param $details: json  de objetos tipo detalle de documento
                     * - type
                    - quantity
                    - unity
                    - description
                    - unit_price
                    - discount
     * @param $document_name : Identificador del documento en el sistema
     * @param $api_username : Usuario FEL
     * @param $llave_cert : Llave certificación de doucmento
     * @param $llave_firma : Llave firma de doucmento
     * @return mixed : JSON con respuesta de certificador
     */
    public function certifyFact($typeDocument, $header, $receiver, $details, $document_name, $api_username, $llave_cert, $llave_firma){
        $xml = $this->buildFact($typeDocument, $header, $receiver, $details, $document_name);
        return $this->sendData($xml, $api_username, $llave_cert, $llave_firma, $document_name);
    }

    /**
     * Certifica la anulación de un documento FEL emitido
     * @param $numeroDocumento : UUID de documento a anular
     * @param $fechaEmisionDocumento : Fecha de emisión de documento a anular
     * @param $nitEmisor : Nit del emisor
     * @param $nitReceptor : Nit del receptor
     * @param $fechaAnul : Fecha actual
     * @param $motivo : Descripción de la anulación
     * @param $api_username : Usuario FEL
     * @param $llave_cert : Llave certificación de doucmento
     * @param $llave_firma : Llave firma de doucmento
     * @return mixed
     */
    public function certifyAnul($numeroDocumento, $fechaEmisionDocumento, $nitEmisor, $nitReceptor, $fechaAnul, $motivo, $api_username, $llave_cert, $llave_firma, $document_name){
        $nitEmisor = strtoupper(str_replace(["-", " "], "",$nitEmisor));
        $nitReceptor = strtoupper(str_replace(["-", " "], "",$nitReceptor));
        $xml = $this->buildAnul($numeroDocumento, $fechaEmisionDocumento, $nitEmisor, $nitReceptor, $fechaAnul, $motivo);
        return $this->sendData($xml, $api_username, $llave_cert, $llave_firma, $document_name);
    }

    /**
     * Certificación de notas de crédito
     * @param $header : json con los datos del emisor
     * - date
    - email
    - nit
    - nombre_comercial
    - nombre_emisor
    - address
    - postal_code
    - municipality
    - departament
     * @param $receiver: json con los datos del receptor
     * - email
    - nit
    - name
    - address
    - postal_code
    - municipality
    - departament
     * @param $details: json  de objetos tipo detalle de documento
     * - type
    - quantity
    - unity
    - description
    - unit_price
    - discount
     * @param $originDocument: json datos de documento al cual se le emite la nota de crédito (Objeto Sale)
     * @param $motivo : Motivo por el cual se realiza la nota de crédito
     * @param $api_username : Usuario FEL
     * @param $llave_cert : Llave certificación de doucmento
     * @param $llave_firma : Llave firma de doucmento
     * @param $document_name : Nombre de nota de crédito en el sistema
     * @return mixed
     */
    public function certifyNcre($header, $receiver, $details, $originDocument, $motivo, $api_username, $llave_cert, $llave_firma, $document_name){
        $xml = $this->buildNcre($header, $receiver, $details, $originDocument, $motivo);
        return $this->sendData($xml, $api_username, $llave_cert, $llave_firma, $document_name);
    }

    private function eliminar_tildes($cadena){

        //Codificamos la cadena en formato utf8 en caso de que nos de errores
        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena );

        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );

        return strtoupper($cadena);
    }

    private function sendData($xml, $username, $llaveCert, $llaveFirm, $document_name){
        $document_name = $this->eliminar_tildes($document_name);
        $URL = "https://certificador.feel.com.gt/fel/procesounificado/transaccion/v2/xml";
        $ch = curl_init($URL);
        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('UsuarioAPI: '.$username, 'LlaveAPI: '.$llaveCert, 'UsuarioFirma: '.$username, 'LlaveFirma: '.$llaveFirm, 'Identificador: '.$document_name));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($output, true);
        return $response;
    }
}

/*
 * EJEMPLO DE IMPLEMENTACIÓN
 *
 * $typeDocument = 'FACT';
        $header = json_decode(json_encode(array(
//            "date"=>date('Y-m-d\TH:i:s.uP'),
            "date"=>"2020-10-05T09:57:00-06:00",
            "email"=>'',
            "nit"=>'79121128',
            "nombre_comercial"=>"GRUPO BEPAISA",
            "nombre_emisor"=>"GRUPO BEPAISA, SOCIEDAD ANONIMA",
            "address"=>"Ciudad 00-00 Ciudad Zona: 0, Guatemala, Guatemala",
            "postal_code"=>"01011",
            "municipality"=>"Guatemala",
            "departament"=>"Guatemala"
        )));
        $receiver = json_decode(json_encode(array(
            "email"=>'',
            "nit"=>'22410422',
            "name"=>"PABLO GARCIA",
            "address"=>"CIUDAD",
            "postal_code"=>"01011",
            "municipality"=>"TOTONICAPÁN",
            "departament"=>"TOTONICAPÁN"
        )));
        $details = json_decode(json_encode(array(
            array("type"=>'B',
            "quantity"=>6,
            "unity"=>"UND",
            "description"=>"MOTOR PISTONES",
            "unit_price"=>120,
            "discount"=>0),
            array("type"=>'B',
                "quantity"=>2,
                "unity"=>"UND",
                "description"=>"ESTE ES EL SEGUNDO PRODUCTO",
                "unit_price"=>45.50,
                "discount"=>0)
        )));
        $document_name = "BEP9";
        $api_username = 'BEPAISA';
        $llave_cert = '8646607AA9AAB508D2BC390643423CCB';
        $llave_firma = 'b8c058810972b3786dc244b24f5767e0';

        return INFILE::certifyFact($typeDocument, $header, $receiver, $details,
            $document_name, $api_username, $llave_cert, $llave_firma);
 */
