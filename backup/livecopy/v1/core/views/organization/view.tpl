<div class="account-home">
	<h1 class="welcome-title"><?php echo 'Organization Management';?></h1>
	<ul id="myaccount" role="tablist" class="nav nav-tabs">
	  <li class="<?php echo $activeTab == "mainTab" ? "active":"";?>"><a class="tab" href="#mainTab">Main</a></li>
	  <li class="<?php echo $activeTab == "members" ? "active":"";?>"><a class="tab" href="#members">Members</a></li>
	  <?php if($isOrgOwner):?>
	  <li class="<?php echo $activeTab == "addmembers" ? "active":"";?>"><a class="tab" href="#addmembers">Add Members</a></li>
	  <?php endif;?>
	  <li class="navbar-right"><a href="<?php echo RouteManager::createUrl('/account');?>">Account Settings</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane <?php echo $activeTab == "mainTab" ? "active":"";?>" id="mainTab">
			<div class="account-section">
				<h4>Organization Info</h4>
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Name</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $orgInfo['name'];?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Description</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo $orgInfo['description'];?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Owner</label>
						<div class="col-sm-8">
						  <p class="form-control-static">
						  <?php
							$owner_id = $orgInfo['user_id'];
							$account = new AccountModel();
							$owner = $account->getUserData($owner_id);
							echo $owner['first_name'] . " " . $owner['last_name'];
						  ?></p>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="tab-pane <?php echo $activeTab == "members" ? "active":"";?>" id="members">
			<div class="account-section">
				<h4>Members</h4>
				<table class="table table-bordered">
				  <thead>
					<tr>
					  <th>Name</th>
					  <th>Role</th>
					  <th>Status</th>
					  <th>Date Joined</th>
					  <?php if($isOrgOwner):?><th></th><?php endif;?>
					</tr>
				  </thead>
				  <tbody>
					<?php if(is_array($orgMembers) && count($orgMembers)> 0):?>
						<?php foreach( $orgMembers as $org ):?>
							<tr id="organization_<?php echo $org['id'];?>">
								<td><?php echo empty($org['full_name']) ? $org['email'] : $org['full_name'];?></td>
								<td><?php echo OrganizationModel::getRole($org['role_id']);?></td>
								<td><?php echo OrganizationModel::getStatus($org['status']);?></td>
								<td><?php echo date("F j, Y",strtotime($org['date_joined']));?></td>
								<?php if($isOrgOwner):?>
								<td>
									<?php if( $org['role_id'] >= 8 ):?>
										<a href="<?php echo RouteManager::createUrl('/organization/view/'.$org['id']);?>" class="btn btn-primary btn-xs">Manage</a>
									<?php else:?>
										<a href="<?php echo RouteManager::createUrl('/organization/view/'.$org['id']);?>" class="btn btn-default btn-xs">View</a>
									<?php endif;?>
								</td>
								<?php endif;?>
							</tr>
						<?php endforeach;?>
					<?php endif;?>
				  </tbody>
				</table>
			</div>
		</div>
		<?php if($isOrgOwner):?>
			<div class="tab-pane <?php echo $activeTab == "addmembers" ? "active":"";?>" id="addmembers">
				<div class="account-section">
					<?php if(isset($error_add_member)):?>
						<div class="alert alert-info" role="alert"><?php echo $error_add_member_message;?></div>
					<?php endif;?>
					<h4>Add Members</h4>
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
					  <button type="submit" class="btn btn-default">Add</button>
					</form>
				</div>
			</div>
		<?php endif;?>
	</div>
</div>