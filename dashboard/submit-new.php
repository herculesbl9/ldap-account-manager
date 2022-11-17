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

    $ldap_dn = "dc=example,dc=com";
    $filter = "(uid=".$_POST['username'].")";
    $vars = array("cn");
    $sr = ldap_search($ldap_con, $ldap_dn, $filter, $vars);
    $results = ldap_get_entries($ldap_con, $sr);
    if ($results["count"] > 0) {
        ldap_unbind($ldap_con);
        header("Location: create-account.php?message=username");
    } else {

        $dn = "uid=" . $_POST["username"] . ",ou=people,dc=example,dc=com";

        $attr["uid"] = array($_POST["username"]);
        $attr["givenname"] = array($_POST["name"]);
        $attr["sn"] = array($_POST["surname"]);
        $attr["telephonenumber"] = array($_POST["phone"]);
        $attr["mail"] = array($_POST["email"]);
        $attr["displayname"] = array($_POST["displayname"]);
        $attr["loginshell"] = array($_POST["loginshell"]);
        $attr["homeDirectory"] = array($_POST["homedir"]);
        $attr["cn"] = array($_POST["cn"]);
        $attr["uidNumber"] = array($_POST["uidnumber"]);
        $attr["gidNumber"] = array(2001);
        $attr["objectClass"] = array("person", "inetOrgPerson", "posixAccount");
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
                $mail->Subject = 'Δημιουργία λογαριασμού';
                $mail->Body    = '
            Έχει δημιουργηθεί για εσάς ενιαίος λογαριασμός LDAP στο διακομιστή της Company.<br><br>
            Τα νέα σας στοιχεία είναι τα εξης:<br>
            username: <strong>' . $_POST["username"] . '</strong><br>
            password: <strong>' . $_POST["password"] . '</strong><br><br>
            Προτείνεται να αλλάξεται τον κωδικό σας στη διεύθυνση <a href="https://ldap-manager.example.com/dashboard/edit-user.php">ldap-manager.example.com/dashboard/edit-user.php</a>, ενώ μπορείτε να βρείτε περισσότερες λεπτομέρειες για τις δυνατότητες που σας δίνει ο λογαριασμός στο <a href="https://ldap-manager.example.com/welcome/">ldap-manager.example.com/welcome/</a>.';
                $mail->AltBody = 'Έχει δημιουργηθεί για εσάς ενιαίος λογαριασμός LDAP στο διακομιστή της Company. Τα νέα σας στοιχεία είναι τα εξης: username: ' . $_POST["username"] . 'password: ' . $_POST["password"] . 'Προτείνεται να αλλάξεται τον κωδικό σας στη διεύθυνση ldap-manager.example.com/dashboard/edit-user.php, ενώ μπορείτε να βρείτε περισσότερες λεπτομέρειες για τις δυνατότητες που σας δίνει ο λογαριασμός στο ldap-manager.example.com/welcome/.';

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
        }

        $res = ldap_add($ldap_con, $dn, $attr);
        $res1 = ldap_mod_replace($ldap_con, "cn=lastUID,dc=example,dc=com", array('serialNumber' => $_POST["uidnumber"]));

        if ($res && $res1) {
            $group_name = "cn=everybody,ou=groups,dc=example,dc=com";
            $group_info['uniquemember'] = $dn;
            $res2 = ldap_mod_add($ldap_con, $group_name, $group_info);
            ldap_unbind($ldap_con);
            if ($res2) header("Location: create-account.php?message=success");
            else header("Location: create-account.php?message=error");
        } else {
            ldap_unbind($ldap_con);
            header("Location: create-account.php?message=error");
        }
    }
} else header("Location: ../login/");
