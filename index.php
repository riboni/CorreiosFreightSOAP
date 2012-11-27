<?php
include_once('class/correiosFrete.php');
include_once('class/correiosSOAP.php');

$correiosSoap = new CorreiosSOAP();

echo "Calculo de FRETE PAC e SEDEX:";
$correiosSoap -> getFrete('41106,40010','26255170','96825150', 1, 0.1, 16, 2, 11);
if( $correiosSoap -> hasError() == false ){
  $result = $correiosSoap -> getResult();
  echo " OK";
}else{
  $result = $correiosSoap -> getErrors();
  echo " ERROR";
}
echo "<br><pre>";Print_r($result);echo "</pre>";
echo "<br><br><br>";

echo "Calculo de PRAZO PAC:";
$correiosSoap -> getPrazo('41106','26255170','96825150');
if( $correiosSoap -> hasError() == false ){
  $result = $correiosSoap -> getResult();
  echo " OK";
}else{
  $result = $correiosSoap -> getErrors();
  echo " ERROR";
}
echo "<br><pre>";Print_r($result);echo "</pre>";
echo "<br><br><br>";

echo "Calculo de PREÇO PAC:";
$correiosSoap -> getPreco('41106','26255170','96825150', 1, 0.1, 16, 2, 11);
if( $correiosSoap -> hasError() == false ){
  $result = $correiosSoap -> getResult();
  echo " OK";
}else{
  $result = $correiosSoap -> getErrors();
  echo " ERROR";
}
echo "<br><pre>";Print_r($result);echo "</pre>";
echo "<br><br><br>";

echo "Calculo de FRETE SEDEX:";
$correiosSoap -> getFrete('40010','26255170','96825150', 1, 0.1, 16, 2, 11);
if( $correiosSoap -> hasError() == false ){
  $result = $correiosSoap -> getResult();
  echo " OK";
}else{
  $result = $correiosSoap -> getErrors();
  echo " ERROR";
}
echo "<br><pre>";Print_r($result);echo "</pre>";
echo "<br><br><br>";

echo "Calculo de FRETE SEDEX a cobrar:";
$correiosSoap -> getFrete('40045','26255170','96825150', 1, 0.1, 16, 2, 11, 10.0);
if( $correiosSoap -> hasError() == false ){
  $result = $correiosSoap -> getResult();
  echo " OK";
}else{
  $result = $correiosSoap -> getErrors();
  echo " ERROR";
}
echo "<br><pre>";Print_r($result);echo "</pre>";
echo "<br><br><br>";

echo "Calculo de FRETE SEDEX 10:";
$correiosSoap -> getFrete('40215','90220020','90440150', 1, 0.1, 16, 2, 11);
if( $correiosSoap -> hasError() == false ){
  $result = $correiosSoap -> getResult();
  echo " OK";
}else{
  $result = $correiosSoap -> getErrors();
  echo " ERROR";
}
echo "<br><pre>";Print_r($result);echo "</pre>";
echo "<br><br><br>";
?>