<div class="content-main">
	<h1>Organization <a href="<?php echo RouteManager::createUrl('organization/create');?>" class="btn btn-success">Add New</a></h1>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">My Organizations</div>
		<div class="panel-body">
			<p>List of my organizations</p>
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
</div>