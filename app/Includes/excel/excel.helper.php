<?php

  /** helpers **/

  function br($times=1,$c=''){
      $GLOBALS["r"] += ($GLOBALS["br"] * $times);
      $GLOBALS["c"] = $c ? $c : $GLOBALS["oc"];
  }
  function fixstr($value) {
    // http://stackoverflow.com/a/35368306/6458222
    // $response = html_entity_decode(htmlentities($response, ENT_QUOTES, 'UTF-8'), ENT_QUOTES , 'ISO-8859-15');

    if(!preg_match('!!u',mb_detect_encoding($value))){
      // https://stackoverflow.com/a/4407996
      return utf8_encode($value);
    }

    $response = stripslashes($value);
    $chars = array(
      "&iuml;&iquest;&frac12;" => "Ñ",
      "&Atilde;&lsquo;" => "Ñ",
      "&Atilde;&plusmn;" => "ñ",
      "&Ntilde;" => "Ñ",
      "&ntilde;" => "ñ",
      "&rsquo;" => "'",
      "&lsquo;" => "`",
      "&frac12;" => "½",
    );
    $response = (htmlentities($response, ENT_QUOTES, 'UTF-8'));
    foreach ($chars as $k => $c) {
      $response = str_replace($k, $c, $response);
    }
    $response = utf8_decode(html_entity_decode($response, ENT_QUOTES, 'ISO-8859-15'));
    return $response;
  }

  function responsive($w,$array){
    $total = array_sum($array);
    $result = $array;
    foreach($result as &$v){
      $v = $w * ($v/$total);
    }
    return $result;
  }

  function inch2cm($inch){
    return $inch * 2.54;
  }
  function inch2pixel($inch){
    return cp(inch2cm($inch));
  }
  function cp($val){
    return $val * 28.32861;
  }

  function write($x,$y,$span,$content,$style,$options=array()){
    global $sheet;
    $defaults = array('br'=>false);
    $options = array_merge($defaults,$options);

    if(!intval($span)){
      $span = 1;
    }
    if($span > 1){
      $sheet->setMerge($y,$x,$y,$x+$span);
    }
    $sheet->write($y,$x,$content,$style);
    $x += $span + intval($span > 1);
    if($options["br"]){
      br();
    }
    return compact('x','y','span');
  }

  function reportInit($paper,$orientation){
    global $xls;
    if(!isset($xls->_pages)){
      $xls->_pages = array();
    }
    $xls->_meta = compact('paper','orientation');
    $xls->_pages[] = $sheet = $xls->addWorksheet();
    $sheet->setMargins(0);

    // @see http://www.rocksolidsolutions.org/reference/excel_file_format_paper_size_table.html
    switch (strtoupper($paper)){
      case 'LETTER': {
        $paper = 1;
        $size = array(inch2pixel(8.5,11));
        break;
      }
      case 'LEGAL': {
        $paper = 4;
        $size = array(inch2pixel(8.5,14));
      }
    }
    $sheet->setPortrait();
    switch (strtolower($orientation)){
      case 'landscape':
        $sheet->setLandscape();
      break;
    }

    return array($paper,$size[0],$size[1]);
  }
