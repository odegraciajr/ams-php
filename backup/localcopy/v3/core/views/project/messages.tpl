<div id="messages" class="content-main">
	<h1>Messages</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/project');?>">My Project</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($projInfo['name']);?></a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages')?>">Messages</a></li>
			<li class="active"><?php echo App::Tools()->sanitize_text($thread['subject']);?></li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id)?>">Main</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/members')?>">Members</a></li>
			<li class="active"><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages')?>">Messages</a></li>
			<?php if($userRole >= 8):?>
				<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/invite')?>">Invite</a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
			<p class="text-right">
				<a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages')?>" class="btn btn-primary btn-lg">Thread List</a>
			</p>
			<div class="thread-main">
				<div class="media">
					<div class="pull-left userinfo">
						<a class="full_name" href="<?php echo $this->createUrl('/account/userprofile/'.$thread['user_id']);?>"><?php echo $thread['full_name'];?></a>
						<br/>
						<span class="label label-danger">Admin</span>
					</div>
					<div class="media-body">
						<h4 class="media-heading"><?php echo App::Tools()->sanitize_text($thread['subject']);?><span class="badge pull-right posted-date">Posted: <?php echo date("F jS Y, g:i a",strtotime($thread['date_created']));?></span></h4>
						<p class="message-body">
							<?php echo App::Tools()->wpautop($thread['message']);?>
						</p>
					</div>
				</div>
			</div>
			<div class="thread-comments">
				<h4>Comments <span class="badge"><?php echo $comment_count['count'];?></span></h4>
				<div class="comment-list">
				<?php if( is_array($comments) && count($comments) > 0):?>
					<?php foreach( $comments as $comment ):?>
						<div class="media" id="comment-<?php echo $comment['id'];?>">
							<div class="pull-left userinfo">
								<a class="full_name" href="<?php echo $this->createUrl('/account/userprofile/'.$comment['user_id']);?>"><?php echo $comment['full_name'];?></a>
								<br/>
								<?php if( $comment['role_id'] == 9 ):?>
									<span class="label label-danger">Admin</span>
								<?php else:?>
									<span class="label label-info">Member</span>
								<?php endif;?>
							</div>
							<div class="media-body">
								<p class="message-body">
									<?php echo App::Tools()->wpautop($comment['message_text']);?>
								</p>
								<p><span class="badge pull-right posted-date">Posted: <?php echo date("F jS Y, g:i a",strtotime($comment['date_created']));?></span></p>
							</div>
						</div>
					<?php endforeach;?>
				<?php endif;?>
					
				</div>
				<h4>Leave Comment</h4>
				<?php if($error_message):?>
					<div class="alert alert-danger" role="alert"><?php echo $error_message;?></div>
				<?php endif;?>
				<div class="comment-form">
					<form method="post" class="form-horizontal" role="form">
					  <div class="form-group">
						<div class="col-sm-12">
						  <textarea id="comment" name="comment" class="form-control" rows="3"></textarea>
						</div>
					  </div>
					  <div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="hidden" value="do_add_comment" name="action_post"/>
							<p class="text-right reset">
								<button type="submit" class="btn btn-primary">Add Comment</button>
							</p>
						</div>
					  </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>