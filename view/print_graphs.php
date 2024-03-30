<?php
require_once("fpdf/fpdf.php");
class PDF extends FPDF{  
    function Header(){
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
        $this->Cell(0, 10, 'Graphical Analysis', 0, 1, 'C');
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
    function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// begin report
$year = Date('Y');
$font = 'Times';
$font1 = 16;
$font2 = 12;
$font3 = 10;
$font4 = 9;
$curr_table = '';
$last_line = '';
$graph_width = 180;
$x = 70;
// add new page for all results returned
$pdf = new PDF();
$pdf->SetTitle($year . ' Graphical Analysis');
$pdf->AliasNbPages();
$pdf->AddPage();
for ($i = 0; $i < count($curr_graphs); $i ++) {
    $TEMPIMGLOC = 'temp' . $i . '.png';
    $data_uri = explode('WIDTH', $curr_graphs[$i])[0];
    $graph_width = explode('WIDTH', $curr_graphs[$i])[1] == 800 ? 180 : 100;
    $x = explode('WIDTH', $curr_graphs[$i])[1] == 800 ? null : 50;
    $data_pieces = explode(',', $data_uri);
    $encoded_img = $data_pieces[1];
    $decoded_img = base64_decode($encoded_img);
    if ($decoded_img != false) {
        // Save image to a temporary location
        if (file_put_contents($TEMPIMGLOC, $decoded_img) != false) {
            //  ->Image(FILENAME, X, Y, WIDTH, HEIGHT, TYPE URL);
            $pdf->Image($TEMPIMGLOC, $x, null, $graph_width);
            unlink($TEMPIMGLOC);
        }
    }
}

//save and output
ob_end_clean();
$pdf->Output('I', date('Y_m_d') . '_graphs.pdf');
?>