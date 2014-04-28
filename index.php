<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Lenovo Store</title>

    <!--CSS-->
    <link rel="stylesheet" type="text/css" href="../css/jquery.fullPage.css" />
    <link rel="stylesheet" type="text/css" href="../css/examples.css" />
    <link rel="stylesheet" type="text/css" href="../css/style.css" />

    <!--JS-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../js/vendors/jquery.slimscroll.min.js"></script>
    <script type="text/javascript" src="../js/jquery.fullPage.js"></script>
    <script type="text/javascript" src="../js/examples.js"></script>

    <!--Script Settings-->
    <script type="text/javascript">
        $(document).ready(function () {
            $.fn.fullpage({
                anchors: ['firstPage', 'secondPage', '3rdPage', '4thPage', '5thPage', '6thPage'],
                //'slidesColor': ['white', '#0099ff', '#daa520', '#A7DBD8', '#F38630', '#666'],
                'scrollOverflow': true,
                navigation: true,
                navigationPosition: 'right',
                navigationTooltips: ['Home', 'Laptops', 'Tablets', 'All-In-Ones', 'PCs', 'Contact Us'],
                slidesNavigation: true,
                slidesNavPosition: 'bottom'
            });
        });
    </script>

</head>
<body>
<?php 

include "conf.php";
		
		//Подключаемся к базе данных.
		
		$dbcon = mysql_connect($base_name, $base_user, $base_pass); 
		mysql_select_db($db_name, $dbcon);
		if (!$dbcon)
		{
			echo "<h2>Error in connecting to MySQL</h2>".mysql_error(); exit();
		} else {
			if (!mysql_select_db($db_name, $dbcon))
			{
				echo("<h2>Selected DB is not existing</h2>");
			}
		}
					
		mysql_query("SET NAMES 'utf8'");
		
		$result1 = mysql_query("SELECT * FROM products WHERE category = 'laptops'",$dbcon);
		
		$result2 = mysql_query("SELECT * FROM products WHERE category = 'tablets'",$dbcon);
		
		$result3 = mysql_query("SELECT * FROM products WHERE category = 'allinone'",$dbcon);
		
		$result4 = mysql_query("SELECT * FROM products WHERE category = 'PC'",$dbcon);
		
		

?>
    <!--Menu-->
    <div class="section " id="section0">
        <div class="intro">
            <img src="../img/bg/bgHome.png" alt="Lenovo" id="bgHome" style="margin-bottom:5%;margin-top:2%;"/><br />
            <a href="#secondPage" class="rollover1" title="Laptop"><span class="displace1">Laptop</span></a>
            <a href="#3rdPage" class="rollover2" title="Tablet"><span class="displace2">Tablet</span></a>
            <a href="#4thPage" class="rollover3" title="AllinOne"><span class="displace3">AllinOne</span></a>
            <a href="#5thPage" class="rollover4" title="PC"><span class="displace4">PC</span></a>
			
			<a href="https://www.facebook.com/lenovo"><img src="../img/icns/f.png" alt="Facebook" width="5%" style="margin-top:5%;"/></a>
			<a href="https://twitter.com/lenovo"><img src="../img/icns/t.png" alt="Facebook" width="5%" style="margin-top:5%; margin-left:5%;"/></a>
			<a href="https://plus.google.com/+Lenovo"><img src="../img/icns/g+.png" alt="Facebook" width="5%" style="margin-top:5%; margin-left:5%;"/></a>
        </div>
    </div>

    <!--Laptops-->
    <div class="section" id="section1">
	
		<?php
		while($laptops = mysql_fetch_array($result1))
		{
			echo("<div class='slide'>
					<div class='insideslide'>");
					
			echo($laptops['describing']);
			
			echo("</div></div>");
		}
	
	
		?>

    </div>

    <!--Tablets-->
    <div class="section" id="section2">
	<?php
		while($tablets = mysql_fetch_array($result2))
		{
			echo("<div class='slide'>
					<div class='insideslide'>");
					
			echo($tablets['describing']);
			
			echo("</div></div>");
		}
	
	
		?>
    </div>

    <!--All-in-Ones-->
    <div class="section" id="section3">
       <?php
		while($allinones = mysql_fetch_array($result3))
		{
			echo("<div class='slide'>
					<div class='insideslide'>");
					
			echo($allinones['describing']);
			
			echo("</div></div>");
		}
	
	
		?>
    </div>

    <!--PCs-->
    <div class="section" id="section4">
       <?php
		while($pcs = mysql_fetch_array($result4))
		{
			echo("<div class='slide'>
					<div class='insideslide'>");
					
			echo($pcs['describing']);
			
			echo("</div></div>");
		}
		?>
    </div>

    <!--Contact Us-->
    <div class="section" id="section5">
            <h1>Contact us</h1>
			<br/>
			<b>customer@panda.fi</b>
			<br/><br/>
			<b>+358 4 0054 6582</b>
			<br/><br/>
            <div id="textPCs">
                <p>
                   Nearest office is in Espoo:
                </p>
				<br/>
				<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1981.7473612558606!2d24.814403!3d60.21801099999999!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x468df67bd79cddc9%3A0xcf5fe12e3a4c2ce9!2sLenovo+Technology+B.V.+sivuliike+Suomessa!5e0!3m2!1sru!2s!4v1398594242988" width="800" height="600" frameborder="0" style="border:0"></iframe>
            </div>
    </div>

</body>
</html>