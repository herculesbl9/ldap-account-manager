<?php
session_start();
if (!isset($_SESSION["uid"])) header("Location: ../login?redir=edit-groups.php");
if ($_SESSION["access_level"] == "user") header("Location: no-access.php");

?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.98.0">
    <title>Company Account Management</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/">





    <link href="/bootstrap-5.2.0-beta1-dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="/dashboard/dashboard.css" rel="stylesheet">
</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="/dashboard/">Company </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <form action="search.php" method="POST" class="p-0 w-100 m-0">
            <input class="form-control form-control-dark w-100 rounded-0 border-0" type="text" id="search_term" name="search_term" placeholder="Αναζήτηση μελών (ονοματεπώνυμο/email)" aria-label="Search">
            <button type="submit" hidden>submit</button>
        </form>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="logout.php">Sign out</a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">
                                <span data-feather="home" class="align-text-bottom"></span>
                                Στοιχεία
                            </a>
                        </li>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="edit-user.php">
                                    <span data-feather="edit" class="align-text-bottom"></span>
                                    Επεξεργασία Στοιχείων
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="search.php">
                                    <span data-feather="search" class="align-text-bottom"></span>
                                    Αναζήτηση Χρηστών
                                </a>
                            </li>
                        </ul>

                        <?php if ($_SESSION["access_level"] == "admin") { ?>
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                                <span>ΕΠΙΛΟΓΕΣ ΔΙΑΧΕΙΡΙΣΤΗ</span>
                            </h6>
                            <ul class="nav flex-column mb-2">
                                <li class="nav-item">
                                    <a class="nav-link" href="edit-users.php">
                                        <span data-feather="file-text" class="align-text-bottom"></span>
                                        Επεξεργασία χρηστών
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/dashboard/create-account.php">
                                        <span data-feather="file-text" class="align-text-bottom"></span>
                                        Δημιουργία χρήστη
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="edit-groups.php">
                                        <span data-feather="file-text" class="align-text-bottom"></span>
                                        Επεξεργασία ομάδων
                                    </a>
                                </li>
                            </ul>
                        <?php } ?>
                </div>
            </nav>
            <?php if (function_exists('ldap_connect')) {
                $ldap_dn = "cn=admin,dc=example,dc=com";
                $ldap_pass = "password";
                $ldap_con = ldap_connect("ldap://localhost");

                putenv('LDAPTLS_REQCERT=never');
                if (ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3))
                    if (ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0))
                        if (ldap_start_tls($ldap_con))
                            $bind = ldap_bind($ldap_con, $ldap_dn, $ldap_pass);
            } else echo "No LDAP";

            if (!$bind) echo "Bind unsuccessful"; ?>

            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </symbol>
                <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                </symbol>
                <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </symbol>
            </svg>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    if ($_POST["form-id"] == "1") {
                        $dn = "dc=example,dc=com";
                        $filter = "(cn=lastGID)";
                        $vars = array("serialNumber");
                        $sr = ldap_search($ldap_con, $dn, $filter, $vars);
                        $lastgid = ldap_get_entries($ldap_con, $sr);

                        $attr["cn"] = array($_POST["group-name"]);
                        $attr["objectClass"] = array("top", "groupOfUniqueNames", "posixGroup");
                        $attr["uniqueMember"] = array("uid=" . $_SESSION["username"] . ",ou=people,dc=example,dc=com");
                        $temp = $lastgid[0]['serialnumber'][0] + 1;
                        $attr["gidNumber"] = array($temp);

                        $group_dn = "cn=" . $_POST["group-name"] . ",ou=groups,dc=example,dc=com";

                        $rs = ldap_add($ldap_con, $group_dn, $attr);
                        if ($rs) {
                            ldap_mod_replace($ldap_con, "cn=lastGID,dc=example,dc=com", array('serialNumber' => $lastgid[0]['serialnumber'][0] + 1));
                ?>
                            <div class="alert alert-success d-flex align-items-center mt-3" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                                    <use xlink:href="#check-circle-fill" />
                                </svg>
                                <div>
                                    Το group δημιουργήθηκε.
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                                    <use xlink:href="#exclamation-triangle-fill" />
                                </svg>
                                <div>
                                    Υπήρξε κάποιο πρόβλημα με τη δημιουργία του group: <?php echo ldap_error($ldap_con); ?>
                                </div>
                            </div>
                            <?php }
                    } else if ($_POST["form-id"] == "2") {
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
                            if ($rs) { ?>
                                <div class="alert alert-success d-flex align-items-center mt-3" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                                        <use xlink:href="#check-circle-fill" />
                                    </svg>
                                    <div>
                                        Οι χρήστες διαγράφηκαν από το group.
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                                        <use xlink:href="#exclamation-triangle-fill" />
                                    </svg>
                                    <div>
                                        Υπήρξε κάποιο πρόβλημα με τη διαγραφή των χρηστών από το group: <?php echo ldap_error($ldap_con); ?>
                                    </div>
                                </div>
                            <?php }
                        }

                        if (isset($attr2)) {
                            $rs = ldap_mod_add($ldap_con, $group, $attr2);
                            if ($rs) { ?>
                                <div class="alert alert-success d-flex align-items-center mt-3" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                                        <use xlink:href="#check-circle-fill" />
                                    </svg>
                                    <div>
                                        Οι χρήστες προστέθηκαν στο το group.
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                                        <use xlink:href="#exclamation-triangle-fill" />
                                    </svg>
                                    <div>
                                        Υπήρξε κάποιο πρόβλημα με την προσθήκη των χρηστών στο το group: <?php echo ldap_error($ldap_con); ?>
                                    </div>
                                </div>
                            <?php }
                        }
                    } else if ($_POST["form-id"] == "3") {
                        $rs = ldap_delete($ldap_con, $_POST["group-to-delete"]);
                        if ($rs) {
                            ?>
                            <div class="alert alert-success d-flex align-items-center mt-3" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                                    <use xlink:href="#check-circle-fill" />
                                </svg>
                                <div>
                                    Το group διαγράφηκε.
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                                    <use xlink:href="#exclamation-triangle-fill" />
                                </svg>
                                <div>
                                    Υπήρξε κάποιο πρόβλημα με τη διαγραφή του group: <?php echo ldap_error($ldap_con); ?>
                                </div>
                            </div>
                <?php }
                    }
                }
                ?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Επεξεργασία ομάδων LDAP</h1>
                </div>

                <div class="card border-dark mb-3">
                    <div class="card-body text-dark">
                        <div class="row g-0">
                            <h5 class="card-title">Τι είναι οι ομάδες LDAP;</h5>
                            <div class="col-md-10">
                                <p class="card-text">Οι ομάδες LDAP (LDAP Groups) είναι ένα χρήσιμο εργαλείο διαχείρισης χρηστών. Ιδιαίτερα όταν διαχειριζόμαστε μια βάση δεδομένων με μεγάλο αριθμό χρηστών, μπορούν να χρησιμοποιηθούν για να
                                    αποθηκεύσουν τους χρήστες που είναι ενταγμένοι σε κάποιο συγκεκριμένο group ή κάποια υποομάδα. Επιπλέον, μπορούν να χρησιμοποιηθούν όταν θέλουμε να δώσουμε επιπλέον δικαιώματα (π.χ. διαχείρισης) σε ένα τμήμα
                                    των χρηστών της Βάσης Δεδομένων.
                                </p>
                            </div>
                            <div class="col-md-2">
                                <div class="align-self-center">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-outline-dark align-self-center">Δημιουργία Ομάδας LDAP</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $dn = "ou=groups,dc=example,dc=com";
                $filter = "(cn=*)";
                $vars = array("cn", "gidNumber", "uniqueMember");
                $sr = ldap_search($ldap_con, $dn, $filter, $vars);
                $info = ldap_get_entries($ldap_con, $sr);

                if ($info["count"] > 0) {
                    echo '<div class="accordion pb-2" id="existing-groups">';
                    for ($i = 0; $i < $info["count"]; $i++) { ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?php echo $info[$i]["cn"][0]; ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $info[$i]["cn"][0]; ?>" aria-expanded="false" aria-controls="collapse<?php echo $info[$i]["cn"][0]; ?>">
                                    <?php echo $info[$i]["cn"][0]; ?>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $info[$i]["cn"][0]; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $info[$i]["cn"][0]; ?>" data-bs-parent="#existing-groups">
                                <div class="accordion-body">
                                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-0 mb-3 border-bottom">
                                        <h5>Βασικά στοιχεία LDAP Group</h5>
                                    </div>
                                    <?php if ($info[$i]["cn"][0] == "deactivated") { ?>
                                        <div class="mb-3">
                                            <strong>
                                                ΠΡΟΣΟΧΗ! Προτιμήστε την επιλογή απενεργοποίηση από τη σελίδα <a href="edit-users.php">επεξεργασία χρηστών</a>! Η μεταφορά των χρηστών στην ομάδα deactivated
                                                δεν απενεργοποιεί αυτόματα τον λογαριασμό τους στο Rocket.Chat!

                                            </strong>
                                        </div>
                                    <?php } ?>
                                    <dl class="row">
                                        <dt class="col-sm-3">Όνομα</dt>
                                        <dd class="col-sm-9"><?php echo $info[$i]["cn"][0]; ?></dd>
                                        <dt class="col-sm-3">Group ID Number</dt>
                                        <dd class="col-sm-9"><?php echo $info[$i]["gidnumber"][0]; ?></dd>
                                        <dt class="col-sm-3">Μέλη LDAP Group</dt>
                                        <dd class="col-sm-9"><?php echo $info[$i]["uniquemember"]["count"]; ?></dd>
                                    </dl>

                                    <?php if (($info[$i]["cn"][0] == "admins") | ($info[$i]["cn"][0] == "everybody") | ($info[$i]["cn"][0] == "deactivated")) { ?>
                                        <button type="button" class="btn btn-outline-dark" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" data-bs-toggle="modal" data-bs-target="#Modalfor<?php echo $info[$i]["cn"][0]; ?>">Επεξεργασία μελών</button>
                                    <?php } else { ?>

                                        <form action="edit-groups.php" method="POST">
                                            <button type="button" class="btn btn-outline-dark" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" data-bs-toggle="modal" data-bs-target="#Modalfor<?php echo $info[$i]["cn"][0]; ?>">Επεξεργασία μελών</button>

                                            <input type="text" name="group-to-delete" value="cn=<?php echo $info[$i]["cn"][0]; ?>,ou=groups,dc=example,dc=com" hidden>
                                            <input type="text" name="form-id" value="3" hidden>
                                            <button type="submit" class="btn btn-outline-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">Διαγραφή group</button>
                                        </form>

                                    <?php
                                    }
                                    $dn = "dc=example,dc=com";
                                    $filter = "(&(displayName=*)(memberOf=cn=" . $info[$i]["cn"][0] . ",ou=groups,dc=example,dc=com))";
                                    $vars = array("uidnumber", "sn", "givenname", "mail", "telephonenumber", "uid");
                                    $sr = ldap_search($ldap_con, $dn, $filter, $vars);
                                    $info2 = ldap_get_entries($ldap_con, $sr);

                                    $filter = "(&(displayName=*)(!(memberOf=cn=" . $info[$i]["cn"][0] . ",ou=groups,dc=example,dc=com)))";
                                    $sr = ldap_search($ldap_con, $dn, $filter, $vars);
                                    $info3 = ldap_get_entries($ldap_con, $sr);
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="modal modal-lg fade" id="Modalfor<?php echo $info[$i]["cn"][0] ?>" tabindex="-1" aria-labelledby="Modalfor<?php echo $info[$i]["cn"][0] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="Modalfor<?php echo $info[$i]["cn"][0] ?>">Επεξεργασία μελών ομάδας <?php echo $info[$i]["cn"][0]; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="edit-groups.php" method="POST">

                                        <input type="text" name="count-enabled" value=<?php echo $info2["count"]; ?> hidden>
                                        <input type="text" name="count-available" value=<?php echo $info3["count"]; ?> hidden>
                                        <input type="text" name="group-name" value=<?php echo $info[$i]["cn"][0]; ?> hidden>
                                        <input type="text" name="form-id" value='2' hidden>

                                        <div class="modal-body">
                                            <?php
                                            if ($info2["count"] >= 1) {
                                            ?>
                                                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-0 mb-3 border-bottom">
                                                    <h5>Μέλη LDAP Group</h5>
                                                </div>
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Επώνυμο</th>
                                                            <th scope="col">Όνομα</th>
                                                            <th scope="col">uid</th>
                                                            <th scope="col">
                                                                <center>Αφαίρεση</center>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <?php
                                                    echo "<tbody>";
                                                    for ($j = 0; $j < $info2["count"]; $j++) {
                                                        echo "<tr>";
                                                        echo "<th class='align-middle' scope='row'>" . ($j + 1) . "</th>";
                                                        echo "<td class='align-middle'>" . $info2[$j]["givenname"][0] . "</td>";
                                                        echo "<td class='align-middle'>" . $info2[$j]["sn"][0] . "</td>";
                                                        echo "<td class='align-middle'>" . $info2[$j]["uidnumber"][0] . "</td>";
                                                        echo '<td class="align-middle"><center><input class="form-check-input" type="checkbox" id="remove" name="remove[' . $j . ']" value="uid=' . $info2[$j]["uid"][0] . ',ou=people,dc=example,dc=com"></center></td>';
                                                        echo "</tr>";
                                                    }
                                                    echo "</tbody></table>";
                                                }

                                                if ($info3["count"] >= 1) {
                                                    ?>
                                                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-0 mb-3 border-bottom">
                                                        <h5>Διαθέσιμα μέλη για προσθήκη στο LDAP Group</h5>
                                                    </div>
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Επώνυμο</th>
                                                                <th scope="col">Όνομα</th>
                                                                <th scope="col">uid</th>
                                                                <th scope="col">
                                                                    <center>Προσθήκη</center>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                    <?php
                                                    echo "<tbody>";
                                                    for ($k = 0; $k < $info3["count"]; $k++) {
                                                        echo "<tr>";
                                                        echo "<th class='align-middle' scope='row'>" . ($k + 1) . "</th>";
                                                        echo "<td class='align-middle'>" . $info3[$k]["givenname"][0] . "</td>";
                                                        echo "<td class='align-middle'>" . $info3[$k]["sn"][0] . "</td>";
                                                        echo "<td class='align-middle'>" . $info3[$k]["uidnumber"][0] . "</td>";
                                                        echo '<td class="align-middle"><center><input class="form-check-input" type="checkbox" id="add" name="add[' . $k . ']" value="uid=' . $info3[$k]["uid"][0] . ',ou=people,dc=example,dc=com"></center></td>';
                                                        echo "</tr>";
                                                    }
                                                    echo "</tbody></table>";
                                                } ?>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-dark">Αποθήκευση αλλαγών</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                <?php }
                    echo '</div>';
                } else echo "Δεν υπάρχουν ομάδες LDAP σε αυτή τη Βάση Δεδομένων.";
                ldap_unbind($ldap_con);
                ?>

            </main>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Δημιουργία νέας ομάδας LDAP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="edit-groups.php" method="POST">
                    <input type="text" name="form-id" value="1" hidden>
                    <div class="modal-body">
                        <div class="alert alert-primary d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                                <use xlink:href="#info-fill" />
                            </svg>
                            <div>
                                Σημείωση: για τη δημιουργία του group πρέπει να υπάρχει εγγεγραμμένος σε αυτό τουλάχιστον ένας χρήστης. Για τεχνικούς λόγους, ως πρώτος
                                χρήστης εισάγεται αυτόματα αυτός που δημιουργεί τη φόρμα. Ο ίδιος μπορεί να αφαιρεθεί μετά την προσθήκη άλλων χρηστών στο group.
                            </div>
                        </div>
                        <label for="group-name" class="form-label">Όνομα group</label>
                        <input type="text" class="form-control" id="group-name" name="group-name" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-dark">Δημιουργία</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="../bootstrap-5.2.0-beta1-dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="dashboard.js"></script>
</body>

</html>