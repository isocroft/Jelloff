<?php

namespace Providers\Tools;

class InputFilter {

	 /**
	  *
	  *
	  *@param InputFilter
	  *@constructor
	  */

     public function __construct(){

     }

     public function sanitizeInput($raw_str, $level){
       if(gettype($level) !== 'integer'
          || preg_match('/^string/', gettype($raw_str))){
           return;
       }

       $result = FALSE;
       $const = -1;
       $opts = NULL;
       /*if($raw_str == ""){
           $raw_str = $result;
       }*/
       switch($level){
        case 1:
          $const = FILTER_VALIDATE_URL;
        break;
        case 2:
          $const = FILTER_VALIDATE_BOOLEAN;
        break;
        case 0:
        case 3:
          $const = FILTER_CALLBACK;
          $opts = ($level == 3)? array('options'=>'str_filter') : array('options'=>'enum_filter');
        break;
        case 4:
          $const = FILTER_VALIDATE_EMAIL;
          strpos($raw_str, '@') ? list(, $mailDomain) = explode('@', $raw_str) : $mailDomain = null;
        break;
        case 5:
          $const = FILTER_VALIDATE_INT;
        break;
       }
       try{
       	   if($opts !== NULL){
               $result = filter_var($raw_str, $const, $opts);
           }else{
               $result = filter_var($raw_str, $const);
               if($result === TRUE && $const === 4 && !is_null($mailDomain)){
                    try{
                      $result = (!is_null($mailDomain) && checkdnsrr($mailDomain, 'MX'));
                    }catch(\Exception $e){
                      $result = TRUE;
                    }
               }
           }

       }catch(\Exception $ex){

           \Logger::error('Sanitizing input error: '. $ex->getMessage());
       }
       
       /* return $raw_str; */
       return $result;
     }

}


?>