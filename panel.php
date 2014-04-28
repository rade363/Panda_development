<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css" type="text/css" /> 
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>  
<script src="js/visualize.jQuery.js"></script>    
<title>Panda development project</title>
<script>
var staymovevar=0;
function statpopupfunc(){
$("#alert_box").hide();
$("#alert_box").slideDown("slow");
setTimeout(function()
    {
		if(staymovevar==0)
			$("#alert_box").slideUp("slow");

    }, 5000);
}

function staymove(){
if(staymovevar==0) { staymovevar++;}
else {
$("#alert_box").slideUp("slow");}
}
</script>
</head>
  
<body onload="statpopupfunc()">


<?php

//world variables:
$action = "";
$id = -1;

?>


<div id="upper_menu"> 
	<ul>
		<a href="panel.php?action=orders"><li <?php if(isset($_GET['action'])) { if($_GET['action'] == "orders") { echo("class='selected'"); $action="orders"; }} else { echo("class='selected'"); $action="orders"; }?>>Orders</li></a>
		<a href="panel.php?action=statistic"><li <?php if(isset($_GET['action'])) { if($_GET['action'] == "statistic") { echo("class='selected'"); $action="statistic"; }} ?>>Statistic</li></a>
		<a href="panel.php?action=products"><li <?php if(isset($_GET['action'])) { if(($_GET['action'] == "products") || ($_GET['action'] == "edit") || ($_GET['action'] == "delete")) { echo("class='selected'"); $action="products"; }} ?>>Products</li></a>
		<a href="panel.php?action=exit"><li><img src="img/exit.png" style="
			margin-top:-4px;
			margin-right:5px;
			vertical-align:middle;
		"/>Exit</li></a>
	</ul>
	
</div>	






<?php

	session_start();
	
	if(isset($_GET['action']))
	{
		if($_GET['action'] == "exit")
		{
			session_destroy();
			header("Location: index.html");
		}
		
		$action = $_GET['action'];
	}
	
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
	}
	
	if((isset($_SESSION["email"])))
	{
		$email = $_SESSION["email"];
		$hash = $_SESSION["hash"];
		
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
		
		$result = mysql_query("SELECT * FROM ultra_secured_table_of_users WHERE email='$email'",$dbcon);
		$myrow = mysql_fetch_array($result);
		
		if($myrow["hash"] == $hash)
		{
			
			/* ACTION = ORDERS */
			
			if($action == "orders")
			{

				/* ALERT BOX */
				if(isset($_SESSION["alert"]))
					if($_SESSION["alert"] == "true")
					{
						echo("<div id='alert_box' onclick='staymove()'>");
						echo("You entered as <b>".$email."</b>, and your IP = <b>".$_SERVER["REMOTE_ADDR"]."</b><br/><br/>");
						echo("Your last login was <b>".$myrow["last_login"]."</b> from IP = <b>".$myrow["last_ip"]."</b><br/><br/>");
						echo("</div>");
						$_SESSION["alert"] = "false";
					}
				/* ALERT BOX */
				
				echo("<a href='panel.php?action=order_add'><div id='add_button'><table><tr><td><img src='img/add.png' style='margin-right:10px;'/></td><td>Add order</td></tr></table></div></a>");
						
				
				echo("
				<table class='features-table'>
				<thead>
					<tr>
						<td style='width:10%;'>ID</td>
						<td class='grey' style='width:50%;'>Describing</td>
						<td class='grey' style='width:10%;'>Product ID</td>
						<td class='grey' style='width:10%;'>Product name</td>
						<td class='grey' style='width:10%;'>How many</td>
						<td class='grey' style='width:10%;'>Total price</td>
						<td class='grey' style='width:10%;'>Date</td>
						<td class='green' style='width:10%;'>Completion</td>
					</tr>
				</thead>
				<tbody>");
				
				$result = mysql_query("SELECT * FROM orders ORDER BY id DESC",$dbcon);	
				while($orders = mysql_fetch_array($result))
				{
				

				
						if($orders['completed'] == "0")
						{						
							echo("
								<tr class='uncompleted'>
									<td>".$orders['id']."</td>
									<td class='grey'>".$orders['description']."</td>
									<td class='grey'>".$orders['product_id']."</td>
									<td class='grey'>".$orders['product_name']."</td>
									<td class='grey'>".$orders['product_amount']."</td>
									<td class='grey'>".$orders['total_price']."</td>
									<td class='grey'>".$orders['date']."</td>
									<td class='green'>In progress <br/><br/>
									<a href='panel.php?action=edit_order&id=".$orders['id']."'  style='margin-right:25px;'><img src='img/edit.png'/></a>
									<a href='panel.php?action=delete_order&id=".$orders['id']."&description=".$orders['description']."'><img src='img/delete.jpg'/></a>
									</td>
								</tr>");
						}
						else
						{
							echo("
								<tr class='completed'>
									<td>".$orders['id']."</td>
									<td class='grey'>".$orders['description']."</td>
									<td class='grey'>".$orders['product_id']."</td>
									<td class='grey'>".$orders['product_name']."</td>
									<td class='grey'>".$orders['product_amount']."</td>
									<td class='grey'>".$orders['total_price']."</td>
									<td class='grey'>".$orders['date']."</td>
									<td class='green'>Finished <br/><br/>
									<a href='panel.php?action=edit_order&id=".$orders['id']."'  style='margin-right:25px;'><img src='img/edit.png'/></a>
									<a href='panel.php?action=delete_order&id=".$orders['id']."&description=".$orders['description']."'><img src='img/delete.jpg'/></a>
									</td>
								</tr>");
						}

				}
					
				echo("</tbody>
				</table>
				");
				
				
				
			}
			

			if($action == "order_add")
			{
				
				if(isset($_GET['id']))
				{
					try
					{
						$id = $_GET['id'];
						$description = $_GET['description'];
						$product_id = $_GET['product_id'];
						$amount = $_GET['amount'];
						
						if(($id == null) || ($description == null) || ($product_id == null) || ($amount == null))
							echo '<h2>You need to fill every field</h2>';
						else
						{
							$result2 = mysql_query("SELECT * FROM products WHERE id = '".$product_id."' LIMIT 1",$dbcon);
							
							while($product = mysql_fetch_array($result2))	
							{					
								$product_name = $product['name'];
								
							
								if($product['store'] >= $amount)
								{
									$DateOfRequest = date("Y-m-d"); 
									$left = $product['store'] - $amount;
									$total_price = $product['price'] * $amount;
									
								
									$sql = mysql_query("INSERT INTO orders (id, description, product_id, product_name, product_amount, total_price, date) VALUES('".$id."', '".$description."',
									'".$product_id."', '".$product_name."', '".$amount."', '".$total_price."', '".$DateOfRequest."');", $dbcon);
									
									$amount += $product['sold_amount'];
									
									$sql2 = mysql_query("UPDATE products SET store = '".$left."', sold_amount = '".$amount."' where id = '".$product_id."'", $dbcon);

									if(($sql) && ($sql2))
										echo "<h2>Order was added</h2>";
									else
										echo "MySQL problem";
								}
								else
								{
									echo '<h2>There is not enough products in the store</h2>';
									echo '<center>You tried to order '.$amount.' items, though there are only '.$product_availability.' in the store</center>';
								}
								
							}
						}
					}
					catch (Exception $e) {
						echo '<h2>Wrong input, try again</h2>';
					}
				}
				else
				{
				
					$result = mysql_query("SELECT MAX(id) AS id FROM orders LIMIT 1",$dbcon);
					$result2 = mysql_query("SELECT * FROM products",$dbcon);
					
					while($id = mysql_fetch_array($result))
					{
							if($id['id'] == NULL)
								$id['id'] = 1;
							else 
								$id['id']++;
						
						
					
					
						echo("<h1>Order adding mode</h1>");
						echo("<div id='form_container'>");
						
						echo("<form name='f'><input type='hidden' name='action' value='order_add'/><table><tr>");
						echo("<td width='150px'>ID:</td><td width='600px'><input type='text' name='id' readonly required value='".$id['id']."'/></td></tr>");
						echo("<tr><td>Description:</td><td><textarea rows='10' cols='67' name='description' required></textarea></td></tr>");
						
						echo("<tr><td>Product:</td><td><select name='product_id' required>");
								
						while($products = mysql_fetch_array($result2))	
						{
							echo("<option value='".$products['id']."'>".$products['id']." - ".$products['name']." - ".$products['store']." items</option>");	
						}
								
						echo("</select>	</td></tr>");	

						
						echo("<tr><td>Amount:</td><td><input type='text' name='amount' required pattern='^[ 0-9]+$'/></td></tr>");
						
						echo("</table>");
						echo("<input type='submit' method='post' action='panel.php' value='Submit changes' style='margin-top:30px; margin-left:5px'/></form>");		
						echo("</div>");
					
					}
				}
			}
			
			/* ACTION = ORDERS */
			
			
			
			/* updating last login and IP */
			date_default_timezone_set("Europe/Helsinki"); 
			$date = date('l jS \of F Y G:i:s');
			$ip = $_SERVER["REMOTE_ADDR"];
			$sql = mysql_query("UPDATE ultra_secured_table_of_users SET last_login = '".$date."', last_ip = '".$ip."' where email = '".$email."'", $dbcon);
			/* updating last login and IP */
		
			
			/* ACTION = PRODUCTS */
			if($action == "products")
			{
				echo("<a href='panel.php?action=product_add'><div id='add_button'><table><tr><td><img src='img/add.png' style='margin-right:10px;'/></td><td>Add product</td></tr></table></div></a>");
			
				$result = mysql_query("SELECT * FROM products",$dbcon);
				
				echo("
				<table class='features-table'>
				<thead>
					<tr>
						<td style='width:5%;'>ID</td>
						<td class='grey' style='width:10%;'>Name</td>
						<td class='grey' style='width:10%;'>Category</td>
						<td class='grey' style='width:10%;'>Price</td>
						<td class='grey' style='width:35%;'>Describing</td>
						<td class='grey' style='width:10%;'>Sold amount</td>
						<td class='grey' style='width:10%;'>Store</td>
						<td class='green' style='width:10%;'>Edit</td>
					</tr>
				</thead>
				 
				<tbody>
				");
				
				
				while($products = mysql_fetch_array($result))
				{
				
				$newdescribing = str_replace("<", "&lt;", $products['describing']);
				$newdescribing = str_replace(">", "&gt;", $newdescribing);
				
				
					echo("
						<tr>
							<td>".$products['id']."</td>
							<td class='grey'>".$products['name']."</td>
							<td class='grey'>".$products['category']."</td>
							<td class='grey'>".$products['price']."</td>
							<td class='grey'>".$newdescribing."</td>
							<td class='grey'>".$products['sold_amount']."</td>
							<td class='grey'>".$products['store']."</td>
							<td class='green'>
							<a href='panel.php?action=edit_product&id=".$products['id']."'  style='margin-right:25px;'><img src='img/edit.png'/></a>
							<a href='panel.php?action=delete_product&id=".$products['id']."&name=".$products['name']."'><img src='img/delete.jpg'/></a>
							</td>
						</tr>
					");
				}
				
				echo("
				
				</tbody>
				</table>
				
				");
			}
			/* ACTION = PRODUCTS */
		
		
		
		
		
		
		
		
			/* ACTION = EDIT */

			if($action == "edit_product")
			{
				/* stupid way to check, did we recieve a request to update the BD from editing */ 
				
				if(isset($_GET['name']))
				{
				
					try
					{
						$id = $_GET['id'];
						$name = $_GET['name'];
						$category = $_GET['category'];
						$price = $_GET['price'];
						$describing = $_GET['describing'];
						$sold = $_GET['sold_amount'];
						$store = $_GET['store'];
						
						$newdescribing = str_replace("&lt;", "<", $describing);
						$newdescribing = str_replace("&gt;", ">", $newdescribing);
						$newdescribing = str_replace("'", "&#39;", $newdescribing);
						
						
						$sql = mysql_query("UPDATE products SET name = '".$name."', category = '".$category."',
						price = '".$price."', describing = '".$newdescribing."', sold_amount = '".$sold."', store = '".$store."'
						where id = '".$id."'", $dbcon);
						
						
						if($sql)
							echo "<h2>Product was updated</h2>";
						else
							echo "<h2>Something went wrong, ask help from support.</h2>";
					}
					catch (Exception $e) {
						echo '<h2>Wrong input, try again</h2>';
					}
					
				
				}
				else
				{				
					echo("<h1>Product editing mode</h1>");
					echo("<div id='form_container'>");
				
					$result = mysql_query("SELECT * FROM products WHERE id = ".$id." LIMIT 1",$dbcon);
					$result2 = mysql_query("SELECT * FROM categories",$dbcon);
					
					while($products = mysql_fetch_array($result))
					{
						echo("<form><input type='hidden' name='action' value='edit_product'/><table><tr>");
						echo("<td width='150px'>ID:</td><td width='600px'><input type='text' name='id' readonly value='".$products['id']."' required/></td></tr>");
						echo("<tr><td>Name:</td><td><textarea name='name' required>".$products['name']."</textarea></td></tr>");
						
						echo("<tr><td>Category:</td><td>
						
						<select name='category' required>");
						
						while($categories = mysql_fetch_array($result2))
						{
							if($products['category'] == $categories['name'])
								echo("<option value='".$categories['name']."' selected>".$categories['name']."</option>");	
							else
								echo("<option value='".$categories['name']."'>".$categories['name']."</option>");	
						}
						
						echo("</select>						
						</td></tr>");					
						
						echo("<tr><td>Price:</td><td><input type='text' name='price' value='".$products['price']."' pattern='^[ 0-9]+$' required /></td></tr>");
						echo("<tr><td>Describing:</td><td><textarea name='describing' rows='10' cols='70' required>".$products['describing']."</textarea></td></tr>");
						echo("<tr><td>Sold amount:</td><td><input type='text' name='sold_amount' value='".$products['sold_amount']."' readonly required/></td></tr>");
						echo("<tr><td>Store:</td><td><input type='text' name='store' pattern='^[ 0-9]+$' value='".$products['store']."' required /></td></tr>");
						echo("</table>");
						echo("<input type='submit' method='post' action='panel.php' value='Submit changes'/></form>");	
					}
					
					echo("</div>");
				}
				
			}
			
			if($action == "edit_order")
			{
			
			/* stupid way to check, did we receive a request to update the BD from editing */ 
			
				if(isset($_GET['description']))
				{
					try
					{
						$prevprodID;
						$prevprodAmount;
						
						$result = mysql_query("SELECT * FROM orders WHERE id = ".$id." LIMIT 1",$dbcon);
						while($oldorder = mysql_fetch_array($result))
						{
							$prevprodID = $oldorder['product_id'];
							$prevprodAmount = $oldorder['product_amount'];
						}
						
						$description = $_GET['description'];
						$product_id = $_GET['product_id'];
						$product_amount = $_GET['product_amount'];
						$date = $_GET['date'];
						$completion = $_GET['completion'];
						$product_name;
						$product_store;
						$total_price = $_GET['total_price'];

						
						$result = mysql_query("SELECT * FROM products WHERE id = ".$product_id." LIMIT 1",$dbcon);
						while($product = mysql_fetch_array($result))
						{
							$product_name = $product['name'];
							$product_store = $product['store'];
							
													
							if($total_price == 0)
								$total_price = $product_amount * $product['price'];
						}
						
						
						if($product_name != "")
						{
							if($product_store >= $product_amount - $prevprodAmount)
							{						
								$sql = mysql_query("UPDATE products SET store = store + ".$prevprodAmount.", sold_amount = sold_amount - ".$prevprodAmount." WHERE id = '".$prevprodID."'");
								$sql2 = mysql_query("UPDATE products SET store = store - ".$product_amount.", sold_amount = sold_amount + ".$product_amount." WHERE id = '".$product_id."'");
							
							
							
								$sql = mysql_query("UPDATE orders SET description = '".$description."', product_id = '".$product_id."',
								product_amount = '".$product_amount."', date = '".$date."', completed = '".$completion."', product_name = '".$product_name."'
								, total_price = '".$total_price."'
								where id = '".$id."'", $dbcon);
								
								if($sql)
									echo "<h2>Order was updated</h2>";
							}
							else
								echo "<h2>You tried to order more than you have in the store.</h2>";
						}
						else
							echo '<h2>Wrong ID of product, try again</h2>';
					}
					catch (Exception $e) {
						echo '<h2>Wrong input, try again</h2>';
					}

				}
				else
				{				
					echo("<h1>Order editing mode</h1>");
					echo("<div id='form_container'>");
				
					$result = mysql_query("SELECT * FROM orders WHERE id = ".$id." LIMIT 1",$dbcon);
					
					while($order = mysql_fetch_array($result))
					{
						$complete = $order['completed'];
					
						echo("<form><input type='hidden' name='action' value='edit_order'/><table><tr>");
						echo("<td width='150px'>ID:</td><td width='600px'><input type='text' name='id' readonly value='".$order['id']."' required/></td></tr>");
						echo("<tr><td>Description:</td><td><textarea name='description' required>".$order['description']."</textarea></td></tr>");
						echo("<tr><td>Product ID:</td><td><input type='text' name='product_id' required value='".$order['product_id']."'></td></tr>");
						echo("<tr><td>Product amount:</td><td><input type='text' name='product_amount' required pattern='^[ 0-9]+$' value='".$order['product_amount']."'></td></tr>");
						echo("<tr><td>Total price:</td><td><input type='text' name='total_price' required pattern='^[ 0-9]+$' value='".$order['total_price']."'></td></tr>");
						echo("<tr><td>Date:</td><td><input type='text' name='date' readonly  required value='".$order['date']."'></td></tr>");
						echo("<tr><td>Completed:</td><td><select name='completion' required>");
						
						if($complete == 0)
						{
							echo("<option value='0' selected>In progress</option>");
							echo("<option value='1'>Finished</option>");
						}
						else
						{
							echo("<option value='0'>In progress</option>");
							echo("<option value='1' selected>Finished</option>");
						}
						
						echo("</select>
						</td></tr>");
						echo("</table>");
						echo("<input type='submit' method='post' action='panel.php' value='Submit changes'/></form>");	
					}
					
					echo("</div>");
				}
			}

			/* ACTION = EDIT */
		
		
		
		
			/* ACTION = DELETE */
			
			if($action == "delete_order")
			{
				if((isset($_GET['confirm'])) && (isset($_GET['id'])))
				{
					$id = $_GET['id'];
					if($_GET['confirm'] == "DELETE")
					{				
						
						
						if($_GET['restore'] == "on")
						{
							$product_id;
							$product_amount;
							$result = mysql_query("SELECT * FROM orders WHERE id = ".$id." LIMIT 1",$dbcon);
						
							while($order = mysql_fetch_array($result))
							{
								$product_id = $order['product_id'];
								$product_amount = $order['product_amount'];
							}
							
							$sql = mysql_query("UPDATE products SET store = store + ".$product_amount.", sold_amount = sold_amount - ".$product_amount." WHERE id = '".$product_id."'",$dbcon);
							
							if($sql)
								echo '<h2>The product amount were restored.</h2>';
							else
								echo '<h2>MySQL problem</h2>';	
						}

					
						$sql = mysql_query("DELETE from orders where id = ".$id." LIMIT 1", $dbcon);
						if($sql)
							echo '<h2>The order was removed</h2>';
						else
							echo '<h2>MySQL problem</h2>';	
					}
					else
						echo '<h2>Wrong input, try again</h2>';
				
				}
				else
				{
					if((isset($_GET['id'])) && (isset($_GET['description'])))
						{
							$id = $_GET['id'];
							$description = $_GET['description'];
							
							echo("<h1>Deletion mode</h1>");
							echo("<div id='form_container'>");
							echo "<h2>Do you want to delete all information about this order?</h2><br/>";
							echo "<h3 align='center'>ID: $id<br/>$description</h3><br/>";
							echo "<p align='center'>To proceed, type <span style='color:#ff0000;'><b>DELETE</b></span> into the form below:</p><br/>";
							echo "<form>
							<input type='hidden' name='action' value='delete_order'/><input type='hidden' name='id' value='".$id."'/>
							<div style='text-align:center; margin:0 auto; margin-bottom:20px;'><input type='checkbox' name='restore' checked > Restore products to base</div>
							<input type='text' name='confirm' autofocus style='display:block; margin:0 auto;'/>
							
							<input type='submit' method='post' action='panel.php' value='Submit changes' style='display:block; margin:15px auto;'/>
							</form>";	
							
							echo("</div>");
						}
						else
							echo '<h2>Wrong input, try again</h2>';
						
				}
		
			}
			
			if($action == "delete_product")
			{
				if((isset($_GET['confirm'])) && (isset($_GET['id'])))
				{
					$id = $_GET['id'];
					if($_GET['confirm'] == "DELETE")
					{				
						$sql = mysql_query("DELETE from products where id = ".$id." LIMIT 1", $dbcon);
						if($sql)
							echo '<h2>The product was removed</h2>';
						else
							echo '<h2>MySQL problem</h2>';
					}
					else
						echo '<h2>Wrong input, try again</h2>';
				
				}
				else
				{
					if((isset($_GET['id'])) && (isset($_GET['name'])))
						{
							$id = $_GET['id'];
							$name = $_GET['name'];
							
							echo("<h1>Deletion mode</h1>");
							echo("<div id='form_container'>");
							echo "<h2>Do you want to delete all information about this product?</h2><br/>";
							echo "<h3 align='center'>$name</h3><br/>";
							echo "<p align='center'>To proceed, type <span style='color:#ff0000;'><b>DELETE</b></span> into the form below:</p><br/>";
							echo "<form>
							<input type='hidden' name='action' value='delete'/><input type='hidden' name='id' value='".$id."'/>
							<input type='text' name='confirm' autofocus style='display:block; margin:0 auto;'/>
							<input type='submit' method='post' action='panel.php' value='Submit changes' style='display:block; margin:15px auto;'/>
							</form>";	
							
							echo("</div>");
						}
						else
							echo '<h2>Wrong input, try again</h2>';
						
				}
		
			}
		
			/* ACTION = DELETE */
			
			
			/* ACTION = STATISTIC */

			if($action == "statistic")
			{
			
				echo("
				<table style='margin: 0 auto;'>
				<tr>
				<td width='50%'>	
				
				
				<div id='stip'>
				<table style='margin: 0 auto;'><tr>
				<td valign='middle'><img src='img/i35047.png' height='50px' style='margin-right:20px'/></td>
				<td valign='middle'>On the X axies you can see product names
				<br/>
				On the Y axies you can see related to title value.</td>
				</tr></table>
				</div>
				
				
				</td>
				</tr>
				</table>
				
				
								
				<table style='margin: 0 auto;'><tr><td>
					<table class='chart_table' style='display:none;'>
					<caption>Amounts of sold product</caption>
					<thead>
						<tr> ");
							
							
							$result = mysql_query("SELECT * FROM products",$dbcon);
							
							while($products = mysql_fetch_array($result))
							{
								echo("
									<th class='chart_td' scope='col'>".$products['short_name']."</th>
								");
							}
							echo("
						</tr>
					</thead>
					<tbody>
						<tr>
							");
							
							$result = mysql_query("SELECT * FROM products",$dbcon);
							
							while($products = mysql_fetch_array($result))
							{
								echo("
									<td class='chart_td' scope='col'>".$products['sold_amount']."</td>
								");
							}
							echo("
						</tr>

					</tbody>
				</table>
				<script>
				$('table.chart_table').visualize();
				</script>
				</td></tr></table>
				
												
				<table style='margin: 0 auto;'><tr><td>
					<table class='chart_table2' style='display:none;'>
					<caption>Amounts of stored product</caption>
					<thead>
						<tr> ");
							
							
							$result = mysql_query("SELECT * FROM products",$dbcon);
							
							while($products = mysql_fetch_array($result))
							{
								echo("
									<th class='chart_td' scope='col'>".$products['short_name']."</th>
								");
							}
							echo("
						</tr>
					</thead>
					<tbody>
						<tr>
							");
							
							$result = mysql_query("SELECT * FROM products",$dbcon);
							
							while($products = mysql_fetch_array($result))
							{
								echo("
									<td class='chart_td' scope='col'>".$products['store']."</td>
								");
							}
							echo("
						</tr>

					</tbody>
				</table>
				<script>
				$('table.chart_table2').visualize();
				</script>
				</td></tr></table>
				
				
								
				<table style='margin: 0 auto;'><tr><td>
				<table class='chart_table3' style='display:none;'>
					<caption>Price levels of products</caption>
					<thead>
						<tr> ");
							
							
							$result = mysql_query("SELECT * FROM products",$dbcon);
							
							while($products = mysql_fetch_array($result))
							{
								echo("
									<th class='chart_td' scope='col'>".$products['short_name']."</th>
								");
							}
							echo("
						</tr>
					</thead>
					<tbody>
						<tr>

							");
							
							$result = mysql_query("SELECT * FROM products",$dbcon);
							
							while($products = mysql_fetch_array($result))
							{
								echo("
									<td class='chart_td' scope='col'>".$products['price']."</td>
								");
							}
							echo("
						</tr>

					</tbody>
				</table>
				<script>
				$('table.chart_table3').visualize();
				</script>
				</td></tr></table>
				
				
				
				
								
				<table style='margin: 0 auto;'><tr><td>
				<table class='chart_table4' style='display:none;'>
					<caption>Sellings during last 30 orders</caption>
					<thead>
						<tr> ");
							
							
							$result = mysql_query("SELECT * FROM orders ORDER BY date ASC LIMIT 30",$dbcon);
							$prevdate = "";

							while($order = mysql_fetch_array($result))
							{
								$date = new DateTime($order['date']);
								$dateres = $date->format('j/m');
								
								if($dateres != $prevdate)
									echo("
										<th class='chart_td' scope='col'>".$dateres."</th>
									");
								
								$prevdate = $dateres;
							}
							
							echo("
						</tr>
					</thead>
					<tbody>
						<tr>

							");
							
							$result = mysql_query("SELECT * FROM orders ORDER BY date ASC LIMIT 30",$dbcon);
							$prevdate = "";

							while($order = mysql_fetch_array($result))
							{
								$sql = mysql_query("SELECT *, SUM(total_price) FROM orders WHERE date = '".$order['date']."'",$dbcon);
								while($row = mysql_fetch_array($sql)){
								
								
								if($row['date'] != $prevdate)
									echo("
										<td class='chart_td' scope='col'>".$row['SUM(total_price)']."</td>
									");
								
								$prevdate = $row['date'];
								
								}
							
								
							}
							
							echo("
						</tr>

					</tbody>
				</table>
				<script>
				$('table.chart_table4').visualize();
				</script>
				</td></tr></table>
				");
			}
	
		
		
		}
		else
		{
			echo("<script>var body=document.getElementsByTagName('body')[0]; while(body.firstChild) body.removeChild(body.firstChild);</script>");
			echo("Sorry, login or password is wrong");
		}
	}
	else
	{
		echo("<script>var body=document.getElementsByTagName('body')[0]; while(body.firstChild) body.removeChild(body.firstChild);</script>");
		echo("Sorry, you need to re-login");
	}
	
?>

</body>


</html> 