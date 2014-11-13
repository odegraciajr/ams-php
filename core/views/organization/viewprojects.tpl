<div class="content-main">
	<h1><?php echo App::Tools()->sanitize_text($orgInfo['name']);?></h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/organization');?>">My Organization</a></li>
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($orgInfo['name']);?></a></li>
			<li class="active">Projects</li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id)?>">Main</a></li>
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id.'/members')?>">Members</a></li>
			<li class="active"><a href="<?php echo $this->createUrl('/organization/view/'.$id.'/projects')?>">Projects</a></li>
			<?php if($isOrgOwner):?>
				<li><a href="<?php echo $this->createUrl('/organization/view/'.$id.'/invite')?>">Invite</a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
			<div class="list">
				<table class="table table-bordered">
				  <thead>
					<tr>
					  <th>Name</th>
					  <th>Description</th>
					  <th>Owner</th>
					  <th>Date Created</th>
					  <th></th>
					</tr>
				  </thead>
				  <tbody>
					<?php if(is_array($projects) && count($projects)> 0):?>
						<?php foreach( $projects as $project ):?>
							<td><?php echo App::Tools()->sanitize_text($project['name']);?></td>
							<td><?php echo App::Tools()->sanitize_text($project['description']);?></td>
							<td>
								<a href="<?php echo $this->getProfileURL($project['user_id']);?>">
									<?php echo App::Tools()->sanitize_text($project['owner_name']);?>
								</a>
							</td>
							<td><?php echo App::Tools()->sanitize_text(date("d/m/Y",strtotime($project['date_created'])));?></td>
							<td><a href="<?php echo $this->createUrl('/project/view/'.$project['id']);?>" class="btn btn-default btn-xs">View</a></td>
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