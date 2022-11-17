function genPassword() {
    var chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var passwordLength = 12;
    var password = "";
 for (var i = 0; i <= passwordLength; i++) {
   var randomNumber = Math.floor(Math.random() * chars.length);
   password += chars.substring(randomNumber, randomNumber +1);
  }
        document.getElementById("password").value = password;
        document.getElementById("cpassword").value = password;
 }

 function autoFillUsername(){
  var gn = document.getElementById("name").value;
  var sn = document.getElementById("surname").value;
  //var un = document.getElementById("username").value;
  document.getElementById("username").value = gn[0].toLowerCase() + sn.toLowerCase();
  document.getElementById("displayname").value = gn + " " + sn;
  document.getElementById("cn").value = gn.toLowerCase() + sn.toLowerCase();
  document.getElementById("homedir").value = "/home/" + gn[0].toLowerCase() + sn.toLowerCase();
 }

function close_window() {
  if (confirm("Close Window?")) {
    close();
  }
}

function validate(name, surname, password, cpassword) {
  var password = document.getElementById("password").value;
  var cpassword = document.getElementById("cpassword".value);
  var name = document.getElementById("name").value;
  var surname = document.getElementById("surname").value;
  
  cpassword.setCustomValidity(password != cpassword ? '0' : '');
  name.setCustomValidity(!/^[a-zA-Z]+$/.test(name) ? '0' : '');
  surname.setCustomValidity(!/^[a-zA-Z]+$/.test(surname) ? '0' : '')
}