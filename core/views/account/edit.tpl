<div class="content-main">
	<h1>Profile</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo RouteManager::createUrl('account');?>">Profile</a></li>
			<li class="active">Edit Profile</li>
		</ol>
	</div>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-body">
			<?php if( $error ): ?>
				<div class="alert alert-<?php echo $error_type;?>" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<?php echo $error_message;?>
				</div>
			<?php endif;?>
			<form class="form-horizontal" method="post" role="form">
				<div class="list profile">
					<div class="form-group">
						<label for="email" class="col-sm-4 control-label text-left">Email</label>
						<div class="col-sm-8">
						  <input type="text" disabled="disabled" class="form-control" id="email" name="email" value="<?php echo $userInfo->email;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="first_name" class="col-sm-4 control-label text-left">First Name</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $userInfo->first_name;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="last_name" class="col-sm-4 control-label text-left">Last Name</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $userInfo->last_name;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="address" class="col-sm-4 control-label text-left">Address</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" id="address" name="address" value="<?php echo $userInfo->address;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="city" class="col-sm-4 control-label text-left">City</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" id="city" name="city" value="<?php echo $userInfo->city;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="state" class="col-sm-4 control-label text-left">State</label>
						<div class="col-sm-8">
						   <input type="text" class="form-control" id="state" name="state" value="<?php echo $userInfo->state;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="postal_code" class="col-sm-4 control-label text-left">Postal Code</label>
						<div class="col-sm-8">
						   <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo $userInfo->postal_code;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="country" class="col-sm-4 control-label text-left">Country</label>
						<div class="col-sm-8">
						   <select id="country" name="country" class="form-control">
								<?php App::Tools()->getCountriesOptionHTML($userInfo->country);?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-4 control-label text-left">Phone</label>
						<div class="col-sm-8">
						   <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $userInfo->phone;?>">
						</div>
					</div>
				</div>
				<p class="text-right reset">
					<input type="hidden" value="do_edit_profile" name="action_post"/>
					<button type="submit" class="btn btn-primary">Update Profile</button>
				</p>
			</form>
		</div>
	</div>
</div>