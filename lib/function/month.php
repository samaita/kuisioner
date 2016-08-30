<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
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
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
// echo file_get_contents('php://input');
// $data = json_decode(file_get_contents('php://input'), true);
// print_r($data);
// echo $data['nama'];

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Telkom Jakarta Barat")
							 ->setLastModifiedBy("Telkom Jakarta Barat")
							 ->setTitle("Laporan Kuisioner")
							 ->setSubject("Laporan Kuisioner")
							 ->setDescription("Laporan Kuisioner")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");


include('config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";

//$dateentry1 = date("Y-m").'-'. date("d");

$day = date("d");
$month = date("m");
$year = date("Y");
$dayweek = date("d")-7;

if($dayweek <= 0){
    $month = $month-1;
    switch ($month) {
        case 1:
            $totalday = 31;
            $dayis = $totalday - $dayweek;
            break;
        case 2:
            if($year%4==0){
                $totalday = 29;
            }else{
                $totalday = 28;
            }
            $dayis = $totalday - $dayweek;
            break;
        case 3:
            $totalday = 31;
            $dayis = $totalday - $dayweek;
            break;
        case 4:
            $totalday = 30;
            $dayis = $totalday - $dayweek;
            break;
        case 5:
            $totalday = 31;
            $dayis = $totalday - $dayweek;
            break;
        case 6:
            $totalday = 30;
            $dayis = $totalday - $dayweek;
            break;
        case 7:
            $totalday = 31;
            $dayis = $totalday - $dayweek;
            break;
        case 8:
            $totalday = 31;
            $dayis = $totalday - $dayweek;
            break;
        case 9:
            $totalday = 30;
            $dayis = $totalday - $dayweek;
            break;
        case 10:
            $totalday = 31;
            break;
        case 11:
           $totalday = 30;
           $dayis = $totalday - $dayweek;
            break;
        case 12:
            $totalday = 31;
            $dayis = $totalday - $dayweek;
            break;   
        case 0:
            $month = 12;
            $totalday = 31;
            $dayis = $totalday - $dayweek;
            $year = $year-1;
            break;                           
        default:
            break;
    }
}else{
    $dayis = $dayweek;
}

$dateentry = date("Y-m").'-%';

// $dateentry = date("Y-m").'-'. (date("d")-7);
//$dateentry2 = date("Y-m").'-'. (date("d"));

//$dateentry = mktime(0,0,0,  date("d")+7,  date("m"), date("Y"));

$sql = "SELECT `nama`, `nomor_kontak`, `kritik_saran`, `nilai_plasa`, `pasang_indihome`, `entry_datetime` FROM `user_entries` WHERE blocked = 0 AND entry_datetime LIKE '". $dateentry."'";
$result = $conn->query($sql);
echo $sql;

        $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Tanggal')
            ->setCellValue('C1', 'Nama')
            ->setCellValue('D1', 'Nomor Kontak')
            ->setCellValue('E1', 'Penilaian')
            ->setCellValue('F1', 'Indihome')
            ->setCellValue('G1', 'Kritik Saran');

if ($result->num_rows > 0) {
       $counter = 1;
    $i = 2;

    while($row = $result->fetch_assoc()) {

        $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $counter)
            ->setCellValue('B'.$i, $row["entry_datetime"])
            ->setCellValue('C'.$i, $row["nama"])
            ->setCellValue('D'.$i, $row["nomor_kontak"])
            ->setCellValue('E'.$i, $row["nilai_plasa"])
            ->setCellValue('F'.$i, $row["pasang_indihome"])
            ->setCellValue('G'.$i, $row["kritik_saran"]);
            $counter++;
            $i++;
    }
} else {
    echo "0 results";
}


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Laporan Kuisioner '.date("Y-m"));


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 95 file
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

echo 'Success';
