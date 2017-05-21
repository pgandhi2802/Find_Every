<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>
			welcome Admin
		</title>
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url().'assests/materialize/css/materialize.min.css'; ?>"  media="screen,projection" />
		<style>
			html,body
			{
				width:100%;
				height:100%;
				background-color:#ddd;
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
		<div class="valign-wrapper container">
			<div class="card row">
				<div class="card-content center">
					<h3>
						Welcome to Find Every Community
					</h3>
				</div>
				<hr />
				<div class="card-content valign">
					<div class="row">
						<div class="col s12">
							<h3 class="center">Category Management</h3>
						</div>
					</div>
					<hr />
					<div class="row">
						<div class="col s12 m8 offset-m2">
							<form action="<?php echo base_url().'admin/delete_cat'; ?>" method="POST">
							<ul class="collection">
								<?php echo validation_errors("<p class='red-text'>");?>
								<?php foreach($category as $row){ ?>
								<li class="collection-item">
									<p>
      									<input type="checkbox" id="test<?php echo $row->cat_id; ?>" name="del_cat[]" value="<?php echo $row->cat_id; ?>" />
      									<label for="test<?php echo $row->cat_id; ?>"><?php echo $row->cat_name; ?></label>
    								</p>
    							</li>
								<?php } ?>
								<li class="center collection-item"><input type="submit" name="del_cat_submit" class="btn " value="Delete Categories"/></li>
							</ul>
							</form>
						</div>
					</div>
				</div>
				<hr />
				<div class="card-content center">
					<a href="admin/logout" class="btn btn-large">Logout</a>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url().'assests/materialize/js/materialize.min.js'; ?>"></script>
	</body>
</html>