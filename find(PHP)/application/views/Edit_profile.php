<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Fill Details | Welcome to Shop Register</title>
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url().'assests/materialize/css/materialize.min.css'; ?>"  media="screen,projection" />
		<style>
			html,body
			{
				width:100%;
				background-color:#ddd;
				padding:50px;
			}
			.card
			{
				display:block;
				width: 100%;
			}
			.picker{
				z-index:1;
			}
		</style>
	</head>
	<body>
		<div class="container ">
			<div class="card row">
				<div class="card-content col s12">
					<div class="card-title">
						<h3 class="center-align" >Welcome to Find Every Community<hr /></h3>
					</div>
					<form action="<?php echo base_url().'shop/edit_profile';?>" method="post">
						<ul class="collection with-header">
							<?php foreach($profile as $row) { ?>
							<li class="collection-item">
								<h5>
									<div class="row">
										<div class="col s3">You Are</div>
										<div class="col s9">
											: 
											<small>
												<?php if($row->shop_cat ==1) {echo "Shop";} else {echo "Event";}?>
											</small>
										</div>
									</div>
								</h5>
							</li>
							<li class="collection-item">
								<h5>
									<div class="row">
										<div class="col s3">
											<?php if($row->shop_cat ==1) {echo "Shop";} else {echo "Event";} ?> Name
										</div>
										<div class="col s9">
											: 
											<small>
												<?php echo $row->shop_name; ?>
											</small>
										</div>
									</div>
								</h5>
							</li>
							<li class="collection-item">
								<h5>
									<div class="row">
										<div class="col s3">
											Email
										</div>
										<div class="col s9">
											: 
											<small>
												<?php echo $row->shop_email; ?>
											</small>
										</div>
									</div>
								</h5>
							</li>
							<li class="collection-item">
								<h5>
									<div class="row">
										<div class="col s3">
											Category
										</div>
										<div class="col s9">
											: 
											<small>
												<?php foreach($category as $cat_row){echo $cat_row->cat_name;} ?>
											</small>
										</div>
									</div>
								</h5>
							</li>
							<li class="collection-item">
								<div class="row">
									<div class="col s3">
										<h5>
											City
										</h5>
									</div>
									<div class="col s9">
										<div class="input-field">
											<input id="Shop_city" name="shop_city" type="text" value="<?php echo $row->shop_city;?>" class="black-text"/>
											<label for="Shop_city">
												<?php echo $row->shop_city;?>
											</label>
											<?php if($edit_profile_error){echo form_error('shop_city',"<p class='red-text'>"); }?>
										</div>
									</div>
								</div>
							</li>
							<li class="collection-item">
								<div class="row">
									<div class="col s3">
										<h5>
											Pincode
										</h5>
									</div>
									<div class="col s9">
										<div class="input-field">
											<input id="Shop_pincode" name="shop_pincode" type="number" value="<?php echo $row->shop_pincode;?>" />
											<label for="Shop_pincode">
												<?php echo $row->shop_pincode;?>
											</label>
											<?php if($edit_profile_error){echo form_error('shop_pincode',"<p class='red-text'>"); }?>
										</div>
									</div>
								</div>
							</li>
							<li class="collection-item">
								<div class="row">
									<div class="col s3">
										<h5>
											Contact No
										</h5>
									</div>
									<div class="col s9">
										<div class="input-field">
											<input id="Shop_contact_no" name="shop_contact_no" type="number" value="<?php echo $row->shop_contact_no;?>" />
											<label for="Shop_contact_no">
												<?php echo $row->shop_contact_no;?>
											</label>
											<?php if($edit_profile_error){echo form_error('shop_contact_no',"<p class='red-text'>"); }?>
										</div>
									</div>
								</div>
							</li>
							<li class="collection-item">
								<div class="row">
									<div class="col s3">
										<h5>
											Address Line 1
										</h5>
									</div>
									<div class="col s9">
										<div class="input-field">
											<input id="Shop_add1" name="shop_add_line_1" type="text" value="<?php echo $row->shop_add_line_1;?>" />
											<label for="Shop_add1">
												<?php echo $row->shop_add_line_1;?>
											</label>
											<?php if($edit_profile_error){echo form_error('shop_add_line_1',"<p class='red-text'>"); }?>
										</div>
									</div>
								</div>
							</li>
							<li class="collection-item">
								<div class="row">
									<div class="col s3">
										<h5>
											Address Line 2
										</h5>
									</div>
									<div class="col s9">
										<div class="input-field">
											<input id="Shop_add2" name="shop_add_line_2" type="text" value="<?php echo $row->shop_add_line_2;?>" />
											<label for="Shop_add1">
												<?php echo $row->shop_add_line_2;?>
											</label>
											<?php if($edit_profile_error){echo form_error('shop_add_line_2',"<p class='red-text'>"); }?>
										</div>
									</div>
								</div>
							</li>

							<?php if($row->shop_cat ==2) { ?>

							<li class="collection-item">
								<div class="row">
									<div class="col s12 m6">

										<div class="col s3">
											<h5>
												Start Date
											</h5>
										</div>
										<div class="col s9">
											<input type="date" class="datepicker" name="start_date" id="start_date"/>
											<label for="start_date"><?php echo date('d F,Y',strtotime($row->shop_start_date)); ?></label>
											<?php if($edit_profile_error){echo form_error('start_date',"<p class='red-text'>"); }?>
										</div>
									</div>
									<div class="col s12 m6">
										<div class="col s3">
											<h5>
												End<br />Date
											</h5>
										</div>
										<div class="col s9">
											<input type="date" class="datepicker" name="end_date"  id="end_date"/>
											<label for="start_date"><?php echo date('d F,Y',strtotime($row->shop_end_date)); ?></label>
											<?php if($edit_profile_error){echo form_error('end_date',"<p class='red-text'>"); }?>
										</div>
									</div>
								</div>
							</li>

							<?php } ?>

							<li class="collection-item">
								<div class="row">
									<div class="col s3">
										<h5>
											Description
										</h5>
									</div>
									<div class="col s9">
										<div class="input-field">
											<input id="Shop_descr" name="shop_descr" type="text" value="<?php echo $row->shop_descr;?>" />
											<label for="Shop_descr">
												<?php echo $row->shop_descr;?>
											</label>
											<?php if($edit_profile_error){echo form_error('shop_descr',"<p class='red-text'>"); }?>
										</div>
									</div>
								</div>
							</li>
							<li class="collection-item">
								<div class="row">
									<div class="col s3">
										<h5>
											Latitude
										</h5>
									</div>
									<div class="col s9">
										<div class="input-field">
											<input id="Shop_lat" name="shop_lat" type="text" value="<?php echo $row->shop_lat;?>" />
											<label for="Shop_lat">
												<?php echo $lat=$row->shop_lat;?>
											</label>
											<?php if($edit_profile_error){echo form_error('shop_lat',"<p class='red-text'>"); }?>
										</div>
									</div>
								</div>
							</li>
							<li class="collection-item">
								<div class="row">
									<div class="col s3">
										<h5>
											Latitude
										</h5>
									</div>
									<div class="col s9">
										<div class="input-field">
											<input id="Shop_lng" name="shop_lng" type="text" value="<?php echo $row->shop_lng;?>" />
											<label for="Shop_lng">
												<?php echo $lng=$row->shop_lng;?>
											</label>
											<?php if($edit_profile_error){echo form_error('shop_lng',"<p class='red-text'>"); }?>
										</div>
									</div>
								</div>
							</li>
							<li>
								<div class="row">
									<div class="col s12">
										<div id="map" style="background-color:#ddd;height:500px;"></div>
									</div>
								</div>
							</li>
							<?php } ?>
						</ul>
						<div class="row center-align col s12">
							<div class="input-field ">
								<input name="Login" type="submit" value="Edit" class="btn btn-large" />
							</div>
						</div>
					</form>
				</div>
				<hr />
				<div class"card-action col s12">
					<div class="row center-align">
						<a class="btn btn-large" href="<?php echo base_url().'/shop/logout'; ?>" >
							Logout
						</a>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url().'assests/materialize/js/materialize.min.js'; ?>"></script>
		<script>
			$(document).ready(function() {
				$('select').material_select();
				$('.datepicker').pickadate({
    				selectMonths: true, // Creates a dropdown to control month
    				selectYears: 15 // Creates a dropdown of 15 years to control year
  				});
			});
		</script>
		<script>
			function initMap() {
				var myLatlng = {lat: <?php echo $lat; ?>, lng: <?php echo $lng;?>};
				var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 12,
					center: myLatlng,
				});
				var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					title: 'Click to zoom',
					draggable : true
				});
				marker.addListener('dragend',function(e){
					marker.setPosition(e.latLng);
					document.getElementById("Shop_lat").value = e.latLng.lat();
					document.getElementById("Shop_lng").value = e.latLng.lng();
					map.setCenter(marker.getPosition());
				});
				map.addListener('click',function(e) {
					marker.setPosition(e.latLng);
					document.getElementById("Shop_lat").value = e.latLng.lat();
					document.getElementById("Shop_lng").value = e.latLng.lng();
					map.setCenter(marker.getPosition());
				});
			}
		</script>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2aiTxZNaAYhY56KCoayJrtcnB-v_lNjU&callback=initMap"></script>
	</body>
</html>