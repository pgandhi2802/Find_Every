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
							<h3 class="center">Approve/Disapprove Management</h3>
						</div>
					</div>
					<hr />
					<div class="row">
					<?php if(!$has_any) { ?>
						<h4>No Request found to approve</h4>
					<?php } 
					else
					{ ?>
						<ul class="pagination center">
							<li <?php if($page_no==1) { ?> class="disabled" <?php } ?>><a <?php if($page_no!=1) { ?>href="<?php echo base_url().'admin/approve_edit/'.($page_no-1); ?>" <?php echo "disabled";} ?>><i class="material-icons">chevron_left</i></a></li>
							<?php if(($page_no-2)>0) { ?><li class="waves-effect"><a href="<?php echo base_url().'admin/approve_edit/'.($page_no-2); ?>"><?php echo $page_no-2; ?></a></li> <?php } ?>
							<?php if(($page_no-1)>0) { ?><li class="waves-effect"><a href="<?php echo base_url().'admin/approve_edit/'.($page_no-1); ?>"><?php echo $page_no-1; ?></a></li> <?php } ?>
							<li class="active"><a href="<?php echo base_url().'admin/approve_edit/'.($page_no); ?>"><?php echo $page_no ?></a></li>
							<?php if(($count_entry-(($page_no)*$block_size))>0){ ?><li class="waves-effect"><a href="<?php echo base_url().'admin/approve_edit/'.($page_no+1); ?>"><?php echo $page_no+1; ?></a></li><?php } ?>
							<?php if(($count_entry-(($page_no+1)*$block_size))>0){ ?><li class="waves-effect"><a href="<?php echo base_url().'admin/approve_edit/'.($page_no+2); ?>"><?php echo $page_no+2; ?></a></li><?php } ?>
							<li <?php if(($page_no*$block_size)>=$count_entry) { ?> class="disabled" <?php } ?>><a <?php if(($count_entry-(($page_no)*$block_size))>0) { ?>href="<?php echo base_url().'admin/approve_edit/'.($page_no+1); ?>" <?php echo "disabled";} ?>><i class="material-icons">chevron_right</i></a></li>
						</ul>
					
						<div class="col s10 offset-s1 m8 offset-m2">
							<ul class="collapsible popout" data-collapsible="accordion">
							<?php foreach($content as $row) { ?>
							    <li>
							      	<div class="collapsible-header">
							      		<i class="material-icons">place</i>
							      		<?php echo $row->shop_name; ?> ( <?php if($row->shop_cat==1) 
																echo "Shop";
															else
																echo "Event";
															?> )
							      		<div class="secondary-content">
								      		<a href="<?php echo base_url().'admin/approve_edit/Delete/'.$row->shop_id; ?>">
								      			<i class="material-icons">delete</i>
								      		</a>
								      		<a href="<?php echo base_url().'admin/approve_edit/Approve/'.$row->shop_id; ?>">
								      			<i class="material-icons">done</i>
								      		</a>
								      		<a href="<?php echo base_url().'admin/approve_edit/View/'.$row->shop_id; ?>">
								      			<i class="material-icons">visibility</i>
								      		</a>
								      	</div>
								      </div>
							      	<div class="collapsible-body">
							      		<ul class="collection">
							      			<li class="collection-item row">
													<p class="col m3">
														Email
													</p>
													<p class="col m9">
														: 
														<?php echo $row->shop_email; ?>
													</p>
											</li>
							      			<li class="collection-item row">
													<p class="col m3">
														Address
													</p>
													<p class="col m9">
														: 
														<?php echo $row->shop_add_line_1.",".$row->shop_add_line_2; ?>
													</p>
											</li>
											<li class="collection-item row">
													<p class="col m3">
														city
													</p>
													<p class="col m9">
														: 
														<?php echo $row->shop_city; ?>
													</p>
											</li>
											<li class="collection-item row">
													<p class="col m3">
														Contact No
													</p>
													<p class="col m9">
														: 
														<?php echo $row->shop_contact_no; ?>
													</p>
											</li>
							      		</ul>
							      	</div>
							    </li>
							    <?php } ?>
  							</ul>
						</div>
						<?php } ?>
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