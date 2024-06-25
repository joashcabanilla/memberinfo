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
    $subheaderB->setFgColor('yellow');

    $subheaderNB = $xls->addFormat(array('Size' => 10));
    $subheaderNB->setLocked();
    $subheaderNB->setFontFamily('Arial');
    $subheaderNB->setAlign("left");
    $subheaderNB->setAlign("vcenter");
    $subheaderNB->setBold();

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
    $c = $r = 0;
    $sheet1->setMerge($r, $c, $r, 7);
    $sheet1->write($r,$c,"Summary Of Encoded Dependents",$header);
    $r++;
    $sheet1->setMerge($r, $c, $r, 7);
    $sheet1->write($r,$c,"As Of ".date("m/d/Y h:i A"),$header);
    $r++;
    $sheet1->setMerge($r, $c, $r, 7);
    $r++;

    $userCount = $c = 0;
    $fields = [
        ['DATE',12],
        ['TOTAL',12]
    ];
    
    foreach($dependentsList as $userId => $dependents){
        $userCount++;
        $startR = $r;
        $sheet1->write($r,$c,"Name: ". $userList[$userId],$subheaderNB);
        $sheet1->setMerge($r, $c, $r, $c+1);
        $r++;
        $startC = $c;

        foreach($fields as $fieldinfo){
            list($caption,$colwidth) = $fieldinfo;
            $sheet1->setColumn($startC,$startC,$colwidth);
            $sheet1->write($r,$startC,$caption,$subheaderB);$startC++;
        }

        ksort($dependents);
        $totalCount = 0;
        foreach($dependents as $date => $dependent){
            $total = count($dependent);
            $r++;
            $startC = $c;
            $sheet1->writeString($r,$startC,$date,$normalC);$startC++;
            $sheet1->writeNumber($r,$startC,$total,$normalC);
            $totalCount+=$total;
        }
        
        $startC = $c;
        $r++;
        $sheet1->writeString($r,$startC,"Total Encoded",$subheaderB);$startC++;
        $sheet1->writeNumber($r,$startC,$totalCount,$subheaderB);$startC++;

        $c+=3;
        $lastR = $r;
        $r = $startR;
        if($userCount > 2){
            $c = 0;
            $r = $lastR + 2;
            $userCount = 0;
        }
    }

    $sheet2 = $xls->addWorksheet("Beneficiaries");
    $c = $r = 0;
    $sheet2->setMerge($r, $c, $r, 7);
    $sheet2->write($r,$c,"Summary Of Encoded Beneficiaries",$header);
    $r++;
    $sheet2->setMerge($r, $c, $r, 7);
    $sheet2->write($r,$c,"As Of ".date("m/d/Y h:i A"),$header);
    $r++;
    $sheet2->setMerge($r, $c, $r, 7);
    $r++;

    $userCount = $c = 0;
    $fields = [
        ['DATE',12],
        ['TOTAL',12]
    ];

    foreach($beneficiariesList as $userId => $beneficiaries){
        $userCount++;
        $startR = $r;
        $sheet2->write($r,$c,"Name: ". $userList[$userId],$subheaderNB);
        $sheet2->setMerge($r, $c, $r, $c+1);
        $r++;
        $startC = $c;

        foreach($fields as $fieldinfo){
            list($caption,$colwidth) = $fieldinfo;
            $sheet2->setColumn($startC,$startC,$colwidth);
            $sheet2->write($r,$startC,$caption,$subheaderB);$startC++;
        }

        ksort($dependents);
        $totalCount = 0;
        foreach($beneficiaries as $date => $beneficiary){
            $total = count($beneficiary);
            $r++;
            $startC = $c;
            $sheet2->writeString($r,$startC,$date,$normalC);$startC++;
            $sheet2->writeNumber($r,$startC,$total,$normalC);
            $totalCount+=$total;
        }
        
        $startC = $c;
        $r++;
        $sheet2->writeString($r,$startC,"Total Encoded",$subheaderB);$startC++;
        $sheet2->writeNumber($r,$startC,$totalCount,$subheaderB);$startC++;

        $c+=3;
        $lastR = $r;
        $r = $startR;
        if($userCount > 2){
            $c = 0;
            $r = $lastR + 2;
            $userCount = 0;
        }
    }

    $xls->send($title.".xls");
    $xls->close();
    die;
?>
    