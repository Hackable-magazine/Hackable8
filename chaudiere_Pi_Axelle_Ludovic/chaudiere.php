<html>
<head><title>Le mas des crocodiles</title>
<link rel="stylesheet" type="text/css" href="./style.css" />
</head>
<body>
<h1>--- Les crocodiles veulent avoir chaud ---</h1>

<a href="./index.php">...Retour &agrave; l'accueil</a><br>
<br>
<a href="./images/2014-schema-chaudiere.jpg"><img src="./images/2014-schema-chaudiere.jpg" width="30%" /></a>
<a href="./images/2014-detail-chaudiere.jpg"><img src="./images/2014-detail-chaudiere.jpg" width="30%" /></a>
<br>

<?php
 echo "Actuellement, la temp&eacute;rature int&eacute;rieure est : <b>";
   $output = array();
   exec('/home/axelle/scripts/intemp.sh', $output);
   echo $output[0];
   echo " &deg;C</b><br>";
?>

<h1>Chaudi&egrave;re (chauffage, eau chaude)</h1>


<div class="rectred">

<?php
   echo "Actuellement, la chaudi&egrave;re est : <b>";
   $output = array();
   exec('sudo /usr/bin/chaudiere.sh chaudiere_status 2>&1', $output);
   echo $output[0];
   echo "</b>";

   echo "<form method='post' action='chaudiere.php'>";
   if ($output[0] == "On") {
     echo "<button name='button_chaudiere_on' class='btndisabled' disabled='True'>On</button>";
     echo "<button name='button_chaudiere_off' class='btnred' value='Off' onClick='window.location.reload()'>Off</button>";
   } else {
    echo "<button value='On' name='button_chaudiere_on' class='btngreen' onClick='window.location.reload()'>On</button>";
    echo "<button value='Off' name='button_chaudiere_off' class='btndisabled' disabled='True'>Off</button>";
   }
   echo "</form>"; 

   if (! empty($_POST["button_chaudiere_on"])) {
      exec('sudo /usr/bin/chaudiere.sh chaudiere_on 2>&1', $output);
      echo "<meta http-equiv=refresh content=\"0; URL=chaudiere.php\">";
   } else if (! empty($_POST["button_chaudiere_off"])) {
      exec('sudo /usr/bin/chaudiere.sh chaudiere_off 2>&1', $output);
      echo "<meta http-equiv=refresh content=\"0; URL=chaudiere.php\">";
   }

?>


</div>

<!--
num 17 : la chaudiere
num 27 : le thermostat en manuel (mode actuel)
num 22 : le thermostat controlee par un programme i.e., on met sur on uniquement si la temperature voulue n'est pas atteinte
num 23 : for future use

22 et 27 ne doivent jamais etre on en meme temps
-->

<h1>Chauffage (radiateurs)</h1>
<div class="rectred">
<?php
   echo "Actuellement, le chauffage est : <b>";
   $output = array();
   exec('sudo /usr/bin/chaudiere.sh chauffage_status 2>&1', $output);
   echo $output[0];
   echo "</b>";

   echo "<form method='post' action='chaudiere.php'>";
   if ($output[0] != 'Chaudiere desactivee') {
     if ($output[0] == 'Off') {
       echo "<input type='radio' name='chauffage' value='off' checked>Off<br>";
     } else {
       echo "<input type='radio' name='chauffage' value='off' >Off<br>";
     }
     if ($output[0] == 'Manuel') {
       echo "<input type='radio' name='chauffage' value='manuel' checked>R&eacute;gulation avec thermostat int&eacute;rieur manuel<br>";
     } else {
       echo "<input type='radio' name='chauffage' value='manuel'>R&eacute;gulation avec thermostat int&eacute;rieur manuel<br>";
     }
     if ($output[0] == 'Auto') {
       echo "<input type='radio' name='chauffage' value='auto' checked>R&eacute;gulation par programme";
     } else {
       echo "<input type='radio' name='chauffage' value='auto' >R&eacute;gulation par programme";
     }
     echo " &agrave; temp&eacute;rature (C):";
     echo "<input type='text' name='temp' value=''> (ne marche pas actuellement)<br>";
     echo "<input type='submit' value='Confirmer'>";
   } else {
     echo "<input type='radio' name='chauffage' value='off' disabled='True'>Off<br>";
     echo "<input type='radio' name='chauffage' value='manuel' disabled='True'>R&eacute;gulation avec thermostat int&eacute;rieur manuel<br>";
     echo "<input type='radio' name='chauffage' value='auto' disabled='True'>R&eacute;gulation par programme";
     echo " &agrave; temp&eacute;rature (C):";
     echo "<input type='text' name='temp' value='' disabled='True'><br>";
     echo "<input type='submit' value='Confirmer' disabled='True'>";
   } 
   echo "</form>";
   if ($_POST["chauffage"] == 'off') {
      exec('sudo /usr/bin/chaudiere.sh chauffage_off 2>&1', $output);
      echo "<meta http-equiv=refresh content=\"0; URL=chaudiere.php\">";
   } else if ($_POST["chauffage"] == 'manuel') {
      exec('sudo /usr/bin/chaudiere.sh chauffage_manuel 2>&1', $output);
      echo "<meta http-equiv=refresh content=\"0; URL=chaudiere.php\">";
   } else if ($_POST["chauffage"] == 'auto') {
      exec('sudo /usr/bin/chaudiere.sh chauffage_auto '.$_POST['temp'].' 2>&1', $output);
      echo "<meta http-equiv=refresh content=\"0; URL=chaudiere.php\">";
   } 
?>


</div>




</body></html>
