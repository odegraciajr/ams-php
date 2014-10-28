<div class="content-main">
	<h1>Profile</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li class="active">Profile</li>
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
						  <p class="form-control-static"><?php echo App::User()->email;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">First Name</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->first_name;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Last Name</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->last_name;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Address</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->address;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">City</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->city;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">State</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->state;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Postal Code</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->postal_code;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Country</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->country;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Phone</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->phone;?></p>
						</div>
					</div>
				</form>
			</div>
			<p style="padding:0;margin:0" class="text-right">
				<a href="<?php echo RouteManager::createUrl('/account/edit');?>" class="btn btn-primary">Edit Profile</a>
			</p>
		</div>
	</div>
</div>