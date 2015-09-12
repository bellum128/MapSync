<html>
	<head>
		<title>MapSync - By bellum128</title>
		<!-- <meta http-equiv="refresh" content="5"> -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-custom.css">		
		<link rel="stylesheet" type="text/css" href="css/map.css">
	</head>

	<body>	
		<?php $loadStart = microtime(true);?>
	
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<nav class="navbar navbar-inverse" role="navigation">
						<div class="navbar-header">								 
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								 <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
							</button> <a class="navbar-brand" href="/">MapSync</a>
						</div>
						
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<li class="active">
									<a href="/index.php">Map</a>
								</li>
								<li>
									<a href="/embed.php">Embed</a>
								</li>
								<li>
									<a href="/about.php">About</a>
								</li>						
							</ul>
						</div>						
					</nav>
				</div>
			</div>
			
			<div class="row  topNotice">
				<div class="col-md-12 well well-sm">
					<p>MapSync allows you to monitor exactly what is happening in your Garry's Mod server.</p>
				</div>
			</div>
			
			<div class="row">
				<iframe style="width:100%;height:690px;border:none;", src="/frame.php?map=true&infobox=true">Map not supported by browser.</iframe>
			
				<footer class="footer">
					<div class="container">
						<?php
							$loadEnd = microtime(true);
							$loadTime = ($loadEnd - $loadStart);
						?>
						<p class="text-muted">MapSync is created by <a href="http://bellum128.weebly.com">bellum128 Productions</a> <span class = "pull-right"> Generated in <?php echo(round($loadTime, 4))?> seconds </br>Updated at: <?php  echo(date("g:i:sa", filemtime("index.php"))) ?></span></p>
					</div>
			    </footer>
			</div>
		</div>

		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/bootstrap.min.js"></script>	
	</body>	
</html>
