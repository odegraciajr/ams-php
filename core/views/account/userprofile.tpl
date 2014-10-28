<div class="content-main">
	<h1>Profile</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/account');?>">My Profile</a></li>
			<li class="active"><?php echo App::Tools()->sanitize_text($userdata['full_name']);?></li>
		</ol>
	</div>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-body">
			<div class="list profile">
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
			</div>
		</div>
	</div>
</div>