<?php
	include("includes/db.php");
	include("includes/functions.php");
	
	$WhatCommand = (isset($_REQUEST["command"])?$_REQUEST["command"]:"");
	$WhichProductId = (isset($_REQUEST["pid"])?$_REQUEST["pid"]:"");
	
	
	
	if($WhatCommand =='delete' && $WhichProductId >0){
		remove_product($WhichProductId);
	}
	else if($WhatCommand =='clear'){
		unset($_SESSION['cart']);
		session_destroy();
	}
	else if($WhatCommand =='update'){
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=intval($_REQUEST['product'.$pid]);
			if($q>0 && $q<=999){
				$_SESSION['cart'][$i]['qty']=$q;
			}
			else{
				$msg='Some proudcts not updated!, quantity must be a number between 1 and 999';
			}
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Shopping Cart</title>
<script language="javascript">
	function del(pid){
		//if(confirm('Do you really want to delete this item')){
			document.form1.pid.value=pid;
			document.form1.command.value='delete';
			document.form1.submit();
		//}
	}
	function clear_cart(){
		if(confirm('This will empty your shopping cart, continue?')){
			document.form1.command.value='clear';
			document.form1.submit();
		}
	}
	function update_cart(){
		document.form1.command.value='update';
		document.form1.submit();
	}


</script>
</head>

<body>
<form name="form1" method="post">
<input type="hidden" name="pid" />
<input type="hidden" name="command" />
	<div style="margin:0px auto; width:600px;" >
    <div style="padding-bottom:10px">
    	<h1 align="center">Your Shopping Cart</h1>
    <input type="button" value="Continue Shopping" onclick="window.location='products.php'" />
    </div>
    	<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background-color:#E1E1E1" width="100%">
    	<?php
		$MyCart = (isset($_SESSION['cart'])?$_SESSION['cart']:"");
			if(is_array($MyCart)){
            	echo '<tr bgcolor="#FFFFFF" style="font-weight:bold"><td>Serial</td><td>Name</td><td>Price</td><td>Qty</td><td>Amount</td><td>Options</td></tr>';
				$max=count($_SESSION['cart']);
				for($i=0;$i<$max;$i++){
					$pid=$_SESSION['cart'][$i]['productid'];
					$q=$_SESSION['cart'][$i]['qty'];
					$pname=get_product_name($pid);
					if($q==0) continue;
			?>
            		<tr bgcolor="#FFFFFF"><td><?=$i+1?></td><td><?=$pname?></td>
                    <td>$ <?=get_price($pid)?></td>
                    <td><input type="text" name="product<?=$pid?>" value="<?=$q?>" maxlength="3" size="2" /></td>                    
                    <td>$ <?=get_price($pid)*$q?></td>
                    <td><a href="javascript:del(<?=$pid?>)">Remove</a></td></tr>
            <?php					
				}
			?>
				<tr><td><b>Order Total: $<?=get_order_total()?></b></td><td colspan="5" align="right"><input type="button" value="Clear Cart" onclick="clear_cart()"><input type="button" value="Update Cart" onclick="update_cart()"><input type="button" value="Place Order" onclick="window.location='billing.php'"></td></tr>
			<?php
            }
			else{
				echo "<tr bgColor='#FFFFFF'><td>There are no items in your shopping cart!</td>";
			}
		?>
        </table>
    </div>
</form>
</body>
</html>