<?php

class PlnNontaglisMessageHelper extends SyhmponiMessageHelper
{
    public function getFinancialInquiryMessage($transaction)
    {
        $jak=new JAK8583();
        $parseDE48=  self::ParseDE48InquiryRequest($transaction);
        $inquiry_messageDE48=  self::getNontaglisInquiryMessage($parseDE48);
        $input_stan=28421;
        $jak->addMTI(self::MTI_REQUEST_CODE);
        $jak->addData(self::DE_PRIMARY_ACCOUNT_NUMBER, "99504");
        $jak->addData(self::DE_SYSTEM_TRACE_AUDIT_NUMBER,str_pad($input_stan,12,"0",STR_PAD_LEFT));
        $jak->addData(self::DE_DATE_TIME_LOCAL_TRANSACTION,"20150521144845");
        $jak->addData(self::DE_MERCHANT_CATEGORY_CODE, "6015");
        $jak->addData(self::DE_BANK_CODE, "4510017");
        $jak->addData(self::DE_PARTNER_CENTRAL_ID,6666666);
        $jak->addData(self::DE_TERMINAL_ID,"0000000000000048");
        $jak->addData(self::DE_ADDITIONAL_DATA,$inquiry_messageDE48);
        
        return $jak->getISO();
        
    }
    
     public function getFinancialPaymentMessage($inquiry_response)
    {
        $jak=new JAK8583();
        $parseDE48=  self::ParseDE48PaymentRequest($inquiry_response);
        $parseDE62= self::ParseDE62PaymentRequest($inquiry_response);
        $inquiry_messageDE48=  self::getNontaglisPaymentMessage($parseDE48);
        $inquiry_messageDE62=  self::getNontaglisPaymentMessageDE62($parseDE62);
        $jak->addMTI(self::MTI_PAYMENT_AND_PURCHASE_REQUEST_CODE);
        $jak->addData(self::DE_PRIMARY_ACCOUNT_NUMBER, "99504");
        $jak->addData(self::DE_TRANSACTION_AMOUNT,$inquiry_response->iso_currency.$inquiry_response->currency_minor.$inquiry_response->value_amount);
        $jak->addData(self::DE_DATE_TIME_LOCAL_TRANSACTION,$inquiry_response->date_time);
        $jak->addData(self::DE_SYSTEM_TRACE_AUDIT_NUMBER,$inquiry_response->stan);
        $jak->addData(self::DE_MERCHANT_CATEGORY_CODE,$inquiry_response->merchant_code);
        $jak->addData(self::DE_BANK_CODE,$inquiry_response->bank_code);
        $jak->addData(self::DE_PARTNER_CENTRAL_ID,$inquiry_response->partner_cid);
        $jak->addData(self::DE_TERMINAL_ID,$inquiry_response->terminal_id);
        $jak->addData(self::DE_ADDITIONAL_DATA,$inquiry_messageDE48);
        $jak->addData(self::DE_ADDITIONAL_DATA_2,$inquiry_messageDE62);
        
        return $jak->getISO();
                
    }
    
    public function SubstrakLengthData($result,$parse_payment) 
    {
        $substrak_DE48= substr($parse_payment,107,3);
        $parsing_lengthDE48=  str_replace($substrak_DE48,$result->length_privated,$parse_payment);
        $substrak_DE62= substr($parse_payment,370,3);
        $parsing_lengthDE62=  str_replace($substrak_DE62,$result->length_add_private_data,$parsing_lengthDE48);
        
        return $parsing_lengthDE62;
        
    }
    
     public function getFinancialReversalMessage($substrakLengthData)
    {
        $substrak_reversal=substr($substrakLengthData,0,4);
        $parse_reversal=  str_replace($substrak_reversal,self::MTI_REVERSAL_REQUEST_CODE,$substrakLengthData);
        
        return $parse_reversal;
                
    }
    
    public function getNontaglisInquiryMessage($plnnontaglis)
    {
        $inquiry_stringnontaglis=$plnnontaglis->switcher_ID.
                $plnnontaglis->registration_number.
                $plnnontaglis->transaction_code
                ;
        
        return $inquiry_stringnontaglis;
    }

    public function getNontaglisPaymentMessage($plnnontaglis)
    {
        $inquiry_stringnontaglis=$plnnontaglis->switcher_ID.
                $plnnontaglis->registration_number.
                $plnnontaglis->transaction_code.
                $plnnontaglis->transaction_name.
                $plnnontaglis->registration_date.
                $plnnontaglis->expiration_date.
                $plnnontaglis->subscriber_ID.
                $plnnontaglis->subscriber_name.
                $plnnontaglis->pln_reference_number.
                $plnnontaglis->switcher_ReferenceNumber.
                $plnnontaglis->service_unit.
                $plnnontaglis->service_unit_address.
                $plnnontaglis->service_unit_phone.
                $plnnontaglis->transaction_amount_minor.
                $plnnontaglis->transaction_amount.
                $plnnontaglis->pln_bill_minor_unit.
                $plnnontaglis->pln_bill_value.
                $plnnontaglis->minor_unit_admin_charges.
                $plnnontaglis->admin_charges
                ;
        
        return $inquiry_stringnontaglis;
    }
    
    public function getNontaglisPaymentMessageDE62($plnnontaglis)
    {
        $inquiry_stringnontaglisDE62=$plnnontaglis->bill_component_type.
                $plnnontaglis->bill_component_minor.
                $plnnontaglis->bill_component_value
                ;
        
        
        return $inquiry_stringnontaglisDE62;
    }
    
    public function getNontaglisReversalMessage($plnnontaglis)
    {
       $inquiry_stringnontaglis=$plnnontaglis->switcher_ID.
                $plnnontaglis->registration_number.
                $plnnontaglis->transaction_code.
                $plnnontaglis->transaction_name.
                $plnnontaglis->registration_date.
                $plnnontaglis->expiration_date.
                $plnnontaglis->subscriber_ID.
                $plnnontaglis->subscriber_name.
                $plnnontaglis->pln_reference_number.
                $plnnontaglis->switcher_ReferenceNumber.
                $plnnontaglis->service_unit.
                $plnnontaglis->service_unit_address.
                $plnnontaglis->service_unit_phone.
                $plnnontaglis->total_transaction_amount_minor.
                $plnnontaglis->total_transaction_amount.
                $plnnontaglis->pln_bill_minor_unit.
                $plnnontaglis->pln_bill_value.
                $plnnontaglis->minor_unit_admin_charges.
                $plnnontaglis->admin_charges
                ;
        
        return $inquiry_stringnontaglis;
    }
    
    public function getNontaglisReversalMessageDE62($plnnontaglis)
    {
        $inquiry_stringnontaglisDE62=$plnnontaglis->bill_component_type.
                $plnnontaglis->bill_component_minor.
                $plnnontaglis->bill_component_value
                ;
        
        return $inquiry_stringnontaglisDE62;

    }

    public function ParseDE48InquiryRequest($transaction)
    {
            $plnMessage = new PlnNontaglisMessageHelper();
            $plnMessage->setSwitcherID("0000000");
            $plnMessage->setRegistrationNumber($transaction->nomor_registrasi);
            $plnMessage->setTransactionCode("000");
            
            return $plnMessage;
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
          $inquiry_response->registrationnum=substr($array_iso,121,13);
          $inquiry_response->transactioncode=substr($array_iso,134,3);
          $inquiry_response->transactionname=substr($array_iso,137,25);
          
          if($inquiry_response->response_code=="0000")
          {
              $inquiry_response->registration_date      =substr($array_iso,162,8);
              $inquiry_response->expiration_date        =substr($array_iso,170,8);
              $inquiry_response->subscriber_id          =substr($array_iso,178,12);
              $inquiry_response->subscriber_name        =substr($array_iso,190,25);
              $inquiry_response->pln_refnumber          =substr($array_iso,215,32);
              $inquiry_response->sw_reff                =substr($array_iso,247,32);
              $inquiry_response->service_unit           =substr($array_iso,279,5);
              $inquiry_response->service_unit_address   =substr($array_iso,284,35);
              $inquiry_response->service_unit_phone     =substr($array_iso,319,15);
              $inquiry_response->total_transaction_minor=substr($array_iso,334,1);
              $inquiry_response->total_transactionamount=substr($array_iso,335,17);
              $inquiry_response->pln_bill_minor_unit    =substr($array_iso,352,1);
              $inquiry_response->pln_bill_value         =substr($array_iso,353,10);
              $inquiry_response->admin_charge_minor_unit=substr($array_iso,363,1);
              $inquiry_response->admin_charge           =substr($array_iso,364,10);
              $inquiry_response->length_add_private_data=substr($array_iso,374,3);
              $inquiry_response->bill_component_type    =substr($array_iso,377,2);
              $inquiry_response->bill_component_minor   =substr($array_iso,379,1);
              $inquiry_response->bill_component_value   =substr($array_iso,380,-3);
          }
          
          return $inquiry_response;
          
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
          $inquiry_response->merchant_code  =substr($array_iso,69,4);
          $inquiry_response->length_bank    =substr($array_iso,73,2);
          $inquiry_response->bank_code      =substr($array_iso,75,7);
          $inquiry_response->length_cid     =substr($array_iso,82,2);
          $inquiry_response->partner_cid    =substr($array_iso,84,7);
          $inquiry_response->response_code  =substr($array_iso,91,4);
          $inquiry_response->terminal_id    =substr($array_iso,95,16);
          $inquiry_response->length_privated=substr($array_iso,111,3);
          $inquiry_response->switcher_id    =substr($array_iso,114,7);
          $inquiry_response->registrationnum=substr($array_iso,121,13);
          $inquiry_response->transactioncode=substr($array_iso,134,3);
          $inquiry_response->transactionname=substr($array_iso,137,25);
          
          if($inquiry_response->response_code=="0000")
          {
              $inquiry_response->registration_date      =substr($array_iso,162,8);
              $inquiry_response->expiration_date        =substr($array_iso,170,8);
              $inquiry_response->subscriber_id          =substr($array_iso,178,12);
              $inquiry_response->subscriber_name        =substr($array_iso,190,25);
              $inquiry_response->pln_refnumber          =substr($array_iso,215,32);
              $inquiry_response->sw_reff                =substr($array_iso,247,32);
              $inquiry_response->service_unit           =substr($array_iso,279,5);
              $inquiry_response->service_unit_address   =substr($array_iso,284,35);
              $inquiry_response->service_unit_phone     =substr($array_iso,319,15);
              $inquiry_response->total_transaction_minor=substr($array_iso,334,1);
              $inquiry_response->total_transactionamount=substr($array_iso,335,17);
              $inquiry_response->pln_bill_minor_unit    =substr($array_iso,352,1);
              $inquiry_response->pln_bill_value         =substr($array_iso,353,10);
              $inquiry_response->admin_charge_minor_unit=substr($array_iso,363,1);
              $inquiry_response->admin_charge           =substr($array_iso,364,10);
              $inquiry_response->length_add_private_data=substr($array_iso,374,3);
              $inquiry_response->bill_component_type    =substr($array_iso,377,2);
              $inquiry_response->bill_component_minor   =substr($array_iso,379,1);
              $inquiry_response->bill_component_value   =substr($array_iso,380,17);
              $inquiry_response->length_infotext        =substr($array_iso,397,3);
              $inquiry_response->infotext               =substr($array_iso,397,-1);
              
          }
          
          return $inquiry_response;
          
    }
    
     public function  ParseReversalResponse($iso_response)
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
          $inquiry_response->registrationnum=substr($array_iso,121,13);
          $inquiry_response->transactioncode=substr($array_iso,134,3);
          $inquiry_response->transactionname=substr($array_iso,137,25);
          
          if($inquiry_response->response_code=="0000")
          {
              $inquiry_response->registration_date      =substr($array_iso,162,8);
              $inquiry_response->expiration_date        =substr($array_iso,170,8);
              $inquiry_response->subscriber_id          =substr($array_iso,178,12);
              $inquiry_response->subscriber_name        =substr($array_iso,190,25);
              $inquiry_response->pln_refnumber          =substr($array_iso,215,32);
              $inquiry_response->sw_reff                =substr($array_iso,247,32);
              $inquiry_response->service_unit           =substr($array_iso,279,5);
              $inquiry_response->service_unit_address   =substr($array_iso,284,35);
              $inquiry_response->service_unit_phone     =substr($array_iso,319,15);
              $inquiry_response->total_transaction_minor=substr($array_iso,334,1);
              $inquiry_response->total_transactionamount=substr($array_iso,335,17);
              $inquiry_response->pln_bill_minor_unit    =substr($array_iso,352,1);
              $inquiry_response->pln_bill_value         =substr($array_iso,353,10);
              $inquiry_response->admin_charge_minor_unit=substr($array_iso,363,1);
              $inquiry_response->admin_charge           =substr($array_iso,364,10);
              $inquiry_response->length_add_private_data=substr($array_iso,374,3);
              $inquiry_response->bill_component_type    =substr($array_iso,377,2);
              $inquiry_response->bill_component_minor   =substr($array_iso,379,1);
              $inquiry_response->bill_component_value   =substr($array_iso,380,17);
              $inquiry_response->length_infotext        =substr($array_iso,397,3);
              $inquiry_response->infotext               =substr($array_iso,397,-1);
              
          }
          
          return $inquiry_response;
          
    }
    
    
    
    public function  ParseDE48InquiryResponse()
    {
          $plnMessage = new PlnNontaglisMessageHelper();
          $plnMessage->setSwitcherID();
          $plnMessage->setRegistrationNumber();
          $plnMessage->setTransactionCode();
          $plnMessage->setTransactionName();
          $plnMessage->setRegistrationDate();
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
          $plnMessage->setPowerConsumingCategory();
          $plnMessage->setMinorUnitAdminCharger();
          $plnMessage->setAdminCharges();
          
          return $plnMessage;
        
    }
    
    public function ParseDE62InquiryResponse()
    {
        $plnMessage = new PlnNontaglisMessageHelper();
        $plnMessage->setBillComponentType();
        $plnMessage->setBillComponentMinor();
        $plnMessage->setBillComponentValue();
        
        return $plnMessage;
        
    }
    
     public function  ParseDE48PaymentRequest($inquiry_response)
    {
          $plnMessage = new PlnNontaglisMessageHelper();
          $plnMessage->setSwitcherID($inquiry_response->switcher_id);
          $plnMessage->setRegistrationNumber($inquiry_response->registrationnum);
          $plnMessage->setRegistrationDate($inquiry_response->registration_date);
          $plnMessage->setTransactionCode($inquiry_response->transactioncode);
          $plnMessage->setTransactionName($inquiry_response->transactionname);
          $plnMessage->setExpirationDate($inquiry_response->expiration_date);
          $plnMessage->setSubscriberID($inquiry_response->subscriber_id);
          $plnMessage->setPLNReferenceNumber($inquiry_response->pln_refnumber);
          $plnMessage->setSwitcherReferenceNumber($inquiry_response->sw_reff);
          $plnMessage->setServiceUnit($inquiry_response->service_unit);
          $plnMessage->setServiceUnitAddress($inquiry_response->service_unit_address);
          $plnMessage->setServicePhoneUnit($inquiry_response->service_unit_phone);
          $plnMessage->setTransactionAmountMinor($inquiry_response->total_transaction_minor);
          $plnMessage->setTransactionAmount($inquiry_response->total_transactionamount);
          $plnMessage->setPlnBillMinorUnit($inquiry_response->pln_bill_minor_unit);
          $plnMessage->setSubscriberName($inquiry_response->subscriber_name);
          $plnMessage->setPlnBillMinorUnit($inquiry_response->pln_bill_minor_unit);
          $plnMessage->setPlnBillValue($inquiry_response->pln_bill_value);
          $plnMessage->setMinorUnitAdminCharges($inquiry_response->admin_charge_minor_unit);
          $plnMessage->setAdminCharges($inquiry_response->admin_charge);
          
          return $plnMessage;
    }
    
    public function  ParseDE62PaymentRequest($inquiry_response)
    {
        $plnMessage = new PlnNontaglisMessageHelper();
        $plnMessage->setBillComponentType($inquiry_response->bill_component_type);
        $plnMessage->setBillComponentMinor($inquiry_response->bill_component_minor);
        $plnMessage->setBillComponentValue($inquiry_response->bill_component_value);
        
        return $plnMessage;
    }
    
      public function  ParseDE48PaymentResponse()
    {
          $plnMessage = new PlnNontaglisMessageHelper();
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
          $plnMessage->setTotalTransaction();
          $plnMessage->setPlnBillMinorUnit();
          $plnMessage->setPlnBillMinorUnit();
          $plnMessage->setPlnBillValue();
          $plnMessage->setMinorUnitAdminCharger();
          $plnMessage->setAdminCharges();
          
          return $plnMessage;
                  
    }
    
      public function  ParseDE62PaymentResponse()
    {
        $plnMessage = new PlnNontaglisMessageHelper();
        $plnMessage->setBillComponentType();
        $plnMessage->setBillComponentMinor();
        $plnMessage->setBillComponentValue();
        
        return $plnMessage;
    }
    
      public function  ParseDE48ReversalRequest()
    {
          $plnMessage = new PlnNontaglisMessageHelper();
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
          $plnMessage->setPlnBillMinorUnit();
          $plnMessage->setPlnBillValue();
          $plnMessage->setMinorUnitAdminCharger();
          $plnMessage->setAdminCharges();
         
          return $plnMessage;
    }
    
      public function  ParseDE62ReversalRequest()
    {
        $plnMessage = new PlnNontaglisMessageHelper();
        $plnMessage->setBillComponentType();
        $plnMessage->setBillComponentMinor();
        $plnMessage->setBillComponentValue();
        
        return $plnMessage;
                  
    }
    
      public function  ParseDE48ReversalResponse()
    {
          $plnMessage = new PlnNontaglisMessageHelper();
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
          $plnMessage->setPlnBillMinorUnit();
          $plnMessage->setPlnBillValue();
          $plnMessage->setMinorUnitAdminCharger();
          $plnMessage->setAdminCharges();
          
          return $plnMessage;
    }
    
      public function  ParseDE62ReversalResponse()
    {
        $plnMessage = new PlnNontaglisMessageHelper();
        $plnMessage->setBillComponentType();
        $plnMessage->setBillComponentMinor();
        $plnMessage->setBillComponentValue();
        
        return $plnMessage;
                  
    }
    

}
