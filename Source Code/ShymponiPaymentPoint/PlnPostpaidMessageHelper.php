<?php

require_once "JAK8583.class.php";
require_once "SyhmponiMessageHelper.php";

class PlnPostPaidMessageHelper extends SyhmponiMessageHelper  {

   
   //Function Convert DE48String
   
   
   public function getInquiryMessage($plnpostpaid)
    {
         
         $inquiryString = $plnpostpaid->switcher_ID.$plnpostpaid->subscriber_ID
                ;
        
        return $inquiryString;
    }
    
    public function getPaymentMessage($plnpostpaid)
    {
        $inquiryString = $plnpostpaid->switcher_ID.
                $plnpostpaid->subscriber_ID.
                $plnpostpaid->bill_status.
                $plnpostpaid->payment_status.
                $plnpostpaid->total_outstandingBill.
                $plnpostpaid->switcher_ReferenceNumber.
                $plnpostpaid->subscriber_name.
                $plnpostpaid->service_unit.
                $plnpostpaid->service_unit_phone.
                $plnpostpaid->subscriber_segmentation.
                $plnpostpaid->power_consuming_category.
                $plnpostpaid->admin_charges.
                $plnpostpaid->bill_period.
                $plnpostpaid->due_date.
                $plnpostpaid->meter_read_date.
                $plnpostpaid->total_electricityBill.
                $plnpostpaid->incenctive.
                $plnpostpaid->value_addedTax.
                $plnpostpaid->penalty_fee.
                $plnpostpaid->previous_meter_reading1.
                $plnpostpaid->current_meter_reading1.
                $plnpostpaid->previous_meter_reading2.
                $plnpostpaid->current_meter_reading2.
                $plnpostpaid->previous_meter_reading3.
                $plnpostpaid->current_meter_reading3;
        
        return $inquiryString;
    }
    
    public function getReversalMessage($plnpostpaid){
        
        $inquiryString = $plnpostpaid->switcher_ID.
                $plnpostpaid->subscriber_ID.
                $plnpostpaid->bill_status.
                $plnpostpaid->payment_status.
                $plnpostpaid->total_outstandingBill.
                $plnpostpaid->switcher_ReferenceNumber.
                $plnpostpaid->subscriber_name.
                $plnpostpaid->service_unit.
                $plnpostpaid->service_unit_phone.
                $plnpostpaid->subscriber_segmentation.
                $plnpostpaid->power_consuming_category.
                $plnpostpaid->admin_charges.
                $plnpostpaid->bill_period.
                $plnpostpaid->due_date.
                $plnpostpaid->meter_read_date.
                $plnpostpaid->total_electricityBill.
                $plnpostpaid->incenctive.
                $plnpostpaid->value_addedTax.
                $plnpostpaid->penalty_fee.
                $plnpostpaid->previous_meter_reading1.
                $plnpostpaid->current_meter_reading1.
                $plnpostpaid->previous_meter_reading2.
                $plnpostpaid->current_meter_reading2.
                $plnpostpaid->previous_meter_reading3.
                $plnpostpaid->current_meter_reading3;
        
        return $inquiryString;
    }
    
    public function GetReversalMessageDE56($plnpostpaid) 
    {
            $DE56String=$plnpostpaid->original_MTI.
                    $plnpostpaid->original_PCTAN.
                    $plnpostpaid->original_DateTime.
                    $plnpostpaid->original_BankCode;
            
            return $DE56String;
    }
    


    
    public  function getFinancialInquiryMessage($transaction)
    { 
        
        $jak=new JAK8583();
        $parseDE48= self::ParseDE48InquiryRequest($transaction);
        $inquiry_message=self::getInquiryMessage($parseDE48);
        $input__stan=28421;
        $jak->addMTI(self::MTI_REQUEST_CODE);
        $jak->addData(self::DE_PRIMARY_ACCOUNT_NUMBER, "99501");
        $jak->addData(self::DE_SYSTEM_TRACE_AUDIT_NUMBER,  str_pad($input__stan,12,"0",STR_PAD_LEFT));
        $jak->addData(self::DE_DATE_TIME_LOCAL_TRANSACTION,20150521144845);
        $jak->addData(self::DE_MERCHANT_CATEGORY_CODE,6021);
        $jak->addData(self::DE_BANK_CODE, "4510017");
        $jak->addData(self::DE_PARTNER_CENTRAL_ID,6666666);
        $jak->addData(self::DE_TERMINAL_ID,"0000000000000048");
        $jak->addData(self::DE_ADDITIONAL_DATA,$inquiry_message);
        
        return $jak->getISO();     
                
    }
    
    public function getFinancialPaymentMessage($inquiry_response)
    {
        $jak=new JAK8583();
        $parseDE48=  self::ParseDE48PaymentRequest($inquiry_response);
        $inquiry_message= self::getPaymentMessage($parseDE48);
        $transaction_amount=$inquiry_response->iso_currency.$inquiry_response->currency_minor.$inquiry_response->value_amount;
        $jak->addMTI(self::MTI_PAYMENT_AND_PURCHASE_REQUEST_CODE);
        $jak->addData(self::DE_PRIMARY_ACCOUNT_NUMBER, "99501");
        $jak->addData(self::DE_TRANSACTION_AMOUNT,$transaction_amount);
        $jak->addData(self::DE_SYSTEM_TRACE_AUDIT_NUMBER,$inquiry_response->stan);
        $jak->addData(self::DE_DATE_TIME_LOCAL_TRANSACTION,$inquiry_response->date_time);
        $jak->addData(self::DE_MERCHANT_CATEGORY_CODE,$inquiry_response->merchant_code);
        $jak->addData(self::DE_BANK_CODE,$inquiry_response->bank_code);
        $jak->addData(self::DE_PARTNER_CENTRAL_ID,$inquiry_response->partner_cid);
        $jak->addData(self::DE_TERMINAL_ID,$inquiry_response->terminal_id);
        $jak->addData(self::DE_ADDITIONAL_DATA,$inquiry_message);
 
        
        return $jak->getISO();
                
    }
    
       public function getFinancialReversalMessage($substrakLengthData)
    {
        $substrak_reversal=substr($substrakLengthData,0,4);
        $parse_reversal=  str_replace($substrak_reversal,self::MTI_REVERSAL_REQUEST_CODE,$substrakLengthData);
        
        return $parse_reversal;
                
    }
    
    public function ParseDE48InquiryRequest($transaction)
    {
            $plnMessage = new PlnPostPaidMessageHelper();
            
            $plnMessage->setSwitcherID("0000000");
            $plnMessage->setSubscriberID($transaction->subscriber_id); // ID Pelanggan
            
            return $plnMessage;
    }
    
    public function ParseInquiryRequest()
    {
         $array_iso=$iso_response;
          $inquiry_response=new stdClass();
          $inquiry_response->mti            =substr($array_iso,0,4);
          $inquiry_response->bitmap         =substr($array_iso,4,16);
          $inquiry_response->length_of_pan  =substr($array_iso,20,2);
          $inquiry_response->pan            =substr($array_iso,22,5);
          $inquiry_response->iso_currency   =substr($array_iso,27,3);
          $inquiry_response->currency_minor =substr($array_iso,30,1);
          $inquiry_response->value_amount   =substr($array_iso,31,12);
          $inquiry_response->stan           =substr($array_iso,43,12);
          $inquiry_response->date_time      =substr($array_iso,55,14);
          $inquiry_response->merchant_code  =substr($array_iso,69,4);
          $inquiry_response->length_bank    =substr($array_iso,73,2);
          $inquiry_response->bank_code      =substr($array_iso,75,7);
          $inquiry_response->length_cid     =substr($array_iso,82,2);
          $inquiry_response->partner_cid    =substr($array_iso,84,7);
          $inquiry_response->response_code  =substr($array_iso,91,4);
          $inquiry_response->terminal_id    =substr($array_iso,95,16);
          $inquiry_response->length_privated=substr($array_iso,111,3);
          $inquiry_response->switcher_id    =substr($array_iso,114,7);
          $inquiry_response->subscriber_id  =substr($array_iso,121,12);
        
        
        
    }


    public function  ParseInquiryResponse($iso_response)
    {
          $array_iso=$iso_response;
          $inquiry_response=new stdClass();
          $inquiry_response->mti            =substr($array_iso,0,4);
          $inquiry_response->bitmap         =substr($array_iso,4,16);
          $inquiry_response->length_of_pan  =substr($array_iso,20,2);
          $inquiry_response->pan            =substr($array_iso,22,5);
          $inquiry_response->iso_currency   =substr($array_iso,27,3);
          $inquiry_response->currency_minor =substr($array_iso,30,1);
          $inquiry_response->value_amount   =substr($array_iso,31,12);
          $inquiry_response->stan           =substr($array_iso,43,12);
          $inquiry_response->date_time      =substr($array_iso,55,14);
          $inquiry_response->merchant_code  =substr($array_iso,69,4);
          $inquiry_response->length_bank    =substr($array_iso,73,2);
          $inquiry_response->bank_code      =substr($array_iso,75,7);
          $inquiry_response->length_cid     =substr($array_iso,82,2);
          $inquiry_response->partner_cid    =substr($array_iso,84,7);
          $inquiry_response->response_code  =substr($array_iso,91,4);
          $inquiry_response->terminal_id    =substr($array_iso,95,16);
          $inquiry_response->length_privated=substr($array_iso,111,3);
          $inquiry_response->switcher_id    =substr($array_iso,114,7);
          $inquiry_response->subscriber_id  =substr($array_iso,121,12);
      
          if($inquiry_response->response_code=="0000")
              {
          $inquiry_response->bill_status              =substr($array_iso,133,1);
          $inquiry_response->total_outstanding_bill   =substr($array_iso,134,2);
          $inquiry_response->switcher_reference_number=substr($array_iso,136,32);
          $inquiry_response->subscriber_name          =substr($array_iso,168,25);
          $inquiry_response->service_unit             =substr($array_iso,193,5);
          $inquiry_response->service_unit_phone       =substr($array_iso,198,15);
          $inquiry_response->subscriber_segmentation  =substr($array_iso,213,4);
          $inquiry_response->power_consuming_category =substr($array_iso,217,9);
          $inquiry_response->total_admin_charges      =substr($array_iso,226,9);
          $inquiry_response->bill_period              =substr($array_iso,235,6);
          $inquiry_response->due_date                 =substr($array_iso,241,8);
          $inquiry_response->meter_read_date          =substr($array_iso,249,8);
          $inquiry_response->total_electricity_bill   =substr($array_iso,257,11);
          $inquiry_response->incentive                =substr($array_iso,268,11);
          $inquiry_response->value_added_tax          =substr($array_iso,279,10);
          $inquiry_response->penalty_fee              =substr($array_iso,289,9);
          $inquiry_response->previous_meter_reading1  =substr($array_iso,298,8);
          $inquiry_response->current_meter_reading1   =substr($array_iso,306,8);
          $inquiry_response->previous_meter_reading2  =substr($array_iso,314,8);
          $inquiry_response->current_meter_reading2   =substr($array_iso,322,8);
          $inquiry_response->previous_meter_reading3  =substr($array_iso,330,8);
          $inquiry_response->current_meter_reading3   =substr($array_iso,338,-3);
              }
          return $inquiry_response;
    }
        
     public function  ParseDE48PaymentRequest($inquiry_response)
    {
          $plnMessage = new PlnPostPaidMessageHelper();
          $plnMessage->setSwitcherID($inquiry_response->switcher_id);
          $plnMessage->setSubscriberID($inquiry_response->subscriber_id);
          $plnMessage->setSubscriberName($inquiry_response->subscriber_name);
          $plnMessage->setSubscriberSegmentation($inquiry_response->subscriber_segmentation);
          $plnMessage->setBillStatus($inquiry_response->bill_status);
          $plnMessage->setBillPeriod($inquiry_response->bill_period);
          $plnMessage->setPaymentStatus($inquiry_response->bill_status);
          $plnMessage->setSwitcherReferenceNumber($inquiry_response->switcher_reference_number);
          $plnMessage->setServiceUnit($inquiry_response->service_unit);
          $plnMessage->setServicePhoneUnit($inquiry_response->service_unit_phone);
          $plnMessage->setTotalOutstandingBill($inquiry_response->total_outstanding_bill);
          $plnMessage->setTotalElectricityBill($inquiry_response->total_electricity_bill);
          $plnMessage->setIncentive($inquiry_response->incentive);
          $plnMessage->setValueAddedTax($inquiry_response->value_added_tax);
          $plnMessage->setPenaltyFee($inquiry_response->penalty_fee);
          $plnMessage->setPreviousMeterReading1($inquiry_response->previous_meter_reading1);
          $plnMessage->setCurrentMeterReading1($inquiry_response->current_meter_reading1);
          $plnMessage->setPreviousMeterReading2($inquiry_response->previous_meter_reading2);
          $plnMessage->setCurrentMeterReading2($inquiry_response->current_meter_reading2);
          $plnMessage->setPreviousMeterReading3($inquiry_response->previous_meter_reading3);
          $plnMessage->setCurrentMeterReading3($inquiry_response->current_meter_reading3);
          $plnMessage->setPowerConsumingCategory($inquiry_response->power_consuming_category);
          $plnMessage->setDueDate($inquiry_response->due_date);
          $plnMessage->setMeterReadDate($inquiry_response->meter_read_date);
          $plnMessage->setAdminChargesPostPaid($inquiry_response->total_admin_charges);
          
          return $plnMessage;
    }
        
      public function  ParsePaymentResponse($iso_response)
    {
          $array_iso=$iso_response;
          $inquiry_response=new stdClass();
          $inquiry_response->mti            =substr($array_iso,0,4);
          $inquiry_response->bitmap         =substr($array_iso,4,16);
          $inquiry_response->length_of_pan  =substr($array_iso,20,2);
          $inquiry_response->pan            =substr($array_iso,22,5);
          $inquiry_response->iso_currency   =substr($array_iso,27,3);
          $inquiry_response->currency_minor =substr($array_iso,30,1);
          $inquiry_response->value_amount   =substr($array_iso,31,12);
          $inquiry_response->stan           =substr($array_iso,43,12);
          $inquiry_response->date_time      =substr($array_iso,55,14);
          $inquiry_response->date_settlement=substr($array_iso,69,8);
          $inquiry_response->merchant_code  =substr($array_iso,77,4);
          $inquiry_response->length_bank    =substr($array_iso,81,2);
          $inquiry_response->bank_code      =substr($array_iso,83,7);
          $inquiry_response->length_cid     =substr($array_iso,90,2);
          $inquiry_response->partner_cid    =substr($array_iso,92,7);
          $inquiry_response->response_code  =substr($array_iso,99,4);
          $inquiry_response->terminal_id    =substr($array_iso,103,16);
          $inquiry_response->length_privated=substr($array_iso,119,3);
          $inquiry_response->switcher_id    =substr($array_iso,122,7);
          $inquiry_response->subscriber_id  =substr($array_iso,129,12);
          $inquiry_response->bill_status    =substr($array_iso,141,1);
      
          if($inquiry_response->response_code=="0000")
              {
          $inquiry_response->payment_status           =substr($array_iso,142,1);
          $inquiry_response->total_outstandingbill    =substr($array_iso,143,2);
          $inquiry_response->sw_referencenumber       =substr($array_iso,145,32);
          $inquiry_response->subscriber_name          =substr($array_iso,177,25);
          $inquiry_response->service_unit             =substr($array_iso,202,5);
          $inquiry_response->service_unit_phone       =substr($array_iso,217,15);
          $inquiry_response->subscriber_segmentation  =substr($array_iso,232,4);
          $inquiry_response->power_consuming_category =substr($array_iso,236,9);
          $inquiry_response->total_admin_charges      =substr($array_iso,245,9);
          $inquiry_response->bill_period              =substr($array_iso,254,6);
          $inquiry_response->due_date                 =substr($array_iso,262,8);
          $inquiry_response->meter_read_date          =substr($array_iso,270,8);
          $inquiry_response->total_electricity_bill   =substr($array_iso,278,11);
          $inquiry_response->incentive                =substr($array_iso,289,11);
          $inquiry_response->value_added_tax          =substr($array_iso,300,10);
          $inquiry_response->penalty_fee              =substr($array_iso,310,9);
          $inquiry_response->previous_meter_reading1  =substr($array_iso,319,8);
          $inquiry_response->current_meter_reading1   =substr($array_iso,327,8);
          $inquiry_response->previous_meter_reading2  =substr($array_iso,335,8);
          $inquiry_response->current_meter_reading2   =substr($array_iso,343,8);
          $inquiry_response->previous_meter_reading3  =substr($array_iso,351,8);
          $inquiry_response->current_meter_reading3   =substr($array_iso,359,8);
          $inquiry_response->length_infotext          =substr($array_iso,367,3);
          $inquiry_response->infotext                 =substr($array_iso,370,-2);
              }
          return $inquiry_response;
                  
    }
    
      public function  ParseReversalResponse($iso_response)
    {
          $array_iso=$iso_response;
          $payment_response=new stdClass();
          $payment_response->mti            =substr($array_iso,0,4);
          $payment_response->bitmap         =substr($array_iso,4,16);
          $payment_response->length_of_pan  =substr($array_iso,20,2);
          $payment_response->pan            =substr($array_iso,22,5);
          $payment_response->iso_currency   =substr($array_iso,27,3);
          $payment_response->currency_minor =substr($array_iso,30,1);
          $payment_response->value_amount   =substr($array_iso,31,12);
          $payment_response->stan           =substr($array_iso,43,12);
          $payment_response->date_time      =substr($array_iso,55,14);
          $payment_response->date_settlement=substr($array_iso,69,8);
          $payment_response->merchant_code  =substr($array_iso,77,4);
          $payment_response->length_bank    =substr($array_iso,81,2);
          $payment_response->bank_code      =substr($array_iso,83,7);
          $payment_response->length_cid     =substr($array_iso,90,2);
          $payment_response->partner_cid    =substr($array_iso,92,7);
          $payment_response->response_code  =substr($array_iso,99,4);
          $payment_response->terminal_id    =substr($array_iso,103,16);
          $payment_response->length_privated=substr($array_iso,119,3);
          $payment_response->switcher_id    =substr($array_iso,122,7);
          $payment_response->subscriber_id  =substr($array_iso,140,12);
          $payment_response->bill_status    =substr($array_iso,152,1);
          $payment_response->payment_status =substr($array_iso,153,1);
          $payment_response->total_bill     =substr($array_iso,152,2);
      
          if($payment_response->response_code=="0000")
              {
          $payment_response->switcher_reference_number=substr($array_iso,154,32);
          $payment_response->subscriber_name          =substr($array_iso,168,25);
          $payment_response->service_unit             =substr($array_iso,11,5);
          $payment_response->service_unit_phone       =substr($array_iso,1,15);
          $payment_response->subscriber_segmentation  =substr($array_iso,193,4);
          $payment_response->power_consuming_category =substr($array_iso,197,9);
          $payment_response->total_admin_charges      =substr($array_iso,208,10);
          $payment_response->bill_period              =substr($array_iso,218,1);
          $payment_response->due_date                 =substr($array_iso,219,10);
          $payment_response->meter_read_date          =substr($array_iso,220,1);
          $payment_response->total_electricity_bill   =substr($array_iso,221,1);
          $payment_response->incentive                =substr($array_iso,222,10);
          $payment_response->value_added_tax          =substr($array_iso,232,1);
          $payment_response->penalty_fee              =substr($array_iso,233,10);
          $payment_response->previous_meter_reading1  =substr($array_iso,317,8);
          $payment_response->current_meter_reading1   =substr($array_iso,309,8);
          $payment_response->previous_meter_reading2  =substr($array_iso,317,8);
          $payment_response->current_meter_reading2   =substr($array_iso,325,8);
          $payment_response->previous_meter_reading3  =substr($array_iso,333,8);
          $payment_response->current_meter_reading3   =substr($array_iso,341,8);
          $payment_response->length_originalDE        =substr($array_iso,349,2);
          $payment_response->originalMTI              =substr($array_iso,351,4);
          $payment_response->originalSwitcherRefNum   =substr($array_iso,349,12);
          $payment_response->originalDateTime         =substr($array_iso,349,14);
          $payment_response->originalBankCode         =substr($array_iso,349,2);
          
          
          
              }
          return $inquiry_response;
                  
    }
    
    
      public function  ParseDE48ReversalRequest()
    {
          $plnMessage = new PlnPostPaidMessageHelper();
          $plnMessage->setSwitcherID("00000000012");
          $plnMessage->setRegistrationNumber();
          $plnMessage->setRegistrationDate();
          $plnMessage->setTransactionCode();
          $plnMessage->setTransactionName();
          $plnMessage->setExpirationDate();
          $plnMessage->setSubscriberID();
          $plnMessage->setSubscriberName();
          $plnMessage->setPLNReferenceNumber();
          $plnMessage->setSwitcherReferenceNumber();
          $plnMessage->setServiceUnit();
          $plnMessage->setServiceUnitAddress();
          $plnMessage->setServiceUnitPhone();
          $plnMessage->setTotalTransactionAmountMinor();
          $plnMessage->setTotalTransactionAmount();
          $plnMessage->setPlnBillMinorUnit();
          $plnMessage->setPlnBillValue();
          $plnMessage->setMinorUnitAdminCharges();
          $plnMessage->setAdminCharges();
                  
          return $plnMessage;
    }
    
      public function  ParseDE56ReversalRequest($inquiry_response)
    {
        $plnMessage = new PlnPostPaidMessageHelper();
        $plnMessage->setOriginalMTI(2200);
        $plnMessage->setOriginalPCTAN($inquiry_response->switcher_reference_number);
        $plnMessage->setOriginalDateTime($inquiry_response->date_time);
        $plnMessage->setOriginalBankCode($inquiry_response->bank_code);
        
        return $plnMessage;
                  
    }
    
      public function  ParseDE48ReversalResponse()
    {
          $plnMessage = new PlnPostPaidMessageHelper();
          $plnMessage->setLengthPrivateData();
          $plnMessage->setSwitcherID();
          $plnMessage->setRegistrationNumber();
          $plnMessage->setRegistrationDate();
          $plnMessage->setTransactionCode();
          $plnMessage->setTransactionName();
          $plnMessage->setExpirationDate();
          $plnMessage->setSubscriberID();
          $plnMessage->setSubscriberName();
          $plnMessage->setPLNReferenceNumber();
          $plnMessage->setSwitcherReferenceNumber();
          $plnMessage->setServiceUnit();
          $plnMessage->setServiceUnitAddress();
          $plnMessage->setServiceUnitPhone();
          $plnMessage->setTotalTransactionAmountMinor();
          $plnMessage->setTotalTransactionAmount();
          $plnMessage->setPlnBillMinorUnit();
          $plnMessage->setPlnBillValue();
          $plnMessage->setMinorUnitAdminCharges();
          $plnMessage->setAdminCharges();              
    }
    
      public function  ParseDE62ReversalResponse()
    {
        $plnMessage = new PlnPostPaidMessageHelper();
        $plnMessage->setBillComponentType();
        $plnMessage->setBillComponentMinor();
        $plnMessage->setBillComponentValue();
                  
    }
    

}
