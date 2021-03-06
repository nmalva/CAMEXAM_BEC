<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
//date_default_timezone_set('Europe/London');


require_once ("../classes/class.utiles.php");
require_once ("../classes/PHPExcel2/Classes/PHPExcel.php");

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/*VARIABLES*/
$get_exa_id=$_GET["exa_id"];
$file_name=get_filename($get_exa_id);



/*PHP FUNCTIONS*/
function get_filename($get_exa_id){
    $class_bd=new bd();
    $class_utiles= new utiles();
    $sql = "SELECT * FROM Exam INNER JOIN TypeExam ON Exam.tye_id=TypeExam.tye_id WHERE exa_id='{$get_exa_id}'";
    $resultado=$class_bd->ejecutar($sql);
    $r=$class_bd->retornar_fila($resultado);
    $string.=$r["tye_name"]."-";
    $string.=$class_utiles->fecha_mysql_php_export($r["exa_date"]);
    return ($string);
}


/*OBJECTS*/
$objPHPExcel = new PHPExcel();
$class_bd= new bd();
$class_utiles=new utiles();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Waraexam")
							 ->setLastModifiedBy("Waraexam")
							 ->setTitle("List of Candidate")
							 ->setSubject("List of Candidates")
							 ->setDescription("List of Candidates")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


 
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Candidate number*')
            ->setCellValue('B1', 'First name*')
            ->setCellValue('C1', 'Last name')
            ->setCellValue('D1', 'AIC Required')
            ->setCellValue('E1', 'Gender')
            ->setCellValue('F1', 'Candidate type*')
            ->setCellValue('G1', 'Preparation centre')
            ->setCellValue('H1', 'Packing code')
            ->setCellValue('I1', 'Date of birth*')
            ->setCellValue('J1', 'Country code')
            ->setCellValue('K1', 'Area code')
            ->setCellValue('L1', 'Contact number')
            ->setCellValue('M1', 'Mobile number')
            ->setCellValue('N1', 'Email address')
            ->setCellValue('O1', 'ID number')
            ->setCellValue('P1', 'Campaign code')
            ->setCellValue('Q1', 'Address line1')
            ->setCellValue('R1', 'Address line2')
            ->setCellValue('S1', 'City')
            ->setCellValue('T1', 'Post/Area code')
            ->setCellValue('U1', 'Country');

// Miscellaneous glyphs, UTF-8


$sql = "SELECT * FROM Candidate 
        INNER JOIN PrepCentre ON Candidate.prc_id=PrepCentre.prc_id
        INNER JOIN ExamPlace ON Candidate.exp_id=ExamPlace.exp_id
        LEFT JOIN ExamPlaceAula ON Candidate.epa_id=ExamPlaceAula.epa_id
        WHERE exa_id='{$get_exa_id}' AND can_status='2'
        ORDER BY Candidate.can_candidatenum ASC, ExamPlace.exp_name ASC, PrepCentre.prc_name ASC, Candidate.can_lastname ASC"; // can_status=1 --> confirmed
$resultado=$class_bd->ejecutar_charset($sql);
$i=2;
while ($line = $class_bd->retornar_fila($resultado)){
    $gender=($line["can_gender"]==0 ? "Female" : "Male");
    $date= $class_utiles->fecha_mysql_php($line["can_datebirth"]);
    $can_candidate_type=($line["can_candidatetype"]==1 ? "Internal" : "External");
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$i,$line["can_candidatenum"])
    ->setCellValue('B'.$i,$line["can_firstname"])
    ->setCellValue('C'.$i,$line["can_lastname"])
    ->setCellValue('D'.$i,"")
    ->setCellValue('E'.$i,$gender)
    ->setCellValue('F'.$i,$can_candidate_type)
    ->setCellValue('G'.$i,$line["prc_name"])
    ->setCellValue('H'.$i,$line["epa_packingcode"]) //before  antes ->setCellValue($line["can_packingcode"],false, $class_excelexport_int);
    ->setCellValue('I'.$i,$date)
    ->setCellValue('J'.$i,"")
    ->setCellValue('K'.$i,"")
    ->setCellValue('L'.$i,"") //se saco por error (int)$line["can_telephone"]
    ->setCellValue('M'.$i,"") // se saco por error $line["can_cellphone"]
    ->setCellValue('N'.$i,$line["can_email"])
    ->setCellValue('O'.$i,$line["can_dni"]) //before  ->setCellValue($line["can_dni"],false, $class_excelexport_int);
    ->setCellValue('P'.$i,"")
    ->setCellValue('Q'.$i,"")
    ->setCellValue('R'.$i,"")
    ->setCellValue('S'.$i,"")
    ->setCellValue('T'.$i,"")
    ->setCellValue('U'.$i,"");
    $i++;
}
 

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("Template");


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$file_name.'_external.xls');
header('Cache-Control: max-age=0');

// If you're serving to IE 9, then the following may be needed
//header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
/*header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0*/

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>