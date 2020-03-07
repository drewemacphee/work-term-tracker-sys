<?php
require('fpdf.php');
class ReportPDF extends FPDF
{
 
  // OVERRIDE empty Header() implementation found in FPDF class 
  // Gets called by addPage() -- should not be called directly in application. 
 
  function Header()
  {
    //Get top margin
    $headerY = $this->GetY();
    //Get date
    $date = date('Y-m-d');
    // Logo
    $this->Image('nscc_logo.png',10,0,30);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->SetFont('Arial','B',20);
    $this->Cell(30,8,$_SESSION['reportTitle'],0,2,'C');
    // Sub-title
    $this->SetFont('Arial','B',11);
    $this->Cell(30,8,'Work Term Employer Tracker - NSCC Marconi Campus',0,0,'C');
    // Date
    $middleX = $this->GetX();
    $this->Cell(30);
    $this->SetFont('Arial','B',13);
    $this->SetY($headerY);
    $this->SetX($middleX + 30);
    $this->MultiCell(60,6,$date,0,'C');
    // Line break
    $this->Ln(20); 
  }
  function makeMasterReport($header, $data)
  { 
      $this->SetAutoPageBreak(false);

      $this->SetFontSize(17);    
      $this->SetX(0);
      $this->SetY(35);
      // Column widths
      $w = array(70, 70, 70);
      // Header
      for($i=0;$i<count($header);$i++)
      // I want a table with no lines
                      // height, width, string, no border, no CR, align ('C' = center)
      $this->Cell($w[$i],7,$header[$i],0,0,'LR');
      $this->Ln();
      $this->Ln();
      
      foreach($data as $row)
      { 
        //smaller font for report body 
        $this->SetFont('Arial','',11);
        // build strings for this row
        //Comp. Info string
        $companyInfo = "ID: ".$row[0]."\n"."Company: ".$row[1]."\n"."Category: ".$row[2]; 
        
        //Contact String
        $contactInfo = $row[3]."\n".$row[4]."\n".$row[5]; 
        //Address String
        $addressInfo = $row[6]."\n".$row[7]."\n".$row[8]."\n".$row[9];
        // Company Pseudo Row
        $current_y = $this->GetY();
        $current_x = $this->GetX();
        $this->MultiCell(70, 6, $companyInfo, 0, 'L');
        $end_y = $this->GetY();
        // Contact Pseudo Row
        $current_x = $current_x + 70;
        $this->SetXY($current_x, $current_y);
        $this->MultiCell(70, 6, $contactInfo, 0, 'L');
        $end_y = ($this->GetY() > $end_y)?$this->GetY() : $end_y;
        // Address Pseudo Row
        $current_x = $current_x + 70;
        $this->SetXY($current_x, $current_y); 
        $this->MultiCell(70, 6, $addressInfo, 0, 'L');
        // IF this cells height is greater than the two previous,
        // set the endY to this value so the next line starts at correct Y location
        $end_y = ($this->GetY() > $end_y)?$this->GetY() : $end_y;
        
        //padding between rows is 10
        $this->SetY($end_y + 10);
        // Check for page break -- 297 is A4 page height, and the Y height of the footer is -15
        // 24mm is an unfair estimation of the maximum possible cell height
        // There are better fixes for this but I don't have time
        if($this->GetY() + 24 > 297 - 15){
          $this->AddPage();
        }
      }
      // Closing line 
      $this->Cell(array_sum($w),0,'','T');
  }

  function makeDetailedReport($header, $data)
  { 
      $this->SetAutoPageBreak(false);

      $this->SetFontSize(17);    
      $this->SetX(0);
      $this->SetY(35);
      // Column widths
      $w = array(55, 55, 47, 55);
      // Header
      for($i=0;$i<count($header);$i++)
      // I want a table with no lines
                      // height, width, string, no border, no CR, align ('C' = center)
      $this->Cell($w[$i],7,$header[$i],0,0,'LR');
      $this->Ln();
      $this->Ln();
      
      foreach($data as $row)
      { 
        //smaller font for report body   
        $this->SetFont('Arial','',11);
        // build strings for this row
        //Comp. Info string
        $companyInfo = "ID: ".$row[0]."\n"."Company: ".$row[1]."\n"."Category: ".$row[2]; 
        //Contact String
        $contactInfo = $row[3]."\n".$row[4]."\n".$row[5]; 
        //Address String
        $addressInfo = $row[6]."\n".$row[7]."\n".$row[8]."\n".$row[9];
        //Rating String
        $ratingInfo ="Rating: ".$row[11]."\n"."Skills: ".$row[10]."\n"."Number of Terms: ".$row[12];
        // Company Pseudo Row
        $current_y = $this->GetY();
        $current_x = $this->GetX();
        $this->MultiCell(45, 6, $companyInfo, 0, 'L');
        $end_y = $this->GetY();
        // Contact Pseudo Row
        $current_x = $current_x + 55;
        $this->SetXY($current_x, $current_y);
        $this->MultiCell(45, 6, $contactInfo, 0, 'L');
        $end_y = ($this->GetY() > $end_y)?$this->GetY() : $end_y;
        // Address Pseudo Row
        $current_x = $current_x + 55;
        $this->SetXY($current_x, $current_y); 
        $this->MultiCell(45, 6, $addressInfo, 0, 'L');
        // Rating Pseudo Row
        $current_x = $current_x + 47;
        $this->SetXY($current_x, $current_y); 
        $this->MultiCell(45, 6, $ratingInfo, 0, 'L');

        // IF this cells height is greater than the two previous,
        // set the endY to this value so the next line starts at correct Y location
        $end_y = ($this->GetY() > $end_y)?$this->GetY() : $end_y;
        
        //padding between rows is 10
        $this->SetY($end_y + 10);
        // Check for page break -- 297 is A4 page height, and the Y height of the footer is -15
        // 24mm is an unfair estimation of the maximum possible cell height
        // There are better fixes for this but I don't have time
        if($this->GetY() + 24 > 297 - 15){// check page break with a variable so this is reliable -- somehow it currently seems reliable...
          $this->AddPage();
        }
      }
      // Closing line 
      $this->Cell(array_sum($w),0,'','T');
    }
    /*
    //
    N.B. Feedback should be easy -- but do I have to read the height of incoming comment before rendering? 
    //
    */
  function makeFeedbackReport($header, $data)
  { 
      $this->SetAutoPageBreak(false);

      //create feedback report name string
      $this->SetY(28);
      //row describing company name 
      $this->Cell(0,8,'Feedback Report For '.$_SESSION['company_feedback_name'],0,0,'C');

      $this->SetFontSize(17);    
      $this->SetX(0);
      $this->SetY(40);
      // Column widths
      //small 20 mm rating column and remainder for comments. 
      $w = array(50, 0);
      // Header
      for($i=0;$i<count($header);$i++)
      // I want a table with no lines
                      // height, width, string, no border, no CR, align ('C' = center)
      $this->Cell($w[$i],7,$header[$i],0,0,'L');
      $this->Ln();
      $this->Ln();
      
      foreach($data as $row)
      { 
        //smaller font for report body   
        $this->SetFont('Arial','',13);
               
        $current_y = $this->GetY();
        $current_x = $this->GetX();
        $this->MultiCell(20, 6, $row[0], 0, 'C');
        $end_y = $this->GetY();
        // Feedback
        $current_x = $current_x + 50;
        $this->SetXY($current_x, $current_y);
        $this->MultiCell(0, 6, $row[1], 0, 'L');
        $end_y = ($this->GetY() > $end_y)?$this->GetY() : $end_y;
      
        // IF this cells height is greater than the two previous,
        // set the endY to this value so the next line starts at correct Y location
        $end_y = ($this->GetY() > $end_y)?$this->GetY() : $end_y;
        
        //padding between rows is 10
        $this->SetY($end_y + 10);
        // Check for page break -- 297 is A4 page height, and the Y height of the footer is -15
        // 24mm is an unfair estimation of the maximum possible cell height
        // There are better fixes for this but I don't have time
        if($this->GetY() + 24 > 297 - 15){
          $this->AddPage();
        }
      }
  }

  function Footer()
  {
    // Go to 1.5 cm from bottom
    $this->SetY(-15);
    // Select Arial italic 8
    $this->SetFont('Arial','I',8);
    // Print centered page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
  }
      
}