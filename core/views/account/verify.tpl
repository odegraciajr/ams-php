<div class="login-wrap">
	<?php if( $error_type ): ?>
		<div class="alert alert-success" role="alert"><?php echo $error_message;?></div>
	<?php else:?>
		<div class="alert alert-danger" role="alert"><?php echo $error_message;?></div>
	<?php endif;?>
	
</div>