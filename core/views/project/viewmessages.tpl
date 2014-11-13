<div id="messages" class="content-main">
	<h1>Messages</h1>
	<?php if(isset($error_add_member)):?>
		<div class="alert alert-info" role="alert"><?php echo $error_add_member_message;?></div>
	<?php endif;?>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/project');?>">My Project</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($projInfo['name']);?></a></li>
			<li class="active">Messages</li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id)?>">Main</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/members')?>">Members</a></li>
			<li class="active"><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages')?>">Messages</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/activity')?>">Activity</a></li>
			<?php if($userRole >= 8):?>
				<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/invite')?>">Invite</a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
			<?php if($userRole >= 8):?>
				<p class="text-right">
					<a href="<?php echo $this->createUrl('/project/view/'.$id.'/newthread')?>" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-plus"></span> Start new thread</a>
				</p>
			<?php endif;?>
			<div class="list-messages">
				<ul class="list-group">
				<?php if( is_array($threads) && count($threads) > 0):?>
					<?php foreach( $threads as $thread ):?>
						<li class="list-group-item">
							<h4 class="list-group-item-heading"><a href="<?php echo $this->createUrl('/project/messages/'.$id.'/'.$thread['id'])?>" class="title"><?php echo App::Tools()->sanitize_text($thread['subject']);?></a></h4>
							<h6 class="list-group-item-text">Started by <a href="<?php echo $this->createUrl('/account/userprofile/'.$thread['user_id'])?>"><?php echo App::Tools()->sanitize_text($thread['full_name']);?></a>, <?php echo date("F jS Y, g:i a",strtotime($thread['date_created']));?></h6>
						</li>
					<?php endforeach;?>
				<?php else:?>
					<div class="alert alert-danger">No messages.</div>
				<?php endif;?>
				</ul>
			</div>
		</div>
	</div>
</div>