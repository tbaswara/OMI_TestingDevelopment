<?php

class ISO8583Helpers 
{
    
    private static $instance;
    
    const FAILED_TO_CONNECT = 100;
    const FAILED_TO_SEND_DATA = 101;
    const SERVER_NOT_RESPONDING = 102;
    const EVERYTHING_OK = 200;

    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}
    
    public function sendMessage($message)
    {
        ini_set('max_execution_time',60);
        
        $server_address='103.16.138.19';
        $server_port='9999';
        $result = array();
        
        try
        {   
            
            $address =$server_address;
            $port =$server_port;
            $socket = socket_create(AF_INET, SOCK_STREAM,SOL_TCP);
            if ($socket === false) 
                {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
    
                die("Couldn't create socket: [$errorcode] $errormsg");
               }
            $socket_connect=socket_connect($socket, $address, $port);
            if(!$socket_connect)
                {
                
                    echo $result['status']=  self::FAILED_TO_CONNECT;
                }
           
            socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 60, "usec" => 0));
            socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 70, "usec" => 0));
            $isSent=  socket_write($socket, $message);            
            $isSent = socket_send($socket, $message, strlen($message), 0);
            if($isSent)
            {
                $response=  socket_read($socket,2048,PHP_BINARY_READ);
                $result['status'] = self::EVERYTHING_OK;
                $result['message'] = $response;
               
            }
            
            else 
            {
                $result['status']=  self::FAILED_TO_SEND_DATA;
                echo $result['status'];    
            }
                        
        }
        catch (Exception $ex) 
        {
            $socketErrorCode = socket_last_error();
            $result['status'] = self::FAILED_TO_CONNECT;
            $result['message'] = socket_strerror($socketErrorCode);
        }
        
        socket_close($socket);
        return $result;
    }
}
