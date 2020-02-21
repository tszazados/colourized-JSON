<?php

   /*  Super-simple JSON string colourizer with PHP's regexp
    *  V1.00 2020-02-21
    *  author: tszazados@gmail.com
    */

    define ( "INPUT_JSON_FILE"     ,         "c:\import\input.json" ) ;
    define ( "MAX_ROWS_TO_SHOW"    ,         5000);
    define ( "COLOR_KEYS"          ,         "#00ff00" ) ;
    define ( "COLOR_VALUES"        ,         "#ffffc0" ) ;
    define ( "COLOR_DEFAULT"       ,         "#ffffff" ) ;
    define ( "COLOR_QUOTES"        ,         "#c0ffc0" ) ;
    define ( "COLOR_NUMBERS"       ,         "#ff9090" ) ;
    
    $data = file_get_contents ( INPUT_JSON_FILE ) ;

    if ( ( $json = json_decode ( $data ) ) === NULL)
    {
        die ( "Cannot re-parse text as JSON." ) ;
    }
    
    $newData = json_encode ( $json , JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT /*0b110000000*/ ) ;

    $newData = str_replace ( [ "\r" , "<" , ">" ], [ "", "&lt;", "&gt;" ], $newData ) ;
   
    $newData = preg_replace ( '/\"(\w+)\":/i'      , '<i style=\'color:' . COLOR_KEYS . '\'>$1</i>":'                  , $newData ) ;
    $newData = preg_replace ( '/\"(\d+)\",/i'      , '"<i style=\'color:' . COLOR_NUMBERS . '\'>$1</i>",'              , $newData ) ;
    $newData = preg_replace ( '/\": \"([^"]+)\"/i' , '": "<i style=\'color:' . COLOR_VALUES . '\'>$1</i>"'             , $newData ) ;
    $newData = str_replace  ( '"'                  , '<i style=\'font-style:normal;color:' . COLOR_QUOTES . '\'>"</i>' , $newData ) ;
    
    $newData = explode ( "\n" , $newData ) ;
    
    print ( "<pre style='font-family:Victor Mono;background-color:#100030;color:".COLOR_DEFAULT.";margin:0;padding:15px;font-size:16px;'>" ) ;
    
    for ( $i = 0 , $l = strlen( min ( MAX_ROWS_TO_SHOW, count ( $newData ) -1 ) ) ; $i < MAX_ROWS_TO_SHOW ; $i++ )
    {
        print array_key_exists ( $i, $newData ) ? ( str_pad($i, $l, "0", STR_PAD_LEFT) . ":   " . $newData [ $i ] . "<br>" ) : "" ;
    }
    
    print "</pre>" ;
