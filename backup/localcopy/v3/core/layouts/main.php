<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->getPageTitle();?></title>

    <!-- Bootstrap -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/css/styles.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div id="page">
		<header>
			<nav role="navigation" class="navbar navbar-default navbar-static-top">
			<!-- We use the fluid option here to avoid overriding the fixed width of a normal container within the narrow content columns. -->
			<div class="container">
				<div class="navbar-header">
				  <button data-target="#bs-example-navbar-collapse-8" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <a href="/" class="navbar-brand"><?php echo App::config()->name;?></a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div id="bs-example-navbar-collapse-8" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li <?php App::Tools()->setActiveNav($this->activeNav,'dashboard');?>><a href="<?php echo $this->createUrl('/dashboard');?>">Dashboard</a></li>
						<li <?php App::Tools()->setActiveNav($this->activeNav,'profile');?>><a href="<?php echo $this->createUrl('/account/');?>">Profile</a></li>
						<li <?php App::Tools()->setActiveNav($this->activeNav,'organization');?>>
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Organization <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?php echo $this->createUrl('/organization');?>">My Organizations</a></li>
								<li><a href="<?php echo $this->createUrl('/organization/create');?>">Create Organization</a></li>
							</ul>
						</li>
						<li <?php App::Tools()->setActiveNav($this->activeNav,'project');?>>
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Project <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?php echo $this->createUrl('/project');?>">My Projects</a></li>
								<li><a href="<?php echo $this->createUrl('/project/create');?>">Create Project</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo App::User()->full_name;?> <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?php echo $this->createUrl('/account/');?>">View Profile</a></li>
								<li><a href="<?php echo $this->createUrl('/account/home');?>">Manage Account</a></li>
								<li class="divider"></li>
								<li><a href="<?php echo $this->createUrl('/account/logout');?>">Logout</a></li>
							</ul>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div>
			</nav>
		</header>
		<div id="main">
			<div class="container">
				<?php require($this->viewFile);?>
			</div>
		</div>
		<footer></footer>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/assets/js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/scripts.js"></script>
  </body>
</html>