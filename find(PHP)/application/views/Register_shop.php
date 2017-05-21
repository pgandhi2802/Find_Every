<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Welcome to Shop Register</title>
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url().'assests/materialize/css/materialize.min.css'; ?>"  media="screen,projection" />
		<style>
			html
			{
				width:100%;
				height:100%;
				background-color:#ddd;
				padding:50px;
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
					<form class="col s8 offset-s2" action="<?php echo base_url().'shop/register';?>" method="post">
						<div class="row">
							<h4>What are you ??</h4>
							<p>
								<input name="shop_cat" type="radio" class="with-gap" id="shop" value="1" checked />
								<label for="shop">Shop</label>
								<input name="shop_cat" type="radio" class="with-gap" id="event" value="2" />
								<label for="event">Event</label>
							</p>
							<div class="input-field">
								<input id="Shop_name" name="shop_name" type="text" />
								<label for="Shop_name">Shop Name/Event Name</label>
								<?php if($signup_error){echo form_error('shop_name',"<p class='red-text'>"); }?>
							</div>
							<div class="input-field">
								<input id="registration_id" name="registration_id" type="email" />
								<label for="registration_id">Registration ID/Email</label>
								<?php if($signup_error){echo form_error('registration_id',"<p class='red-text'>"); }?>
							</div>
							<div class="input-field">
								<input id="password" name="password" type="password" />
								<label for="password" class="text-red">Password</label>
							</div>
							<div class="input-field">
								<input id="confirm_password" name="confirm_password" type="password" />
								<label for="confirm_password" class="text-red">Confirm Password</label>
								<?php if($signup_error){echo form_error('confirm_password',"<p class='red-text'>"); }?>
							</div>
						</div>
						<div class="row center-align col s12">
							<div class="input-field ">
								<input name="Register" type="submit" class="btn btn-large" />
							</div>
						</div>
					</form>
				</div>
				<hr />
				<div class"card-action col s12">
					<div class="row center-align">
						<a class="btn btn-large" href="<?php echo base_url().'shop/Login'; ?>" >Login</a>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url().'assests/materialize/js/materialize.min.js'; ?>"></script>
	</body>
</html>