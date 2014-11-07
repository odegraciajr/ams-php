<div class="account-home">
	<h1 class="welcome-title">User Profile</h1>
	<ul id="myaccount" role="tablist" class="nav nav-tabs">
	  <li class="active"><a class="tab" href="#profile">Profile</a></li>
	 <li class="navbar-right"><a href="<?php echo RouteManager::createUrl('/account');?>">Account Settings</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane active" id="profile">
			<div class="account-section">
				<h4>Profile Info</h4>
				<?php if($userdata):?>
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Email</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $userdata['email'];?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">First Name</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $userdata['first_name'];?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Last Name</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $userdata['last_name'];?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Address</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $userdata['address'];?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">City</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $userdata['city'];?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">State</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $userdata['state'];?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Postal Code</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $userdata['postal_code'];?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Country</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $userdata['country'];?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Phone</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $userdata['phone'];?></p>
						</div>
					</div>
				</form>
				<?php else:?>
					<p>User not found!</p>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>