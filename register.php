<?php
static $db;

if(!isset($db)) {
	 
$config = parse_ini_file('confi2132sxds2.ini'); 
$db = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']);
 

 if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
 } else {
	 
?>
  
  
<?php
}
?> 