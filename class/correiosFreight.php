<?php
/*
* Correios Freight PHP class
*
* @pakage CORREIOS_FREIGHT_PHP
* @author Giuliano Riboni <giuliano@riboni.com.br>
* @copyright 2012 Giuliano Riboni
* @date 2012-11-27
* @version 1.0.0
*
* Correios Webservice Documentation:
*   http://www.correios.com.br/webservices/
*
* Code on GitHub:
*    https://github.com/riboni/CorreiosFreightSOAP
*
*/
class CorreiosFreight{
  var $serviceCode;
  var $serviceName;
  var $totalValue;
  var $deliveryTime;
  var $byHandValue;
  var $noticeReceiptValue;
  var $statedValue;
  var $homeDelivery;
  var $saturdayDelivery;

  function CorreiosFreight(){
  }

  function setServiceCode($v) {
    $this -> serviceCode = $v;
  }
  function getServiceCode() {
    return $this -> serviceCode;
  }

  function setServiceName($v) {
    $this -> serviceName = $v;
  }
  function getServiceName() {
    return $this -> serviceName;
  }

  function setTotalValue($v) {
    $this -> totalValue = $v;
  }
  function getTotalValue() {
    return $this -> totalValue;
  }

  function setDeliveryTime($v) {
    $this -> deliveryTime = $v;
  }
  function getDeliveryTime() {
    return $this -> deliveryTime;
  }

  function setByHandValue($v) {
    $this -> byHandValue = $v;
  }
  function getByHandValue() {
    return $this -> byHandValue;
  }

  function setNoticeReceiptValue($v) {
    $this -> noticeReceiptValue = $v;
  }
  function getNoticeReceiptValue() {
    return $this -> noticeReceiptValue;
  }

  function setStatedValue($v) {
    $this -> statedValue = $v;
  }
  function getStatedValue() {
    return $this -> statedValue;
  }

  function setHomeDelivery($v) {
    $this -> homeDelivery = $v;
  }
  function getHomeDelivery() {
    return $this -> homeDelivery;
  }

  function setSaturdayDelivery($v) {
    $this -> saturdayDelivery = $v;
  }
  function getSaturdayDelivery() {
    return $this -> saturdayDelivery;
  }

  function setByStdClass($object){
    $this -> setServiceCode( $object -> Codigo );
    $this -> setTotalValue( $this -> _makeMoney( $object -> Valor ) );
    $this -> setDeliveryTime( $object -> PrazoEntrega );
    $this -> setByHandValue( $this -> _makeMoney( $object -> ValorMaoPropria ) );
    $this -> setNoticeReceiptValue( $this -> _makeMoney( $object -> ValorAvisoRecebimento ) );
    $this -> setStatedValue( $this -> _makeMoney( $object -> ValorValorDeclarado ) );
    $this -> setHomeDelivery( $object -> EntregaDomiciliar );
    $this -> setSaturdayDelivery( $object -> EntregaSabado );
  }

  function _makeMoney($v){
    return str_replace(',', '.', $v);
  }
}
?>