<?php
    error_reporting(0);
    require_once(app_path('Includes/excel/spreadsheet/Writer.php'));

    function convertEncoding($string) {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $string);
    }

    $xls = new Spreadsheet_Excel_Writer();
    $header = $xls->addFormat(array('Size' => 11));
    $header->setLocked();
    $header->setBold();
    $header->setFontFamily('Arial');
    $header->setAlign("center");
    $header->setAlign("vcenter");

    $subheader = $xls->addFormat(array('Size' => 10));
    $subheader->setLocked();
    $subheader->setFontFamily('Arial');
    $subheader->setAlign("center");
    $subheader->setAlign("vcenter");
 
    $subheaderB = $xls->addFormat(array('Size' => 10));
    $subheaderB->setLocked();
    $subheaderB->setFontFamily('Arial');
    $subheaderB->setAlign("center");
    $subheaderB->setAlign("vcenter");
    $subheaderB->setBold();
    $subheaderB->setBorder(1);

    $subheaderNB = $xls->addFormat(array('Size' => 10));
    $subheaderNB->setLocked();
    $subheaderNB->setFontFamily('Arial');
    $subheaderNB->setAlign("left");
    $subheaderNB->setAlign("vcenter");
    $subheaderNB->setBold();

    $subheaderName = $xls->addFormat(array('Size' => 10));
    $subheaderName->setLocked();
    $subheaderName->setFontFamily('Arial');
    $subheaderName->setAlign("left");
    $subheaderName->setAlign("vcenter");
    $subheaderName->setBold();

    $normal = $xls->addFormat(array('Size' => 10));
    $normal->setFontFamily('Arial');
    $normal->setAlign("left");
    $normal->setAlign("vcenter");
    $normal->setTextWrap();
    $normal->setLocked();
    $normal->setBorder(1);
    
    $normalC = $xls->addFormat(array('Size' => 10));
    $normalC->setFontFamily('Arial');
    $normalC->setAlign("center");
    $normalC->setAlign("vcenter");
    $normalC->setTextWrap();
    $normalC->setLocked();
    $normalC->setBorder(1);
    
    $normalR = $xls->addFormat(array('Size' => 10));
    $normalR->setFontFamily('Arial');
    $normalR->setAlign("right");
    $normalR->setAlign("vcenter");
    $normalR->setTextWrap();
    $normalR->setBold();
    $normalR->setLocked();
    $normalR->setBorder(1);

    $sheet = $xls->addWorksheet($title);
    $xls->send($title.".xls");

    $fields = array(
        array('Member Type',15),
        array('MemId',15),
        array('PbNo',15),
        array('Title',15),
        array('Last Name',20),
        array('First Name',20),
        array('Middle Name',20),
        array('Suffix',15),
        array('Region',20),
        array('Province',20),
        array('City',20),
        array('Barangay',20),
        array('Unit Floor No.',20),
        array('Street',20),
        array('Subdivision',20),
        array('Area',10),
        array('Updated By',20),
    );

    $c = $r = 0;
    foreach($fields as $fieldinfo):
        list($caption,$colwidth) = $fieldinfo;
        $sheet->setColumn($c,$c,$colwidth);
        $sheet->write($r,$c,$caption,$subheaderB);$c++;
    endforeach;
    $r++;
    foreach($memberList as $data){
        $c = 0;
        $sheet->writeString($r,$c,$data["member_type"],$normalC);$c++;
        $sheet->writeString($r,$c,$data["memid"],$normalC);$c++;
        $sheet->writeString($r,$c,$data["pbno"],$normalC);$c++;
        $sheet->writeString($r,$c,$data["title"],$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["lastname"]),$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["firstname"]),$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["middlename"]),$normal);$c++;
        $sheet->writeString($r,$c,$data["suffix"],$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["regionName"]),$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["provinceName"]),$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["cityName"]),$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["barangayName"]),$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["unit_floor_no"]),$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["street"]),$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["subdivision"]),$normal);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["area"]),$normalC);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["updated_by"]),$normal);$c++;
        $r++;
    }

    $r+=2;
    foreach($updateCountList as $userId => $dataEncoded){
        $c = 0;
        $sheet->write($r,$c,"Name: ". $userList[$userId],$subheaderNB);
        $sheet->setMerge($r, $c, $r, $c+1);
        $c+=2;
        $sheet->write($r,$c,"Total: ". count($totalCountList[$userId]),$subheaderNB);

        $c = 0;
        $r++;
        $sheet->write($r,$c,"DATE",$subheaderB);$c++;
        $sheet->write($r,$c,"TOTAL",$subheaderB);

        
        $r++;
        ksort($dataEncoded);
        foreach($dataEncoded as $date => $total){
            $c = 0;
            $sheet->writeString($r,$c,$date,$normalC);$c++;
            $sheet->writeString($r,$c,count($total),$normalC);$c++;
            $r++;
        }
        $r+=2;
    }

    $xls->close();
    die;
?>
    