<div class="content-main">
	<h1><?php echo App::Tools()->sanitize_text($orgInfo['name']);?></h1>
	<?php if(isset($error_add_member)):?>
		<div class="alert alert-info" role="alert"><?php echo $error_add_member_message;?></div>
	<?php endif;?>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/organization');?>">My Organization</a></li>
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($orgInfo['name']);?></a></li>
			<li class="active">Invite</li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id)?>">Main</a></li>
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id.'/members')?>">Members</a></li>
			<li class="active"><a href="<?php echo $this->createUrl('/organization/view/'.$id.'/invite')?>">Invite</a></li>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
			<form method="post" class="form" role="form">
			  <div class="form-group">
				<label class="sr-only" for="email">Email</label>
				<input type="email" class="form-control" id="email" value="<?php echo $email_value;?>" name="email" placeholder="Enter email address">
			  </div>
			  <?php if($new_user_invite):?>
			  <div class="form-group">
				<label class="sr-only" for="first_name">First Name</label>
				<input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
			  </div>
			  <div class="form-group">
				<label class="sr-only" for="last_name">Last Name</label>
				<input type="email" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
			  </div>
			  <?php endif;?>
			  <input type="hidden" value="do_organization_invite" name="action_post"/>
			  <p class="text-right reset">
				<button type="submit" class="btn btn-primary">Invite Member</button>
			  </p>
			</form>
		</div>
	</div>
</div>