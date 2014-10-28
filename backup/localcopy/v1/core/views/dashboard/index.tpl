<div class="content-main">
	<h1>Dashboard</h1>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">Recent Activity</div>
		<div class="panel-body">
			<div class="list">
				<p><a href="#">user1</a> commented on <a href="#">Project AMS</a>.</p>
				<p><a href="#">userAdmin</a> posted a note on <a href="#">DSS Organization</a>.</p>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">My Organization</div>
		<div class="panel-body">
			<div class="list">
				<table class="table">
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
	</div>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">My Projects</div>
		<div class="panel-body">
			<div class="list">
				<table class="table">
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