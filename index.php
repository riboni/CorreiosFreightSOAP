<?php
include_once('class/correiosFreight.php');
include_once('class/correiosFreightSOAP.php');

$correiosSoap            = new CorreiosFreightSOAP();
$services                = $correiosSoap -> getServices();
$formats                 = $correiosSoap -> getFormats();
$packages                = $correiosSoap -> getPackages();
$noPackage               = array();
$noPackage['CATEGORY']   = 'Sem Embalagem';
$noPackage['TYPE']       = 'Tipo 0';
$noPackage['DETAILS']    = 'Sem Detalhes';
$noPackage['DIMENSIONS'] = '0mm x 0mm x 0mm';
$noPackage['PRICE']      = 0;
$packages                = array_merge( array('_NOPACKAGE_' => $noPackage), $packages);
if( isset( $_POST['makeQuery'] ) && $_POST['makeQuery'] == 'TRUE' ){
  if( isset( $_POST['selecionarTodosButton'] ) && $_POST['selecionarTodosButton'] != '' ){
    $selecionarTodosButton = ' checked';
  }else{
    $selecionarTodosButton = '';
  }
  if( is_array( $_POST['servico'] ) ){
    $servicos         = array_flip($_POST['servico']);
    $servicosPesquisa = implode(',', $_POST['servico']);
  }else{
    $servicos         = array();
    $servicosPesquisa = 'noCode';
  }
  $cepOrigem      = $_POST['cepOrigem'];
  $cepDestino     = $_POST['cepDestino'];
  $formato        = $_POST['formato'];
  $peso           = $_POST['peso'];
  $comprimento    = $_POST['comprimento'];
  $largura        = $_POST['largura'];
  $altura         = $_POST['altura'];
  $valorDeclarado = $_POST['valorDeclarado'];
  $diametro       = $_POST['diametro'];
  if( $_POST['maoPropria'] == 'TRUE' ){
    $maoPropria = true;
  }else{
    $maoPropria = false;
  }
  if( $_POST['avisoRecebimento'] == 'TRUE' ){
    $avisoRecebimento = true;
  }else{
    $avisoRecebimento = false;
  }
  if( isset( $_POST['codigo'] ) && $_POST['codigo'] != '' ){
    $codigo = $_POST['codigo'];
    $correiosSoap -> setCorreiosCompanyCode( $_POST['codigo'] );
  }
  if( isset( $_POST['senha'] ) && $_POST['senha'] != '' ){
    $senha = $_POST['senha'];
    $correiosSoap -> setCorreiosCompanyPassword( $_POST['senha'] );
  }
  if( $_POST['embalagem'] !== '_NOPACKAGE_' ){
    $embalagem        = $_POST['embalagem'];
    $embalagemDetails = $correiosSoap -> getPackageDetails( $embalagem );
  }else{
    $embalagem                 = '_NOPACKAGE_';
    $embalagemDetails['PRICE'] = 0;
  }
  $correiosSoap -> calculateFreight($servicosPesquisa, $cepOrigem, $cepDestino, $formato, $peso, $comprimento, $largura, $altura, $valorDeclarado , $diametro, $maoPropria, $avisoRecebimento);
}else{
  $query            = false;
  $servicos         = array();
  $cepOrigem        = '26255170';
  $cepDestino       = '96825150';
  $formato          = false;
  $peso             = '0.100';
  $comprimento      = '16';
  $largura          = '11';
  $altura           = '2';
  $valorDeclarado   = '0';
  $diametro         = '0';
  $maoPropria       = false;
  $avisoRecebimento = false;
  $codigo           = '';
  $senha            = '';
  $embalagem        = '_NOPACKAGE_';
}
?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='pt-br'>
<head>
<head>
	<title>API Soap Correios</title>
  <META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=ISO-8859-1'/>
  <style>
    fieldset{
      background-color: rgba(255, 255, 255, 0.7);
      width:            800px;
      margin-top:       20px;
    }
    .alwaysOn{
      background-color: #FFFFFF;
    }
    legend{
      background-color: #FFFFFF;
    }
    .resultTitle{
      background-color: #C0C0C0;
    }
    .resultError{
      background-color: #FF3366;
    }
  </style>
</head>
<body>
<fieldset class="alwaysOn">
  <legend>Pesquisa Frete:</legend>
  <form action="index.php" method="post" name="pesquisaFrete" id="pesquisaFrete" enctype="multipart/form-data">
    <input type="hidden" name="makeQuery" id="makeQuery" value="TRUE">
    <table cellspacing="2" cellpadding="2" border="0">
    <tr>
        <td valign="top">Servi&ccedil;o dos Correios:</td>
        <td>
          <input type="checkbox" name="selecionarTodosButton" onClick="selecionarTodos(this);"<?php echo $selecionarTodosButton; ?>>Selecionar Todos<br>
<?php
  if( is_array( $services ) && sizeof( $services ) > 0 ){
    foreach($services as $k => $v){
      if( array_key_exists($k, $servicos) ){
        $checked = ' checked';
      }else{
        $checked = '';
      }
      echo '          <input type="checkbox" name="servico[]" value="'.$k.'"'.$checked.'>'.utf8_decode($v).'<br>'."\n";
    }
  }
?>
        </td>
    </tr>
    <tr>
        <td>CEP Origem:</td>
        <td><input type="text" name="cepOrigem" id="cepOrigem" value="<?php echo $cepOrigem; ?>"></td>
    </tr>
    <tr>
        <td>CEP Destino:</td>
        <td><input type="text" name="cepDestino" id="cepDestino" value="<?php echo $cepDestino; ?>"></td>
    </tr>
    <tr>
        <td>Formato:</td>
        <td>
          <select name="formato" id="formato" size="1">
<?php
  if( is_array( $formats ) && sizeof( $formats ) > 0 ){
    foreach($formats as $k => $v){
      if( $k == $formato ){
        $selected = ' SELECTED';
      }else{
        $selected = '';
      }
      echo '            <option value="'.$k.'"'.$selected.'>'.utf8_decode($v).'</option>'."\n";
    }
  }
?>
          </select>
        </td>
    </tr>
    <tr>
        <td>Peso (kg):</td>
        <td><input type="text" name="peso" id="peso" value="<?php echo $peso; ?>"></td>
    </tr>
    <tr>
        <td>Comprimento (Cm):</td>
        <td><input type="text" name="comprimento" id="comprimento" value="<?php echo $comprimento; ?>"></td>
    </tr>
    <tr>
        <td>Largura (Cm):</td>
        <td><input type="text" name="largura" id="largura" value="<?php echo $largura; ?>"></td>
    </tr>
    <tr>
        <td>Altura (Cm):</td>
        <td><input type="text" name="altura" id="altura" value="<?php echo $altura; ?>"></td>
    </tr>
    <tr>
        <td>Valor Declarado (RRCC):</td>
        <td><input type="text" name="valorDeclarado" id="valorDeclarado" value="<?php echo $valorDeclarado; ?>"></td>
    </tr>
    <tr>
        <td>Diametro (Cm):</td>
        <td><input type="text" name="diametro" id="diametro" value="<?php echo $diametro; ?>"></td>
    </tr>
    <tr>
        <td>M&atilde;o Pr&oacute;pria:</td>
        <td>
          <select name="maoPropria" id="maoPropria" size="1">
<?php
  if( $maoPropria === true ){
    $yesSelected = ' SELECTED';
    $noSelected  = '';
  }else{
    $yesSelected = '';
    $noSelected  = ' SELECTED';
  }
?>
            <option value="TRUE"<?php echo $yesSelected; ?>>Sim</option>
            <option value="FALSE"<?php echo $noSelected; ?>>N&atilde;o</option>
          </select>
        </td>
    </tr>
    <tr>
        <td>Aviso de Recebimento:</td>
        <td>
          <select name="avisoRecebimento" id="avisoRecebimento" size="1">
<?php
  if( $avisoRecebimento === true ){
    $yesSelected = ' SELECTED';
    $noSelected  = '';
  }else{
    $yesSelected = '';
    $noSelected  = ' SELECTED';
  }
?>
            <option value="TRUE"<?php echo $yesSelected; ?>>Sim</option>
            <option value="FALSE"<?php echo $noSelected; ?>>N&atilde;o</option>
          </select>
        </td>
    </tr>
    <tr>
        <td>Embalagem</td>
        <td>
          <select name="embalagem" id="embalagem" size="1">
<?php
  if( is_array( $packages ) && sizeof( $packages ) > 0 ){
    foreach($packages as $k => $v){
      if( $embalagem == $k ){
        $selected = ' SELECTED';
      }else{
        $selected = '';
      }
      $packageDetails = $packages[ $k ];
      $fullName = $packageDetails['CATEGORY'].' - '.$packageDetails['TYPE'].' - '.$packageDetails['DETAILS'].' - '.$packageDetails['DIMENSIONS'].'. R$:'.number_format($packageDetails['PRICE'], 2, ',', ' ');
      echo '            <option value="'.$k.'"'.$selected.'>'.utf8_decode($fullName).'</option>'."\n";
    }
  }
?>
          </select>
        </td>
    </tr>
    <tr>
        <td>Codigo Administrativo:</td>
        <td><input type="text" name="codigo" id="codigo" value="<?php echo $codigo; ?>"></td>
    </tr>
    <tr>
        <td>Senha Administrativa:</td>
        <td><input type="text" name="senha" id="senha" value="<?php echo $senha; ?>"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" id="submit" value="Pesquisar"></td>
    </tr>
    </table>
  </form>
</fieldset>
<?php
  if( $query !== false ){
    if( $correiosSoap -> hasError() == false ){
      $result = $correiosSoap -> getResult();
?>
<fieldset>
  <legend>Resultado:</legend>
  <table cellspacing="2" cellpadding="2" border="0">
<?php
  if( is_array( $result ) && sizeof( $result ) > 0 ){
    foreach($result as $k => $v){
      $freteObject = $result[ $k ];
      echo '  <tr>'."\n";
      echo '    <td colspan="2" class="resultTitle">'.$freteObject -> getServiceName().' ('.$freteObject -> getServiceCode().')</td>'."\n";
      echo '  </tr>'."\n";
      if( $correiosSoap -> serviceHasError( $freteObject -> getServiceCode() ) ){
        $serviceErrors = $correiosSoap -> getErrors( $freteObject -> getServiceCode() );
        echo '  <tr>'."\n";
        echo '    <td colspan="2" class="resultError"><b>Houve um, ou mais, erro(s) neste servi&ccedil;o</b></td>'."\n";
        echo '  </tr>'."\n";
        if( is_array( $serviceErrors ) && sizeof( $serviceErrors ) > 0 ){
          foreach($serviceErrors as $k => $v){
            echo '  <tr>'."\n";
            echo '    <td colspan="2" class="resultError">'.utf8_decode($v).' ('.$k.')</td>'."\n";
            echo '  </tr>'."\n";
          }
        }
      }else{
        echo '  <tr>'."\n";
        echo '    <td>Valor Declarado:</td>'."\n";
        echo '    <td>R$: '.number_format($freteObject -> getStatedValue(), 2, ',', ' ').'</td>'."\n";
        echo '  </tr>'."\n";
        echo '  <tr>'."\n";
        echo '    <td>Valor Entrega M&atilde;o pr&oacute;pria:</td>'."\n";
        echo '    <td>R$: '.number_format($freteObject -> getByHandValue(), 2, ',', ' ').'</td>'."\n";
        echo '  </tr>'."\n";
        echo '  <tr>'."\n";
        echo '    <td>Valor Aviso de Recebimento:</td>'."\n";
        echo '    <td>R$: '.number_format($freteObject -> getNoticeReceiptValue(), 2, ',', ' ').'</td>'."\n";
        echo '  </tr>'."\n";
        echo '  <tr>'."\n";
        echo '    <td>Entrega em Casa:</td>'."\n";
        if( $freteObject -> getHomeDelivery() == 'S' ){
          echo '    <td>Sim</td>'."\n";
        }else{
          echo '    <td>N&atilde;o</td>'."\n";
        }
        echo '  </tr>'."\n";
        echo '  <tr>'."\n";
        echo '    <td>Entrega Sabado:</td>'."\n";
        if( $freteObject -> getSaturdayDelivery() == 'S' ){
          echo '    <td>Sim</td>'."\n";
        }else{
          echo '    <td>N&atilde;o</td>'."\n";
        }
        echo '  </tr>'."\n";
        echo '  <tr>'."\n";
        echo '    <td>Prazo de Entrega:</td>'."\n";
        echo '    <td><b>'.$freteObject -> getDeliveryTime().' Dia(s)<b></td>'."\n";
        echo '  </tr>'."\n";
        echo '  <tr>'."\n";
        echo '    <td>Valor Frete:</td>'."\n";
        echo '    <td>R$: '.number_format($freteObject -> getTotalValue(), 2, ',', ' ').'</td>'."\n";
        echo '  </tr>'."\n";
        echo '  <tr>'."\n";
        echo '    <td>Valor Embalagem:</td>'."\n";
        echo '    <td>R$: '.number_format($embalagemDetails['PRICE'], 2, ',', ' ').'</td>'."\n";
        echo '  </tr>'."\n";
        echo '  <tr>'."\n";
        echo '    <td>Valor Total:</td>'."\n";
        echo '    <td><b>R$: '.number_format($freteObject -> getTotalValue() + $embalagemDetails['PRICE'], 2, ',', ' ').'</b></td>'."\n";
        echo '  </tr>'."\n";
      }
    }
  }
?>
  </table>
</fieldset>
<?php
    }else{
      $embalagemDetails = false;
      $errorList = $correiosSoap -> getErrors();
?>
<fieldset>
  <legend>Erro na Pesquisa:</legend>
  <table cellspacing="2" cellpadding="2" border="0">
<?php
  if( is_array( $errorList ) && sizeof( $errorList ) > 0 ){
    echo '  <tr>'."\n";
    echo '    <td colspan="2" class="resultError"><b>Houve um, ou mais, erro(s) neste servi&ccedil;o</b></td>'."\n";
    echo '  </tr>'."\n";
    foreach($errorList as $k => $v){
      echo '  <tr>'."\n";
      echo '    <td colspan="2" class="resultError">'.utf8_decode($v).' ('.$k.')</td>'."\n";
      echo '  </tr>'."\n";
    }
  }
?>
  </table>
</fieldset>
<?php
    }
  }
?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-27137457-8']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  function selecionarTodos(source){
    checkboxes = document.getElementsByName('servico[]');
    if( checkboxes.length > 0 ){
      for (var i = 0; i < checkboxes.length; i++) {
        var checkbox = checkboxes[ i ];
        checkbox.checked = source.checked;
      }
    }
  }
</script>
</body>
</html>