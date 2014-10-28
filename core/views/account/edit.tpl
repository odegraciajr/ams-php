<div class="content-main">
	<h1>Profile</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo RouteManager::createUrl('account/home');?>">Profile</a></li>
			<li class="active">Edit Profile</li>
		</ol>
	</div>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-body">
			<div class="list profile">
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label for="email" class="col-sm-4 control-label text-left">Email</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" id="email" name="email" value="<?php echo App::User()->email;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="first_name" class="col-sm-4 control-label text-left">First Name</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo App::User()->first_name;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="last_name" class="col-sm-4 control-label text-left">Last Name</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo App::User()->last_name;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="address" class="col-sm-4 control-label text-left">Address</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" id="address" name="address" value="<?php echo App::User()->address;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="city" class="col-sm-4 control-label text-left">City</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" id="city" name="city" value="<?php echo App::User()->city;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="state" class="col-sm-4 control-label text-left">State</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->state;?></p>
						   <input type="text" class="form-control" id="state" name="state" value="<?php echo App::User()->state;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="postal_code" class="col-sm-4 control-label text-left">Postal Code</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->postal_code;?></p>
						   <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo App::User()->postal_code;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="country" class="col-sm-4 control-label text-left">Country</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->country;?></p>
						   <input type="text" class="form-control" id="country" name="country" value="<?php echo App::User()->country;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-4 control-label text-left">Phone</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->phone;?></p>
						   <input type="text" class="form-control" id="phone" name="phone" value="<?php echo App::User()->phone;?>">
						</div>
					</div>
				</form>
			</div>
			<p style="padding:0;margin:0" class="text-right">
				<a href="#" class="btn btn-primary">Update Profile</a>
			</p>
		</div>
	</div>
</div>