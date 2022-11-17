<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function generate_salt($length)
{

    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./';

    mt_srand((float)microtime() * 1000000);

    $salt = '';
    while (strlen($salt) < $length) {
        $salt .= substr($permitted_chars, (rand() % strlen($permitted_chars)), 1);
    }

    return $salt;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION["uid"])) header("Location: ../login/?redir=edit-user.php");

    if (function_exists('ldap_connect')) {
        $ldap_dn = "cn=admin,dc=example,dc=com";
        $ldap_pass = "password";
        $ldap_con = ldap_connect("ldap://localhost");

        putenv('LDAPTLS_REQCERT=never');
        if (ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3))
            if (ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0))
                if (ldap_start_tls($ldap_con))
                    $bind = ldap_bind($ldap_con, $ldap_dn, $ldap_pass);
    } else echo "No LDAP";

    if (!$bind) echo "Bind unsuccessful";

    $dn = "uid=" . $_POST["username"] . ",ou=people,dc=example,dc=com";
    $attr["givenname"] = array($_POST["name"]);
    $attr["sn"] = array($_POST["surname"]);
    $attr["telephonenumber"] = array($_POST["phone"]);
    $attr["mail"] = array($_POST["email"]);
    $attr["displayname"] = array($_POST["displayname"]);
    $attr["loginshell"] = array($_POST["loginshell"]);
    $attr["homeDirectory"] = array($_POST["homedir"]);
    $attr["cn"] = array($_POST["cn"]);
    if ($_POST["password"] != "") $attr["userPassword"] = '{CRYPT}' . crypt($_POST["password"], '$6$' . generate_salt(8));

    if ($_POST["send-email"]) {
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.example.com';                         // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'mail@example.com';                   // SMTP username
            $mail->Password = 'smtp-password';                           // SMTP password
            $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_STARTTLS';       // Enable SSL encryption, TLS also accepted with port 587
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('mail@example.com', 'Company Accounts');
            $mail->addAddress($_POST["email"], $_POST["name"] . ' ' . $_POST["surname"]);     // Add a recipient
            //$mail->addAddress('contact@example.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Μεταβολή στοιχείων';
            $mail->Body    = 'Τα στοιχεία του ενιαίου λογαριασμού σας στην Company έχουν μεταβληθεί.<br>Τα νέα σας στοιχεία είναι τα εξης:<br>username: ' . $_POST["username"] . '<br>password: ' . $_POST["password"] . '<br>Προτείνεται να αλλάξετε τον κωδικό σας στη διεύθυνση <a href="https://ldap-manager.example.com/dashboard/edit-user.php">ldap-manager.example.com/dashboard/edit-user.php</a>';
            $mail->AltBody = 'Τα στοιχεία του ενιαίου λογαριασμού σας στην Company έχουν μεταβληθεί. Τα νέα σας στοιχεία είναι τα εξης: username: ' . $_POST["username"] . 'password: ' . $_POST["password"] . 'Προτείνεται να αλλάξετε τον κωδικό σας στη διεύθυνση ldap-manager.example.com/dashboard/edit-user.php';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }

    $res = ldap_mod_replace($ldap_con, $dn, $attr);

    if ($res) {
        ldap_unbind($ldap_con);
        header("Location: edit-users-form.php?user=" . $_POST["username"] . "&&message=success");
    } else {
        header("Location: edit-users-form.php?user=" . $_POST["username"] . "&&message=error");
        ldap_unbind($ldap_con);
    }
} else header("Location: ../login/");
