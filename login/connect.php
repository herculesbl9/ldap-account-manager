<?php

if (function_exists('ldap_connect')) {
	$ldap_dn = "uid=" . $_POST["username"] . ",ou=people,dc=example,dc=com";
	$ldap_pass = $_POST["password"];
	$ldap_con = ldap_connect("ldap://localhost");

	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	if (ldap_bind($ldap_con, $ldap_dn, $ldap_pass)) {

		$dn = "ou=people,dc=example,dc=com";
		$filter = "(uid=" . $_POST["username"] . ")";
		$vars = array("uidnumber", "sn", "givenname", "mail", "telephonenumber", "memberOf");

		$sr = ldap_search($ldap_con, $dn, $filter, $vars);
		$info = ldap_get_entries($ldap_con, $sr);

		session_start();
		$_SESSION["name"] = $info[0]["givenname"][0];
		$_SESSION["surname"] = $info[0]["sn"][0];
		$_SESSION["phone"] = $info[0]["telephonenumber"][0];
		$_SESSION["email"] = $info[0]["mail"][0];
		$_SESSION["username"] = $_POST["username"];
		$_SESSION["uid"] = $info[0]["uidnumber"][0];

		for ($i = 0; $i < $info[0]["memberof"]["count"]; $i++) {
			if ($info[0]["memberof"][$i] == "cn=admins,ou=groups,dc=example,dc=com") $_SESSION["access_level"] = "admin";
			else if ($info[0]["memberof"][$i] == "cn=management,ou=groups,dc=example,dc=com") $_SESSION["access_level"] = "management";
			else $_SESSION["access_level"] = "user";
		}
		ldap_unbind($ldap_con);
		if (isset($_GET['redir'])) header("Location: /dashboard/" . $_GET['redir']);
		else header("Location: ../dashboard");
	} else header("Location: /login/?message=error");
} else echo "No LDAP";

