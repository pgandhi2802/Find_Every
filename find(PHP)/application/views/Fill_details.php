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
		</style>
	</head>
	<body>
		<div class="container ">
			<div class="card row">
				<div class="card-content col s12">
					<div class="card-title">
						<h3 class="center-align" >Welcome to Find Every Community<hr /></h3>
					</div>
					<div class="row">
						<div class="col s3">
							<h5>You Are</h5>
						</div>
						<div class="col s9">
							: <?php echo $shop_cat;?>
						</div>
					</div>
					<div class="row">
						<div class="col s3">
							<h5><?php echo $shop_cat; ?> Name</h5>
						</div>
						<div class="col s9">
							: <?php echo $shop_name; ?>
						</div>
					</div>
					<div class="row">
						<div class="col s3">
							<h5>Email</h5>
						</div>
						<div class="col s9">
							: <?php echo $shop_email; ?>
						</div>
					</div>
					<form class="col s8 offset-s2" action="<?php echo base_url().'shop/fill_details';?>" method="post">
						<div class="input-field">
							<select name="shop_sub_cat" id="Shop_sub_cat">
								<option value="" disabled selected>Choose your Category</option>
								<?php foreach($cat_list as $row){ ?>
								<option value="<?php echo $row->cat_id; ?>"><?php echo $row->cat_name; ?></option>
								<?php } ?>
							</select>
							<?php if($fill_details_error){echo form_error('shop_sub_cat',"<p class='red-text'>"); }?>
						</div>
						<div class="input-field">
							<input id="Shop_city" name="shop_city" type="text" />
							<label for="Shop_city">City</label>
							<?php if($fill_details_error){echo form_error('shop_city',"<p class='red-text'>"); }?>
						</div>
						<div class="input-field">
							<input id="Shop_pincode" name="shop_pincode" type="number" />
							<label for="Shop_pincode">Pincode</label>
							<?php if($fill_details_error){echo form_error('shop_pincode',"<p class='red-text'>"); }?>
						</div>
						<div class="input-field">
							<input id="Shop_contact_no" name="shop_contact_no" type="number" />
							<label for="Shop_contact_no">Contact No</label>
							<?php if($fill_details_error){echo form_error('shop_contact_no',"<p class='red-text'>"); }?>
						</div>
						<div class="input-field">
							<input id="Shop_add1" name="shop_add_line_1" type="text" />
							<label for="Shop_add1">Address Line 1</label>
							<?php if($fill_details_error){echo form_error('shop_add_line_1',"<p class='red-text'>"); }?>
						</div>
						<div class="input-field">
							<input id="Shop_add2" name="shop_add_line_2" type="text" />
							<label for="Shop_add2">Address Line 2</label>
							<?php if($fill_details_error){echo form_error('shop_add_line_2',"<p class='red-text'>"); }?>
						</div>

						<?php if($shop_cat_no==2){ ?>

						<div class="input-field">
							<input id="Start_date" name="start_date" type="date" class="datepicker" />
							<label for="Start_date">Start Date</label>
							<?php if($fill_details_error){echo form_error('shop_add_line_2',"<p class='red-text'>"); }?>
						</div>
						<div class="input-field">
							<input id="End_date" name="end_date" type="date" class="datepicker" />
							<label for="End_date">End Date</label>
							<?php if($fill_details_error){echo form_error('shop_add_line_2',"<p class='red-text'>"); }?>
						</div>
						<?php } ?>
						<div class="input-field">
							<input id="Shop_descr" name="shop_descr" type="text" />
							<label for="Shop_descr">Description</label>
							<?php if($fill_details_error){echo form_error('shop_descr',"<p class='red-text'>"); }?>
						</div>
						<div class="input-field col s12">
							<input id="Shop_lng" name="shop_lng" type="text"/>
							<?php if($fill_details_error){echo form_error('shop_lng',"<p class='red-text'>"); }?>
						</div>
						<div class="input-field col s12">
							<input id="Shop_lat" name="shop_lat" type="text"/>
							<?php if($fill_details_error){echo form_error('shop_lat',"<p class='red-text'>"); }?>
						</div>
						<div class="col s12">
							<div id="map" style="height:500px;background-color:#ddd;"></div>
						</div>
						<div class="row center-align col s12">
							<div class="input-field ">
								<input name="Fiil_Details" type="submit" value="Submit Details"class="btn btn-large" />
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url().'assests/materialize/js/materialize.min.js'; ?>"></script>
		<script>
			$(document).ready(function() {
				$('select').material_select();
				$('.datepicker').pickadate({
    				selectMonths: true,
    				selectYears: 15
  				});
			});
		</script>
		<script>
			function initMap() {
				var map = new google.maps.Map(document.getElementById('map'), {
					center: {lat: 24.4362736, lng: 77.1600089},
					zoom: 12
				});
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(function(position) {
						var pos = {
							lat: position.coords.latitude,
							lng: position.coords.longitude
						};
						var marker = new google.maps.Marker({
							position: pos,
							map: map,
							draggable:true
						});
						document.getElementById("Shop_lat").value = position.coords.latitude;
						document.getElementById("Shop_lng").value = position.coords.longitude;
						map.setCenter(marker.getPosition());
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
					});
				}
				else 
				{
					handleLocationError(false, infoWindow, map.getCenter());
				}
			}
			function handleLocationError(browserHasGeolocation, infoWindow, pos) {
				infoWindow.setPosition(pos);
				infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' :'Error: Your browser doesn\'t support geolocation.');
			}
		</script>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2aiTxZNaAYhY56KCoayJrtcnB-v_lNjU&callback=initMap"></script>
	</body>
</html>