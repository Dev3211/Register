<?php
require_once 'register.php';


function check_email_address($email) {
return filter_var($email, FILTER_VALIDATE_EMAIL) ? 1 : 0;
}
		
function error($error){
$fullerror = "<center><div id=error><h2>Error:</h2><p>
".$error."</div></center>";
die($fullerror);
}
	
if (isset($_POST['submit'])) {
$username = $_POST["username"];
$password = $_POST["password"];
$email = $_POST["email"];
$password2 = $_POST["password2"];
   
$username = mysqli_real_escape_string($db, $username);
$email = mysqli_real_escape_string($db, $email);
$password = mysqli_real_escape_string($db, $password);
$password2 = mysqli_real_escape_string($db, $password2);

$username = addslashes($username);
$email = addslashes($email);
$password = addslashes($password);
$password2 = addslashes($password2);
 
	  
	  if ($_POST['password']!= $_POST['password2'])
 {
     die("Oops! Password did not match! Try again. ");
 }
 
 
 if(strlen($_POST['password']) <= 3){
error('Sorry, that password was too short.');
}

 
 if(strlen($_POST['username']) <= 3){
error('Sorry, that username was too short.');
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error('Your email is an invalid email address, please recheck!');
}

if(empty($username) || empty($email) || empty($password) || empty($password2))
    {
    error('You did not fill out the required fields');
    die();  
    }

if(preg_match("/([<%\$#\*|>]+)/", $username))
{
   error('Illegal charcters');
}
      $sql = $db->prepare("SELECT email FROM users WHERE email = ?");
	  $sql->bind_param("s", $email);
	  $sql->execute();
	  $sql->store_result();

      if($sql->num_rows == 1) {
       $sql->close(); 
       error('Email already in use!');
    }
	
	  $sql = $db->prepare("SELECT username FROM users WHERE username = ?");
	  $sql->bind_param("s", $username);
	  $sql->execute();
	  $sql->store_result();

      if($sql->num_rows == 1) {
       $sql->close(); 
       error('Username already in use!');
    }
	
   $password = md5($password);

   require_once 'recaptchalib.php';
  $privatekey = "6LeaXSITAAAAAPJMKEsZPUS07yhrT41s0mPwiSvJ";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
         "(reCAPTCHA said: " . $resp->error . ")");
     } else {
       $query = $db->prepare("INSERT INTO users (`username`,`email`, `password`)VALUES (?, ?, ?)");
       $query->bind_param("sss", $username, $email, $password);
       $query->execute();
       $query->close();
}	   
       if($query) {
          echo "Thank You, your username is $username and your password is $password2";
       }
  
} else {
 
?>
 
<html>
<title> Welcome </title>
<body>
<link rel="stylesheet" href="css/style.css">
</body>		
<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
<fieldset>
<img src="http://putyourlegohere">
<tr>
<label for="username">Username</label></div></td>
<td><input name="username" type="username" class="input" size="25" 
</tr>
<tr>
<label for="email">Email</label></div></td>
<td><input name="email" type="email" class="input" size="25" 
</tr>
<tr>
<label for="password">Password</label></div></td>
<td><input name="password" type="password" class="input" size="25" 
</tr>
<label for="password">Re-enter your password</label></div></td>
<td><input name="password2" type="password" class="input" size="25" 
</tr>
<?php
          require_once('recaptchalib.php');
          $publickey = "6LeaXSITAAAAAGU8qEcGAXMZMW_-suusfEZHKM55"; // you got this from google recapatha's page.
          echo recaptcha_get_html($publickey);
        ?>
<tr>
<td height="23"></td>
<td><div align="right">
  <input type="submit" name="submit" value="Register!" />
</div></td>
</tr>
</table>
</fieldset>
</form>
</html> 
 <?php
}
?> 
