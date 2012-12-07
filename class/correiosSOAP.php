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
  var $serviceHasError         = false;
  var $serviceError            = array();
  var $soapResult              = false;
  var $correiosServicesCodes   = array('40010' => 'SEDEX sem contrato',
                                       '40045' => 'SEDEX a Cobrar, sem contrato',
                                       '40126' => 'SEDEX a Cobrar, com contrato',
                                       '40215' => 'SEDEX 10, sem contrato',
                                       '40290' => 'SEDEX Hoje, sem contrato',
                                       '40096' => 'SEDEX com contrato',
                                       '40436' => 'SEDEX com contrato',
                                       '40444' => 'SEDEX com contrato',
                                       '40568' => 'SEDEX com contrato',
                                       '40606' => 'SEDEX com contrato',
                                       '41106' => 'PAC sem contrato',
                                       '41068' => 'PAC com contrato',
                                       '81019' => 'e-SEDEX, com contrato',
                                       '81027' => 'e-SEDEX Prioritário, com conrato',
                                       '81035' => 'e-SEDEX Express, com contrato',
                                       '81868' => '(Grupo 1) e-SEDEX, com contrato',
                                       '81833' => '(Grupo 2) e-SEDEX, com contrato',
                                       '81850' => '(Grupo 3) e-SEDEX, com contrato');
  var $correiosFormatsCodes    = array('1' => 'Formato caixa/pacote',
                                       '2' => 'Formato rolo/prisma',
                                       '3' => 'Envelope');
  var $correiosPackageCodes    = array('CET4B' => array('CATEGORY' => 'Caixa de Encomenda', 'TYPE' => 'Tipo 4B', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '360mm x 270mm x 180mm', 'PRICE' => 6.00),
                                       'CET5B' => array('CATEGORY' => 'Caixa de Encomenda', 'TYPE' => 'Tipo 5B', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '540mm x 360mm x 270mm', 'PRICE' => 12.70),
                                       'CET1A' => array('CATEGORY' => 'Caixa de Encomenda Correios', 'TYPE' => 'Tipo 1', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '180mm x 135mm x 90mm', 'PRICE' => 3.25),
                                       'CET2A' => array('CATEGORY' => 'Caixa de Encomenda Correios', 'TYPE' => 'Tipo 2', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '270mm x 180mm x 90mm', 'PRICE' => 3.40),
                                       'CET2B' => array('CATEGORY' => 'Caixa de Encomenda Correios', 'TYPE' => 'Tipo 2B', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '270mm x 180mm x 90mm', 'PRICE' => 2.40),
                                       'CET3A' => array('CATEGORY' => 'Caixa de Encomenda Correios', 'TYPE' => 'Tipo 3', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '270mm x 225mm x 135mm', 'PRICE' => 4.80),
                                       'CET4A' => array('CATEGORY' => 'Caixa de Encomenda Correios', 'TYPE' => 'Tipo 4', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '360mm x 270mm x 180mm', 'PRICE' => 6.60),
                                       'CET6B' => array('CATEGORY' => 'Caixa de Encomenda Correios', 'TYPE' => 'Tipo 6B', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '360mm x 270mm x 270mm', 'PRICE' => 10.00),
                                       'CET7A' => array('CATEGORY' => 'Caixa de Encomenda Correios', 'TYPE' => 'Tipo 7', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '360mm x 280mm x 40mm', 'PRICE' => 4.10),
                                       'CPT01' => array('CATEGORY' => 'Caixa de Encomenda Presentes', 'TYPE' => 'Tipo 01', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '180mm x 135mm x 90mm', 'PRICE' => 3.75),
                                       'CPT03' => array('CATEGORY' => 'Caixa de Encomenda Presentes', 'TYPE' => 'Tipo 03', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '270mm x 225mm x 135mm', 'PRICE' => 5.30),
                                       'CTT2A' => array('CATEGORY' => 'Caixa de Encomenda Temática: Brasília', 'TYPE' => 'Tipo 2', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '270mm x 180mm x 90mm', 'PRICE' => 3.90),
                                       'CTT02' => array('CATEGORY' => 'Caixa de Encomenda Temática: Laço', 'TYPE' => 'Tipo 02', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '270mm x 180mm x 90mm', 'PRICE' => 3.90),
                                       'CTT03' => array('CATEGORY' => 'Caixa de Encomenda Temática: Laço', 'TYPE' => 'Tipo 03', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '270mm x 225mm x 135mm', 'PRICE' => 5.30),
                                       'CTT2A' => array('CATEGORY' => 'Caixa de Encomenda Temática: Presente', 'TYPE' => 'Tipo 2', 'DETAILS' => 'Papelão Ondulado', 'DIMENSIONS' => '270mm x 180mm x 90mm', 'PRICE' => 3.90));
  var $correiosCompanyCode     = '';
  var $correiosCompanyPassword = '';

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

  function addError($code, $error, $errorOnService = false){
    if( $errorOnService === false ){
      $this -> soapError[ $code ] = $error;
      $this -> soapHasError       = true;
    }else{
      $this -> serviceError[ $errorOnService ][ $code ] = $error;
      $this -> serviceHasError                          = true;
    }
  }

  function getErrors($serviceCode = false){
    if( $serviceCode === false ){
      return $this -> soapError;
    }else{
      if( isset( $this -> serviceError[ $serviceCode ] ) ){
        return $this -> serviceError[ $serviceCode ];
      }else{
        return array();
      }
    }
  }

  function hasError(){
    return $this -> soapHasError;
  }

  function serviceHasError($serviceCode = false){
    if( $serviceCode === false ){
      return $this -> serviceHasError;
    }else{
      return isset( $this -> serviceError[ $serviceCode ] );
    }
  }

  function formatCEP($cep){
    $cep = str_replace('-', '', $cep);
    return $cep;
  }

  function getServices(){
    return $this -> correiosServicesCodes;
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
      return 'Serviço não encontrado';
    }
  }

  function getFormats(){
    return $this -> correiosFormatsCodes;
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
      return 'Formato não encontrado';
    }
  }

  function getPackages(){
    return $this -> correiosPackageCodes;
  }

  function getPackageDetails($code){
    if( isset( $this -> correiosPackageCodes[ $code ] ) ){
      return $this -> correiosPackageCodes[ $code ];
    }else{
      return 'Embalagem não encontrada';
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
            $this -> addError('WS01', 'Código do serviço inválido');
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
      $this -> addError('WS02', 'Nenhum resultado foi retornado');
      return false;
    }elseif( isset( $result -> $resultString ) && count( $result -> $resultString ) === 0 ){
      $this -> addError('WS03', 'Nenhum resultado foi encontrado');
      return false;
    }else{
      if( !isset( $result -> $resultString -> Servicos ) ){
        $this -> addError('WS04', 'Nenhum resultado de "Serviços" foi encontrado');
        return false;
      }else{
        if( !isset( $result -> $resultString -> Servicos -> cServico ) ){
          $this -> addError('WS05', 'Nenhum resultado de "cServico" foi encontrado');
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
          $currentObject = new CorreiosFrete();
          $currentObject -> setByStdClass( $currentResult );
          $currentObject -> setServiceName( $this -> getServiceName( $currentResult -> Codigo ) );
          $returnArray[ $currentResult -> Codigo ] = $currentObject;
          $this -> addError($currentResult -> Erro, $currentResult -> MsgErro, $currentResult -> Codigo);
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

  function calculateFrete($serviceCode, $originCEP, $destinationCEP, $formatCode, $weight, $length, $width, $height, $declaredValue = 0, $diameter = 0, $deliverByHand = false, $sendNoticeReceipt = false){
    if( !$this -> isFormatValid( $formatCode ) ){
      $this -> addError('WS06', 'Código de formato inválido');
      return false;
    }
    if( $weight < 0.1 ){
      $this -> addError('WS07', 'Peso mínimo 0.100 Kg');
      return false;
    }
    if( $length < 16 ){
      $this -> addError('WS08', 'Comprimento mínimo 16 Cm');
      return false;
    }
    if( $width < 11 ){
      $this -> addError('WS09', 'Largura mínima 11 Cm');
      return false;
    }
    if( $height < 2 ){
      $this -> addError('WS09', 'Altura mínima 2 Cm');
      return false;
    }
    $parameters['nCdServico']        = $serviceCode;
    $parameters['sCepOrigem']        = $this -> formatCEP( $originCEP );
    $parameters['sCepDestino']       = $this -> formatCEP( $destinationCEP );
    $parameters['nVlPeso']           = $weight;
    $parameters['nCdFormato']        = $formatCode;
    $parameters['nVlComprimento']    = $length;
    $parameters['nVlLargura']        = $width;
    $parameters['nVlAltura']         = $height;
    $parameters['nVlValorDeclarado'] = $declaredValue;
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

  function getPreco($serviceCode, $originCEP, $destinationCEP, $formatCode, $weight, $length, $width, $height, $value = '0', $diameter = 0, $deliverByHand = false, $sendNoticeReceipt = false){
    if( !$this -> isFormatValid( $formatCode ) ){
      $this -> addError('WS06', 'Código de formato inválido');
      return false;
    }
    if( $length < 16 ){
      $this -> addError('WS08', 'Comprimento mínimo 16 Cm');
      return false;
    }
    if( $width < 11 ){
      $this -> addError('WS09', 'Largura mínima 11 Cm');
      return false;
    }
    if( $height < 2 ){
      $this -> addError('WS09', 'Altura mínima 2 Cm');
      return false;
    }
    $parameters['nCdServico']        = $serviceCode;
    $parameters['sCepOrigem']        = $this -> formatCEP( $originCEP );
    $parameters['sCepDestino']       = $this -> formatCEP( $destinationCEP );
    $parameters['nVlPeso']           = $weight;
    $parameters['nCdFormato']        = $formatCode;
    $parameters['nVlComprimento']    = $length;
    $parameters['nVlLargura']        = $width;
    $parameters['nVlAltura']         = $height;
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