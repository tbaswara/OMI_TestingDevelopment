<?php

class PlnPrepaidMessageHelper extends SyhmponiMessageHelper
{
    

    public function getFinancialInquiryMessage($transaction)
    {
        $jak=new JAK8583();
        $parseDE48=  self::ParseDE48InquiryRequest($transaction);
        $inquiry_stringDE48= self::getPrepaidInquiryMessage($parseDE48);
        $input_stan=27453;
        $jak->addMTI(self::MTI_REQUEST_CODE);
        $jak->addData(self::DE_PRIMARY_ACCOUNT_NUMBER,99502);
        $jak->addData(self::DE_SYSTEM_TRACE_AUDIT_NUMBER,str_pad($input_stan,12,"0",STR_PAD_LEFT));
        $jak->addData(self::DE_DATE_TIME_LOCAL_TRANSACTION,20150518110419);
        $jak->addData(self::DE_MERCHANT_CATEGORY_CODE,6021);
        $jak->addData(self::DE_BANK_CODE, "4510017"); // Bank Code yang digunakan 4510017
        $jak->addData(self::DE_PARTNER_CENTRAL_ID,8888888);
        $jak->addData(self::DE_TERMINAL_ID,"0000000000000048");
        $jak->addData(self::DE_ADDITIONAL_DATA,$inquiry_stringDE48);
        
        return $jak->getISO();
        
    }
    
    public function GetFinancialPurchaseMessage($inquiry_response,$transaction)
    {
        $jak=new JAK8583();
        $parseDE48=  self::ParseDE48PurchaseRequest($inquiry_response,$transaction);
        $parseDE62=  self::ParseDE62PurchaseRequest($inquiry_response);
        $inquiry_stringDE48=  self::getPrepaidPurchaseMessage($parseDE48);
        $inquiry_stringDE62=  self::getPrepaidPurchaseMessageDE62($parseDE62);
        $input_stan=$inquiry_response->stan;
        $currency_code=360;
        $jumlah_uang=30000;
        $total_uang=  str_pad($jumlah_uang,13,"0",STR_PAD_LEFT);
        $jak->addMTI(self::MTI_PAYMENT_AND_PURCHASE_REQUEST_CODE);
        $jak->addData(self::DE_PRIMARY_ACCOUNT_NUMBER,99502);
        $jak->addData(self::DE_TRANSACTION_AMOUNT,($currency_code.$total_uang));
        $jak->addData(self::DE_SYSTEM_TRACE_AUDIT_NUMBER,str_pad($input_stan,12,"0",STR_PAD_LEFT));
        $jak->addData(self::DE_DATE_TIME_LOCAL_TRANSACTION,$inquiry_response->date_time);
        $jak->addData(self::DE_MERCHANT_CATEGORY_CODE,$inquiry_response->merchant);
        $jak->addData(self::DE_BANK_CODE,$inquiry_response->bankcode);
        $jak->addData(self::DE_PARTNER_CENTRAL_ID,$inquiry_response->cid);
        $jak->addData(self::DE_TERMINAL_ID,$inquiry_response->terminal_id);
        $jak->addData(self::DE_ADDITIONAL_DATA,$inquiry_stringDE48);
        $jak->addData(self::DE_ADDITIONAL_DATA_2,$inquiry_stringDE62);
        
        return $jak->getISO();
    }
    
      public function GetFinancialPurchaseAdviceMessage($inquiry_response,$transaction)
    {
        $jak=new JAK8583();
        $parseDE48=  self::ParseDE48PurchaseRequest($inquiry_response,$transaction);
        $parseDE62=  self::ParseDE62PurchaseRequest($inquiry_response);
        $inquiry_stringDE48=  self::getPrepaidPurchaseMessage($parseDE48);
        $inquiry_stringDE62=  self::getPrepaidPurchaseMessageDE62($parseDE62);
        $input_stan=$inquiry_response->stan;
        $currency_code=360;
        $jumlah_uang=30000;
        $total_uang=  str_pad($jumlah_uang,13,"0",STR_PAD_LEFT);
        $jak->addMTI(self::MTI_ADVICE_REQUEST_CODE);
        $jak->addData(self::DE_PRIMARY_ACCOUNT_NUMBER,99502);
        $jak->addData(self::DE_TRANSACTION_AMOUNT,($currency_code.$total_uang));
        $jak->addData(self::DE_SYSTEM_TRACE_AUDIT_NUMBER,str_pad($input_stan,12,"0",STR_PAD_LEFT));
        $jak->addData(self::DE_DATE_TIME_LOCAL_TRANSACTION,$inquiry_response->date_time);
        $jak->addData(self::DE_MERCHANT_CATEGORY_CODE,$inquiry_response->merchant);
        $jak->addData(self::DE_BANK_CODE,$inquiry_response->bankcode);
        $jak->addData(self::DE_PARTNER_CENTRAL_ID,$inquiry_response->cid);
        $jak->addData(self::DE_TERMINAL_ID,$inquiry_response->terminal_id);
        $jak->addData(self::DE_ADDITIONAL_DATA,$inquiry_stringDE48);
        $jak->addData(self::DE_ADDITIONAL_DATA_2,$inquiry_stringDE62);
        
        return $jak->getISO();
    }
    
    public function getPrepaidInquiryMessage($plnprepaid)
    {
        $InquiryStringPrepaid=$plnprepaid->switcher_ID.
                $plnprepaid->material_number.
                $plnprepaid->subscriber_ID.
                $plnprepaid->flag
                ;
        
                return $InquiryStringPrepaid;
        
    }
    
    public function getPrepaidPurchaseMessage($plnprepaid)
    {
        $InquiryStringPrepaid=$plnprepaid->switcher_ID.
                $plnprepaid->material_number.
                $plnprepaid->subscriber_ID.
                $plnprepaid->flag.
                $plnprepaid->pln_reference_number.
                $plnprepaid->switcher_ReferenceNumber.
                $plnprepaid->subscriber_name.
                $plnprepaid->subscriber_segmentation.
                $plnprepaid->power_consuming_category.
                $plnprepaid->minor_unit_admin_charges.
                $plnprepaid->admin_charges.
                $plnprepaid->buying_option
                ;
        
                return $InquiryStringPrepaid;
    }
    
    public function getPrepaidPurchaseAdviceMessage($plnprepaid)
    {
        $InquiryStringPrepaid=$plnprepaid->switcher_ID.
                $plnprepaid->material_number.
                $plnprepaid->subscriber_ID.
                $plnprepaid->flag.
                $plnprepaid->pln_reference_number.
                $plnprepaid->switcher_ReferenceNumber.
                $plnprepaid->subscriber_name.
                $plnprepaid->subscriber_segmentation.
                $plnprepaid->power_consuming_category.
                $plnprepaid->minor_unit_admin_charges.
                $plnprepaid->admin_charges.
                $plnprepaid->buying_option
                ;
        
                return $InquiryStringPrepaid;
    }
    
    public function getPrepaidPurchaseMessageDE62($plnprepaid)
    {
            $InquiryStringDE62=$plnprepaid->distribution_code.
                    $plnprepaid->service_unit.
                    $plnprepaid->service_unit_phone.
                    $plnprepaid->max_kwh_unit.
                    $plnprepaid->total_repeat.
                    $plnprepaid->power_purchase
                    ;
            
            return $InquiryStringDE62;
        
    }
    
     public function getPrepaidPurchaseAdviceMessageDE62($plnprepaid)
    {
            $InquiryStringDE62=$plnprepaid->distribution_code.
                    $plnprepaid->service_unit.
                    $plnprepaid->service_unit_phone.
                    $plnprepaid->max_kwh_unit.
                    $plnprepaid->total_repeat.
                    $plnprepaid->pp_repeat
                    ;
        
    }
    
    public function ParseDE48InquiryRequest($transaction)
    {
        $plnMessage=new PlnPrepaidMessageHelper();
        $plnMessage->setSwitcherID("JTL53L3");
        $plnMessage->setMaterialNumber($transaction->nometer);
        $plnMessage->setSubscriberID($transaction->idpel); //ID Pelanggan
        $plnMessage->setFlag($transaction->flag);
        
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
          $inquiry_response->stan           =substr($array_iso,27,12);
          $inquiry_response->date_time      =substr($array_iso,39,14);
          $inquiry_response->merchant       =substr($array_iso,53,4);
          $inquiry_response->lbank          =substr($array_iso,57,2);
          $inquiry_response->bankcode       =substr($array_iso,59,7);
          $inquiry_response->lcid           =substr($array_iso,66,2);
          $inquiry_response->cid            =substr($array_iso,68,7);
          $inquiry_response->rc             =substr($array_iso,75,4);
          $inquiry_response->terminal_id    =substr($array_iso,79,16);
          $inquiry_response->length_privated=substr($array_iso,95,3);
          $inquiry_response->switcher_id    =substr($array_iso,98,7);
          $inquiry_response->material_number=substr($array_iso,105,11);
          $inquiry_response->subscriber_id  =substr($array_iso,116,12);
          $inquiry_response->flag           =substr($array_iso,128,1);
          
          if($inquiry_response->rc=="0000")
          {
              $inquiry_response->pln_reference_number       =substr($array_iso,129,32);
              $inquiry_response->switcher_reference_number  =substr($array_iso,161,32);
              $inquiry_response->subscriber_name            =substr($array_iso,193,25);
              $inquiry_response->subscriber_segmentation    =substr($array_iso,218,4);
              $inquiry_response->power_consuming_category   =substr($array_iso,222,9);
              $inquiry_response->minor_unit_admin_charges   =substr($array_iso,231,1);
              $inquiry_response->admin_charges              =substr($array_iso,232,10);
              $inquiry_response->length_add_private_2       =substr($array_iso,242,3);
              $inquiry_response->distribution_code          =substr($array_iso,245,2);
              $inquiry_response->service_unit               =substr($array_iso,247,5);
              $inquiry_response->service_phone_unit         =substr($array_iso,252,15);
              $inquiry_response->max_kwh_unit               =substr($array_iso,267,5);
              $inquiry_response->total_repeat               =substr($array_iso,272,1);
              $inquiry_response->power_purchase             =substr($array_iso,273,-3);
              
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
          $inquiry_response->iso_code       =substr($array_iso,27,3);
          $inquiry_response->currency_minor =substr($array_iso,30,1);
          $inquiry_response->value_amount   =substr($array_iso,31,12);
          $inquiry_response->stan           =substr($array_iso,43,12);
          $inquiry_response->time           =substr($array_iso,55,14);
          $inquiry_response->settlement     =substr($array_iso,69,8);
          $inquiry_response->merchant       =substr($array_iso,77,4);
          $inquiry_response->lbank          =substr($array_iso,81,2);
          $inquiry_response->bankcode       =substr($array_iso,83,7);
          $inquiry_response->lcid           =substr($array_iso,90,2);
          $inquiry_response->cid            =substr($array_iso,92,7);
          $inquiry_response->rc             =substr($array_iso,99,4);
          $inquiry_response->terminal_id    =substr($array_iso,103,16);
          $inquiry_response->length_privated=substr($array_iso,119,3);
          $inquiry_response->switcher_id    =substr($array_iso,122,7);
          $inquiry_response->material_number=substr($array_iso,129,11);
          $inquiry_response->subscriber_id  =substr($array_iso,140,12);
          $inquiry_response->flag           =substr($array_iso,152,1);
          
          if($inquiry_response->rc=="0000")
          {
              $inquiry_response->pln_reference_num       =substr($array_iso,153,32);
              $inquiry_response->switcher_reference_num  =substr($array_iso,185,32);
              $inquiry_response->vending_receive_num     =substr($array_iso,217,8);
              $inquiry_response->subscriber_name         =substr($array_iso,225,25);
              $inquiry_response->subscriber_segmentation =substr($array_iso,250,4);
              $inquiry_response->power_consuming_category=substr($array_iso,254,9);
              $inquiry_response->buying_option           =substr($array_iso,263,1);
              $inquiry_response->minor_unit_admin_charges=substr($array_iso,264,1);
              $inquiry_response->admin_charges           =substr($array_iso,265,10);
              $inquiry_response->minor_stamp_duty        =substr($array_iso,275,1);
              $inquiry_response->stamp_duty              =substr($array_iso,276,10);
              $inquiry_response->minor_value_tax         =substr($array_iso,286,1);
              $inquiry_response->value_tax               =substr($array_iso,287,10);
              $inquiry_response->minor_unit_tax          =substr($array_iso,297,1);
              $inquiry_response->public_tax              =substr($array_iso,298,10);
              $inquiry_response->minor_unit_installment  =substr($array_iso,308,1);
              $inquiry_response->customer_installent     =substr($array_iso,309,10);
              $inquiry_response->minorunit_power_purchase=substr($array_iso,319,1);
              $inquiry_response->power_purchase          =substr($array_iso,320,12);
              $inquiry_response->minor_purchase_kwh_unit =substr($array_iso,332,1);
              $inquiry_response->purchase_kwh_unit       =substr($array_iso,333,10);
              $inquiry_response->token_number            =substr($array_iso,343,20);
              $inquiry_response->length_privatedata2     =substr($array_iso,363,3);
              $inquiry_response->distribution_code       =substr($array_iso,366,2);
              $inquiry_response->service_unit            =substr($array_iso,368,5);
              $inquiry_response->service_phone_unit      =substr($array_iso,373,15);
              $inquiry_response->max_kwh_unit            =substr($array_iso,388,5);
              $inquiry_response->total_repeat            =substr($array_iso,393,1);
              $inquiry_response->power_purchase_unsold   =substr($array_iso,394,11);
              $inquiry_response->length_infotext         =substr($array_iso,405,3);
              $inquiry_response->infotext                =substr($array_iso,408,-3);
              
          }
          
        
          return $inquiry_response;
    }
    
    public function  ParsePaymentRequest($iso_response)
    {
          $array_iso=$iso_response;
          $inquiry_response=new stdClass();
          $inquiry_response->mti            =substr($array_iso,0,4);
          $inquiry_response->bitmap         =substr($array_iso,4,16);
          $inquiry_response->length_of_pan  =substr($array_iso,20,2);
          $inquiry_response->pan            =substr($array_iso,22,5);
          $inquiry_response->currency_code  =substr($array_iso,27,3);
          $inquiry_response->minor_unit     =substr($array_iso,30,1);
          $inquiry_response->transacion_jml =substr($array_iso,31,12);
          $inquiry_response->stan           =substr($array_iso,43,12);
          $inquiry_response->time           =substr($array_iso,55,14);
          $inquiry_response->merchant       =substr($array_iso,69,4);
          $inquiry_response->lbank          =substr($array_iso,73,2);
          $inquiry_response->bankcode       =substr($array_iso,75,7);
          $inquiry_response->lcid           =substr($array_iso,82,2);
          $inquiry_response->cid            =substr($array_iso,84,7);
          $inquiry_response->terminal_id    =substr($array_iso,91,16);
          $inquiry_response->length_privated=substr($array_iso,107,3);
          $inquiry_response->switcher_id    =substr($array_iso,110,7);
          $inquiry_response->material_number=substr($array_iso,117,11);
          $inquiry_response->subscriber_id  =substr($array_iso,128,12);
          $inquiry_response->flag           =substr($array_iso,140,1);
          $inquiry_response->pln_rfr_number =substr($array_iso,141,32);
          $inquiry_response->switch_rfr_nmbr=substr($array_iso,173,32);
          $inquiry_response->subscriber_name=substr($array_iso,205,25);
        $inquiry_response->subscriber_segmentation =substr($array_iso,230,4);
              $inquiry_response->power_consuming_category=substr($array_iso,234,9);
              $inquiry_response->minor_unit_admin_charges=substr($array_iso,243,1);
              $inquiry_response->admin_charges           =substr($array_iso,244,10);
              $inquiry_response->buying_option           =substr($array_iso,254,1);
              $inquiry_response->length_add_private_2    =substr($array_iso,255,3);
              $inquiry_response->distribution_code       =substr($array_iso,258,2);
              $inquiry_response->service_unit            =substr($array_iso,260,5);
              $inquiry_response->service_phone_unit      =substr($array_iso,265,15);
              $inquiry_response->max_kwh_unit            =substr($array_iso,280,5);
              $inquiry_response->total_repeat            =substr($array_iso,285,1);
              $inquiry_response->power_purchase          =substr($array_iso,286,11);  
          
          
    
          return $inquiry_response;
    }
    
    public function  ParsePurchaseAdviceResponse($iso_response)
    {
          $array_iso=$iso_response;
          $purchaseAdvice_response=new stdClass();
          $purchaseAdvice_response->mti            =substr($array_iso,0,4);
          $purchaseAdvice_response->bitmap         =substr($array_iso,4,16);
          $purchaseAdvice_response->length_of_pan  =substr($array_iso,20,2);
          $purchaseAdvice_response->pan            =substr($array_iso,22,5);
          $purchaseAdvice_response->iso_code       =substr($array_iso,27,3);
          $purchaseAdvice_response->currency_minor =substr($array_iso,30,1);
          $purchaseAdvice_response->value_amount   =substr($array_iso,31,12);
          $purchaseAdvice_response->stan           =substr($array_iso,43,12);
          $purchaseAdvice_response->time           =substr($array_iso,55,14);
          $purchaseAdvice_response->merchant       =substr($array_iso,69,4);
          $purchaseAdvice_response->lbank          =substr($array_iso,73,2);
          $purchaseAdvice_response->bankcode       =substr($array_iso,75,7);
          $purchaseAdvice_response->lcid           =substr($array_iso,82,2);
          $purchaseAdvice_response->cid            =substr($array_iso,84,7);
          $purchaseAdvice_response->rc             =substr($array_iso,91,4);
          $purchaseAdvice_response->terminal_id    =substr($array_iso,95,16);
          $purchaseAdvice_response->length_privated=substr($array_iso,111,3);
          $purchaseAdvice_response->switcher_id    =substr($array_iso,114,7);
          $purchaseAdvice_response->material_number=substr($array_iso,121,11);
          $purchaseAdvice_response->subscriber_id  =substr($array_iso,132,12);
          $purchaseAdvice_response->flag           =substr($array_iso,144,1);
          
          if($purchaseAdvice_response->rc=="0000")
          {
              $purchaseAdvice_response->pln_reference_num        =substr($array_iso,145,32);
              $purchaseAdvice_response->switcher_reference_num   =substr($array_iso,177,32);
              $purchaseAdvice_response->vending_receive_num      =substr($array_iso,209,8);
              $purchaseAdvice_response->subscriber_name          =substr($array_iso,217,25);
              $purchaseAdvice_response->subscriber_segmentation  =substr($array_iso,242,4);
              $purchaseAdvice_response->power_consuming_category =substr($array_iso,246,9);
              $purchaseAdvice_response->buying_option            =substr($array_iso,255,1);
              $purchaseAdvice_response->minor_unit_admin_charges =substr($array_iso,256,1);
              $purchaseAdvice_response->admin_charges            =substr($array_iso,257,10);
              $purchaseAdvice_response->minor_stamp_duty         =substr($array_iso,267,1);
              $purchaseAdvice_response->stamp_duty               =substr($array_iso,268,10);
              $purchaseAdvice_response->minor_unit_value_tax     =substr($array_iso,278,1);
              $purchaseAdvice_response->value_added_tax          =substr($array_iso,279,10);
              $purchaseAdvice_response->minor_unit_public_tax    =substr($array_iso,289,1);
              $purchaseAdvice_response->public_tax               =substr($array_iso,290,10);
              $purchaseAdvice_response->minor_unit_installment   =substr($array_iso,300,1);
              $purchaseAdvice_response->customer_installment     =substr($array_iso,301,10);
              $purchaseAdvice_response->minor_unit_power_purchase=substr($array_iso,311,1);
              $purchaseAdvice_response->power_purchase           =substr($array_iso,312,12);
              $purchaseAdvice_response->minor_purchase_kwh_unit  =substr($array_iso,324,1);
              $purchaseAdvice_response->purchase_kwh_unit        =substr($array_iso,325,10);
              $purchaseAdvice_response->token_number             =substr($array_iso,335,20);
              $purchaseAdvice_response->length_private_data2     =substr($array_iso,355,3);
              $purchaseAdvice_response->distribution_code        =substr($array_iso,358,2);
              $purchaseAdvice_response->service_unit             =substr($array_iso,360,5);
              $purchaseAdvice_response->service_phone_unit       =substr($array_iso,365,15);
              $purchaseAdvice_response->max_kwh_unit             =substr($array_iso,380,5);
              $purchaseAdvice_response->total_repeat             =substr($array_iso,385,1);
              $purchaseAdvice_response->power_purchase_unsold    =substr($array_iso,386,11);
              $purchaseAdvice_response->length_infotext          =substr($array_iso,397,3);
              $purchaseAdvice_response->info_text                =substr($array_iso,400,-1);
              
              
              
              
          }
          
        
          return $purchaseAdvice_response;
    }
    
    public function ParseDE62InquiryResponse()
    {
        $plnMessage = new PlnPrepaidMessageHelper();
        $plnMessage->setDistributionCode();
        $plnMessage->setServiceUnit();
        $plnMessage->setServicePhoneUnit();
        $plnMessage->setMaxKwhUnit();
        $plnMessage->setTotalRepeat();
        $plnMessage->setPowerPurchase();
    
        return $plnMessage;
    }
    
     public function  ParseDE48PurchaseRequest($hasil_inquiry,$transaction)
    {
          $plnMessage = new PlnPrepaidMessageHelper();
          $plnMessage->setSwitcherID($hasil_inquiry->switcher_id);
          $plnMessage->setMaterialNumber($hasil_inquiry->material_number);
          $plnMessage->setSubscriberID($hasil_inquiry->subscriber_id);
          $plnMessage->setFlag($transaction->flag);
          $plnMessage->setPLNReferenceNumber($hasil_inquiry->pln_reference_number);
          $plnMessage->setSwitcherReferenceNumber($hasil_inquiry->switcher_reference_number);
          $plnMessage->setSubscriberName($hasil_inquiry->subscriber_name);
          $plnMessage->setSubscriberSegmentation($hasil_inquiry->subscriber_segmentation);
          $plnMessage->setPowerConsumingCategory($hasil_inquiry->power_consuming_category);
          $plnMessage->setMinorUnitAdminCharges($hasil_inquiry->minor_unit_admin_charges);
          $plnMessage->setAdminCharges($hasil_inquiry->admin_charges);
          $plnMessage->setBuyingOption($transaction->buying_option);
        
          return $plnMessage;
    }
    
    
    
    public function  ParseDE62PurchaseRequest($hasil_inquiry)
    {
        $plnMessage = new PlnPrepaidMessageHelper();
        $plnMessage->setDistributionCode($hasil_inquiry->distribution_code);
        $plnMessage->setServiceUnit($hasil_inquiry->service_unit);
        $plnMessage->setServicePhoneUnit($hasil_inquiry->service_phone_unit);
        $plnMessage->setMaxKwhUnit($hasil_inquiry->max_kwh_unit);
        $plnMessage->setTotalRepeat($hasil_inquiry->total_repeat);
        $plnMessage->setPowerPurchase($hasil_inquiry->power_purchase);
        
        return $plnMessage;
    }
    
      public function  ParseDE48PurchaseResponse()
    {
          $plnMessage = new PLNMessageHelper();
          $plnMessage->setSwitcherID("00000000012");
          $plnMessage->setMaterialNumber("00000000011");
          $plnMessage->setSubscriberID("00000000000289201");
          $plnMessage->setFlag("1");
          $plnMessage->setPLNReferenceNumber("0123456789012345668990");
          $plnMessage->setSwitcherReferenceNumber("00000000000012123131223123");
          $plnMessage->setVendingReceiveNumber("0987592");
          $plnMessage->setSubscriberName("Valentino Rossi");
          $plnMessage->setSubscriberSegmentation("");
          $plnMessage->setPowerConsumingCategory();
          $plnMessage->setMinorUnitAdminCharges();
          $plnMessage->setAdminCharges("100000");
          $plnMessage->setMinorStampDuty("");
          $plnMessage->setStampDuty();
          $plnMessage->setMinorUnitValueAddTax();
          $plnMessage->setValueAddTax();
          $plnMessage->setMinorUnitPublicLightingTax();
          $plnMessage->setPublicLightingTax();
          $plnMessage->setMinorUnitCustomerPayableInstallment();
          $plnMessage->setCustomerPayableInstallment();
          $plnMessage->setMinorUnitPowerPurchase();
          $plnMessage->setPowerPurchase();
          $plnMessage->setPurchaseKwhUnit();
          $plnMessage->setTokenNumber();
                  
          return $plnMessage;
    }
    
      public function  ParseDE62PurchaseResponse()
    {
        $plnMessage = new PLNMessageHelper();
        $plnMessage->setDistributionCode();
        $plnMessage->setServiceUnit();
        $plnMessage->setServicePhoneUnit();
        $plnMessage->setMaxKwhUnit();
        $plnMessage->setTotalRepeat();
        $plnMessage->setPowerPurchase();
                  
        return $plnMessage;
    }
    
      public function  ParseDE48PurchaseAdviceRequest()
    {
          $plnMessage = new PlnPrepaidMessageHelper();
          $plnMessage->setSwitcherID("00000000012");
          $plnMessage->setMaterialNumber("00000000011");
          $plnMessage->setSubscriberID("00000000000289201");
          $plnMessage->setFlag("1");
          $plnMessage->setPLNReferenceNumber("0123456789012345668990");
          $plnMessage->setSwitcherReferenceNumber("00000000000012123131223123");
          $plnMessage->setVendingReceiveNumber("0987592");
          $plnMessage->setSubscriberName("");
          $plnMessage->setSubscriberSegmentation("01233");
          $plnMessage->setPowerConsumingCategory("123456789");
          $plnMessage->setMinorUnitAdminCharges("1");
          $plnMessage->setAdminCharges("10000");
          $plnMessage->setBuyingOption("1");
          
          return $plnMessage;
                  
    }
    
      public function  ParseDE62PurchaseAdviceRequest()
    {
        $plnMessage = new PlnPrepaidMessageHelper();
        $plnMessage->setDistributionCode("01");
        $plnMessage->setServiceUnit("12345");
        $plnMessage->setServicePhoneUnit("0000012356");
        $plnMessage->setMaxKwhUnit("67890");
        $plnMessage->setTotalRepeat("67890");
        $plnMessage->setPowerPurchase("520000");
        
        return $plnMessage;
    }
    
      public function  ParseDE48PurchaseAdviceResponse()
    {
          $plnMessage = new PLNMessageHelper();
          $plnMessage->setLengthPrivateData();
          $plnMessage->setSwitcherID();
          $plnMessage->setMaterialNumber();
          $plnMessage->setSubscriberID();
          $plnMessage->setFlag();
          $plnMessage->setPLNReferenceNumber();
          $plnMessage->setSwitcherReferenceNumber();
          $plnMessage->setSubscriberName();
          $plnMessage->setSubscriberSegmentation();
          $plnMessage->setPowerConsumingCategory();
          $plnMessage->setBuyingOption();
          $plnMessage->setMinorUnitAdminCharges();
          $plnMessage->setAdminCharges();
          $plnMessage->setMinorStampDuty();
          $plnMessage->setStampDuty();
          $plnMessage->setBuyingOption();
          $plnMessage->setMinorUnitValueAddTax();
          $plnMessage->setValueAddTax();
          $plnMessage->setMinorUnitPublicLightingTax();
          $plnMessage->setPublicLightingTax();
          $plnMessage->setMinorUnitCustomerPayableInstallment();
          $plnMessage->setCustomerPayableInstallment();
          $plnMessage->setMinorUnitPowerPurchase();
          $plnMessage->setPowerPurchase();
          $plnMessage->setPurchaseKwhUnit();
          $plnMessage->setTokenNumber();
           
          return $plnMessage;
    }
    
    
    
    
    
    
    
    
    
}
