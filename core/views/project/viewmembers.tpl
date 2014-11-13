<div class="content-main">
	<h1><?php echo App::Tools()->sanitize_text($projInfo['name']);?></h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/project');?>">My Project</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($projInfo['name']);?></a></li>
			<li class="active">Members</li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id)?>">Main</a></li>
			<li class="active"><a href="<?php echo $this->createUrl('/project/view/'.$id.'/members')?>">Members</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages/');?>">Messages</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/activity')?>">Activity</a></li>
			<?php if($isProjOwner):?>
				<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/invite')?>">Invite</a></li>
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
				</tr>
			  </thead>
			  <tbody>
				<?php if(is_array($projMembers) && count($projMembers)> 0):?>
					<?php $members_user_ids=array();?>
					<?php foreach( $projMembers as $proj ):?>
						<tr id="project_<?php echo $proj['id'];?>">
							<td>
								<a href="<?php echo RouteManager::createUrl('/account/userprofile/'.$proj['user_id']);?>">
									<?php echo empty($proj['full_name']) ? $proj['email'] : $proj['full_name'];?>
								</a>
							</td>
							<td><?php echo ProjectModel::getRole($proj['role_id']);?></td>
							<td><?php echo ProjectModel::getStatus($proj['status']);?></td>
							<td><?php echo date("F j, Y",strtotime($proj['date_joined']));?></td>
						</tr>
						<?php $members_user_ids[]=intval($proj['user_id']);?>
					<?php endforeach;?>
				<?php endif;?>
			  </tbody>
			</table>
		</div>
	</div>
</div>