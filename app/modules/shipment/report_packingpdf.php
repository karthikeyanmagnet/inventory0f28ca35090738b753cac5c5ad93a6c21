<?php$shipment_id = $_POST['shipment_order_id'];$customer_id = $_POST['customer_id'];$maindir = dirname(realpath('..'));include $maindir . '/rapper/class.rapper.php';include 'class.script.php';//ini_set('display_errors',1);//exit($maindir.'/rapper/class.rapper.php');include $maindir . '/rapper/fpdf/PrintPosition.php';$po_entry = new shipment;$postArr = $_POST;$data = $po_entry->getSingleView($postArr);$data = json_decode($data, true);$rsData = $data['rsData'];$itmgrpdet_details = $rsData['itmgrpdet_details'];$itemlist = $rsData['itemlist'];$items = array();foreach ($itemlist as $itm) {    $items[$itm['item_id']] = $itm['item_code'];}class RPDF extends PDF {    function Header() {        //logo_payslip        $this->Rect(10, 10, 190, 277, 'D'); //For A4        $this->SetFont('Verdana-Bold', '', 14);        $this->SetTextColor(255, 0, 0);        $this->Cell(0, 10, 'PSMITH PROCUREMENT ', 0, 0, 'L');        $this->ln(5);        $this->Cell(0, 10, 'PRIVATE LIMITED ', 0, 0, 'L');        $this->ln(10);        $this->ImagePngWithAlpha(dirname($this->maindir) . '/assets/images/pl_invt_logo.png', 90, 11, 52);        $this->ln(10);        $this->SetXY(150, 13);        $this->SetFont('Verdana-Bold', '', 12);        $this->SetTextColor(0, 0, 0);        $this->Cell(0, 15, 'EXPORT INVOICE', 0, 0, 'L');        $this->ln(10);        $y = $this->y;        $this->SetFont('Arial', '', 8);////        $this->Cell(0, 15, 'Head office: 2nd Floor, Emerald Towers, #5', 0, 0, 'L');////        $this->ln(5);////        //$this->SetX(9);////        $this->Cell(0, 15, 'Krishnasamy nagar, Ramanathapuram,', 0, 0, 'L');////        $this->ln(5);////        //$this->SetX(9);////        $this->Cell(0, 15, 'COIMBATORE - 641045 Phone / Fax: 0422-4204070', 0, 0, 'L');////        $this->ln(5);////        //$this->SetX(9);////        $this->Cell(0, 15, 'TIN: 33366404099 Dated 13/08/2016', 0, 0, 'L');////        $this->ln(15);        $this->Cell(0, 15, '(Export under Letter of Undertaking without payment of IGST)', 0, 0, 'L');        $this->ln(5);        //$this->SetX(9);        $this->Cell(0, 15, 'PSMITH PROCUREMENT PRIVATE LIMITED', 0, 0, 'L');        $this->ln(5);        //$this->SetX(9);        $this->Cell(0, 15, 'EMERALD TOWERS, No.5, 50 FEET ROAD, KRISHNASAMY NAGAR', 0, 0, 'L');        $this->ln(5);        //$this->SetX(9);        $this->Cell(0, 15, 'RAMANATHAPURAM, COIMBATORE - 641045, INDIA.', 0, 0, 'L');        $this->ln(15);        $this->SetXY(132, $y + 5);        $this->Cell(34, 6, 'ORIGINAL FOR CONSIGNEE', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(132, $y);        $this->Cell(34, 6, 'DUPLICATE FOR TRANSPORTER', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(132, $y);        $this->Cell(34, 6, 'TRIPLICATE FOR FILE', 0, 0, 'L');        $this->ln(6);        $this->SetFont('Verdana-Bold', '', 8);        $this->SetTextColor(255, 255, 255);        $this->SetFillColor(0, 0, 0);        $this->SetXY(10, 48);        $this->Cell(50, 7, '  VENDOR', 0, 0, 'L', 1);        $this->Cell(50, 7, '  SHIP AND BILLED TO', 0, 0, 'L', 1);        $this->ln(7);        $this->SetTextColor(0, 0, 0);        $this->SetFont('Verdana', '', 8);        $this->SetXY(10, 55);        $this->MultiCell(50, 5, 'Contact No : +0422-4204070', 1, 'L', 0);        $this->SetXY(60, 55);        $this->MultiCell(50, 5, 'EmailID:PPugalenthi@sigmaco.com', 1, 'L', 0);        $this->SetXY(10, 60);        $this->MultiCell(50, 5, 'GSTIN: 33AAICP6784H1Z1', 1, 'L', 0);        $this->SetXY(60, 60);        $this->MultiCell(50, 5, 'State: Tamilnadu', 1, 'L', 0);        $this->SetXY(10, 65);        $this->MultiCell(100, 5, 'IE CODE: 3216911055', 1, 'L', 0);        $this->SetXY(10, 70);        $this->MultiCell(50, 5, 'CONSIGNEE (BILL TO)', 1, 'L', 0);        $this->SetXY(60, 70);        $this->MultiCell(50, 5, 'BUYER OTHER THAN CONSIGNEE', 1, 'L', 0);        $this->SetXY(10, 75);        $this->MultiCell(50, 5, 'Industrial Park,NAIDUPET ', 1, 'L', 0);        $this->SetXY(60, 75);        $this->MultiCell(50, 5, 'qqqqqq', 1, 'L', 0);        $this->SetXY(10, 80);        $this->MultiCell(100, 5, 'Buyer Order and Date: M104744 Dtd.', 1, 'L', 0);        $this->SetXY(10, 85);        $this->MultiCell(50, 5, 'Other References if any', 1, 'L', 0);        $this->SetXY(60, 85);        $this->MultiCell(50, 5, '', 1, 'L', 0);        $this->SetXY(10, 90);        $this->MultiCell(50, 5, 'Country Of Origin: India', 1, 'L', 0);        $this->SetXY(60, 90);        $this->MultiCell(50, 5, 'Country Of Final Destination', 1, 'L', 0);        $this->SetXY(10, 95);        $this->MultiCell(50, 5, 'Terms of Delivery &', 1, 'L', 0);        $this->SetXY(60, 95);        $this->MultiCell(50, 5, 'Payment Terms: At', 1, 'L', 0);//        $this->SetXY(10, 120);////        $this->MultiCell(300, 5, 'Payment Terms: At', 1, 'L', 0);        $this->ln(5);        $y = $this->y;        //  $this->SetXY(122, 45);        $this->SetXY(120, 45);        $this->Cell(34, 6, 'Invoice No :', 0, 0, 'R');        $this->Cell(45, 6, '3PL/1718/8', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $y = $this->y;        $this->SetXY(122, $y);        $this->Cell(34, 6, 'Invoice Date :', 0, 0, 'R');        $this->Cell(45, 6, '04/11/17 ', 0, 0, 'L');        $this->ln(6);        $this->SetTextColor(0, 0, 0);    }    function Footer() {        // Go to 1.5 cm from bottom        $this->SetY(-8);        // Select Arial italic 8        $this->SetFont('Verdana', '', 8);        $this->SetFillColor(100, 100, 100);        $this->SetX(115);        $this->Cell(0, 8, 'Page ' . $this->PageNo() . ' of ' . $this->AliasNbPages, 0, 0, 'L');        $this->SetX(183);        $this->Cell(0, 8, 'Copyright © ' . date('Y'), 0, 0, 'L');        $this->ln(4);        $this->AliasNbPages();        //$this->Cell(0,10,'Page '.$this->PageNo().' of '.$this->AliasNbPages,0,0,'C');    }    function CheckPageBreak($h) {        $h = 15;        //$this->PageBreakTrigger = 230;        //If the height h would cause an overflow, add a new page immediately        if ($this->GetY() + $h > 170) {            $this->AddPage($this->CurOrientation);        }    }    function printTableHeader() {        $tableWidth = $this->tableWidth;        $tableColumns = $this->tableColumns;        $tableAlign = $this->tableAlign;        $this->SetTextColor(255, 255, 255);        $this->SetFont('Verdana-Bold', '', 8);        foreach ($this->tableColumns as $colkey => $column) {            $this->Cell($tableWidth[$colkey], 8, $column, 1, 0, 'C', 1);        }        $this->SetTextColor(0, 0, 0);        $this->ln(8);    }    function printTableRow($row) {        $tableWidth = $this->tableWidth;        $tableColumns = $this->tableColumns;        $tableAlign = $this->tableAlign;        foreach ($this->tableColumns as $colkey => $column) {            $this->Cell($tableWidth[$colkey], 5, $row[$colkey], 1, 0, $tableAlign[$colkey]);        }        $this->ln(5);    }}//if(count($paydet)>0){    $title = "Payslip for the Month of";    $monYer = date('M Y', strtotime($date));    $sigma_addr = '700 Goldman Drive, Cream Ridge, NJ 08514';    $pdf = new RPDF();    $pdf->maindir = $maindir;    $pdf->title = $title;    $pdf->monYer = $monYer;    $pdf->sigma_addr = $sigma_addr;    $pdf->employee_name = $employee_name;    $pdf->employee_doj = $employee_doj;    $pdf->employee_id = $employee_id;    $pdf->employee_code = $employee_code;    $pdf->designation_name = $designation_name;    $pdf->esi_number = $esi_number;    $pdf->pf_number = $pf_number;    $pdf->AliasNbPages();    $pdf->AddFont('Verdana-Bold', '', 'verdana_bold.php');    $pdf->AddFont('Verdana', '', 'verdana.php');    //$pdf->AddFont('Times-Italic','','timesi.php');    $pdf->AddPage();    $pdf->SetLeftMargin(10);    $pdf->SetRightMargin(15);    $curHeadPos = $pdf->y;    $pdf->SetTextColor(255, 255, 255);    $pdf->SetFont('Verdana-Bold', '', 8);    $pdf->Cell(10, 7, 'S.No', 0, 0, 'C', 1);    $pdf->Cell(18, 7, 'Description', 0, 0, 'C', 1);    $pdf->Cell(18, 7, 'HSN CODE', 0, 0, 'C', 1);    $pdf->Cell(8, 7, 'UOM', 0, 0, 'C', 1);    $pdf->Cell(17, 7, 'QUANTITY', 0, 0, 'C', 1);    $pdf->Cell(20, 7, 'RATE in USD', 0, 0, 'C', 1);    $pdf->Cell(13, 7, 'VALUE', 0, 0, 'C', 1);    $pdf->Cell(20, 7, 'DISCOUNT', 0, 0, 'C', 1);    $pdf->Cell(22, 7, 'NET AMOUNT', 0, 0, 'C', 1);    $pdf->Cell(10, 7, 'IGST', 0, 0, 'C', 1);    $pdf->Cell(35, 7, 'AMOUNT AFTER TAX', 0, 0, 'C', 1);    $pdf->ln(7);    $pdf->SetTextColor(0, 0, 0);    $pdf->SetFont('Verdana', '', 8);    $pdf->Cell(10, 5, 'S.No', 1, 0, 'L');    $pdf->Cell(18, 5, 'Description', 1, 0, 'L');    $pdf->Cell(18, 5, 'HSN CODE', 1, 0, 'L');    $pdf->Cell(8, 5, 'UOM', 1, 0, 'L');    $pdf->Cell(17, 5, 'QUANTITY', 1, 0, 'L');    $pdf->Cell(20, 5, 'RATE in USD', 1, 0, 'L');    $pdf->Cell(13, 5, 'VALUE', 1, 0, 'L');    $pdf->Cell(19, 5, 'DISCOUNT', 1, 0, 'L');    $pdf->Cell(22, 5, 'NET AMOUNT', 1, 0, 'L');    $pdf->Cell(10, 5, 'IGST', 1, 0, 'L');    $pdf->Cell(35, 5, 'AMOUNT AFTER TAX', 1, 0, 'L');    $pdf->ln(5);    //$pdf->SetTextColor(0, 0, 0);    // $pdf->SetFont('Verdana', '', 8);    $pdf->Cell(10, 5, 'S.No', 1, 0, 'L');    $pdf->Cell(18, 5, 'Description', 1, 0, 'L');    $pdf->Cell(18, 5, 'HSN CODE', 1, 0, 'L');    $pdf->Cell(8, 5, 'UOM', 1, 0, 'L');    $pdf->Cell(17, 5, 'QUANTITY', 1, 0, 'L');    $pdf->Cell(20, 5, 'RATE in USD', 1, 0, 'L');    $pdf->Cell(13, 5, 'VALUE', 1, 0, 'L');    $pdf->Cell(19, 5, 'DISCOUNT', 1, 0, 'L');    $pdf->Cell(22, 5, 'NET AMOUNT', 1, 0, 'L');    $pdf->Cell(10, 5, 'IGST', 1, 0, 'L');    $pdf->Cell(35, 5, 'AMOUNT AFTER TAX', 1, 0, 'L');    $pdf->ln(5);    //$pdf->SetTextColor(0, 0, 0);    // $pdf->SetFont('Verdana', '', 8);    $pdf->Cell(10, 5, '', 1, 0, 'L');    $pdf->Cell(80, 5, 'Freight Charges', 1, 0, 'L');    $pdf->Cell(14, 5, '3658.40	', 1, 0, 'L');    $pdf->Cell(86, 5, '', 1, 0, 'L');    $pdf->ln(5);    //$pdf->SetTextColor(0, 0, 0);    // $pdf->SetFont('Verdana', '', 8);    $pdf->Cell(10, 5, '', 1, 0, 'L');    $pdf->Cell(44, 5, 'Total', 1, 0, 'L');    $pdf->Cell(17, 5, '3', 1, 0, 'L');    $pdf->Cell(19, 5, '', 1, 0, 'L');    $pdf->Cell(14, 5, '2500', 1, 0, 'L');    $pdf->Cell(86, 5, '', 1, 0, 'L');    $pdf->ln(20);       $pdf->SetXY(10, 145);    $pdf->MultiCell(90, 10, 'Declarations: 1) I/We declare that this invoice shows actual price of the goods and/ or services described and that all particulars are true and correct 2) Subject to the jurisdiction of courts in Mumbai                                                                                                                                                      ', 1, 1);    $pdf->SetXY(100, 145);    $pdf->MultiCell(100, 10, 'Amount Chargeable in Words / Currency: US Dollar Five Thousand Three Hundred and Eighty Five and cents Ninety only', 1, 1);    $pdf->SetXY(100, 165);    $pdf->MultiCell(100, 40, '', 1, 1);    $pdf->SetXY(102, 170);    $pdf->MultiCell(48, 10, 'BANK', 1, 1);    $pdf->SetXY(150, 170);    $pdf->MultiCell(48, 10, 'AXIS BANK', 1, 1);    $pdf->SetXY(102, 180);    $pdf->MultiCell(48, 10, 'ACCOUNT', 1, 1);    $pdf->SetXY(150, 180);    $pdf->MultiCell(48, 10, '916020042297998', 1, 1);    $pdf->SetXY(102, 190);    $pdf->MultiCell(48, 10, 'IFSC CODE', 1, 1);    $pdf->SetXY(150, 190);    $pdf->MultiCell(48, 10, 'UTIB0002811', 1, 1);    $pdf->SetXY(10, 205);    $pdf->MultiCell(90, 10, 'Place : Coimbatore', 1, 1);    $pdf->SetXY(100, 205);    $pdf->MultiCell(100, 10, 'Ceritified that the particulars given above are true and correct', 1, 1);    $pdf->SetXY(10, 215);    $pdf->MultiCell(90, 10, 'Date : 12/11/17', 1, 1);    $pdf->SetXY(100, 215);    $pdf->MultiCell(100, 10, 'for Psmith Procurement Private Limited', 1, 1);    $pdf->SetXY(10, 225);    $pdf->MultiCell(90, 10, 'TOTAL NETT WEIGHT: 22 Kgs', 1, 1);    //$pdf->SetXY(100, 225);    //$pdf->MultiCell(100, 10, '', 1, 1);      $pdf->SetXY(10, 235);    $pdf->MultiCell(90, 10, 'Authorised Signatory', 1, 1);    //$pdf->SetXY(100, 235);    //$pdf->MultiCell(100, 10, '', 1, 1);    $pdf->SetXY(10, 245);    $pdf->MultiCell(190, 10, 'Works: PSMITH PROCUREMENT PRIVATE LIMITED, Plot no. 34, Block B, Industrial Park, Menakuru SEZ, NAIDUPET - 524421, Nellore District, Andhra Pradesh. TIN# 37805792891 Dated 01/08/2016 GSTIN: 37AAICP6784H1ZT', 1, 1);    //$pdf->SetXY(100, 245);    //$pdf->MultiCell(100, 10, '', 1, 1);    //$pdf->SetXY(10, 255);    //$pdf->MultiCell(190, 10, '', 1, 1);    //$pdf->SetXY(100, 255);    //$pdf->MultiCell(100, 10, '', 1, 1);    $pdf->SetXY(10, 265);    $pdf->MultiCell(190, 10, 'CIN NO : U74999TZ2016FTC027717', 1, 1);    //$pdf->SetXY(100, 265);    //$pdf->MultiCell(100, 10, '', 1, 1);    $pdf->ln(10);//    $pdf->tableWidth = array(30, 50, 28, 32, 50);////    $pdf->tableColumns = array('ITEM #', 'DESCRIPTION', 'QTY in Nos.', 'UNIT PRICE in INR', 'TOTAL in INR');////    $pdf->tableAlign = array('L', 'L', 'C', 'R', 'R');////    $pdf->printTableHeader();    $i = 0;//    foreach ($itmgrpdet_details as $itmg) {////        $arr = array($items[$itmg['item_id']], $itmg['item_desc'], $itmg['ordered_qty'], $itmg['unit_cost'], $itmg['line_total']);////        $pdf->printTableRow($arr);////        $i++;//    }//    for ($i = $i; $i < 2; $i++) {////        $arr = array('', '', '', '', '');////        $pdf->printTableRow($arr);//    }////     $pdf->ln(10);////    $pdf->SetFont('Verdana', '', 8);//    //   //    $pdf->MultiCell(80, 5, 'Cost for only Casting. Ex.works Price. For 16" weight based on exisitng available weight. For 18", 20" and 30" Parts weights are calculated based on limited samples. This will be corrected after average weight taken for 5 Nos. from your production.', 1, 'L', 0);//    $pdf->SetX(100);//    $pdf->MultiCell(80, 5, 'Cost for only Casting. Ex.works Price. For 16" weight based on exisitng available weight. For 18", 20" and 30" Parts weights are calculated based on limited samples. This will be corrected after average weight taken for 5 Nos. from your production.', 1, 'L', 0);//////    $pdf->SetX(118);    //   $pdf->SetFont('Verdana', '', 8);//    $pdf->Cell(32, 5, 'SUBTOTAL', 1, 0, 'L');////    $pdf->Cell(50, 5, 'zzzzzzzzzzz', 1, 0, 'R');//    $pdf->ln(5);////    $pdf->SetFont('Verdana-Bold', '', 8);////    $pdf->Cell(80, 10, 'Comments or Special Instructions', 1, 0, 'L', 1);////    $y = $pdf->y;//    $pdf->SetXY(118, $y);////    $pdf->MultiCell(32, 5, 'Excise Duty', 0, 'L', 0);////    $y1 = $pdf->y;////    $pdf->SetXY(150, $y);////    $pdf->Cell(50, 10, '', 1, 0, 'R');////    $pdf->ln(5);////    $pdf->y = $y1;////////    $pdf->SetX(118);////    $pdf->Cell(32, 5, '', 0, 0, 'L');////    $pdf->Cell(50, 5, '', 1, 0, 'R');////    $pdf->ln(5);//    $pdf->SetX(118);////    $pdf->Cell(32, 5, 'VAT@ 5%', 1, 0, 'L');////    $pdf->Cell(50, 5, $y, 1, 0, 'R');////    $pdf->ln(5);////////    $pdf->SetX(118);////    $pdf->Cell(32, 5, 'VAT@ 5%', 1, 0, 'L');////    $pdf->Cell(50, 5, $x, 1, 0, 'R');////    $pdf->ln(5);////////    $pdf->SetX(118);////    $pdf->Cell(32, 5, 'TOTAL', 1, 0, 'L');////    $pdf->SetFont('Verdana-Bold', '', 8);////    $pdf->Cell(50, 5, '', 1, 0, 'R');////    $pdf->ln(5);//    $pdf->SetX(118);////    $pdf->Cell(32, 5, 'Round Off', 0, 0, 'L');////    $pdf->Cell(50, 5, '', 1, 0, 'R');////    $pdf->ln(5);////    $pdf->SetFont('Verdana', '', 8);////    $pdf->SetX(150);////    $pdf->Cell(50, 5, 'Authorized Signatory', 0, 0, 'L');////    $pdf->ln(5);////    $pdf->SetX(118);////    $pdf->Cell(82, 5, date('m/d/Y'), 0, 0, 'R');//    $pdf->ln(8);////    $pdf->SetX(125);////    $pdf->SetFont('Verdana', '', 18.5);////    $pdf->Cell(10, 5, 'X', 0, 0, 'L');////    $pdf->Line(120, $pdf->y + 8, 190, $pdf->y + 8);////    $pdf->ImagePngWithAlpha(dirname($pdf->maindir) . '/assets/images/pl_invt_sign.png', 131, $pdf->y - 7, 50);////    $pdf->ln(10);////    $pdf->PageBreakTrigger = 290;    ////    $pdf->SetX(130);////    $pdf->SetFont('Verdana', '', 6);////    $pdf->Cell(10, 3, 'P. PUGALENTHI', 0, 0, 'L');////    $pdf->ln(3);////    $pdf->SetX(130);////    $pdf->Cell(10, 3, 'Assitant Manager-Supply Chain', 0, 0, 'L');////    $pdf->ln(3);////    $pdf->ln(15);    $monYer = str_replace(' ', '', $monYer);    //$filenamec="Payslip_$monYer.pdf";    $filenamec = "invoice_3PL_1718_$shipment_id.pdf";    $filePath = dirname($maindir) . "/" . $filenamec;    if ($download == 1) {        $data = dirname($maindir) . "/data/export/" . session_id() . "/";        //$rapper->makedirectory($data);        $name = preg_replace("/[^a-zA-Z0-9]/", "", $employee_name);        $filenamec = "{$name}_$monYer.pdf";        $filePath = $data . "/" . $filenamec;    }    $pdf->Output($filePath, 'F');    $filePath = $filenamec; //"Payslip_$monYer.pdf";    $result = array('status' => 'success', 'file' => $filePath . '?' . uniqid());}//else{    //$result = array('status'=>'failure','file'=>'', 'message'=>'No records found');}$file = "../../../$filenamec";$pdf->Output();//header("location:$file");//$len = filesize($file); // Calculate File Size////ob_clean();////header("Pragma: public");////header("Expires: 0");////header("Cache-Control: must-revalidate, post-check=0, pre-check=0");////header("Cache-Control: public");////header("Content-Description: File Transfer");////header("Content-Type:application/pdf"); // Send type of file////$header = "Content-Disposition: attachment; filename=$filenamec;"; // Send File Name////header($header);////header("Content-Transfer-Encoding: binary");////header("Content-Length: " . $len); // Send File Size////readfile($file);////exit;//echo json_encode($result);?>