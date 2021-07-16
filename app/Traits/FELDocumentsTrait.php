<?php

namespace App\Traits;

trait FELDocumentsTrait {

    public static function buildNcre($header, $receiver, $details, $originDocument, $motivo){
        $totalTaxes = 0;
        $totalDocument =0;
        $xml_data = '<dte:GTDocumento xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:dte="http://www.sat.gob.gt/dte/fel/0.2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="0.1" xsi:schemaLocation="http://www.sat.gob.gt/dte/fel/0.2.0">
  <dte:SAT ClaseDocumento="dte">
    <dte:DTE ID="DatosCertificados">
      <dte:DatosEmision ID="DatosEmision">
        <dte:DatosGenerales CodigoMoneda="GTQ" FechaHoraEmision="'.$header->date.'" Tipo="NCRE"></dte:DatosGenerales>
        <dte:Emisor AfiliacionIVA="GEN" CodigoEstablecimiento="1" CorreoEmisor="'.$header->email.'" NITEmisor="'.$header->nit.'" NombreComercial="'.$header->nombre_comercial.'" NombreEmisor="'.$header->nombre_emisor.'">
          <dte:DireccionEmisor>
            <dte:Direccion>'.$header->address.'</dte:Direccion>
            <dte:CodigoPostal>'.$header->postal_code.'</dte:CodigoPostal>
            <dte:Municipio>'.$header->municipality.'</dte:Municipio>
            <dte:Departamento>'.$header->departament.'</dte:Departamento>
            <dte:Pais>GT</dte:Pais>
          </dte:DireccionEmisor>
        </dte:Emisor>
        <dte:Receptor CorreoReceptor="'.$receiver->email.'" IDReceptor="'.$receiver->nit.'" NombreReceptor="'.$receiver->name.'">
          <dte:DireccionReceptor>
            <dte:Direccion>'.$receiver->address.'</dte:Direccion>
            <dte:CodigoPostal>'.$receiver->postal_code.'</dte:CodigoPostal>
            <dte:Municipio>'.$receiver->municipality.'</dte:Municipio>
            <dte:Departamento>'.$receiver->departament.'</dte:Departamento>
            <dte:Pais>GT</dte:Pais>
          </dte:DireccionReceptor>
        </dte:Receptor>
        <dte:Items>';
        foreach($details as $key => $detail){
            $tmpTotal = ($detail->quantity * $detail->unit_price);
            $totalDocument += $tmpTotal;
            $totalTaxes += round(($tmpTotal/1.12)*0.12, 2);
            $xml_data.='<dte:Item BienOServicio="'.$detail->type.'" NumeroLinea="'.($key+1).'">
                <dte:Cantidad>'.$detail->quantity.'</dte:Cantidad>
                <dte:UnidadMedida>'.$detail->unity.'</dte:UnidadMedida>
                <dte:Descripcion>'.$detail->description.'</dte:Descripcion>
                <dte:PrecioUnitario>'.round($detail->unit_price, 2).'</dte:PrecioUnitario>
                <dte:Precio>'.round($tmpTotal, 2).'</dte:Precio>
                <dte:Descuento>'.$detail->discount.'</dte:Descuento>
                <dte:Impuestos>
                  <dte:Impuesto>
                    <dte:NombreCorto>IVA</dte:NombreCorto>
                    <dte:CodigoUnidadGravable>1</dte:CodigoUnidadGravable>
                    <dte:MontoGravable>'.round(($tmpTotal/1.12), 2).'</dte:MontoGravable>
                    <dte:MontoImpuesto>'.round(($tmpTotal/1.12)*0.12, 2).'</dte:MontoImpuesto>
                  </dte:Impuesto>
                </dte:Impuestos>
                <dte:Total>'.$tmpTotal.'</dte:Total>
              </dte:Item>';
        }
        $xml_data.='</dte:Items>
        <dte:Totales>
          <dte:TotalImpuestos>
            <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto="'.round($totalTaxes, 2).'"></dte:TotalImpuesto>
          </dte:TotalImpuestos>
          <dte:GranTotal>'.round($totalDocument, 2).'</dte:GranTotal>
        </dte:Totales>
        <dte:Complementos>
          <dte:Complemento IDComplemento="Notas" NombreComplemento="Notas" URIComplemento="http://www.sat.gob.gt/fel/notas.xsd">
            <cno:ReferenciasNota xmlns:cno="http://www.sat.gob.gt/face2/ComplementoReferenciaNota/0.1.0" FechaEmisionDocumentoOrigen="'.date("Y-m-d",strtotime($originDocument->created_at )).'" MotivoAjuste="'.$motivo.'" NumeroAutorizacionDocumentoOrigen="'.$originDocument->api_uuid.'" NumeroDocumentoOrigen="'.$originDocument->api_numero.'" SerieDocumentoOrigen="'.$originDocument->api_serie.'" Version="0.0" xsi:schemaLocation="http://www.sat.gob.gt/face2/ComplementoReferenciaNota/0.1.0 C:\Users\User\Desktop\FEL\Esquemas\GT_Complemento_Referencia_Nota-0.1.0.xsd"></cno:ReferenciasNota>
          </dte:Complemento>
        </dte:Complementos>
      </dte:DatosEmision>
    </dte:DTE>
  </dte:SAT>
</dte:GTDocumento>
';
        return $xml_data;
    }

    public static function buildAnul($numeroDocumento, $fechaEmisionDocumento, $nitEmisor, $nitReceptor, $fechaAnul, $motivo){
        $xml_data = '<?xml version="1.0" encoding="utf-8"?>
<dte:GTAnulacionDocumento xmlns:dte="http://www.sat.gob.gt/dte/fel/0.1.0"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    Version="0.1">
    <dte:SAT>
        <dte:AnulacionDTE ID="DatosCertificados">
            <dte:DatosGenerales ID="DatosAnulacion" NumeroDocumentoAAnular="' . $numeroDocumento . '" NITEmisor="' . $nitEmisor . '"
                IDReceptor="' . $nitReceptor . '" FechaEmisionDocumentoAnular="' . $fechaEmisionDocumento . '"
                FechaHoraAnulacion="' . $fechaAnul . '" MotivoAnulacion="' . $motivo . '"/>
        </dte:AnulacionDTE>
    </dte:SAT>
</dte:GTAnulacionDocumento>';
        return $xml_data;
    }

    public static function buildFact($typeDocument, $header, $receiver, $details, $name_document){
        $totalTaxes = 0;
        $totalDocument =0;
        $xml_data = '<dte:GTDocumento xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:dte="http://www.sat.gob.gt/dte/fel/0.2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="0.1" xsi:schemaLocation="http://www.sat.gob.gt/dte/fel/0.1.0">
  <dte:SAT ClaseDocumento="dte">
    <dte:DTE ID="DatosCertificados">
      <dte:DatosEmision ID="DatosEmision">
        <dte:DatosGenerales CodigoMoneda="GTQ" FechaHoraEmision="'.$header->date.'" Tipo="'.$typeDocument.'"></dte:DatosGenerales>
        <dte:Emisor AfiliacionIVA="GEN" CodigoEstablecimiento="1" CorreoEmisor="'.$header->email.'" NITEmisor="'.$header->nit.'" NombreComercial="'.$header->nombre_comercial.'" NombreEmisor="'.$header->nombre_emisor.'">
          <dte:DireccionEmisor>
            <dte:Direccion>'.$header->address.'</dte:Direccion>
            <dte:CodigoPostal>'.$header->postal_code.'</dte:CodigoPostal>
            <dte:Municipio>'.$header->municipality.'</dte:Municipio>
            <dte:Departamento>'.$header->departament.'</dte:Departamento>
            <dte:Pais>GT</dte:Pais>
          </dte:DireccionEmisor>
        </dte:Emisor>
        <dte:Receptor CorreoReceptor="'.$receiver->email.'" IDReceptor="'.$receiver->nit.'" NombreReceptor="'.$receiver->name.'">
          <dte:DireccionReceptor>
            <dte:Direccion>'.$receiver->address.'</dte:Direccion>
            <dte:CodigoPostal>'.$receiver->postal_code.'</dte:CodigoPostal>
            <dte:Municipio>'.$receiver->municipality.'</dte:Municipio>
            <dte:Departamento>'.$receiver->departament.'</dte:Departamento>
            <dte:Pais>GT</dte:Pais>
          </dte:DireccionReceptor>
        </dte:Receptor>
        <dte:Frases>
          <dte:Frase CodigoEscenario="1" TipoFrase="1"></dte:Frase>
        </dte:Frases>
        <dte:Items>';
        foreach($details as $key => $detail){
            $tmpTotal = ($detail->quantity * $detail->unit_price);
            $totalDocument += $tmpTotal;
            $totalTaxes += round(($tmpTotal/1.12)*0.12, 2);
            $xml_data.='<dte:Item BienOServicio="'.$detail->type.'" NumeroLinea="'.($key+1).'">
                <dte:Cantidad>'.$detail->quantity.'</dte:Cantidad>
                <dte:UnidadMedida>'.$detail->unity.'</dte:UnidadMedida>
                <dte:Descripcion>'.$detail->description.'</dte:Descripcion>
                <dte:PrecioUnitario>'.round($detail->unit_price, 2).'</dte:PrecioUnitario>
                <dte:Precio>'.round($tmpTotal, 2).'</dte:Precio>
                <dte:Descuento>'.$detail->discount.'</dte:Descuento>
                <dte:Impuestos>
                  <dte:Impuesto>
                    <dte:NombreCorto>IVA</dte:NombreCorto>
                    <dte:CodigoUnidadGravable>1</dte:CodigoUnidadGravable>
                    <dte:MontoGravable>'.round(($tmpTotal/1.12), 2).'</dte:MontoGravable>
                    <dte:MontoImpuesto>'.round(($tmpTotal/1.12)*0.12, 2).'</dte:MontoImpuesto>
                  </dte:Impuesto>
                </dte:Impuestos>
                <dte:Total>'.$tmpTotal.'</dte:Total>
              </dte:Item>';
        }
        $xml_data.='</dte:Items>
        <dte:Totales>
          <dte:TotalImpuestos>
            <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto="'.round($totalTaxes, 2).'"></dte:TotalImpuesto>
          </dte:TotalImpuestos>
          <dte:GranTotal>'.round($totalDocument, 2).'</dte:GranTotal>
        </dte:Totales>
      </dte:DatosEmision>
    </dte:DTE>
    <dte:Adenda>
      <Documento>'.$name_document.'</Documento>
    </dte:Adenda>
  </dte:SAT>';
        return $xml_data;
    }

    public static function tmpTest(){
//SCRIPT EMISION DE FACTURA INFILE
        $URL = "https://certificador.feel.com.gt/fel/procesounificado/transaccion/v2/xml";
//GENERACION DE XML
        $xml_data = '<dte:GTDocumento xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:dte="http://www.sat.gob.gt/dte/fel/0.2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="0.1" xsi:schemaLocation="http://www.sat.gob.gt/dte/fel/0.2.0">  
  <dte:SAT ClaseDocumento="dte">    
    <dte:DTE ID="DatosCertificados">      
      <dte:DatosEmision ID="DatosEmision">        
        <dte:DatosGenerales CodigoMoneda="GTQ" FechaHoraEmision="2020-10-08T11:36:29-06:00" Tipo="NCRE"></dte:DatosGenerales>        
        <dte:Emisor AfiliacionIVA="GEN" CodigoEstablecimiento="1" CorreoEmisor="" NITEmisor="79121128" NombreComercial="BEPAISA" NombreEmisor="BEPAISA">          
          <dte:DireccionEmisor>            
            <dte:Direccion>Calle Demetrio Laparra  5-28 Zona 2, Tejutla, San Marcos</dte:Direccion>            
            <dte:CodigoPostal>01011</dte:CodigoPostal>            
            <dte:Municipio>Guatemala</dte:Municipio>            
            <dte:Departamento>Guatemala</dte:Departamento>            
            <dte:Pais>GT</dte:Pais>          
          </dte:DireccionEmisor>        
        </dte:Emisor>        
        <dte:Receptor CorreoReceptor="" IDReceptor="2163551" NombreReceptor="ACREDICOM R.L">          
          <dte:DireccionReceptor>            
            <dte:Direccion>TEJUTLA, SAN MARCOS </dte:Direccion>            
            <dte:CodigoPostal>01011</dte:CodigoPostal>            
            <dte:Municipio>Guatemala</dte:Municipio>            
            <dte:Departamento>Guatemala</dte:Departamento>            
            <dte:Pais>GT</dte:Pais>          </dte:DireccionReceptor>        
          </dte:Receptor>        
          <dte:Items>
            <dte:Item BienOServicio="B" NumeroLinea="1">                
              <dte:Cantidad>1</dte:Cantidad>                
              <dte:UnidadMedida>UND</dte:UnidadMedida>                
              <dte:Descripcion>DESCUENTO GENERAL</dte:Descripcion>                
              <dte:PrecioUnitario>31.75</dte:PrecioUnitario>                
              <dte:Precio>31.75</dte:Precio>                
              <dte:Descuento>0</dte:Descuento>                
              <dte:Impuestos>                  
                <dte:Impuesto>                    
                  <dte:NombreCorto>IVA</dte:NombreCorto>                    
                  <dte:CodigoUnidadGravable>1</dte:CodigoUnidadGravable>                    
                  <dte:MontoGravable>28.35</dte:MontoGravable>                    
                  <dte:MontoImpuesto>3.4</dte:MontoImpuesto>                  
                </dte:Impuesto>                
              </dte:Impuestos>                
              <dte:Total>31.75</dte:Total>              
            </dte:Item>
          </dte:Items>        
          <dte:Totales>          
            <dte:TotalImpuestos>            
              <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto="3.4"></dte:TotalImpuesto>          
            </dte:TotalImpuestos>          
            <dte:GranTotal>31.75</dte:GranTotal>        
          </dte:Totales>        
          <dte:Complementos>          
            <dte:Complemento IDComplemento="Notas" NombreComplemento="Notas" URIComplemento="http://www.sat.gob.gt/fel/notas.xsd">            <cno:ReferenciasNota xmlns:cno="http://www.sat.gob.gt/face2/ComplementoReferenciaNota/0.1.0" FechaEmisionDocumentoOrigen="2020-10-07" MotivoAjuste="Descuento" NumeroAutorizacionDocumentoOrigen="D2F91AEA-90FC-47F3-A58B-A9A55C4C1115" NumeroDocumentoOrigen="2432452595" SerieDocumentoOrigen="**PRUEBAS**" Version="0.0" xsi:schemaLocation="http://www.sat.gob.gt/face2/ComplementoReferenciaNota/0.1.0 C:\Users\User\Desktop\FEL\Esquemas\GT_Complemento_Referencia_Nota-0.1.0.xsd"></cno:ReferenciasNota>          
          </dte:Complemento>        
        </dte:Complementos>      
      </dte:DatosEmision>    
    </dte:DTE>  
  </dte:SAT>
</dte:GTDocumento>';
        $ch = curl_init($URL);
        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('UsuarioAPI: BEPAISA', 'LlaveAPI: 8646607AA9AAB508D2BC390643423CCB', 'UsuarioFirma: BEPAISA', 'LlaveFirma: b8c058810972b3786dc244b24f5767e0', 'Identificador: Nota Crédito 1'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($output, true);
//print_r($response);
        echo '<hr/>';
        if ($response['uuid']) {
            return json_encode($response);
        } else {
            return json_encode($response['descripcion_errores'][0]['mensaje_error']);
        }
    }
}
