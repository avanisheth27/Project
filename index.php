<?php
ini_set('display_errors', 'On');                            
error_reporting(E_ALL);
class Manage {                                              
public static function autoload($class) {
include $class . '.php';
}
}
spl_autoload_register(array('Manage', 'autoload'));
$obj = new main();                                           
class main {
public function __construct() {
$pageRequest = 'uploadForm';
if(isset($_REQUEST['page'])) {
$pageRequest = $_REQUEST['page'];
}
$page = new $pageRequest;
if($_SERVER['REQUEST_METHOD'] == 'GET') {
$page->get();
} else {
$page->post();
}
}
}
abstract class page {
protected $html;
public function __construct() {
$this->html .= '<html>';
$this->html .= '<link rel="stylesheet" href="styles.css">';
$this->html .= '<body>';
}
public function __destruct() {
$this->html .= '</body></html>';
stringFunctions::printThis($this->html);
}
public function get() {
echo 'default get message';
}
public function post() {
print_r($_POST);
}
}
class stringFunctions {             
static public function printThis($inputText) {
return print($inputText);
}
}
class uploadForm extends page
{
public function get() {

    $form = '<form action="index.php?page=uploadForm" method="POST" enctype="multipart/form-data">';
$form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
$form .= '<input type="submit" value="Upload CSV" name="submit">';
$form .= '</form>';
 $this->html .= htmlTags::headingOne('Upload Form');
$this->html .= $form;
}
public function post() {                         
$target_dir = "uploads/";
$target_file = $target_dir . $_FILES["fileToUpload"]["name"];
$filename = $_FILES["fileToUpload"]["name"];
if (file_exists($target_file)) {
unlink($target_file);
}
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
header("Location: index.php?page=htmlTable&filename=$filename");
}
}
}
class htmlTags {                
static public function headingOne($text) {
return '<h1>' . $text . '</h1>';
}
static public function tableFormat() {
echo "<table cellpadding='5px' border='1px' style='border-collapse: collapse'>";
}
static public function tableHeader($text) {
echo '<th style="font-size: large">'.$text.'</th>';
}
static public function tableContent($text) {
echo '<td>'.$text.'</td>';
}
static public function breakTableRow() {
echo '</tr>';
}
}
class htmlTable extends page {                         
public function get() {
 $csv = $_GET['filename'];
chdir('uploads');                                     
$file = fopen($csv,"r");
htmlTags::tableFormat();               
$row = 1;
while (($data=fgetcsv($file))!== FALSE){    
foreach($data as $value) {
if ($row == 1) {
 htmlTags::tableHeader($value);
}else{
htmlTags::tableContent($value);
}
}
$row++;
htmlTags::breakTableRow();
}
fclose($file);
        }
    }
?>