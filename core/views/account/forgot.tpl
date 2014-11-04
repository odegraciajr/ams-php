<div class="login-wrap">
	<?php if( $error ): ?>
		<div class="alert alert-<?php echo $error_type;?>" role="alert"><?php echo $error_message;?></div>
	<?php endif;?>
	<form method="post" action="/account/forgot" class="form-horizontal" role="form">
	  <div class="form-group">
		<label for="email_reset" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
		  <input type="email" class="form-control" id="email_reset" name="email_reset" placeholder="email@account.com">
		</div>
	  </div>
	  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-default">Reset Password</button>
		  <a class="forgot-link" href="<?php echo $this->createUrl('/account/login');?>">Login</a>
		</div>
	  </div>
	</form>
</div>