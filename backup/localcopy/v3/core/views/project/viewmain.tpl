<div class="content-main">
	<h1><?php echo App::Tools()->sanitize_text($projInfo['name']);?></h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('project');?>">My Project</a></li>
			<li class="active"><?php echo App::Tools()->sanitize_text($projInfo['name']);?></li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo $this->createUrl('/project/view/'.$id)?>">Main</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/members')?>">Members</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages/');?>">Messages</a></li>
			<?php if($isProjOwner):?>
				<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/invite')?>">Invite</a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label class="col-sm-4 control-label text-left">Name</label>
					<div class="col-sm-8">
					  <p class="form-control-static"><?php echo $projInfo['name'];?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label text-left">Description</label>
					<div class="col-sm-8">
					  <p class="form-control-static"><?php echo $projInfo['description'];?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label text-left">Owner</label>
					<div class="col-sm-8">
					  <p class="form-control-static">
					  <?php
						$owner_id = $projInfo['user_id'];
						$account = new AccountModel();
						$owner = $account->getUserData($owner_id);
						echo $owner['first_name'] . " " . $owner['last_name'];
					  ?></p>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>