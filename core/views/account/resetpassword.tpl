<div class="login-wrap register">
	<?php if( $error ): ?>
		<div class="alert alert-<?php echo $error_type;?>" role="alert"><?php echo $error_message;?></div>
	<?php endif;?>
	<h4>Reset Password</h4>
	<form method="post" class="form-horizontal" role="form">
		<div class="form-group">
			<label for="password_reset" class="col-sm-4 control-label">Password</label>
			<div class="col-sm-8">
			<input type="password" class="form-control" id="password_reset" name="password_reset" >
			</div>
		</div>
		<div class="form-group">
			<label for="password_confirm_reset" class="col-sm-4 control-label">Confirm Password</label>
			<div class="col-sm-8">
			<input type="password" class="form-control" id="password_confirm_reset" name="password_confirm_reset" >
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input name="action_post" value="do_password_reset" type="hidden">
				<button type="submit" class="btn btn-default register-btn">Reset Password</button>
			</div>
		</div>
	</form>
</div>