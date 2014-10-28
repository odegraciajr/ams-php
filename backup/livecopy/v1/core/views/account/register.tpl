<div class="login-wrap register">
	<?php if( $error ): ?>
		<div class="alert alert-danger" role="alert"><?php echo $error_message;?></div>
	<?php endif;?>
	<form method="post" class="form-horizontal" role="form">
	  <div class="form-group">
		<label for="email" class="col-sm-4 control-label">Email</label>
		<div class="col-sm-8">
		  <input type="email" class="form-control" id="email" name="email" placeholder="email@account.com">
		</div>
	  </div>
	  <div class="form-group">
		<label for="password" class="col-sm-4 control-label">Password</label>
		<div class="col-sm-8">
		  <input type="password" class="form-control" id="password" name="password" >
		</div>
	  </div>
	  <div class="form-group">
		<label for="password_confirm" class="col-sm-4 control-label">Confirm Password</label>
		<div class="col-sm-8">
		  <input type="password" class="form-control" id="password_confirm" name="password_confirm" >
		</div>
	  </div>
	  <div class="form-group">
		<label for="first_name" class="col-sm-4 control-label">First Name</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="first_name" name="first_name">
		</div>
	  </div>
	  <div class="form-group">
		<label for="last_name" class="col-sm-4 control-label">Last Name</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="last_name" name="last_name">
		</div>
	  </div>
	  <!--<div class="form-group">
		<label for="address" class="col-sm-4 control-label">Address</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="address" name="address">
		</div>
	  </div>
	  <div class="form-group">
		<label for="country" class="col-sm-4 control-label">Country</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="country" name="country">
		</div>
	  </div>
	  <div class="form-group">
		<label for="city" class="col-sm-4 control-label">City</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="city" name="city">
		</div>
	  </div>
	  <div class="form-group">
		<label for="state" class="col-sm-4 control-label">State</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="state" name="state">
		</div>
	  </div>
	  <div class="form-group">
		<label for="postal_code" class="col-sm-4 control-label">Postal Code</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="postal_code" name="postal_code">
		</div>
	  </div>
	  <div class="form-group">
		<label for="phone" class="col-sm-4 control-label">Phone</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="phone" name="phone">
		</div>
	  </div>-->
		<div class="form-group">
			<div class="col-sm-12">
				<input name="action_post" value="do_register" type="hidden">
				<a class="login-link" href="<?php echo RouteManager::createUrl('/account/login');?>">Login</a>
				<button type="submit" class="btn btn-default register-btn">Register</button>
			</div>
		</div>
	</form>
</div>