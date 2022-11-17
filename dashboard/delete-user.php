<?php
session_start();
if (!isset($_SESSION["uid"])) header("Location: ../login/?redir=edit-users.php");
if ($_SESSION["access_level"] == "user") header("Location: no-access.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

$rs = ldap_delete($ldap_con, "uid=" . $_POST['user-to-delete'] . ",ou=people,dc=example,dc=com");

ldap_unbind($ldap_con);
if($rs) header("Location: edit-users.php?result=success");
else header("Location: edit-users.php?result=failure");
}
else header("Location: edit-users.php");