<div class="content-main">
	<h1>Project <a href="<?php echo RouteManager::createUrl('project/create');?>" class="btn btn-success">Add New</a></h1>
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