<?php
session_start();
if (!isset($_SESSION["uid"])) header("Location: ../login/?redir=search.php");

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
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="/dashboard">Company </a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <form action="search.php" method="POST" class="p-0 w-100 m-0">
      <input class="form-control form-control-dark w-100 rounded-0 border-0" type="search" id="search_term" name="search_term" placeholder="Αναζήτηση μελών (ονοματεπώνυμο/email)" aria-label="Search" autofocus>
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
                <a class="nav-link active" href="#">
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
                  <a class="nav-link" href="create-account.php">
                    <span data-feather="file-text" class="align-text-bottom"></span>
                    Δημιουργία χρήστη
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="edit-groups.php">
                    <span data-feather="file-text" class="align-text-bottom"></span>
                    Επεξεργασία ομάδων
                  </a>
                </li>
              </ul>
            <?php } ?>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Αναζήτηση Μελών</h1>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
          <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
          </symbol>
        </svg>
        <div class="alert alert-primary d-flex align-items-center" role="alert">
          <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
            <use xlink:href="#info-fill" />
          </svg>
          <div>
            Χρησιμοποιήστε το παραπάνω πεδίο για να αναζητήσετε μέλη της ομάδας.
          </div>
        </div>
        <?php
        if (isset($_POST["search_term"])) {
          if (function_exists('ldap_connect')) {
            $ldap_dn = "cn=admin,dc=example,dc=com";
            $ldap_pass = "password";
            $ldap_con = ldap_connect("ldap://localhost");

            ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
            if (!ldap_bind($ldap_con, $ldap_dn, $ldap_pass)) echo "Internal Error! Please contact the system administrator";
          } else echo "The php LDAP module is not installed on this server.";

          $dn = "ou=people,dc=example,dc=com";
          $filter = "(&(|(mail=" . $_POST["search_term"] . "*)(displayName=*" . $_POST["search_term"] . "*))(!(memberOf=cn=deactivated,ou=groups,dc=example,dc=com)))";
          $vars = array("uidnumber", "sn", "givenname", "mail", "telephonenumber");
          $sr = ldap_search($ldap_con, $dn, $filter, $vars);
          $info = ldap_get_entries($ldap_con, $sr);
          if ($info["count"] >= 1) {
        ?>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Όνομα</th>
                    <th scope="col">Επώνυμο</th>
                    <th scope="col">Email</th>
                    <th scope="col">Τηλέφωνο</th>
                  </tr>
                </thead>
            <?php
            echo "<tbody>";
            for ($i = 0; $i < $info["count"]; $i++) {
              echo "<tr>";
              echo "<th scope='row'>" . ($i + 1) . "</th>";
              echo "<td>" . $info[$i]["givenname"][0] . "</td>";
              echo "<td>" . $info[$i]["sn"][0] . "</td>";
              echo "<td>" . $info[$i]["mail"][0] . "</td>";
              echo "<td>" . $info[$i]["telephonenumber"][0] . "</td>";
              echo "</tr>";
            }
            echo "</tbody></table></div>";
          } else echo "Η αναζήτηση σας δεν επέστρεψε κάποιο αποτέλεσμα.";
          
          ldap_unbind($ldap_con);
        }
            ?>
      </main>
    </div>
  </div>


  <script src="../bootstrap-5.2.0-beta1-dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
  <script src="dashboard.js"></script>
</body>

</html>