<?php
/*
* CorreiosWebservice SOAP PHP class - Wrapper to Correios Webservice
*
* @pakage CORREIOS_SOAP_PHP
* @author Giuliano Riboni <giuliano@riboni.com.br>
* @copyright 2012 Giuliano Riboni
* @date 2012-11-27
* @version 1.0.0
*
* Webservice Correios Documentation:
*   http://www.correios.com.br/webservices/
*
* Correios Services Codes:
*   40010 - SEDEX sem contrato.
*   40045 - SEDEX a Cobrar, sem contrato.
*   40126 - SEDEX a Cobrar, com contrato.
*   40215 - SEDEX 10, sem contrato.
*   40290 - SEDEX Hoje, sem contrato.
*   40096 - SEDEX com contrato.
*   40436 - SEDEX com contrato.
*   40444 - SEDEX com contrato.
*   40568 - SEDEX com contrato.
*   40606 - SEDEX com contrato.
*   41106 - PAC sem contrato.
*   41068 - PAC com contrato.
*   81019 - e-SEDEX, com contrato.
*   81027 - e-SEDEX Prioritário, com conrato.
*   81035 - e-SEDEX Express, com contrato.
*   81868 - (Grupo 1) e-SEDEX, com contrato.
*   81833 - (Grupo 2) e-SEDEX, com contrato.
*   81850 - (Grupo 3) e-SEDEX, com contrato.
*
* Correios Format Codes:
*   1 – Formato caixa/pacote.
*   2 – Formato rolo/prisma.
*   3 - Envelope.
*
* Code on GitHub:
*    https://github.com/riboni/CorreiosSOAP
*
*/
class CorreiosSOAP{
  var $classVersion            = '1.0.0';
  var $soapUrl                 = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL';
  var $soapTimeout             = 30;
  var $soapKeepAlive           = false;
  var $soapHasError            = false;
  var $soapError               = array();
  var $soapResult              = false;
  var $correiosCompanyCode     = '';
  var $correiosCompanyPassword = '';
  var $correiosServicesCodes   = array('40010' => 'SEDEX sem contrato', '40045' => 'SEDEX a Cobrar, sem contrato', '40126' => 'SEDEX a Cobrar, com contrato', '40215' => 'SEDEX 10, sem contrato', '40290' => 'SEDEX Hoje, sem contrato', '40096' => 'SEDEX com contrato', '40436' => 'SEDEX com contrato', '40444' => 'SEDEX com contrato', '40568' => 'SEDEX com contrato', '40606' => 'SEDEX com contrato', '41106' => 'PAC sem contrato', '41068' => 'PAC com contrato', '81019' => 'e-SEDEX, com contrato', '81027' => 'e-SEDEX Prioritário, com conrato', '81035' => 'e-SEDEX Express, com contrato', '81868' => '(Grupo 1) e-SEDEX, com contrato', '81833' => '(Grupo 2) e-SEDEX, com contrato', '81850' => '(Grupo 3) e-SEDEX, com contrato');
  var $correiosFormatsCodes    = array('1' => 'Formato caixa/pacote', '2' => 'Formato rolo/prisma', '3' => 'Envelope');

  function CorreiosSOAP(){
  }

  function setSoapTimeout($v){
    $this -> soapTimeout = $v;
  }

  function setSoapKeepAlive($v){
    $this -> soapKeepAlive = $v;
  }

  function setCorreiosCompanyCode($v){
    $this -> correiosCompanyCode = $v;
  }

  function setCorreiosCompanyPassword($v){
    $this -> correiosCompanyPassword = $v;
  }

  function addError($code, $error){
    $this -> soapError[ $code ] = $error;
    $this -> soapHasError       = true;
  }
  
  function getErrors(){
    return $this -> soapError;
  }
  
  function hasError(){
    return $this -> soapHasError;
  }

  function formatCEP($cep){
    $cep = str_replace('-', '', $cep);
    return $cep;
  }

  function isServiceCodeValid($serviceCode){
    $explodedCodes = explode(',', $serviceCode);
    if( is_array( $explodedCodes ) && sizeof( $explodedCodes ) == 1 ){
      $explodedCodes = array_shift( $explodedCodes );
    }
    if( $explodedCodes == false ){
      $explodedCodes = $serviceCode;
    }
    if( is_array( $explodedCodes ) ){
      foreach($explodedCodes as $k => $v){
        if( !$this -> isServiceCodeValid($v) ){
          return false;
        }
      }
      return true;
    }else{
      if( array_key_exists($explodedCodes, $this -> correiosServicesCodes) ){
        return true;
      }else{
        return false;
      }
    }
  }

  function getServiceName($serviceCode){
    if( $this -> isServiceCodeValid( $serviceCode ) ){
      $explodedCodes     = explode(',', $serviceCode);
      $serviceNameString = '';
      $glue              = '';
      foreach($explodedCodes as $k => $v){
        $serviceNameString .= $glue.$this -> correiosServicesCodes[ $v ];
        $glue = ', ';
      }
      return $serviceNameString;
    }else{
      return 'Service not found';
    }
  }

  function isFormatValid($formatCode){
    if( array_key_exists($formatCode, $this -> correiosFormatsCodes) ){
      return true;
    }else{
      return false;
    }
  }

  function getFormatName($formatCode){
    if( $this -> isFormatValid( $formatCode ) ){
      return $this -> correiosFormatsCodes[$formatCode ];
    }else{
      return 'Format not found';
    }
  }

  function reset(){
    $this -> soapHasError = false;
    $this -> soapError    = array();
    $this -> soapResult   = false;
  }

  function getResult(){
    return $this -> soapResult;
  }

  function call($method, $parametersRaw = array()){
    $this -> reset();
    $parameters = array();
    if( is_array( $parametersRaw ) && sizeof( $parametersRaw ) > 0 ){
      foreach($parametersRaw as $k => $v){
        if( $k == 'nCdServico' ){
          if( !$this -> isServiceCodeValid( $v ) ){
            $this -> addError('W00', 'Invalid Service Code');
            return false;
          }
        }
        $parameters[ $k ] = $v;
      }
    }
    $parameters['nCdEmpresa'] = $this -> correiosCompanyCode;
    $parameters['sDsSenha']   = $this -> correiosCompanyPassword;
    try{
      $client = new SoapClient($this -> soapUrl, array('connection_timeout' => $this -> soapTimeout,'keep_alive' => $this -> soapKeepAlive));
      $result = $client -> $method( $parameters );
      unset($client);
    }catch( Exception $e ){
      $this -> addError($e -> getCode(), $e -> getMessage());
      return false;
    }
    $resultString = $method.'Result';
    if( !isset( $result -> $resultString ) ){
      $this -> addError('WS01', 'No result returned');
      return false;
    }elseif( isset( $result -> $resultString ) && count( $result -> $resultString ) === 0 ){
      $this -> addError('WS02', 'No results found');
      return false;
    }else{
      if( !isset( $result -> $resultString -> Servicos ) ){
        $this -> addError('WS03', 'No "Servicos" result returned');
        return false;
      }else{
        if( !isset( $result -> $resultString -> Servicos -> cServico ) ){
          $this -> addError('WS04', 'No "cServico" result returned');
          return false;
        }else{
          $result = $result -> $resultString -> Servicos -> cServico;
          if( !is_array( $result ) ){
            $result = array( $result );
          }
        }
      }
      $returnArray = array();
      foreach($result as $k => $v){
        $currentResult = $result[ $k ];
        if( $currentResult -> Erro != 0 ){
          $this -> addError($currentResult -> Erro, $currentResult -> MsgErro);
        }else{
          $currentObject = new CorreiosFrete();
          $currentObject -> setByStdClass( $currentResult );
          $currentObject -> setServiceName( $this -> getServiceName( $currentResult -> Codigo ) );
          $returnArray[ $currentResult -> Codigo ] = $currentObject;
        }
      }
      $this -> soapResult = $returnArray;
      return true;
    }
  }

  function getFrete($serviceCode, $originCEP, $destinationCEP, $formatCode, $weight, $length, $height, $width, $value = '0', $diameter = 0, $deliverByHand = false, $sendNoticeReceipt = false){
    if( !$this -> isFormatValid( $formatCode ) ){
      $this -> addError('W05', 'Invalid Format Code');
      return false;
    }
    $parameters['nCdServico']        = $serviceCode;
    $parameters['sCepOrigem']        = $this -> formatCEP( $originCEP );
    $parameters['sCepDestino']       = $this -> formatCEP( $destinationCEP );
    $parameters['nVlPeso']           = $weight;
    $parameters['nCdFormato']        = $formatCode;
    $parameters['nVlComprimento']    = $length;
    $parameters['nVlAltura']         = $height;
    $parameters['nVlLargura']        = $width;
    $parameters['nVlValorDeclarado'] = $value;
    $parameters['nVlDiametro']       = $diameter;
    if( $deliverByHand == true ){
      $parameters['sCdMaoPropria'] = 'S';
    }else{
      $parameters['sCdMaoPropria'] = 'N';
    }
    if ( $sendNoticeReceipt == true ){
      $parameters['sCdAvisoRecebimento'] = 'S';
    }else{
      $parameters['sCdAvisoRecebimento'] = 'N';
    }
    return $this -> call('CalcPrecoPrazo', $parameters);
  }
  
  function getPrazo($serviceCode, $originCEP, $destinationCEP){
    $parameters['nCdServico'] = $serviceCode;
    $parameters['sCepOrigem'] = $this -> formatCEP( $originCEP );
    $parameters['sCepDestino'] = $this -> formatCEP( $destinationCEP );
    $this -> call('CalcPrazo', $parameters);
  }

  function getPreco($serviceCode, $originCEP, $destinationCEP, $formatCode, $weight, $length, $height, $width, $value = '0', $diameter = 0, $deliverByHand = false, $sendNoticeReceipt = false){
    if( !$this -> isFormatValid( $formatCode ) ){
      $this -> addError('W06', 'Invalid Format Code');
      return false;
    }
    $parameters['nCdServico']        = $serviceCode;
    $parameters['sCepOrigem']        = $this -> formatCEP( $originCEP );
    $parameters['sCepDestino']       = $this -> formatCEP( $destinationCEP );
    $parameters['nVlPeso']           = $weight;
    $parameters['nCdFormato']        = $formatCode;
    $parameters['nVlComprimento']    = $length;
    $parameters['nVlAltura']         = $height;
    $parameters['nVlLargura']        = $width;
    $parameters['nVlValorDeclarado'] = $value;
    $parameters['nVlDiametro']       = $diameter;
    if( $deliverByHand == true ){
      $parameters['sCdMaoPropria'] = 'S';
    }else{
      $parameters['sCdMaoPropria'] = 'N';
    }
    if ( $sendNoticeReceipt == true ){
      $parameters['sCdAvisoRecebimento'] = 'S';
    }else{
      $parameters['sCdAvisoRecebimento'] = 'N';
    }
    $this -> call('CalcPreco', $parameters);
  }
}
?>