<div class="login-wrap">
	<?php if( $error ): ?>
		<div class="alert alert-danger" role="alert"><?php echo $error_message;?></div>
	<?php endif;?>
	<?php if( isset($regs_success) ): ?>
		<div class="alert alert-success" role="alert"><?php echo $regs_success;?></div>
	<?php endif;?>
	
	<form method="post" action="/account/login" class="form-horizontal" role="form">
	  <div class="form-group">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
		  <input type="email" class="form-control" id="email" name="email" placeholder="email@account.com">
		</div>
	  </div>
	  <div class="form-group">
		<label for="password" class="col-sm-2 control-label">Password</label>
		<div class="col-sm-10">
		  <input type="password" class="form-control" id="password" name="password" >
		</div>
	  </div>
	  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <div class="checkbox">
			<label>
			  <input name="remember" type="checkbox"> Remember me
			</label>
			<a class="register-link" href="<?php echo RouteManager::createUrl('/account/register');?>">Create an account</a>
		  </div>
		</div>
	  </div>
	  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-default">Login</button>
		</div>
	  </div>
	</form>
</div>