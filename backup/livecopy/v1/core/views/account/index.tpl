<div class="account-home">
	<h1 class="welcome-title">Welcome <?php echo App::User()->first_name;?>!</h1>
	<ul id="myaccount" role="tablist" class="nav nav-tabs">
	  <li class="<?php echo $activeTab == "profile" ? "active":"";?>"><a class="tab" href="#profile">Profile</a></li>
	  <li class="<?php echo $activeTab == "organization" ? "active":"";?>"><a class="tab" href="#organization">Organization</a></li>
	  <li class="<?php echo $activeTab == "project" ? "active":"";?>"><a class="tab" href="#project">Project</a></li>
	  <li class="dropdown navbar-right">
		<a href="#" data-toggle="dropdown" class="dropdown-toggle">
		  Account <span class="caret"></span>
		</a>
		<ul role="menu" class="dropdown-menu">
		  <li><a href="/account/logout">Logout</a></li>
		</ul>
	  </li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane <?php echo $activeTab == "profile" ? "active":"";?>" id="profile">
			<div class="account-section">
				<h4>Profile Info</h4>
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Email</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->email;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">First Name</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->first_name;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Last Name</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->last_name;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Address</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->address;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">City</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->city;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">State</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->state;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Postal Code</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->postal_code;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Country</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->country;?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Phone</label>
						<div class="col-sm-8">
						  <p class="form-control-static"><?php echo App::User()->phone;?></p>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="tab-pane <?php echo $activeTab == "organization" ? "active":"";?>" id="organization">
			<?php if(App::User()->type == 1):?>
				<div class="account-section">
					<h4>Create Oranization</h4>
					<form method="post" class="form-horizontal" role="form">
					  <div class="form-group">
						<label for="name" class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10">
						  <input type="text" class="form-control" id="name" name="name" placeholder="Oranization Name">
						</div>
					  </div>
					  <div class="form-group">
						<label for="description" class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10">
						  <textarea id="description" name="description" class="form-control" rows="3"></textarea>
						</div>
					  </div>
					  <div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="hidden" value="do_organization" name="action_post"/>
						  <button type="submit" class="btn btn-default">Create Organization</button>
						</div>
					  </div>
					</form>
				</div>
			<?php endif;?>
			<div class="account-section">
				<h4>My Organizations</h4>
				<table class="table table-bordered">
				  <thead>
					<tr>
					  <th>Name</th>
					  <th>Description</th>
					  <th>Role</th>
					  <th>Date Joined</th>
					  <th></th>
					</tr>
				  </thead>
				  <tbody>
					<?php if(is_array($myOrg) && count($myOrg)> 0):?>
						<?php foreach( $myOrg as $org ):?>
							<tr id="organization_<?php echo $org['id'];?>">
								<td><?php echo $org['name'];?></td>
								<td><?php echo $org['description'];?></td>
								<td><?php echo OrganizationModel::getRole($org['role_id']);?></td>
								<td><?php echo date("F j, Y",strtotime($org['date_joined']));?></td>
								<td>
									<?php if( $org['role_id'] >= 8 ):?>
										<a href="<?php echo RouteManager::createUrl('/organization/view/'.$org['id']);?>" class="btn btn-primary btn-xs">Manage</a>
									<?php else:?>
										<a href="<?php echo RouteManager::createUrl('/organization/view/'.$org['id']);?>" class="btn btn-default btn-xs">View</a>
									<?php endif;?>
								</td>
							</tr>
						<?php endforeach;?>
					<?php else:?>
						<tr><td colspan="5">No results</td></tr>
					<?php endif;?>
				  </tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane <?php echo $activeTab == "project" ? "active":"";?>" id="project">
			<?php if(App::User()->type == 1):?>
				<div class="account-section">
					<h4>Create Project</h4>
					<form method="post" class="form-horizontal" role="form">
					  <div class="form-group">
						<label for="name" class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10">
						  <input type="text" class="form-control" id="name" name="name" placeholder="Oranization Name">
						</div>
					  </div>
					  <div class="form-group">
						<label for="description" class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10">
						  <textarea id="description" name="description" class="form-control" rows="3"></textarea>
						</div>
					  </div>
					  <div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="hidden" value="do_project" name="action_post"/>
						  <button type="submit" class="btn btn-default">Create Project</button>
						</div>
					  </div>
					</form>
				</div>
			<?php endif;?>
			<div class="account-section">
				<h4>My Projects</h4>
				<table class="table table-bordered">
				  <thead>
					<tr>
					  <th>Name</th>
					  <th>Description</th>
					  <th>Role</th>
					  <th>Date Joined</th>
					  <th></th>
					</tr>
				  </thead>
				  <tbody>
					<?php if(is_array($myProject) && count($myProject)> 0):?>
						<?php foreach( $myProject as $project ):?>
							<tr id="organization_<?php echo $project['id'];?>">
								<td><?php echo $project['name'];?></td>
								<td><?php echo $project['description'];?></td>
								<td><?php echo ProjectModel::getRole($project['role_id']);?></td>
								<td><?php echo date("F j, Y",strtotime($project['date_joined']));?></td>
								<td>
									<?php if( $project['role_id'] >= 8 ):?>
										<a href="<?php echo RouteManager::createUrl('/project/view/'.$project['id']);?>" class="btn btn-primary btn-xs">Manage</a>
									<?php else:?>
										<a href="<?php echo RouteManager::createUrl('/project/view/'.$project['id']);?>" class="btn btn-default btn-xs">View</a>
									<?php endif;?>
								</td>
							</tr>
						<?php endforeach;?>
					<?php else:?>
						<tr><td colspan="5">No results</td></tr>
					<?php endif;?>
				  </tbody>
				</table>
			</div>
		</div>
	</div>
</div>