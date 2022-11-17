<?php
session_start();

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

	$dn = "uid=" . $_SESSION["username"] . ",ou=people,dc=example,dc=com";
	$flag = 0;

	if ($_POST["surname"] != "") {
		$attr["sn"] = array($_POST["surname"]);
		$_SESSION["surname"] = $_POST["surname"];
		$flag = 1;
	}
	if ($_POST["name"] != "") {
		$attr["givenname"] = array($_POST["name"]);
		$_SESSION["name"] = $_POST["name"];
		$flag = 1;
	}

	if ($_POST["surname"] != "" && $_POST["name"] != ""){
		$temp = $_POST["name"] . " " . $_POST["surname"];
		$attr["displayname"] = array($temp);
		$temp = strtolower($_POST["name"] . $_POST["surname"]);
		$attr["cn"] = array($temp);
	}
	else if ($_POST["surname"] != ""){
		$temp = $_SESSION["name"] . " " . $_POST["surname"];
		$attr["displayname"] = array($temp);
		$temp = strtolower($_SESSION["name"] . $_POST["surname"]);
		$attr["cn"] = array($temp);
	}
	else if ($_POST["name"] != ""){
		$temp = $_POST["name"] . " " . $_SESSION["surname"];
		$attr["displayname"] = array($temp);
		$temp = strtolower($_POST["name"] . $_SESSION["surname"]);
		$attr["cn"] = array($temp);
	}

	if ($_POST["phone"] != "") {
		$attr["telephonenumber"] = array($_POST["phone"]);
		$_SESSION["phone"] = $_POST["phone"];
		$flag = 1;
	}
	if ($_POST["email"] != "") {
		$attr["mail"] = array($_POST["email"]);
		$_SESSION["email"] = $_POST["email"];
		$flag = 1;
	}

	if ($_POST["password"] != "") {
		$attr["userPassword"] = '{CRYPT}' . crypt($_POST["password"], '$6$' . generate_salt(8));
		$flag = 1;
	}

	if ($flag == 1) $res = ldap_mod_replace($ldap_con, $dn, $attr);

	if ($res) {
		ldap_unbind($ldap_con);
		header("Location: edit-user.php?message=success");
	} else {
		ldap_unbind($ldap_con);
		header("Location: edit-user.php?message=error");
	}
} else header("Location: ../login/?redir=edit-user.php");
?>