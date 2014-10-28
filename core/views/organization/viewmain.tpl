<div class="content-main">
	<h1><?php echo App::Tools()->sanitize_text($orgInfo['name']);?></h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('organization');?>">My Organization</a></li>
			<li class="active"><?php echo App::Tools()->sanitize_text($orgInfo['name']);?></li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo $this->createUrl('/organization/view/'.$id)?>">Main</a></li>
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id.'/members')?>">Members</a></li>
			<?php if($isOrgOwner):?>
			<li><a href="<?php echo $this->createUrl('/organization/view/'.$id.'/invite')?>">Invite</a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
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
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Members</label>
						<div class="col-sm-8">
						  <p class="form-control-static">
							<?php $text = "";?>
							<?php if( is_array($membersCount) && count($membersCount)>0 ):
								foreach($membersCount as $count):
									if($count['status'] == 1)
										$text .= '<span class="memberscount">'.$count['members'] . " Active" . '</span>';
										
									if($count['status'] == 0)
										$text .= '<span class="memberscount">'.$count['members'] . " Pending" . '</span>';
										
								endforeach;
							endif;?>
							<?php echo $text;?>
						  </p>
						</div>
					</div>
				</form>
		</div>
	</div>
</div>