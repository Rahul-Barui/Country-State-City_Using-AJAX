<?php
	$con = mysqli_connect("localhost","root","","country_state_city");
	if(!$con){
		die('Connection Error');
	}
	
	if(isset($_POST["country_id"]) && !empty($_POST["country_id"])){
    	$country_id = $_POST["country_id"];
    	$sql_query = "SELECT * FROM `states` WHERE `country_id` = '$country_id'";
    	$res_query = mysqli_query($con,$sql_query) or die('Error'.mysqli_error($con));
		$count = mysqli_num_rows($res_query);
    	if($count > 0){
		?>
		<option value="">-Select Your State-</option>
		<?php
			while($row = mysqli_fetch_assoc($res_query)){ 
				?>
				<option value="<?php echo $row['state_id']; ?>"><?php echo $row['state_name']; ?></option>
				<?php
			}
		}else{
		?>
		<option value="">State Not Available</option>
		<?php
		}
	}

	if(isset($_POST["state_id"]) && !empty($_POST["state_id"])){
    	$state_id = $_POST["state_id"];
    	$sql_query = "SELECT * FROM `cities` WHERE `state_id` = '$state_id'";
    	$res_query = mysqli_query($con,$sql_query) or die('Error'.mysqli_error($con));
		$count = mysqli_num_rows($res_query);
    	if($count > 0){
		?>
		<option value="">-Select Your City-</option>
		<?php
			while($row = mysqli_fetch_assoc($res_query)){ 
				?>
				<option value="<?php echo $row['city_id']; ?>"><?php echo $row['city_name']; ?></option>
				<?php
			}
		}else{
		?>
		<option value="">City Not Available</option>
		<?php
		}
	}
?>
