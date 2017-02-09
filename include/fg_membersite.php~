<?PHP
/*
    Registration/Login script from HTML Form Guide
    V1.0

    This program is free software published under the
    terms of the GNU Lesser General Public License.
    http://www.gnu.org/copyleft/lesser.html


This program is distributed in the hope that it will
be useful - WITHOUT ANY WARRANTY; without even the
implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.

For updates, please visit:
http://www.html-form-guide.com/php-form/php-registration-form.html
http://www.html-form-guide.com/php-form/php-login-form.html

*/
require_once("class.phpmailer.php");
require_once("formvalidator.php");

class FGMembersite
{
    var $admin_email;
    var $from_address;

    var $username;
    var $pwd;
    var $database;
    var $tablename;
    var $connection;
    var $rand_key;

    var $error_message;

    //-----Initialization -------
    function FGMembersite()
    {
        $this->sitename = 'localhost';
        $this->rand_key = '0iQx5oBk66oVZep';
    }

    function InitDB($host,$uname,$pwd,$database,$tablename)
    {
        $this->db_host  = $host;
        $this->username = $uname;
        $this->pwd  = $pwd;
        $this->database  = $database;
        $this->tablename = $tablename;

    }
    function SetAdminEmail($email)
    {
        $this->admin_email = $email;
    }

    function SetWebsiteName($sitename)
    {
        $this->sitename = $sitename;
    }

    function SetRandomKey($key)
    {
        $this->rand_key = $key;
    }

    //-------Main Operations ----------------------
    function RegisterUser()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }

        $formvars = array();

        if(!$this->ValidateRegistrationSubmission())
        {
            return false;
        }

        $this->CollectRegistrationSubmission($formvars);

        if(!$this->SaveToDatabase($formvars))
        {
            return false;
        }

        if(!$this->SendUserConfirmationEmail($formvars))
        {
            return false;
        }

        $this->SendAdminIntimationEmail($formvars);

        return true;
    }

    function ConfirmUser()
    {
        if(empty($_GET['code'])||strlen($_GET['code'])<=10)
        {
            $this->HandleError("Please provide the confirm code");
            return false;
        }
        $user_rec = array();
        if(!$this->UpdateDBRecForConfirmation($user_rec))
        {
            return false;
        }

        $this->SendUserWelcomeEmail($user_rec);

        $this->SendAdminIntimationOnRegComplete($user_rec);

        return true;
    }

    function Login()
    {
        if(empty($_POST['username']))
        {
            $this->HandleError("UserName is empty!");
            return false;
        }

        if(empty($_POST['password']))
        {
            $this->HandleError("Password is empty!");
            return false;
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if(!isset($_SESSION)){ session_start(); }
        if(!$this->CheckLoginInDB($username,$password))
        {
            return false;
        }

        $_SESSION[$this->GetLoginSessionVar()] = $username;

        return true;
    }

    function CheckLogin()
    {
         if(!isset($_SESSION)){ session_start(); }

         $sessionvar = $this->GetLoginSessionVar();

         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
         return true;
    }

    function UserFullName()
    {
        return isset($_SESSION['name_of_user'])?$_SESSION['name_of_user']:'';
    }

    function UserEmail()
    {
        return isset($_SESSION['email_of_user'])?$_SESSION['email_of_user']:'';
    }

    function LogOut()
    {
        session_start();

        $sessionvar = $this->GetLoginSessionVar();

        $_SESSION[$sessionvar]=NULL;

        unset($_SESSION[$sessionvar]);
    }

    function EmailResetPasswordLink()
    {
        if(empty($_POST['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        $user_rec = array();
        if(false === $this->GetUserFromEmail($_POST['email'], $user_rec))
        {
            return false;
        }
        if(false === $this->SendResetPasswordLink($user_rec))
        {
            return false;
        }
        return true;
    }

    function ResetPassword()
    {
        if(empty($_GET['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        if(empty($_GET['code']))
        {
            $this->HandleError("reset code is empty!");
            return false;
        }
        $email = trim($_GET['email']);
        $code = trim($_GET['code']);

        if($this->GetResetPasswordCode($email) != $code)
        {
            $this->HandleError("Bad reset code!");
            return false;
        }

        $user_rec = array();
        if(!$this->GetUserFromEmail($email,$user_rec))
        {
            return false;
        }

        $new_password = $this->ResetUserPasswordInDB($user_rec);
        if(false === $new_password || empty($new_password))
        {
            $this->HandleError("Error updating new password");
            return false;
        }

        if(false == $this->SendNewPassword($user_rec,$new_password))
        {
            $this->HandleError("Error sending new password");
            return false;
        }
        return true;
    }

    function ChangePassword()
    {
        if(!$this->CheckLogin())
        {
            $this->HandleError("Not logged in!");
            return false;
        }

        if(empty($_POST['oldpwd']))
        {
            $this->HandleError("Old password is empty!");
            return false;
        }
        if(empty($_POST['newpwd']))
        {
            $this->HandleError("New password is empty!");
            return false;
        }

        $user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }

        $pwd = trim($_POST['oldpwd']);
        $pwd = $this->SanitizeForSQL($pwd);

        if($user_rec['password'] != hash('sha512',$pwd.$user_rec['salt']))
        {
            $this->HandleError("The old password does not match!");
            return false;
        }
        $newpwd = trim($_POST['newpwd']);

        if(!$this->ChangePasswordInDB($user_rec, $newpwd))
        {
            return false;
        }
        return true;
    }

    //-------Public Helper functions -------------
    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }

    function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return htmlentities($_POST[$value_name]);
    }

    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }

    function GetSpamTrapInputName()
    {
        return 'sp'.hash('sha512','KHGdnbvsgst'.$this->rand_key);
    }

    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = nl2br(htmlentities($this->error_message));
        return $errormsg;
    }
    //-------Private Helper functions-----------

    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }

    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n mysqlerror:".mysql_error());
    }

    function GetFromAddress()
    {
        if(!empty($this->from_address))
        {
            return $this->from_address;
        }

        $host = $_SERVER['SERVER_NAME'];

        $from ="nobody@$host";
        return "baheffron@gmail.com";
    }

    function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }

    function CheckLoginInDB($username,$password)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        $qry = "Select salt from $this->tablename where username='$username'";
        $salt_result = mysql_query($qry,$this->connection);
        if(!$salt_result || mysql_num_rows($salt_result) <= 0)
        {
            $this->HandleError("Error logging in. The username does not exist");
            return false;
        }
        $salt = mysql_fetch_row($salt_result);
        $username = $this->SanitizeForSQL($username);
        $password = $this->SanitizeForSQL($password);
        $pwdsha512 = hash('sha512',$password.$salt[0]);
        $qry = "Select u.name, e.user, e.tld, e.domain, u.id, u.username from $this->tablename as u JOIN email as e on e.id = u.email_id where u.username='$username' and u.password='$pwdsha512' and u.confirmcode='y'";

        $result = mysql_query($qry,$this->connection);

        if(!$result || mysql_num_rows($result) <= 0)
        {
            $this->HandleError("Error logging in. The username or password does not match");
            return false;
        }

        $row = mysql_fetch_assoc($result);

        $_SESSION['name_of_user']  = $row['name'];
        $_SESSION['email_of_user'] = $row['user'].'@'.$row['domain'].'.'.$row['tld'];
        $_SESSION['username']      = $row['username'];
        $_SESSION['user_id']       = $row['id'];

        return true;
    }

    function UpdateDBRecForConfirmation(&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        $confirmcode = $this->SanitizeForSQL($_GET['code']);

        $result = mysql_query("Select u.name, u.email_id, e.user, e.domain, e.tld from $this->tablename as u
            JOIN email as e on e.id = u.email_id where u.confirmcode='$confirmcode'",$this->connection);
        if(!$result || mysql_num_rows($result) <= 0)
        {
            $this->HandleError("Wrong confirm code.");
            return false;
        }
        $row = mysql_fetch_assoc($result);
        $user_rec['name'] = $row['name'];
        $user_rec['email']= $row['user'].'@'.$row['domain'].'.'.$row['tld'];


        $qry = "Update $this->tablename Set confirmcode='y' Where  confirmcode='$confirmcode'";

        if(!mysql_query( $qry ,$this->connection))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$qry");
            return false;
        }
        return true;
    }

    function ResetUserPasswordInDB($user_rec)
    {
        $new_password = substr(hash('sha512',uniqid()),0,10);

        if(false == $this->ChangePasswordInDB($user_rec,$new_password))
        {
            return false;
        }
        return $new_password;
    }

    function ChangePasswordInDB($user_rec, $newpwd)
    {
        $newpwd = $this->SanitizeForSQL($newpwd);

        $salt = GenerateSalt();
        $newpwd .= $salt;

        $qry = "Update $this->tablename Set password='".hash('sha512',$newpwd)."', salt='".$salt."' Where  id=".$user_rec['id_user']."";

        if(!mysql_query( $qry ,$this->connection))
        {
            $this->HandleDBError("Error updating the password \nquery:$qry");
            return false;
        }
        return true;
    }

    function GetUserFromEmail($email,&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        $email_values = $this->EmailSplit($email);
        $domain = $this->SanitizeForSQL($email_values['domain']);
        $user = $this->SanitizeForSQL($email_values['user']);
        $tld = $this->SanitizeForSQL($email_values['tld']);

        $result = mysql_query("Select u.id, u.password, u.salt, u.name, u.email_id, e.tld, e.user, e.domain  from email as e
          JOIN $this->tablename() as u on u.email_id = e.id
          where e.domain='$domain'
          AND e.tld='$tld'
          AND e.user='$user'",$this->connection);

        if(!$result || mysql_num_rows($result) <= 0)
        {
            $this->HandleError("There is no user with email: $email");
            return false;
        }
        $row = mysql_fetch_assoc($result);
        $user_rec['name'] = $row['name'];
        $user_rec['email'] = $email;
        $user_rec['password'] = $row['password'];
        $user_rec['id_user'] = $row['id'];
        $user_rec['salt'] = $user_rec_temp['salt'];

        return true;
    }

    function SendUserWelcomeEmail(&$user_rec)
    {
       /* $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($user_rec['email'],$user_rec['name']);

        $mailer->Subject = "Welcome to ".$this->sitename;

        $mailer->From = $this->GetFromAddress();

        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Welcome! Your registration  with ".$this->sitename." is completed.\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending user welcome email.");
            return false;
        }
        return true;*/
        $subject = "Welcome to ".$this->sitename;
        $body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Welcome! Your registration  with ".$this->sitename." is completed.\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

      $this->smtpmailer($user_rec['email'],'baheffron@gmail.com','Blaine Heffron',$subject,$body);
    }
    function smtpmailer($to, $from, $from_name, $subject, $body) {
      global $error;
      $mail = new PHPMailer();  // create a new object
      $mail->IsSMTP(); // enable SMTP
      $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
      $mail->SMTPAuth = true;  // authentication enabled
      $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
      $mail->Host = 'smtp.gmail.com';
      $mail->Port = 465;
      $mail->Username = 'baheffron@gmail.com';
      $mail->Password = '148632bh!';
      $mail->SetFrom($from, $from_name);
      $mail->Subject = $subject;
      $mail->Body = $body;
      $mail->AddAddress($to);
      if(!$mail->Send()) {
        $this->HandleError("Failed sending user welcome email.");
        return false;
      }
      else {
        return true;
      }
    }

    function SendAdminIntimationOnRegComplete(&$user_rec)
    {
        if(empty($this->admin_email))
        {
            return false;
        }/*
        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($this->admin_email);

        $mailer->Subject = "Registration Completed: ".$user_rec['name'];

        $mailer->From = $this->GetFromAddress();

        $mailer->Body ="A new user registered at ".$this->sitename."\r\n".
        "Name: ".$user_rec['name']."\r\n".
        "Email address: ".$user_rec['email']."\r\n";

        if(!$mailer->Send())
        {
            return false;
        }
        return true;*/
      $subject = "Registration Completed: ".$user_rec['name'];
      $body ="A new user registered at ".$this->sitename."\r\n".
        "Name: ".$user_rec['name']."\r\n".
        "Email address: ".$user_rec['email']."\r\n";

      $this->smtpmailer($this->admin_email,'baheffron@gmail.com','Blaine Heffron',$subject,$body);
    }

    function GetResetPasswordCode($email)
    {
       return substr(hash('sha512',$email.$this->sitename.$this->rand_key),0,10);
    }

    function SendResetPasswordLink($user_rec)
    {
        /*$email = $user_rec['email'];

        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($email,$user_rec['name']);

        $mailer->Subject = "Your reset password request at ".$this->sitename;

        $mailer->From = $this->GetFromAddress();

        $link = $this->GetAbsoluteURLFolder().
                '/resetpwd.php?email='.
                urlencode($email).'&code='.
                urlencode($this->GetResetPasswordCode($email));

        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "There was a request to reset your password at ".$this->sitename."\r\n".
        "Please click the link below to complete the request: \r\n".$link."\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            return false;
        }
        return true;*/
      $subject = "Your reset password request at ".$this->sitename;
      $body = "Hello ".$user_rec['name']."\r\n\r\n".
        "There was a request to reset your password at ".$this->sitename."\r\n".
        "Please click the link below to complete the request: \r\n".$link."\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
      $this->smtpmailer($user_rec['email'],'baheffron@gmail.com','Blaine Heffron',$subject,$body);
    }

    function SendNewPassword($user_rec, $new_password)
    {
      /*  $email = $user_rec['email'];

        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($email,$user_rec['name']);

        $mailer->Subject = "Your new password for ".$this->sitename;

        $mailer->From = $this->GetFromAddress();

        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Your password is reset successfully. ".
        "Here is your updated login:\r\n".
        "username:".$user_rec['username']."\r\n".
        "password:$new_password\r\n".
        "\r\n".
        "Login here: ".$this->GetAbsoluteURLFolder()."/login.php\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            return false;
        }
        return true;  */
      $subject = "Your new password for ".$this->sitename;

      $body = "Hello ".$user_rec['name']."\r\n\r\n".
        "Your password is reset successfully. ".
        "Here is your updated login:\r\n".
        "username:".$user_rec['username']."\r\n".
        "password:$new_password\r\n".
        "\r\n".
        "Login here: ".$this->GetAbsoluteURLFolder()."/login.php\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

      $this->smtpmailer($user_rec['email'],'baheffron@gmail.com','Blaine Heffron',$subject,$body);
    }

    function ValidateRegistrationSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Automated submission prevention: case 2 failed");
            return false;
        }

        $validator = new FormValidator();
        $validator->addValidation("name","req","Please fill in Name");
        $validator->addValidation("email","email","The input for Email should be a valid email value");
        $validator->addValidation("email","req","Please fill in Email");
        $validator->addValidation("username","req","Please fill in UserName");
        $validator->addValidation("password","req","Please fill in Password");


        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= $inpname.':'.$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }
        return true;
    }

    function CollectRegistrationSubmission(&$formvars)
    {
        $formvars['name'] = $this->Sanitize($_POST['name']);
        $formvars['email'] = $this->Sanitize($_POST['email']);
        $formvars['username'] = $this->Sanitize($_POST['username']);
        $formvars['password'] = $this->Sanitize($_POST['password']);
        $formvars['opt_out'] = isset($_POST['opt_out']) ? 1 : 0;
    }

    function SendUserConfirmationEmail(&$formvars)
    {
        /*$mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($formvars['email'],$formvars['name']);

        $mailer->Subject = "Your registration with ".$this->sitename;

        $mailer->From = $this->GetFromAddress();

        $confirmcode = $formvars['confirmcode'];

        $confirm_url = $this->GetAbsoluteURLFolder().'/confirmreg.php?code='.$confirmcode;

        $mailer->Body ="Hello ".$formvars['name']."\r\n\r\n".
        "Thanks for your registration with ".$this->sitename."\r\n".
        "Please click the link below to confirm your registration.\r\n".
        "$confirm_url\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending registration confirmation email.");
            return false;
        }
        return true;*/
      $confirmcode = $formvars['confirmcode'];
      $confirm_url = $this->GetAbsoluteURLFolder().'/confirmreg.php?code='.$confirmcode;
      $body ="Hello ".$formvars['name']."\r\n\r\n".
        "Thanks for your registration with ".$this->sitename."\r\n".
        "Please click the link below to confirm your registration.\r\n".
        "$confirm_url\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
      $subject = "Your registration with ".$this->sitename;

      $this->smtpmailer($formvars['email'],'baheffron@gmail.com','Blaine Heffron',$subject,$body);
    }
    function GetAbsoluteURLFolder()
    {
        $scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
        $scriptFolder .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
        return $scriptFolder;
    }

    function SendAdminIntimationEmail(&$formvars)
    {
       /* if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($this->admin_email);

        $mailer->Subject = "New registration: ".$formvars['name'];

        $mailer->From = $this->GetFromAddress();

        $mailer->Body ="A new user registered at ".$this->sitename."\r\n".
        "Name: ".$formvars['name']."\r\n".
        "Email address: ".$formvars['email']."\r\n".
        "UserName: ".$formvars['username'];

        if(!$mailer->Send())
        {
            return false;
        }
        return true;*/
      $body ="A new user registered at ".$this->sitename."\r\n".
        "Name: ".$formvars['name']."\r\n".
        "Email address: ".$formvars['email']."\r\n".
        "UserName: ".$formvars['username'];
      $subject = "New registration: ".$formvars['name'];
      $this->smtpmailer($this->admin_email,'baheffron@gmail.com','Blaine Heffron',$subject,$body);
    }

    function SaveToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->IsFieldUnique($formvars,'email'))
        {
            $this->HandleError("This email is already registered");
            return false;
        }

        if(!$this->IsFieldUnique($formvars,'username'))
        {
            $this->HandleError("This UserName is already used. Please try another username");
            return false;
        }
        if(!$this->InsertIntoDB($formvars))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
    }

    function IsFieldUnique($formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select username from $this->tablename where $fieldname='".$field_val."'";
        $result = mysql_query($qry,$this->connection);
        if($result && mysql_num_rows($result) > 0)
        {
            return false;
        }
        return true;
    }

    function DBLogin()
    {

        $this->connection = mysql_connect($this->db_host,$this->username,$this->pwd);

        if(!$this->connection)
        {
            $this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
            return false;
        }
        if(!mysql_select_db($this->database, $this->connection))
        {
            $this->HandleDBError('Failed to select database: '.$this->database.' Please make sure that the database name provided is correct');
            return false;
        }
        if(!mysql_query("SET NAMES 'UTF8'",$this->connection))
        {
            $this->HandleDBError('Error setting utf8 encoding');
            return false;
        }
        return true;
    }

    function InsertIntoDB(&$formvars)
    {

        $confirmcode = $this->MakeConfirmationMd5($formvars['email']);
        $salt = $this->GenerateSalt();
        $salted_pw = $this->SanitizeForSQL($formvars['password']);
        $salted_pw .= $salt;

        $formvars['confirmcode'] = $confirmcode;

        $email_values = $this->EmailSplit($formvars['email']);
        $domain = $this->SanitizeForSQL($email_values['domain']);
        $user = $this->SanitizeForSQL($email_values['user']);
        $tld = $this->SanitizeForSQL($email_values['tld']);
        $select_email = 'SELECT id from email
                         WHERE user = "'.$user.'"
                         AND domain = "'.$domain.'"
                         AND tld = "'.$tld.'"';
        $result = mysql_query($select_email,$this->connection);
        if(!$result || mysql_num_rows($result) <= 0)
        {
          $insert_email = 'Insert INTO email (user, domain, tld, is_opted_out) values ("'.$user.'","'.$domain.'","'.$tld.'","'.$formvars['opt_out'].'")';
          if(!mysql_query( $insert_email, $this->connection))
          {
            $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
            return false;
          }
          $email_id = mysql_insert_id();
          $insert_query = 'insert into '.$this->tablename.'(
                      name,
                      username,
                      password,
                      creation_date,
                      email_id,
                      salt,
                      confirmcode
                      )
                      values
                      (
                      "' . $this->SanitizeForSQL($formvars['name']) . '",
                      "' . $this->SanitizeForSQL($formvars['username']) . '",
                      "' . hash('sha512',$salted_pw) . '",
                      NOW(),
                      "' . $email_id . '",
                      "' . $salt . '",
                      "' . $confirmcode . '"
                      )';
          if(!mysql_query( $insert_query ,$this->connection))
          {
              $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
              return false;
          }
          return true;
        }
        else
        {
            $this->HandleError("There is already a user registered under $email.");
            return false;
        }
    }
    function MakeConfirmationMd5($email)
    {
        $randno1 = rand();
        $randno2 = rand();
        return md5($email.$this->rand_key.$randno1.''.$randno2);
    }
    function GenerateSalt()
    {
      $rand_salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
      return $rand_salt;
    }
    function EmailSplit($email_address)
    {
      $email_split = array();
      $email_split['user'] = strstr($email_address, '@', true);
      $dom = strstr($email_address, '@');
      $dom = substr($dom,1);
      $email_split['domain'] = strstr($dom, '.', true);
      $tld = strstr($dom, '.');
      $email_split['tld'] = substr($tld,1);
      return $email_split;
    }

    function SanitizeForSQL($str)
    {
        if( function_exists( "mysql_real_escape_string" ) )
        {
              $ret_str = mysql_real_escape_string( $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }

 /*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
    */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }
}
?>
