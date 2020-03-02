<?php

require_once 'JAK8583.class.php';

class SyhmponiMessageHelper
{
    protected $distribution_code;
    protected $original_MTI;
    protected $original_PCTAN;
    protected $original_BankCode;
    protected $original_DateTime;
    protected $registration_number;
    protected $registration_date;
    protected $transaction_amount;
    protected $transaction_code;
    protected $transaction_name;
    protected $expiration_date;
    protected $switcher_ID;
    protected $switcher_ReferenceNumber;
    protected $subscriber_ID;
    protected $subscriber_name;
    protected $subscriber_segmentation;
    protected $service_unit;
    protected $service_unit_address;
    protected $service_unit_phone;
    protected $stan;
    protected $due_date;
    protected $flag;
    protected $lengthDE48;
    protected $max_kwh_unit;
    protected $total_repeat;
    protected $meter_read_date;
    protected $material_number;
    protected $buying_option;
    protected $bill_status;
    protected $bill_period;
    protected $bill_component_type;
    protected $bill_component_minor;
    protected $bill_component_value;
    protected $total_outstandingBill;
    protected $total_electricityBill;
    protected $incenctive;
    protected $value_addedTax;
    protected $vending_receive_number;
    protected $penalty_fee;
    protected $current_meter_reading1;
    protected $current_meter_reading2;
    protected $current_meter_reading3;
    protected $previous_meter_reading1;
    protected $previous_meter_reading2;
    protected $previous_meter_reading3;
    protected $payment_status;
    protected $power_consuming_category;
    protected $power_purchase;
    protected $pp_repeat;
    protected $minor_unit_admin_charges;
    protected $admin_charges;
    protected $pln_bill_value;
    protected $pln_bill_minor_unit;
    protected $pln_reference_number;
    protected $total_transaction_amount;
    protected $total_transaction_amount_minor;
    

    protected $iso;
    const TYPE_INQUIRY_RESPONSE                  = 1;
    const TYPE_PAYMENT_RESPONSE                  = 2;
    const TYPE_PURCHASE_RESPONSE                 = 3;
    const MTI_REQUEST_CODE                       = '2100';
    const MTI_RESPONSE_CODE                      = '2110';
    const MTI_PAYMENT_AND_PURCHASE_REQUEST_CODE  = '2200';
    const MTI_PAYMENT_RESPONSE_CODE              = '2210';
    const MTI_ADVICE_REQUEST_CODE                = '2220';
    const MTI_ADVICE_RESPONSE_CODE               = '2230';
    const MTI_REVERSAL_REQUEST_CODE              = '2400';
    const MTI_REVERSAL_RESPONSE_CODE             = '2401';

    const DE_PRIMARY_ACCOUNT_NUMBER = 2;
    const DE_CURRENCY_CODE      =4;
    const DE_CURRENCY_MINOR_UNIT =4;
    const DE_TRANSACTION_AMOUNT = 4;
    const DE_TRANSMISSION_DATE_TIME = 7;
    const DE_SYSTEM_TRACE_AUDIT_NUMBER = 11;
    const DE_DATE_TIME_LOCAL_TRANSACTION = 12;
    const DE_DATE_SETTLEMENT = 15;
    const DE_MERCHANT_CATEGORY_CODE= 26;
    const DE_BANK_CODE = 32;
    const DE_PARTNER_CENTRAL_ID=33;
    const DE_RETRIEVAL_REFERENCE_NUMBER = 37;
    const DE_RESPONSE_CODE = 39;
    const DE_TERMINAL_ID = 41;
    const DE_CARD_ACCEPTOR_NAME = 43;
    const DE_ADDITIONAL_DATA = 48;
    const DE_CURRENCY_CODE_TRANSACTION = 49;
    const DE_ORIGINAL_DATA_ELEMENT = 56;
    const DE_ADDITIONAL_DATA_2 = 62;
    
    const RESPONSE_CODE_SUCCESS                     = '0000';
    const RESPONSE_CODE_INVALID_MERCHANT_TYPE       = '0003';
    const RESPONSE_CODE_UNREGISTERED_BILL           = '0004';
    const RESPONSE_CODE_ERROR_OTHER                 = '0005';
    const RESPONSE_CODE_ERROR_BLOCKED_PARTNER_CENTRAL='0006';
    const RESPONSE_CODE_ERROR_BLOCKED_TERMINAL      = '0007';
    const RESPONSE_CODE_ERROR_INVALID_ACCESS_TIME   = '0008';
    const RESPONSE_CODE_ERROR_NEED_TO_SIGN_ON       = '0011';
    const RESPONSE_CODE_ERROR_INVALID_TRANSACTION_AM= '0013';
    const RESPONSE_CODE_ERROR_UNKNOWN_SUBSCRIBER    = '0014';
    const RESPONSE_CODE_ERROR_INVALID_MESSAGE       = '0030';
    const RESPONSE_CODE_ERROR_UNREGISTERED_BANK_CODE= '0031';
    const RESPONSE_CODE_ERROR_UNREGISTERED_PARTNER  = '0032';
    const RESPONSE_CODE_ERROR_UNREGISTERED_PRODUCT  = '0033';
    const RESPONSE_CODE_ERROR_UNREGISTERED_TERMINAL = '0034';
    const RESPONSE_CODE_ERROR_INVALID_ADMIN_CHARGES = '0045';
    const RESPONSE_CODE_ERROR_TIMEOUT               = '0068';
    const RESPONSE_CODE_ERROR_TIMEOUT_PLN_PAYMENT   = '0069';
    const RESPONSE_CODE_ERROR_BILLS_ALREADY_PAID    = '0088';
    const RESPONSE_CODE_ERROR_CUT_OFF_IN_PROCESS    = '0090';
    const RESPONSE_CODE_ERROR_SWITCHER_NUMBER_NA    = '0092';
    const RESPONSE_CODE_ERROR_INVALID_SWITCHER_TRACE= '0093';
    const RESPONSE_CODE_ERROR_SWITCHINGID_OR_BANK   = '0097';
    const RESPONSE_CODE_INVALID_REFERENCE_NUMBER    = '0098';

 
    public function __construct() 
    {
        $this->iso = new JAK8583();
    }
    
    public function setAdminCharges($input_admincharges)
            {
                 $this->admin_charges=str_pad($input_admincharges,10,0,STR_PAD_LEFT);
            }
    
    public function setAdminChargesPostPaid($input_admincharges)
            {
                 $this->admin_charges=str_pad($input_admincharges,9,0,STR_PAD_LEFT);
            }        
            
            
    public function setBillStatus($input_billstatus)
            {
        $this->bill_status=$input_billstatus;
            }
    
     public function setBillPeriod($input_billperiod)
            {
         $this->bill_period=$input_billperiod;
            }        
    
    public function setBuyingOption($input_buyingoption)
            {
        $this->buying_option=$input_buyingoption;
            }
            
     public function setCurrentMeterReading1($input_cmr1)
            {
        
         $this->current_meter_reading1=str_pad($input_cmr1,8,"0",STR_PAD_LEFT);
            }
            
     public function setCurrentMeterReading2($input_cmr2)
            {        
         $this->current_meter_reading2=str_pad($input_cmr2,8,"0",STR_PAD_LEFT);
            }        
    
     public function setCurrentMeterReading3($input_cmr3)
            {
         $this->current_meter_reading3=str_pad($input_cmr3,8,"0",STR_PAD_LEFT); 
            }                    
    
    public function setDueDate($input_duedate)
            {
        
        $this->due_date=$input_duedate;
            } 
            
    public function setDistributionCode($input_distributioncode)
    {
        $this->distribution_code=$input_distributioncode;
    }
    
    public function setExpirationDate($input_expirationdate)
    {
        $this->expiration_date=$input_expirationdate;
    }
    
    public function setFlag($input_flag)
    {
        $this->flag=$input_flag;
    }

    public function setMaxKwhUnit($input_kwhunit)
    {
        
        $this->max_kwh_unit=str_pad($input_kwhunit,5,"0",STR_PAD_LEFT);
    }        

        public function setMeterReadDate($input_mrd)
            {
        $this->meter_read_date=$input_mrd;
        
            }          
  
    public function setMinorUnitAdminCharges($input_MinorUnitAdminCharges)
            {
            
        $this->minor_unit_admin_charges=$input_MinorUnitAdminCharges;
            }


 
    public function setIncentive($input_incentive)
            {
        
        $this->incenctive=$input_incentive;
            }          
   
    public function setOriginalMTI($input_originalMTI) 
            {
                
            $this->original_MTI=$input_originalMTI;    
            }
    
    public function setOriginalPCTAN($input_originalPCTAN) 
            {
                
            $this->original_PCTAN=$input_originalPCTAN;    
            } 
    
            public function setOriginalDateTime($input_originalDateTime) 
            {
                $this->original_DateTime=$input_originalDateTime;
                
            }   
    
            public function setOriginalBankCode($input_originalBankCode) 
            {
                $this->original_BankCode=$input_originalBankCode;
            }        
            
            
    public function setPaymentStatus($input_paymentstatus)
            {
        
       $this->payment_status=$input_paymentstatus;
            }         
            
    public function setPenaltyFee($input_penaltyfee)
            {
        
        $this->penalty_fee=$input_penaltyfee;
            }
    
    public function setPreviousMeterReading1($input_pmr1)
            {
        
        $this->previous_meter_reading1=str_pad($input_pmr1,8,"0",STR_PAD_LEFT);
            }        
    
    public function setPreviousMeterReading2($input_pmr2)
            {
        
        $this->previous_meter_reading2=str_pad($input_pmr2,8,"0",STR_PAD_LEFT);
            }         
    
    public function setPreviousMeterReading3($input_pmr3)
            {
        
        $this->previous_meter_reading3=str_pad($input_pmr3,8,"0",STR_PAD_LEFT);
            }         
            
    public function setPlnBillValue($input_plnbillvalue)
            {
        
        $this->pln_bill_value=$input_plnbillvalue;
            }            
   
    public function setPlnBillMinorUnit($input_plnbillminorunit)
            {
        
        $this->pln_bill_minor_unit=$input_plnbillminorunit;
            }        
   
    public function setPlnReferenceNumber($input_PlnReferenceNumber)
            {
        
        $this->pln_reference_number=str_pad($input_PlnReferenceNumber,32," ",STR_PAD_RIGHT);
            }          
            
    public function setPowerConsumingCategory($input_pcc)
            {
        
       $this->power_consuming_category=str_pad($input_pcc,9,0,STR_PAD_LEFT);
            } 
    
    public function setPowerPurchase($input_powerpurchase)
            {
        
       $this->power_purchase=str_pad($input_powerpurchase,11,"0",STR_PAD_LEFT);
            } 
            
            public function setLengthPrivatedDE48($input_lengthDE48)
            {
                $this->lengthDE48=$input_lengthDE48;
            }
            
             public function setLengthPrivatedDE62($input_lengthDE62)
            {
                $this->lengthDE62=$input_lengthDE62;
            }

                        public function setRegistrationNumber($input_registrationNumber)
    {
        $this->registration_number=str_pad($input_registrationNumber,13," ",STR_PAD_RIGHT);
    }
    
    public function setRegistrationDate($input_registrationDate)
    {
        $this->registration_date=$input_registrationDate;
    }


    public function setServiceUnit($input_serviceunit)
            {
        
        $this->service_unit=$input_serviceunit;
            }         
    
    public function setServiceUnitAddress($input_service_unit_address)
    {
        $this->service_unit_address=$input_service_unit_address;
    }
            
                 
    public function setServicePhoneUnit($input_serviceunitphone)
            {
        
        $this->service_unit_phone=str_pad($input_serviceunitphone,15," ",STR_PAD_RIGHT);
            }
    
    public function setSubscriberID($input_subscriberID)
            {
        
        $this->subscriber_ID=str_pad($input_subscriberID,12," ",STR_PAD_LEFT);
            }        
    
   public function setSubscriberSegmentation($input_subscriberSegmen)
            {
        
       $this->subscriber_segmentation=str_pad($input_subscriberSegmen,4," ",STR_PAD_RIGHT);
            }
            
   public function setSubscriberName($input_subscriberName)
            {
        
       $this->subscriber_name=str_pad($input_subscriberName,25," ",STR_PAD_RIGHT);
            } 
            
   public function setSwitcherTraceAuditNumber($input_stan)
   {
       $this->stan=  str_pad($input_stan,12,"0",STR_PAD_LEFT);
   }
            
   public function setSwitcherID($input_switcherID)
            {
        
       $this->switcher_ID=$input_switcherID;
            }
   
 public function  setMaterialNumber($input_materialNumber)
 {
     $this->material_number=$input_materialNumber;
 }


 
   public function setSwitcherReferenceNumber($input_switcherReferenceNumber)
            {
        
       $this->switcher_ReferenceNumber=  str_pad($input_switcherReferenceNumber,32," ",STR_PAD_RIGHT);
            }          
            
   public function setTransactionAmount($input_transactionAmount)
            {
        
       $this->transaction_amount=$input_transactionAmount;
            } 
  
   public function setTransactionAmountMinor($input_transactionAmountMinor)
            {
        
       $this->transaction_amount_minor=$input_transactionAmountMinor;
            }          
   
   public function setTotalRepeat($input_totalrepeat)
            {
        
       $this->total_repeat=$input_totalrepeat;
            }          
            
   public function setTotalElectricityBill($input_totalelectricitybill)
            {
        
       $this->total_electricityBill=$input_totalelectricitybill;
            }           
            
   public function setTotalOutstandingBill($input_totaloutstandingbill)
            {
        
       $this->total_outstandingBill=$input_totaloutstandingbill;
            }  
            
   public function setTransactionCode($input_transactionCode)
            {
        
       $this->transaction_code=$input_transactionCode;
            }          
            
   public function setTransactionName($input_transactionName)
            {
        
       $this->transaction_name=$input_transactionName;
            }    
  
   public function setValueAddedTax($input_valueaddedtax)
   {
       
       $this->value_addedTax=$input_valueaddedtax;
   }
    
    public function setBillComponentType($input_bill_component_type)
    {
        $this->bill_component_type=$input_bill_component_type;
    }
    
    public function setBillComponentMinor($input_bill_component_minor)
    {
        $this->bill_component_minor=$input_bill_component_minor;
    }
    
    public function setBillComponentValue($input_bill_component_value)
    {
        $this->bill_component_value=$input_bill_component_value;
    }
    
    public function setVendingReceiveNumber($input_VendingReceiveNumber)
    {
        $this->vending_receive_number=$input_VendingReceiveNumber;
    }
}
