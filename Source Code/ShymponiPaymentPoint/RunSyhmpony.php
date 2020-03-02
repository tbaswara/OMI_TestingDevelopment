<?php

require_once 'CallFunction.php';
require_once 'PlnPostpaidMessageHelper.php';
require_once 'PlnPrepaidMessageHelper.php';
require_once 'PlnNontaglisMessageHelper.php';
require_once 'JAK8583.class.php';
require_once 'ISO8583Helpers.php';

$tipe_transaksi=    $_GET['tipe_transaksi']; 

switch ($tipe_transaksi)
{
    
    case "prepaid":

$transaction_prepaid = new stdClass();

$transaction_prepaid->flag          = $_GET['flag'];
$transaction_prepaid->nometer_idpel = $_GET['nometer_idpel'];
$transaction_prepaid->buying_option = $_GET['buying_option'];
    
    break;
    
    case "postpaid":
    
    $transaction_postpaid = new stdClass();

    $transaction_postpaid->subscriber_id = $_GET['subscriber_id'];

    break;

    case "nontaglis":
    $transaction_nontaglis = new stdClass();

    $transaction_nontaglis->nomor_registrasi = $_GET['noreg'];

    break;
    default :"Jenis Transaksi yang dimasukan salah"; 
}




switch ($tipe_transaksi)
{
    
    case "prepaid":
    
          
    CallFunction::connectprepaid($transaction_prepaid);
        
        break;    
    
    case "postpaid":
          
    CallFunction::connectpostpaid($transaction_postpaid);    
        
        break;    
        
    case "nontaglis":
        
    CallFunction::connectnontaglis($transaction_nontaglis);
        
        break;
    
    default :"Jenis Transaksi yang dimasukan salah";    
    
}







