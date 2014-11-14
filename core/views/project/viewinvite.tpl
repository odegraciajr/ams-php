<div class="content-main">
	<h1>Invite</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/project');?>">My Project</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($projInfo['name']);?></a></li>
			<li class="active">Invite</li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id)?>">Main</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/members')?>">Members</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages/');?>">Messages</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/activity')?>">Activity</a></li>
			<li class="active"><a href="<?php echo $this->createUrl('/project/view/'.$id.'/invite')?>">Invite</a></li>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
			<?php if(isset($error_add_member)):?>
				<div class="alert alert-info" role="alert"><?php echo $error_add_member_message;?></div>
			<?php endif;?>
			<?php
				$acc = new ProjectModel();
				$allusers = $acc->getAllProjectUsersForInvite($projInfo['id']);
				$validUserInvites = array();
			?>
			<?php if(is_array($projMembers) && count($projMembers)> 0):?>
					<?php $members_user_ids=array();?>
					<?php foreach( $projMembers as $proj ):?>
						<?php $members_user_ids[]=intval($proj['user_id']);?>
					<?php endforeach;?>
				<?php endif;?>
			<h4>Add existing user</h4>
			<form method="post" class="form" role="form">
				<div class="form-group">
					<div class="input-group">
						<input type="text" class="form-control" id="search_user" placeholder="Type any name or email"/>
						<span class="input-group-btn">
							<button type="button" id="reset-all-user-invite" class="btn btn-danger">Reset</button>
						</span>
					</div>
				</div>
				<div class="form-group">
					<label class="sr-only" for="email">Email</label>
					<select id="user-invite-select" name="email[]" class="form-control" multiple>
						<?php foreach($allusers as $user):?>
							<?php if(!in_array(intval($user['id']),$members_user_ids,true)):?>
								<option value="<?php echo $user['email'];?>"><?php echo $user['full_name'];?></option>
								<?php $validUserInvites[] = $user;?>
							<?php endif;?>
						<?php endforeach;?>
					</select>
				</div>
			  <input type="hidden" value="do_project_invite" name="action_post"/>
			  <p class="text-right reset">
				<button type="submit" class="btn btn-primary">Invite User</button>
			  </p>
			</form>

			<h4>Add External user</h4>
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
			  <input type="hidden" value="do_project_invite" name="action_post"/>
			  <p class="text-right reset">
				<button type="submit" class="btn btn-primary">Invite User</button>
			  </p>
			</form>
		</div>
	</div>
</div>
<script>
	var AllProjectUsersForInvite = <?php echo App::Tools()->toJson($validUserInvites,false);?>;
	var AllProjectMemberUserIds = <?php echo App::Tools()->toJson($members_user_ids,false);?>;
</script>