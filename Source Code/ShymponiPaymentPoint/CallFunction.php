<?php

class CallFunction
{
    
    public function connectprepaid($transaction)
    {

        $prepaid_inquiry= PlnPrepaidMessageHelper::GetFinancialInquiryMessage($transaction);
        echo 'Inquiry Request Message String:'.'</br>'.$prepaid_inquiry.'</br>';
        $connect_prepaid= ISO8583Helpers::getInstance()->sendMessage($prepaid_inquiry.PHP_EOL);
        if($connect_prepaid['status']==ISO8583Helpers::EVERYTHING_OK)
        {
            $string_response=$connect_prepaid['message'];
            $result=  PlnPrepaidMessageHelper::ParseInquiryResponse($string_response);
            echo 'Inquiry Response Message String :'.'</br>'.$string_response.'</br>';
            echo '</br>';
            echo 'Inquiry Response Message Array :'.'</br>';
            echo '<pre>'.json_encode($result,JSON_PRETTY_PRINT).'</pre>';
            echo '</br>';
            
        }      
        
        $message_payment=  PlnPrepaidMessageHelper::GetFinancialPurchaseMessage($result,$transaction);
        echo 'Payment Request Message String :'.'</br>'.$message_payment.'</br>';
        $prepaid_payment= ISO8583Helpers::getInstance()->sendMessage($message_payment.PHP_EOL);
        
        if($prepaid_payment['status']==ISO8583Helpers::EVERYTHING_OK)
        {
            $string_response=$prepaid_payment['message'];
            $result_payment=  PlnPrepaidMessageHelper::ParsePaymentResponse($string_response);
            $result_postpaid_payment=  json_encode($result_payment,JSON_PRETTY_PRINT);
            echo 'Payment Response Message String :'.'</br>'.$string_response.'</br>';
            echo '</br>';
            echo 'Payment Response Message Array;'.'</br>';
            echo '<pre>'.$result_postpaid_payment.'</pre>';
            echo '</br>';
            
            
        }
        
        $message_advice=  PlnPrepaidMessageHelper::GetFinancialPurchaseAdviceMessage($result,$transaction);
        echo 'Purchase Advice Request Message String'.'</br>'.$message_advice.'</br>';
        $prepaid_advice=  ISO8583Helpers::getInstance()->sendMessage($message_advice.PHP_EOL);
        
        if($prepaid_advice['status']==ISO8583Helpers::EVERYTHING_OK)
        {
            $string_PurchaseAdviceresponse=$prepaid_advice['message'];
            $result_PurchaseAdvice=  PlnPrepaidMessageHelper::ParsePurchaseAdviceResponse($string_PurchaseAdviceresponse);
            $result_PurchaseAdviceResponse=  json_encode($result_PurchaseAdvice,JSON_PRETTY_PRINT);
            echo 'Purchase Advice Response Message String'.'</br>'.$string_PurchaseAdviceresponse.'</br>';
            echo 'Purchase Advice Response Message Array;'.'</br>';
            echo '<pre>'.$result_PurchaseAdviceResponse.'</pre>';
            echo '</br>';
        }
 
        
    }
    
    public function connectpostpaid($transaction)
    {        
        $postpaid_inquiry=  PlnPostPaidMessageHelper::getFinancialInquiryMessage($transaction);
        $connect_postpaid= ISO8583Helpers::getInstance()->sendMessage($postpaid_inquiry.PHP_EOL);
        echo 'Inquiry Request Message String:'.'</br>'.$postpaid_inquiry.'</br>';
        if($connect_postpaid['status']==ISO8583Helpers::EVERYTHING_OK)
        {
            $string_response=$connect_postpaid['message'];
            $result         =  PlnPostPaidMessageHelper::ParseInquiryResponse($string_response);
            echo 'Inquiry Response Message String :'.'</br>'.$string_response.'</br>';
            echo 'Inquiry Response Message Array :'.'</br>';
            echo '<pre>'.json_encode($result,JSON_PRETTY_PRINT).'</pre>';
            echo '</br>';
        }
        
        $message_postpaid_payment=  PlnPostPaidMessageHelper::getFinancialPaymentMessage($result);
        echo 'Payment Request Message String :'.'</br>'.$message_postpaid_payment.'</br>';
        $postpaid_payment= ISO8583Helpers::getInstance()->sendMessage($message_postpaid_payment.PHP_EOL);
        
        if($postpaid_payment['status']==ISO8583Helpers::EVERYTHING_OK)
        {
            $string_response=$postpaid_payment['message'];
            $result_payment=  PlnPostpaidMessageHelper::ParsePaymentResponse($string_response);
            echo 'Payment Response Message String :'.'</br>'.$string_response.'</br>';
            echo 'Payment Response Message Array :'.'</br>';
            echo '<pre>'.json_encode($result_payment,JSON_PRETTY_PRINT).'</pre>';
            echo '</br>';
            
        }
        
        if($result_payment->response_code==SyhmponiMessageHelper::RESPONSE_CODE_ERROR_OTHER||SyhmponiMessageHelper::RESPONSE_CODE_ERROR_TIMEOUT)
        {    
        $parse_reversal_postpaid= PlnPostPaidMessageHelper::getFinancialReversalMessage($message_postpaid_payment);
        $kirim_reversal_postpaid= ISO8583Helpers::getInstance()->sendMessage($parse_reversal_postpaid.PHP_EOL);
        
        if($kirim_reversal_postpaid['status']==ISO8583Helpers::EVERYTHING_OK)
        {
            $response_reversal_postpaid=$kirim_reversal_postpaid['message'];
            $parse_reversal_response=PlnNontaglisMessageHelper::ParseReversalResponse($response_reversal_postpaid);
            echo 'Reversal Response Message String'.'</br>'.$response_reversal_postpaid.'</br>';
            echo 'Reversal Response Message Array :'.'</br>';
            echo '<pre>'.json_encode($parse_reversal_response,JSON_PRETTY_PRINT).'</pre>';
            echo '</br>';
            
            
        }
        
        }
        

        
    }
    
    public function connectnontaglis($transaction)
    {
        $nontaglis_inquiry= PlnNontaglisMessageHelper::getFinancialInquiryMessage($transaction);        
        $connect_nontaglis= ISO8583Helpers::getInstance()->sendMessage($nontaglis_inquiry.PHP_EOL);
        echo 'Inquiry Request Message String:'.'</br>'.$nontaglis_inquiry.'</br>';
        if($connect_nontaglis['status']==ISO8583Helpers::EVERYTHING_OK)
        {
            $string_response=$connect_nontaglis['message'];
            $result         = PlnNontaglisMessageHelper::ParseInquiryResponse($string_response);
            echo 'Inquiry Response Message String :'.'</br>'.$string_response.'</br>';
            echo 'Inquiry Response Message Array :'.'</br>';
            echo '<pre>'.json_encode($result,JSON_PRETTY_PRINT).'</pre>';
            echo '</br>';
            
        }
        
        $parse_payment=  PlnNontaglisMessageHelper::getFinancialPaymentMessage($result);
         echo 'Payment Request Message String :'.'</br>'.$parse_payment.'</br>';
        $substrakLengthData=PlnNontaglisMessageHelper::SubstrakLengthData($result,$parse_payment);
        $kirim_payment=  ISO8583Helpers::getInstance()->sendMessage($substrakLengthData.PHP_EOL);
        
        if($kirim_payment['status']==ISO8583Helpers::EVERYTHING_OK)
        {
            $response_payment=$kirim_payment['message'];
            $parse_payment_response=  PlnNontaglisMessageHelper::ParsePaymentResponse($response_payment);
            echo 'Payment Response Message String :'.'</br>'.$response_payment.'</br>';
            echo 'Payment Response Message Array :'.'</br>';
            echo '<pre>'.json_encode($parse_payment_response,JSON_PRETTY_PRINT).'</pre>';
            echo '</br>';
            
        }
        
        if($parse_payment_response==SyhmponiMessageHelper::RESPONSE_CODE_ERROR_OTHER||SyhmponiMessageHelper::RESPONSE_CODE_ERROR_TIMEOUT)
        {
        $parse_reversal=  PlnNontaglisMessageHelper::getFinancialReversalMessage($substrakLengthData);
        echo 'Reversal Request Message String'.'</br>'.$parse_reversal.'</br>';
        $kirim_reversal= ISO8583Helpers::getInstance()->sendMessage($parse_reversal.PHP_EOL);
        if($kirim_reversal['status']==ISO8583Helpers::EVERYTHING_OK)
        {
            $response_reversal_nontaglis=$kirim_reversal['message'];
            $parse_reversal_response=  PlnNontaglisMessageHelper::ParseReversalResponse($response_reversal_nontaglis);
            echo 'Reversal Response Message String'.'</br>'.$response_reversal_nontaglis.'</br>';
            echo 'Reversal Response Message Array :'.'</br>';
            echo '<pre>'.json_encode($result,JSON_PRETTY_PRINT).'</pre>';
            echo '</br>';
        }
        
        }

        
        
    }
    
    
    
}
