<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION["uid"])) header("Location: ../login/?redir=edit-groups.php");
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

    $j = 0;
    for ($i = 0; $i < $_POST['count-enabled']; $i++) {
        if (isset($_POST["remove"][$i])) {
            $attr["uniqueMember"][$j] = $_POST["remove"][$i];
            $j++;
        }
    }

    $j = 0;
    for ($i = 0; $i < $_POST['count-available']; $i++) {
        if (isset($_POST["add"][$i])) {
            $attr2["uniquemember"][$j] = $_POST["add"][$i];
            $j++;
        }
    }

    $group = "cn=" . $_POST["group-name"] . ",ou=groups,dc=example,dc=com";

    if (isset($attr)) {
        $rs = ldap_mod_del($ldap_con, $group, $attr);
        if ($rs) echo "users deleted from group";
        else echo "an error was encountered when trying to delete the users from the group";
    }

    if (isset($attr2)) {
        $rs = ldap_mod_add($ldap_con, $group, $attr2);
        if ($rs) echo "users added to the group";
        else echo "an error was encountered when trying to add the users to the group";
    }
}
else header("Location: ../login/?redir=edit-groups.php");