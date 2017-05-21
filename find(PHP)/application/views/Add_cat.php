<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>
			Add Category | Admin
		</title>
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url().'assests/materialize/css/materialize.min.css'; ?>"  media="screen,projection" />
		<style>
			html,body
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
						<h3 class="center-align" >
							Welcome Admin | Add category
						</h3>
					</div>
					<form class="col s8 offset-s2" action="<?php echo base_url().'admin/add_cat'; ?>" method="POST">
						<div class="row">
							<?php if($add_cat_error==1)
								echo validation_errors("<p class='red-text'>");
								?>
							<div class="input-field">
								<p>
      								<input name="big_cat" type="radio" id="shop_cat" value="1" checked/>
      								<label for="shop_cat">Shop</label>
    							</p>
							</div>
							<div class="input-field">
								<p>
      								<input name="big_cat" type="radio" id="event_cat" value="2" />
      								<label for="event_cat">Event</label>
    							</p>
							</div>
							<div class="input-field">
								<input id="add_cat" name="add_cat" type="text" />
								<label for="add_cat">
									Category
								</label>
							</div>
						</div>
						<div class="row center-align col s12">
							<div class="input-field ">
								<input name="Login" type="submit" class="btn btn-large" />
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url().'assests/materialize/js/materialize.min.js'; ?>"></script>
	</body>
</html>