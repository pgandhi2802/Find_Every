<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>welcome Admin</title>
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
		<div class="valign-wrapper container center-align">
			<div class="card row">
				<div class="card-content">
					<h3>Welcome to Find Every Community</h3>
				</div>
				<hr />
				<div class="card-content valign">
					<div class="row">
						<div class="center-text col s12">
							<h3>Category Management</h3>
						</div>
						<div class="center-text col s12 m4">
							<a class="btn btn-large" href="<?php echo base_url().'admin/show_cat'; ?>" >Show Category</a>
						</div>
						<div class="center-text col s12 m4">
							<a class="btn btn-large" href="<?php echo base_url().'admin/add_cat'; ?>" >Add Category</a>
						</div>
						<div class="center-text col s12 m4">
							<a class="btn btn-large" href="<?php echo base_url().'admin/delete_cat'; ?>" >Delete Category</a>
						</div>
					</div>
					<hr />
					<div class="row">
						<div class="center-text col s12">
							<h3>Shop/Event Regostration Management</h3>
						</div>
						<div class="center-text col s12 m6">
							<a class="btn btn-large" href="<?php echo base_url().'admin/approve_new'; ?>" >Approve/Disapprove New Entry Request</a>
						</div>
						<div class="center-text col s12 m6">
							<a class="btn btn-large" href="<?php echo base_url().'admin/approve_edit'; ?>" >Approve/Disapprove Update Request</a>
						</div>
					</div>
				</div>
				<hr />
				<div class="card-content">
					<a href="logout" class="btn btn-large">Logout</a>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url().'assests/materialize/js/materialize.min.js'; ?>"></script>
	</body>
</html>