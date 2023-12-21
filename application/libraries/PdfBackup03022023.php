<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }

    public function generate_pdf($invoice, $download= false) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        if ( $invoice == null ) {

        }
        
        // die($invoice->discount);
        // WL_DUMP($invoice->discount);
        if ( isset($invoice->discount) && $invoice->discount == 0 ) {
            $fileTemplate = "template_without_discount.jpg";
            $minHeight = floatval("-4.3");
            $minHeightName = floatval("-4.85");
            $discount = 0;
        }
        else {
            $fileTemplate = "template_with_discount.jpg";
            $minHeight = 0;
            $minHeightName = 0;
            $discount = $invoice->discount;
        }
     
        if($invoice->order_name!=null) {
            $invoice->name = ucwords($invoice->order_name);
        } else {
            $invoice->name = $invoice->title;
        }
        // $font = "Helvetica";
        $font = "tcm";

        $pdf->SetAuthor('Peponi Travel');
        $pdf->SetTitle('Invoice ' . $invoice->id);

        // set default header data
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 009', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->AddPage();
        $pdf->setJPEGQuality(100);
        // Example of Image from data stream ('PHP rules')
        $imgdata = base64_decode('iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABlBMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDrEX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==');

        // The '@' character is used to indicate that follows an image data stream and not an image file name
        // $pdf->Image('@'.$imgdata);
        $x = "4";
        $y = 0;
        $w = "240";
        $h = "auto";
        $pdf->Image(FCPATH.'asset/pdf/'.$fileTemplate, $x, $y, $w, $h, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox=false, false, false);

        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        $pdf->SetFont($font, 'B', 15);
        // $pdf->SetDrawColor(0, 50, 0, 0);
        // $pdf->SetFillColor(0, 100, 0, 0);
        $pdf->SetTextColor(0, 0, 0); // Green
        $pdf->Text(142, "23.3",  $invoice->invoice_header);
        $pdf->SetTextColor(89, 0, 67, 16); // Green
        $pdf->Text(58, "22.7", $invoice->id);
        
        $pdf->Text(120, "34.3", "IDR");
        $pdf->MultiCell("41.5", "30.8", $this->convert_currency($invoice->total-$discount), 1, "R", 1, 0, 154, "34.3");
        
        $pdf->SetFont($font, 'B', 8);
        $pdf->SetTextColor(0,0,0,100); //Black
        if ($invoice->invoice_header=="INVOICE" || $invoice->invoice_header=="Invoice"){
            $pdf->Text(15, "39.0", "Jatuh Tempo : ");
            $pdf->Text(32, "39.0", date("d M Y \J\a\m H:i ", strtotime($invoice->due_date)));
        }

        $pdf->SetFont($font, 'B', 11);
        $pdf->Text("15.5", "53.5", $invoice->name);

        $pdf->SetFont($font, '', "9");
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        $pdf->Text("18.4", "78.5", $invoice->quantity);

        // $pdf->Text("57.2", "75.8", $invoice->detail);
        $pdf->setCellHeightRatio("1.47");
        $pdf->MultiCell("70.5", "90.8", $invoice->description, 1, "L", 1, 0, "57.2", "77.8");
        $pdf->Text("128.4", "77.8", $this->convert_currency($invoice->price));        

        $pdf->MultiCell("40.5", "30.8", $this->convert_currency($invoice->total-$discount), 1, "R", 1, 0, "151.8", "77.8"); //"178.5", "75.8"
        if ( $discount > 0 )
            $pdf->MultiCell("41.5", "30.8", $this->convert_currency($discount), 1, "R", 1, 0, "151.8", "148.5");
        $pdf->MultiCell("41.5", "30.8", $this->convert_currency($invoice->total-$discount), 1, "R", 1, 0, "151.8", floatval("152.9")+$minHeight );
        $pdf->MultiCell("41.5", "30.8", $invoice->tax, 1, "R", 1, 0, "151.8", floatval("157.4")+$minHeight);
        $pdf->SetFont($font, 'B', "10.65");
        $pdf->MultiCell("41.5", "30.8", $this->convert_currency($invoice->total-$discount), 1, "R", 1, 0, "151.8", floatval("164.2")+$minHeight );

        $pdf->SetFont($font, 'B', "10.4");
        $pdf->MultiCell("51.5", "30.8", $invoice->name, 0, "C", 0, 0, "62.5", floatval("195.5")+$minHeightName); //

        // $pdf->Cell(0, 0, 'TEST CELL STRETCH: scaling', 1, 1, 'C', 0, '', 1);
        // $pdf->Cell(0, 0, 'TEST CELL STRETCH: force scaling', 1, 1, 'C', 0, '', 2);
        // $pdf->Cell(0, 0, 'TEST CELL STRETCH: spacing', 1, 1, 'C', 0, '', 3);
        // $pdf->Cell(0, 0, 'TEST CELL STRETCH: force spacing', 1, 1, 'C', 0, '', 4);
        //
        // $pdf->Ln(5);
        //
        // $pdf->Cell(45, 0, 'TEST CELL STRETCH: scaling', 1, 1, 'C', 0, '', 1);
        // $pdf->Cell(45, 0, 'TEST CELL STRETCH: force scaling', 1, 1, 'C', 0, '', 2);
        // $pdf->Cell(45, 0, 'TEST CELL STRETCH: spacing', 1, 1, 'C', 0, '', 3);
        // $pdf->Cell(45, 0, 'TEST CELL STRETCH: force spacing', 1, 1, 'C', 0, '', 4);
        if ( $invoice->invoice_header == "RECEIPT" ){
            $pdf_type = "receipt";
            $pdf->SetFont($font, 'B', 12);
            $status = "PAID";
            $pdf->SetTextColor(89, 0, 67, 16);
            $pdf->Text(27, "32.1", $status);
        }
        else {
            $pdf_type = "invoice";
            $pdf->SetFont($font, 'B', 12);
            $status = "UNPAID";
            $pdf->SetTextColor(0, 100, 100, 0); // Red
            $pdf->Text(27, "32.1", $status);
        }
            
        $pdf->Output( FCPATH . 'asset/pdf/'.$pdf_type.'.pdf', 'F');

        if ( !$download)
            return FCPATH . 'asset/pdf/'.$pdf_type.'.pdf';
        else
            $pdf->Output( $pdf_type . '.pdf', 'I');
        // return FCPATH . 'asset/pdf/'.$pdf_type.'.pdf';
        // $pdf->Output( $pdf_type . '.pdf', 'I');
        // echo "work!";
    }

    public function convert_currency($number){
        $formatted = number_format($number,0);
        $withRp = 'Rp '.$formatted;
        return $withRp;
    }
}
