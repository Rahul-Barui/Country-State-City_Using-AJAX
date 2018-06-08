<?php
	$con = mysqli_connect("localhost","root","","country_state_city");
	if(!$con){
		die('Connection Error');
	}
	
	$sql_fetch = "SELECT * FROM `insert_all`";
	$res_fetch = mysqli_query($con,$sql_fetch);
	$row_fetch = mysqli_fetch_assoc($res_fetch);
	
	if(isset($_POST['submit'])){
		extract($_POST);
		
		$sql_getCountryName = "SELECT `country_name` FROM `countries` WHERE `country_id` = '$country'";
		$query_getCountryName = mysqli_query($con,$sql_getCountryName);
		$row_getCountryName = mysqli_fetch_assoc($query_getCountryName);
		
		$sql_getStateName = "SELECT `state_name` FROM `states` WHERE `state_id` = '$state'";
		$query_getStateName = mysqli_query($con,$sql_getStateName);
		$row_getStateName = mysqli_fetch_assoc($query_getStateName);
		
		$sql_getCityName = "SELECT `city_name` FROM `cities` WHERE `city_id` = '$city'";
		$query_getCityName = mysqli_query($con,$sql_getCityName);
		$row_getCityName = mysqli_fetch_assoc($query_getCityName);
		
		$country = $row_getCountryName['country_name'];
		$state = $row_getStateName['state_name'];
		$city = $row_getCityName['city_name'];
		
		$sql2_fetch = "SELECT * FROM `insert_all`";
		$res2_fetch = mysqli_query($con,$sql2_fetch);
		$row2_fetch = mysqli_fetch_assoc($res2_fetch);
		$id = $row2_fetch['id'];
		$count = mysqli_num_rows($res2_fetch);
		if($count == '0'){
			$sql_Insert = "INSERT INTO `insert_all`(`id`, `country_name`, `state_name`, `city_name`) VALUES ('','$country','$state','$city')"; //echo $sql_Insert; die;
			$Query = mysqli_query($con,$sql_Insert);
		}
		else {
			$sql_Update = "UPDATE `insert_all` SET `country_name`='$country',`state_name`='$state',`city_name`='$city' WHERE `id`='$id'"; //echo $sql_Update; die;
			$Query = mysqli_query($con,$sql_Update);
		}
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Country | State | City | Using-Ajax</title>
	<script src="js/jquery1.11.3.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			
			$("#country").one("finishedLoading", function () {
		  		setTimeout(function () {$("#state").val("<?= (filter_input(INPUT_POST,"state")?:"[]") ?>").trigger("change")},10);
			});
			$("#state").one("finishedLoadingState", function () {
				setTimeout(function () { $("#city").val("<?= (filter_input(INPUT_POST,"city")?:"[]") ?>").trigger("change") }, 10);
			});
			
			$('#country').on('change',function(){
				var countryID = $(this).val();
				if(countryID){
					$.ajax({
						type:'POST',
						url:'ajaxData.php',
						data:'country_id='+countryID,
						success:function(html){
							$('#state').html(html);
							$('#city').html('<option value="">Select state first</option>');
							$("#country").trigger("finishedLoading"); //Little custom event we can listen for 
						}
					}); 
				}else{
					$('#state').html('<option value="">-Select Your Country First-</option>');
					$('#city').html('<option value="">Select state first</option>'); 
					$("#country").trigget("finishedLoading");
				}
			}).trigger("change"); //Trigger immediately in case there's a value pre-selected
			
			$('#state').on('change',function(){
				var stateID = $(this).val();
				if(stateID){
					$.ajax({
						type:'POST',
						url:'ajaxData.php',
						data:'state_id='+stateID,
						success:function(html){
							$('#city').html(html);
						}
					}); 
				}else{
					$('#city').html('<option value="">Select state first</option>'); 
				}
			});
		});
	</script>
</head>

<body>
	<?php 
		$con = mysqli_connect("localhost","root","","country_state_city");
		if(!$con){
			die('Connection Error');
		}
	?>
		<br /><br /><br />
	<form method="post">
		<center>
			<font color="#0033FF" size="3"><b>-: <u>Country - State - City</u> :- </b></font><br /><br /><br />
			<label> Select Country : </label>
			<select name="country" id="country">
				<option>-Select Your Country-</option>
				<?php
					$sql = "SELECT * FROM `countries`";
					$res = mysqli_query($con,$sql);
					$selectedCountry = filter_input(INPUT_POST, "country");
					while($row = mysqli_fetch_assoc($res)){
				?>
					<option <?php if($row['country_name'] == $row_fetch['country_name']){ echo "selected";} ?> value="<?php echo $row['country_id'] ?>"><?php echo $row['country_name'] ?></option>
				<?php } ?>
			</select><br /><br />
			
			<label> Select State : </label>
			<select name="state" id="state">
				<option value="">-Select Your Country First-</option>
			</select><br /><br />
			
			<label> Select City : </label>
			<select name="city" id="city">
				<option value="">-Select Your State First-</option>
			</select><br /><br />
			
			<input type="submit" name="submit" value="SUBMIT" />
		
		</center>
	</form>
</body>
</html>
