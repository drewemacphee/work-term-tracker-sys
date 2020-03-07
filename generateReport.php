<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('ReportPDF.php');

session_start();

if ($_SESSION['reportTitle'] == 'MASTER REPORT') {
  
  ob_start();
  $data = $_SESSION['data'];
  $_SESSION['reportTitle']='MASTER REPORT';
  $pdf = new ReportPDF();
  $pdf->AddPage();

  $pdf->AliasNbPages();
  $header = array('Company Info', 'Contact', 'Address');
  
  $pdf->makeMasterReport($header, $data);
  $pdf->Output();
  ob_end_flush();  

} elseif ($_SESSION['reportTitle'] == 'DETAILED REPORT') {
  ob_start();
  $data = $_SESSION['data'];
  $_SESSION['reportTitle']='DETAILED REPORT';
  $pdf = new ReportPDF();
  $pdf->AddPage();

  $pdf->AliasNbPages();
  $header = array('Company Info', 'Contact', 'Address', 'Rating');
    
  $pdf->makeDetailedReport($header, $data);
  $pdf->Output();
  ob_end_flush();  

} elseif ($_SESSION['reportTitle'] == 'FEEDBACK REPORT') {
  ob_start();
  //Another array getting all the feedback for a single feedback_id
  //$data = $_SESSION['data'];  
  $data = $_SESSION['feedback'];
  
  $pdf = new ReportPDF();
  $pdf->AddPage();

  $pdf->AliasNbPages();
  $header = array('Rating', 'Comments');
   
  $pdf->makeFeedbackReport($header, $data);
  $pdf->Output();
  ob_end_flush();  
}
?>