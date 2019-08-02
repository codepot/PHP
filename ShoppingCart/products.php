<?php
include ("includes/db.php");
include ("includes/functions.php");

$WhatCommand = (isset($_REQUEST["command"]) ? $_REQUEST["command"] : "");

$WhichProductId = (isset($_REQUEST["productid"]) ? $_REQUEST["productid"] : "");

if ($WhatCommand == 'add' && $WhichProductId > 0) {
   addtocart($WhichProductId, 1);
   header("location:shoppingcart.php");
   exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<title>Products</title>
 
<script language="javascript">
   function addtocart(pid) {
      document.form1.productid.value = pid;
      document.form1.command.value = 'add';
      document.form1.submit();
   }
</script>
</head>


<body>
<form name="form1">
	<input type="hidden" name="productid" value="0" />
    <input type="hidden" name="command" value="unknown"/>
</form>

<div align="center">
	<h1 align="center">Products</h1>
	<table border="0" cellpadding="2px" width="600px">
		<?php
		
			$result=mysql_query("select * from products");
			while($row=mysql_fetch_array($result)){
		?>
    	<tr>
        	
        	
        	<td><a href="<?php echo $row['picture']; ?>" ><img src="<?php echo $row['picture']; ?>" width="70%"></a></td>
            <td>   <span class="name">	<?php echo $row['name']; ?></span><br />
            		 <span class="description"><?php echo $row['description']; ?></span><br />
                    <span class="price">Price:</span><big style="color:green">
                    	$<?php echo $row['price']; ?></big><br /><br />
                    <input type="button" value="Add to Cart" onclick="addtocart(<?php echo $row['serial']; ?>)" />
			</td>
		</tr>
        <tr><td colspan="2"><hr size="1" /></td>
        <?php } ?>
    </table>
</div>
</body>
</html>
