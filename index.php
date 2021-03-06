<?php
if (isset($_COOKIE["locale"]) && !isset($_GET["locale"])) {
  header("Location: interface.php?locale=".$_COOKIE["locale"]);
}
header('X-UA-Compatible: IE=edge');
?>

<!DOCTYPE html>
<html>
<head>
<title>African Flood and Drought Monitor</title>
<link rel="icon" type="image/ico" href="icons/AWCM_logo.ico">
<link href="css/bootstrap.css" rel="stylesheet" media="screen">
<style>
body{
 font-family:"Trebuchet MS", Helvetica, sans-serif
}
#Background_Image{
position: fixed;
top: 0;
left: 0;
min-width: 100%;
min-height: 100%;
z-index:-1;
opacity:0.8;
}
#Welcome{
text-align:center;
}
#Controls{
 margin: auto;
 margin-top: 125px;
 padding: 25px;
 background: #FFF;
 width: 500px;
 -moz-box-shadow: 0px 0px 15px #000;
 -webkit-box-shadow: 0px 0px 15px #000;
 box-shadow: 0px 0px 15px #000;
}
#ChangeLanguage{
 margin:auto;
 margin-left: 50px;
 font-size: 15.5px; 
 width: 170px;
}
#Language{
 margin-left:50px;
 font-size: 20.5px;
 font-weight: normal;
 font-family:"Trebuchet MS", Helvetica, sans-serif;
}
#Title{
 font-weight: normal;
 font-size: 3.5em;
 margin-top: 50px;
 min-width:720px;
}
#Proceed{
 margin-left:250px;
 background-color:#4D962B;
 color:white;
 font-size: 17.5px;
}
#Logos_Left{
 top:200px;
 left:0px;
 width:150px;
 position:absolute;
}
#Logos_Right{
 top:200px;
 right:0px;
 width:150px;
 position:absolute;
}

#Logos_Image{
 width:100px;
 padding-bottom:30px;
 padding-left:25px;
 padding-right:25px;
}

#Title_Image{
 margin-top: 50px;
 width:400px;
 top:
}
</style>
</head>
<body>
<img src="icons/NigerSunset.jpg" id="Background_Image">
<div id="Welcome">
 <img id="Title_Image" src="icons/AFDM.png"><br>
 <!--<h1 id="Title">African Water Cycle Monitor</h1>-->
</div>
<div id="Controls">
 <form name="select" action="interface.php" method="get">
 <h3 id="Language">Choose a Language
  <select name="locale" id="ChangeLanguage" onchange=ChangeLanguage(value)>
   <option value="en">English</option>
   <option value="sp">Español</option>
   <option value="fr">Français</option>
   <option value="cn">中文</option>
   <option value="ar">عربي</option>
  </select>
 </h3>
 <hr noshade>
 <input id="Proceed" type="submit" value="Proceed to Website">
 </form>
</div>
 <div id="Logos_Left">
        <a href="http://www.princeton.edu"><img id="Logos_Image" src="icons/puLogo.png"></a><br>
        <a href="http://www.unesco.org"><img id="Logos_Image" src="icons/Unesco_logo.svg"></a><br>
        <!--<a href="http://www.washington.edu"><img id="Logos_Image" src="icons/UW_logo.jpg"></a>-->
 </div>
 <div id="Logos_Right">
        <a href="http://www.agrhymet.ne"><img id="Logos_Image" src="icons/CILSS_Logo.png"></a><br>
        <a href="http://www.icpac.net"><img id="Logos_Image" src="icons/ICPAC_logo.jpg"></a><br>
 </div>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="jsscripts/bootstrap.min.js"></script>
</body>
</html>
