<?php

  /*
    *  Super-simple JSON string colourizer with PHP's regexp
    *  V1.00 2020-02-21
    *
    *  author: tszazados@gmail.com
    *
    */

    define ( "INPUT_JSON_FILE"     ,         "c:\import\input.json" ) ;
    define ( "MAX_ROWS_TO_SHOW"    ,         5000);

    define ( "COLOR_KEYS"          ,         "#00ff00" ) ;
    define ( "COLOR_VALUES"        ,         "#ffffc0" ) ;
    define ( "COLOR_DEFAULT"       ,         "#ffffff" ) ;
    define ( "COLOR_QUOTES"        ,         "#c0ffc0" ) ;
    define ( "COLOR_NUMBERS"       ,         "#ff9090" ) ;
    
    
    $data = file_get_contents ( INPUT_JSON_FILE ) ;


//    $data = file_get_contents("c:\import\out.tsv" ) ;
//    $data = "AIEJhduze3kdi_d__ds[".$data . "{}]";
//    $data = str_replace ("AIEJhduze3kdi_d__ds[ADAT", "[",     $data ) ;
//    file_put_contents("c:\import\xx", $data);

    if ( ( $json = json_decode ( $data ) ) === NULL)
    {
        die ( "Cannot re-parse text as JSON." ) ;
    }
    
    $newData = json_encode ( $json , JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT /*0b110000000*/ ) ;
    //file_put_contents("c:\import\output_formatted.json", $newData);
    
    $newData = str_replace ( [ "\r" , "<" , ">" ], [ "", "&lt;", "&gt;" ], $newData ) ;
    
    $col1 = COLOR_KEYS    ;
    $col2 = COLOR_DEFAULT ;
    $col3 = COLOR_VALUES  ;
    $col4 = COLOR_QUOTES  ;
    $col5 = COLOR_NUMBERS ;
    
    $newData = preg_replace ( '/\"(\w+)\":/i'       ,  '<i style=\'color:'.$col1.'\'>$1</i>":'                , $newData ) ;
    $newData = preg_replace ( '/\"(\d+)\",/i'       ,  '"<i style=\'color:'.$col5.'\'>$1</i>",'               , $newData ) ;
    $newData = preg_replace ( '/\": \"([^"]+)\"/i'  ,  '": "<i style=\'color:'.$col3.'\'>$1</i>"'             , $newData ) ;
    $newData = str_replace  ( '"'                   ,  '<i style=\'font-style:normal;color:'.$col4.'\'>"</i>' , $newData ) ;
    
    $newData = explode ( "\n" , $newData ) ;
    
    print "<pre style='font-family:Victor Mono;background-color:#100030;color:$col2;margin:0;padding:15px;font-size:16px;'>" ;
    
    for ( $i = 0 , $l = strlen( min ( MAX_ROWS_TO_SHOW, count ( $newData ) -1 ) ) ; $i < MAX_ROWS_TO_SHOW ; $i++ )
    {
        
        print array_key_exists ( $i, $newData ) ? ( str_pad($i, $l, "0", STR_PAD_LEFT) . ":   " . $newData [ $i ] . "<br>" ) : "" ;
        
    }
    
    print "</pre>" ;
    

