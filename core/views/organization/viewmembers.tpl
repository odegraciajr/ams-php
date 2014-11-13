<div class="content-main">
	<h1><?php echo App::Tools()->sanitize_text($orgInfo['name']);?></h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/organization');?>">My Organization</a></li>
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($orgInfo['name']);?></a></li>
			<li class="active">Members</li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id)?>">Main</a></li>
			<li class="active"><a href="<?php echo $this->createUrl('/organization/view/'.$id.'/members')?>">Members</a></li>
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id.'/projects')?>">Projects</a></li>
			<?php if($isOrgOwner):?>
				<li><a href="<?php echo $this->createUrl('/organization/view/'.$id.'/invite')?>">Invite</a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
			<table class="table">
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
								<td>
									<a href="<?php echo RouteManager::createUrl('/account/userprofile/'.$org['user_id']);?>">
										<?php echo empty($org['full_name']) ? $org['email'] : $org['full_name'];?>
									</a>
								</td>
								<td><?php echo OrganizationModel::getRole($org['role_id']);?></td>
								<td><?php echo OrganizationModel::getStatus($org['status']);?></td>
								<td><?php echo date("F j, Y",strtotime($org['date_joined']));?></td>
								<?php if($isOrgOwner):?>
								<td>
									<?php if( $org['role_id'] >= 8 ):?>
										<a href="<?php echo RouteManager::createUrl('/organization/view/'.$org['id']);?>" class="btn btn-primary btn-xs">action</a>
									<?php else:?>
										<a href="<?php echo RouteManager::createUrl('/organization/view/'.$org['id']);?>" class="btn btn-default btn-xs">action</a>
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
</div>