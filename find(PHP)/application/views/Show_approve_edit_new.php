<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Welcome</title>
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url().'assests/materialize/css/materialize.min.css'; ?>"  media="screen,projection" />
		<style>
			html
			{
				width:100%;
				height:100%;
				background-color:#ddd;
				padding:100px;
			}
			.valign-wrapper
			{
				height:100%;
			}
			.card
			{
				display:block;
				width: 100%;
			}
		</style>
	</head>
	<body>
		<div class="valign-wrapper container ">
			<div class="card row valign">
				<div class="card-content col s12">
					<div class="card-title">
						<h3 class="center-align" >Welcome to Find Every Community<hr /></h3>
					</div>
					<ul class="collection with-header">
						<?php foreach($profile as $row){ ?>
						<li class="collection-header">
							<h4>
								<a href="<?php echo base_url()."admin/approve/".$row->shop_id; ?>" class="right btn bn-large"><i class="material-icons" >done</i></a>
								<a href="<?php echo base_url()."admin/delete/".$row->shop_id; ?>" class="right btn bn-large"><i class="material-icons" >delete</i></a>
							</h4>
						</li>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">You are</p>
									<p class="col m9">: <?php if($row->shop_cat==1){echo "Shop"; }else {echo "Event";} ?></p>
								</h5>
							</div>
						</li>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">You belongs to</p>
									<p class="col m9">: <?php foreach($category as $row_cat) echo $row_cat->cat_name; ?></p>
								</h5>
							</div>
						</li>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">Name</p>
									<p class="col m9">: <?php echo $row->shop_name; ?></p>
								</h5>
							</div>
						</li>
						<?php if($row->shop_cat==2){ ?>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">Start Date</p>
									<p class="col m9">: <?php echo date("d F,Y",strtotime($row->shop_start_date)); ?></p>
								</h5>
							</div>
						</li>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">
										End Date
									</p>
									<p class="col m9">
										: 
										<?php echo date("d F,Y",strtotime($row->shop_end_date)); ?>
									</p>
								</h5>
							</div>
						</li>
						<?php } ?>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">
										Email
									</p>
									<p class="col m9">
										: 
										<?php echo $row->shop_email; ?>
									</p>
								</h5>
							</div>
						</li>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">
										City
									</p>
									<p class="col m9">
										: 
										<?php echo $row->shop_city; ?>
									</p>
								</h5>
							</div>
						</li>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">
										Pincode
									</p>
									<p class="col m9">
										: 
										<?php echo $row->shop_pincode; ?>
									</p>
								</h5>
							</div>
						</li>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">
										Address
									</p>
									<p class="col m9">
										: 
										<?php echo $row->shop_add_line_1." ".$row->shop_add_line_2; ?>
									</p>
								</h5>
							</div>
						</li>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">
										Contact No.
									</p>
									<p class="col m9">
										: 
										<?php echo $row->shop_contact_no; ?>
									</p>
								</h5>
							</div>
						</li>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">
										Description
									</p>
									<p class="col m9">
										: 
										<?php echo $row->shop_descr; ?>
									</p>
								</h5>
							</div>
						</li>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">
										Approval Status
									</p>
									<p class="col m9">
										: 
										<?php if($row->shop_approved==1) {echo "approved";} else { echo "not approved";} ?>
									</p>
								</h5>
							</div>
						</li>



						<?php if($row->shop_approved==1) {  ?>
						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">
										Edit Approval Status
									</p>
									<p class="col m9">
										: 
										<?php if($row->edit_shop_approved==1) { echo "not approved";} ?>
									</p>
								</h5>
							</div>
						</li>
						<?php } ?>

						<li class="collection-item">
							<div class="row">
								<h5>
									<p class="col m3">
										You are located at
									</p>
									<p class="col m9">
										: 
										<?php echo $row->shop_lat." ,".$row->shop_lng; ?>
									</p>
								</h5>
							</div>
							<div class="row">
								<div id="map" style="height:500px;background-color:#ddd;"></div>
							</div>
						</li>
						<?php 
							$lat=$row->shop_lat;
							$lng=$row->shop_lng;
						} ?>
					</ul>
				</div>
				<hr />
				<div class"card-action col s12">
					<div class="row center-align">
						<a class="btn btn-large" href="<?php echo base_url().'/admin/logout'; ?>" >Logout</a>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url().'assests/materialize/js/materialize.min.js'; ?>"></script>
		<script>
			function initMap() {
				var myLatlng = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};
				var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 12,
					center: myLatlng,
					draggable:true
				});
				var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					title: 'Click to zoom',
					draggable : false
				});
				map.addListener('dragend',function(){
					map.setCenter(marker.getPosition());
				});
				map.addListener('click',function() {
					map.setCenter(marker.getPosition());
				});
				map.addListener('dblclick',function() {
					map.setCenter(marker.getPosition());
				});
			}
		</script>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2aiTxZNaAYhY56KCoayJrtcnB-v_lNjU&callback=initMap"></script>
	</body>
</html>