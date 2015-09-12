<html>
	<head>
		<title>MapSync - By bellum128 :: Embed</title>
		<!-- <meta http-equiv="refresh" content="5"> -->
		<meta name="viewport" content="width=device-width, initial-scale=1">		
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-custom.css">		
		<link rel="stylesheet" type="text/css" href="css/map.css">
	</head>

	<body onload="updateEmbedCode();">	
		<?php
			function currPageURL() {
			 $pageURL = 'http';
			 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			 $pageURL .= "://";
			 if ($_SERVER["SERVER_PORT"] != "80") {
			  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
			 } else {
			  $pageURL .= $_SERVER["SERVER_NAME"];
			 }
			 return $pageURL;
			}
		?>

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
								<li>
									<a href="/index.php">Map</a>
								</li>
								<li class="active">
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
					<p>Use this page to embed your server's MapSync map on your own site.</p>
				</div>
			</div>

			<div class="row topNotice">
				<div class="col-md-6 well well-sm">
					<p><u>Options</u></p>
					<form>
						<input type="checkbox" id="chkMap" value="Map" onclicked = "updateEmbedCode();" checked>Map
						<br/>
						<input type="checkbox" id="chkInfo" value="Server Info Box" onclicked = "updateEmbedCode();" checked>Server Info Box
					</form>
				</div>
				<div class="col-md-6">
					<p>You can embed your MapSync map into any web page using the code below:</p>
					<input class="embedCode" type="text" id="embed" value= "null" disabled = "true">
				</div>
			</div>
			
			<div class="row">
			    <footer class="footer">
					<div class="container">
						<p class="text-muted">MapSync is created by <a href="http://bellum128.weebly.com">bellum128 Productions</a></p>
					</div>
			    </footer>
			</div>	
		</div>

		<script>
			var url = <?php  print('"' . htmlentities(currPageURL()) . '"'); ?>;
			function updateEmbedCode()
			{
				document.getElementById("embed").value = '<iframe style="width:100%;height:690px;border:none;", src="' + url + '/frame.php?map=' + document.getElementById("chkMap").checked +'&infobox='+ document.getElementById("chkInfo").checked +'">MapSync is not supported by this browser.</iframe>';
			}
			document.getElementById("chkMap").onclick = function() 
			{
				updateEmbedCode();
			}

			document.getElementById("chkInfo").onclick = function() 
			{
				updateEmbedCode();
			}
		</script>
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/bootstrap.min.js"></script>	
	</body>	
</html>
