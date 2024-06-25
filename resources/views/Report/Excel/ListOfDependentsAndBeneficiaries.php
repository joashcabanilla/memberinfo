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

    $sheet1 = $xls->addWorksheet("Dependents");
    $fields = array(
        array("Memid",10),
        array("Pbno",10),
        array("Last Name",20),
        array("First Name",20),
        array("Middle Name",20),
        array("Suffix",7),
        array("Birthdate",10),
        array("Relationship",10),
        array("Contact No",10),
    );

    $c = $r = 0;
    foreach($fields as $fieldinfo):
        list($caption,$colwidth) = $fieldinfo;
        $sheet1->setColumn($c,$c,$colwidth);
        $sheet1->write($r,$c,$caption,$subheaderB);$c++;
    endforeach;
    $r++;

    foreach($dependentsList as $dependent){
        $c = 0;
        $sheet1->writeString($r,$c,$dependent["memid"],$normalC);$c++;
        $sheet1->writeString($r,$c,$dependent["pbno"],$normalC);$c++;
        $sheet1->writeString($r,$c,convertEncoding($dependent["lastname"]),$normal);$c++;
        $sheet1->writeString($r,$c,convertEncoding($dependent["firstname"]),$normal);$c++;
        $sheet1->writeString($r,$c,convertEncoding($dependent["middlename"]),$normal);$c++;
        $sheet1->writeString($r,$c,convertEncoding(strtoupper($dependent["suffix"])),$normal);$c++;
        $sheet1->writeString($r,$c,$dependent["birthdate"],$normalC);$c++;
        $sheet1->writeNumber($r,$c,$dependent["relationship"],$normalC);$c++;
        $sheet1->writeString($r,$c,$dependent["contact_no"],$normalC);$c++;
        $r++;
    }

    $sheet2 = $xls->addWorksheet("Beneficiaries");
    $c = $r = 0;
    foreach($fields as $fieldinfo):
        list($caption,$colwidth) = $fieldinfo;
        $sheet2->setColumn($c,$c,$colwidth);
        $sheet2->write($r,$c,$caption,$subheaderB);$c++;
    endforeach;
    $r++;

    foreach($beneficiariesList as $beneficiaries){
        $c = 0;
        $sheet2->writeString($r,$c,$beneficiaries["memid"],$normalC);$c++;
        $sheet2->writeString($r,$c,$beneficiaries["pbno"],$normalC);$c++;
        $sheet2->writeString($r,$c,convertEncoding($beneficiaries["lastname"]),$normal);$c++;
        $sheet2->writeString($r,$c,convertEncoding($beneficiaries["firstname"]),$normal);$c++;
        $sheet2->writeString($r,$c,convertEncoding($beneficiaries["middlename"]),$normal);$c++;
        $sheet2->writeString($r,$c,convertEncoding(strtoupper($beneficiaries["suffix"])),$normal);$c++;
        $sheet2->writeString($r,$c,$beneficiaries["birthdate"],$normalC);$c++;
        $sheet2->writeNumber($r,$c,$beneficiaries["relationship"],$normalC);$c++;
        $sheet2->writeString($r,$c,$beneficiaries["contact_no"],$normalC);$c++;
        $r++;
    }

    $xls->send($title.".xls");
    $xls->close();
    die;
?>
    