
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
echo "<h1><b><center >  Project WSD </h1 ></b></center>  ";


class assignment
{
public static function autoload($class) //goes directly to the class name mentioned in the bracket
{
include $class . '.php';
}
}
spl_autoload_register(array('assignment', 'autoload')); 
$object = new main();
class main
{
public function __construct()
{
$pageRequest = 'uploadForm';
if(isset($_REQUEST['page']))
{
$pageRequest = $_REQUEST['page'];
}
$page = new $pageRequest;
if($_SERVER['REQUEST_METHOD'] == 'GET')
{
$page->get();
} else
{
$page->post();
}
}
}


abstract class page
{
protected $html;
public function __construct()
{
$this->html .= '<html>';
$this->html .= '<link rel="stylesheet" href="styles.css">';
$this->html .= '<body>';
$this->html .= '<center> ';
}



public function __destruct()
{
$this->html .= '</body></html></center > ';
strings::printThis($this->html);
}
public function get()
{
echo 'default get message';
}
public function post()
{
print_r($_POST);
}
}

//creates the table format
class tags
{
static public function headingOne($msg1)
{
return '<h1>' . $msg1 . '</h1>';
}
static public function Format()
{
echo "<table cellpadding='8px' border='2px' style='border-collapse:collapse'>";
}
static public function Row()
{
echo '</tr>';
}
static public function top($msg1)
{
echo '<th style="font-size: large">'.$msg1.'</th>';
}
static public function content($msg1)
{
echo '<td>'.$msg1.'</td>';
}
}

class strings
{
static public function printThis($input)
{
return print($input);
}
}

//Creating the Html form to upload the csv file
class uploadForm extends page
{
public function get()
{
$f = '<form action="index.php?page=uploadForm" method="POST" enctype="multipart/form-data">';
$f .= '<input type="file" name="fileToUpload" id="fileToUpload">';
$f .= '<input type="submit" value="Upload" name="submit">';
$f .= '</form>';
$this->html .= $f;
}


//have to create uploads folder in the current working directory
public function post()
{
$target_dir = "uploads/";
$target_file = $target_dir . $_FILES["fileToUpload"]["name"];
$filename = $_FILES["fileToUpload"]["name"];
if (file_exists($target_file))
{
unlink($target_file);
}
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
{
header("Location: index.php?page=table&filename=$filename");
}
}
}


class table extends page
{
public function get()
{
$csv = $_GET['filename'];
chdir('uploads');
$fil = fopen($csv,"r");
tags::Format();
$ran = 1;
while (($data=fgetcsv($fil))!== FALSE)
{
foreach($data as $val)
{
if ($ran == 1)
{
tags::top($val);
}
else
{
tags::content($val);
}
}
$ran++;
tags::Row();
}
fclose($fil);
}
}
?>
