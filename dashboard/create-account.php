<?php
session_start();

if (!isset($_SESSION["uid"])) header("Location: ../login/?redir=create-account.php");
if ($_SESSION["access_level"] == "user") header("Location: no-access.php");

if (function_exists('ldap_connect')) {
  $ldap_dn = "cn=admin,dc=example,dc=com";
  $ldap_pass = "password";
  $ldap_con = ldap_connect("ldap://localhost");

  ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
  ldap_bind($ldap_con, $ldap_dn, $ldap_pass);
} else echo "No LDAP";

$dn = "dc=example,dc=com";
$filter = "(cn=lastUID)";
$vars = array("serialNumber");
$sr = ldap_search($ldap_con, $dn, $filter, $vars);
$info = ldap_get_entries($ldap_con, $sr);
ldap_unbind($ldap_con);
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
  <link href="../dashboard/dashboard.css" rel="stylesheet">
  <script src="func.js"></script>
</head>

<body>

  <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="/dashboard">Company </a>
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
                  <a class="nav-link active" href="#">
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

        <?php if ($_GET["message"] == "success") { ?>

          <div class="alert alert-success d-flex align-items-center mt-3" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
              <use xlink:href="#check-circle-fill" />
            </svg>
            <div>
              Τα στοιχεία καταχωρήθηκαν.
            </div>
          </div>
        <?php } else if ($_GET["message"] == "error") { ?>

          <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
              <use xlink:href="#exclamation-triangle-fill" />
            </svg>
            <div>
              Υπήρξε κάποιο πρόβλημα με την καταχώρηση των στοιχείων σας.
            </div>
          </div>
        <?php } else if ($_GET["message"] == "username") { ?>

          <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
              <use xlink:href="#exclamation-triangle-fill" />
            </svg>
            <div>
              Υπάρχει άλλος χρήστης με το ίδιο username. Χρησιμοποιήστε τη φόρμα που βρίσκεται <a href="create-account-manually">εδώ</a> για να συμπληρώσετε όλα τα πεδία χειροκίνητα.
            </div>
          </div>
        <?php } ?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Δημιουργία Λογαριασμού</h1>
        </div>

        <div class="alert alert-primary d-flex align-items-center" role="alert">
          <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
            <use xlink:href="#info-fill" />
          </svg>
          <div>
            Το πεδίο username παράγεται αυτόματα και βάσει αυτού διαμορφώνονται όλα τα απενεργοποιημένα πεδία.<br>
            Σε περίπτωση που θέλετε να συμπληρώσετε μόνοι σας όλα τα πεδία, πατήστε <a href="create-account-manually.php" class="alert-link">εδώ</a>.
          </div>
        </div>
        <div class="container-fluid">
          <form action="submit-new.php" method="POST" class="row g-3 needs-validation" oninput="cpassword.setCustomValidity(password.value != cpassword.value ? '0' : '');name.setCustomValidity(!/^[a-zA-Z]+$/.test(name.value) ? '0' : '');surname.setCustomValidity(!/^[a-zA-Z]+$/.test(surname.value) ? '0' : '')" novalidate>
            <div class="col-md-6">
              <label for="name" class="form-label">Όνομα</label>
              <input type="text" onkeyup="autoFillUsername();" placeholder="Λατινικοί χαρακτήρες" class="form-control" id="name" name="name" required>
              <div class="invalid-feedback">Αυτό το πεδίο είναι υποχρεωτικό και πρέπει να συμπληρωθεί με λατινικούς χαρακτήρες.</div>
            </div>
            <div class="col-md-6">
              <label for="surname" class="form-label">Επώνυμο</label>
              <input type="text" onkeyup="autoFillUsername();" placeholder="Λατινικοί χαρακτήρες" class="form-control" id="surname" name="surname" required>
              <div class="invalid-feedback">Αυτό το πεδίο είναι υποχρεωτικό και πρέπει να συμπληρωθεί με λατινικούς χαρακτήρες.</div>
            </div>
            <div class="col-md-6">
              <label for="phone" class="form-label">Τηλέφωνο</label>
              <input type="tel" class="form-control" id="phone" name="phone" required>
              <div class="invalid-feedback">Πρέπει να συμπληρωθεί με έναν έγκυρο τηλεφωνικό αριθμό.</div>
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
              <div class="invalid-feedback">Πρέπει να συμπληρωθεί μια έγκυρη διεύθυνση e-mail.</div>
            </div>
            <div class="col-md-6">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" readonly>
            </div>
            <div class="col-md-6">
              <label for="displayname" class="form-label">Εμφανιζόμενο Όνομα</label>
              <input type="text" class="form-control" id="displayname" name="displayname" readonly>
            </div>
            <div class="col-md-6">
              <label for="homedir" class="form-label">Home Directory</label>
              <input type="text" class="form-control" id="homedir" name="homedir" readonly>
            </div>
            <div class="col-md-6">
              <label for="cn" class="form-label">Common Name</label>
              <input type="text" class="form-control" id="cn" name="cn" readonly>
            </div>
            <div class="col-md-6">
              <label for="loginshell" class="form-label">Login Shell</label>
              <input type="text" class="form-control" id="loginshell" name="loginshell" value="/bin/bash" readonly>
            </div>
            <div class="col-md-6">
              <label for="uidnumber" class="form-label">UID</label>
              <input type="text" class="form-control" id="uidnumber" value=<?php echo $info[0]['serialnumber'][0] + 1; ?> name="uidnumber" readonly>
            </div>
            <div class="col-md-6">
              <label for="password" class="form-label">Κωδικός Πρόσβασης</label>
              <input type="password" class="form-control" id="password" name="password" required>
              <div class="invalid-feedback">Αυτό το πεδίο είναι υποχρεωτικό.</div>
            </div>
            <div class="col-md-6">
              <label for="cpassword" class="form-label">Επιβεβαίωση Κωδικού Πρόσβασης</label>
              <input type="password" class="form-control" id="cpassword" name="cpassword" required>
              <div class="invalid-feedback">Αυτό το πεδίο είναι υποχρεωτικό και πρέπει να συμφωνεί με τον κωδικό πρόσβασης.</div>
            </div>
            <div class="col-md-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="send-email1" id="send-email" name="send-email">
                <label class="form-check-label" for="send-email">
                  Αποστολή στοιχείων με email στον χρήστη <?php echo $_GET["user"] ?>
                </label>
              </div>
            </div>
            <div class="col-auto">
              <button type="submit" class="btn btn-primary">Υποβολή</button>
              <button type="reset" class="btn btn-secondary">Εκκαθάριση</button>
              <input id="clickMe" class="btn btn-outline-secondary" type="button" value="Παραγωγή τυχαίου κωδικού" onclick="genPassword();" />
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>


  <script src="../bootstrap-5.2.0-beta1-dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
  <script src="dashboard.js"></script>
  <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
      'use strict'

      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      const forms = document.querySelectorAll('.needs-validation')

      // Loop over them and prevent submission
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
</body>

</html>