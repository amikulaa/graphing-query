<?php
require_once("fpdf/fpdf.php");

class PDF extends FPDF
{
    
    // Page header
    function Header()
    {
        $year = Date('Y');
        $font = 'Times';
        $font1 = 16;
        $font2 = 12;
        $font3 = 10;
        $font4 = 9;
        
        $this->Image(LOGO, 15, 10, 40);
        $this->SetFont($font, '', $font3);
        
        // title page header
        $this->SetFont($font, 'B', $font1);
        $this->Cell(0, 10, $year . ' Tracking Report', 0, 1, 'C');
        $this->Ln(2);
        $this->SetFont($font, 'B', $font3);
        $this->Cell(0, 0, 'Jefferson County Police Department', 0, 1, 'C');
        $this->SetFont($font, '', $font3);
        $this->Cell(0, 10, '411 S. Center Avenue, Room 114', 0, 1, 'C');
        $this->Cell(0, 0, 'Jefferson, WI 53549', 0, 1, 'C');
        $this->Cell(0, 10, 'Phone: (920) 674 - 7310', 0, 1, 'C');
        $this->SetY(10);
        $this->SetX(160);
        $this->Write(10, 'Date: ');
        $this->SetFont($font,'U', $font3);
        $this->Write(10, date('M d, Y'));
        $this->SetY(60);
    }
    
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}
$year = Date('Y');
$font = 'Times';
$font1 = 16;
$font2 = 12;
$font3 = 10;
$font4 = 9;
$curr_table = '';
$last_line = '';

// add new page for all results returned
$pdf = new PDF();
$pdf->SetTitle($year . ' Report');
$pdf->AliasNbPages();
$pdf->AddPage();

// create table
$by_table = explode(">", $html_table);
$pdf->SetFont($font,'', $font3);
for($i = 0; $i < count($by_table); $i++){
        $line = $by_table[$i];
        $line = str_replace("⊆", "{}", $line);
        if($last_line == 'BREAKPAGE' && trim($line) != null){
            $pdf->AddPage();
        }
        if(strpos($line, 'table') != false){   
            $pdf->Cell(0, 1, '', 1, 2, 'C', true);
            $last_line = $line;
        } else if(strpos($line, 'span') != false){
            $new_line = explode("<span", $line)[0];
            $new_line = explode("<", $new_line)[0];
            $new_line = explode("</", $new_line)[0];
            if($new_line != null){
                // span + string = subset header
                $pdf->SetFont($font, 'B', $font2);
                $pdf->Cell(150, 10, $new_line, 1, 0, 'L');
            }
            $last_line = $line;
        } else if(strpos($line, 'th') != false){
            $new_line = explode("<th", $line)[0];
            $new_line = explode("<", $new_line)[0];
            $new_line = explode("</", $new_line)[0];
            if($new_line != null){
                if(filter_var($new_line, FILTER_VALIDATE_INT)){
                    // th + int = total count of "type", etc.
                    $pdf->SetFont($font, '', $font2);
                    $pdf->Cell(40, 10, $new_line, 1, 1, 'R');
                } else {
                    // th + string = table title
                    $curr_table = $new_line;
                    $pdf->SetFont($font, 'B', $font2);
                    $pdf->Cell(0, 10, $curr_table, 1, 1, 'L');
                }
            }
            $last_line = $line;
        } else if(strpos($line, 'td') != false){
            $new_line = explode("<td", $line)[0];
            $new_line = explode("<", $new_line)[0];
            $new_line = explode("</", $new_line)[0];
            if($new_line != null){
                if(filter_var($new_line, FILTER_VALIDATE_INT)){
                    if(strpos($last_line, 'count') != false){
                        // td + int = count
                        $pdf->SetFont($font, '', $font2);
                        $pdf->Cell(40, 10, $new_line, 1, 1, 'R');
                    } else {
                        // td + string = subset header
                        $pdf->SetFont($font, 'B', $font2);
                        $pdf->Cell(150, 10, $new_line, 1, 0, 'R');
                    }
                } else {
                    // td + string = subset header
                    $pdf->SetFont($font, 'B', $font2);
                    $pdf->Cell(150, 10, $new_line, 1, 0, 'R');
                }
            }
            $last_line = $line;
        } else if(strpos($line, 'br') != false){
            $last_line = 'BREAKPAGE';
        }
}

//save and output
ob_end_clean();
$pdf->Output('I', date('Y_m_d') . '_table.pdf');
?>