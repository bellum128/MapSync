<?php $apiKey = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"; ?>
<html>
	<head>
		<meta http-equiv="refresh" content="10">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="css/map.css">
	</head>

	<body style="background:none transparent;">	
		<?php
			// Convert Garry's Mod's JSON to actual JSON.
		 	$contents = str_replace( "\&quot;", '"', htmlentities(file_get_contents("mapdata.txt"), ENT_QUOTES, "UTF-8")); // Read GMOD JSON from stream file.	 	
		 	$contents = substr($contents, strpos($contents, "=") + 1);
		 	$contents = str_replace("&quot;{", "[", $contents);
		 	$contents = str_replace("}&quot;", "]", $contents);
		 	$contents = str_replace(",]", "]", $contents);
		 	$contentsJSON = json_decode(html_entity_decode($contents, ENT_QUOTES, "UTF-8"), true);

			$mapInfoJSON = json_decode(html_entity_decode(file_get_contents("maps/mapinfo.json"), ENT_QUOTES, "UTF-8"), true);

		 	/*
		 	* Gets a value from JSON for given name.		 	
		 	* @param string $name Name of the field to obtain.
		 	* @return Value for requested field.
		 	*/
			function getValue($name)
			{
				global $contentsJSON;
				return $contentsJSON[$name];
			}

			/*
		 	* Convert a coordinate to proper size using scale factor.
		 	* @param int $coord Number to convert.
		 	* @return Converted number.
		 	*/
			function convertCoord($coord)
			{
				global $mapInfoJSON;
				return ($coord * ($mapInfoJSON[getValue("map")][0]));			
			}

			/*
		 	* Convert a bottom right styled box to a width and height styled box.
		 	* @param Coordinates of top left (1) and bottom right (2).
		 	* @return Converted coordinate .
		 	*/
			function bottomRightToWidthHeight($x1, $y1, $x2, $y2)
			{
				return $x1 . "," . $y1 . "," . ($x2 - $x1) . "," . ($y2 - $y1);
			}

			/*
		 	* Remove quotes from a table.
		 	* @param table $table Table to convert.
		 	* @param boolean $removeQuotes Rather to remove quotes from this particular table.
		 	* @return array Converted table.
		 	*/
			function tableStringToArray($table, $removeQuotes = FALSE)
			{
				if($removeQuotes === FALSE)
				{
					for($i = 0; $i < count($table); $i++)
					{
						$table[$i] = "\"" . $table[$i] . "\"";
					}
				}
				return $table;
			}
		?>
		
		<!-- Garry's Mod MapSync Addon Version: <?php echo(getValue("version")) ?> -->

		<div class="container-fluid">			
			<div class="row">
				<?php 
					if((htmlspecialchars($_GET["map"]) == "true")) // If ?map=true...
					{
				?>					
					<?php 
						$mapImagePath = ("maps/" . strtolower( getValue("map")) . ".png");  // Set the map background image path.
						if(file_exists($mapImagePath) && $mapInfoJSON[getValue("map")]) // Determine if the current map is installed.
						{
							$mapImageSize = getImageSize($mapImagePath); // Set the current map background image size.
					?>

						<div class="col-md-8", style="width: 615px;">
							<div class="mapWrapper">
								<canvas id = "mapBackground", class = "map", <?php echo(" " . $mapImageSize[3] . " "); ?>, style = "z-index: 0;">Your browser does not have canvas support. Like... I think even Opera has canvas support o.O</canvas>
								<canvas id = "mapCanvas", class = "map", <?php echo(" " . $mapImageSize[3] . " "); ?>, style = "z-index: 1;">Your browser does not have canvas support. Like... I think even Opera has canvas support o.O</canvas>
							</div>	
							
							<script>
								var canvas = document.getElementById("mapCanvas");
								var context = canvas.getContext("2d");

								var backgroundCanvas = document.getElementById("mapBackground");
								var backgroundContext = backgroundCanvas.getContext("2d")

								// Draw background color behind image.
								backgroundContext.beginPath(); 
								backgroundContext.rect(0,0,<?php echo($mapImageSize[0]);?>,<?php echo($mapImageSize[1]);?>);
								backgroundContext.fillStyle = "#545B2F";
								backgroundContext.fill();

								var mapImage = new Image(); // Initialize map background image.

								mapImage.onload = function()
								{
									backgroundContext.drawImage(mapImage, 0, 0); // Draw background image.
								}
								mapImage.src = <?php echo("\"" . $mapImagePath . "\"") ?> ;
							
							<?php							
								for($i = 0; $i < getValue("propcount"); $i++) // Loop through and draw each prop.
								{
									$posX1 = convertCoord(explode(" ", tableStringToArray(getValue("propsmin"), TRUE)[$i])[1])  + convertCoord($mapInfoJSON[getValue("map")][1]);
									$posY1 = convertCoord(explode(" ", tableStringToArray(getValue("propsmin"), TRUE)[$i])[0]) + convertCoord($mapInfoJSON[getValue("map")][2]);
									$posX2 = convertCoord(explode(" ", tableStringToArray(getValue("propsmax"), TRUE)[$i])[1]) + convertCoord($mapInfoJSON[getValue("map")][1]);
									$posY2 = convertCoord(explode(" ", tableStringToArray(getValue("propsmax"), TRUE)[$i])[0]) + convertCoord($mapInfoJSON[getValue("map")][2]);
									$convertedPos = bottomRightToWidthHeight($posX1, $posY1, $posX2, $posY2); // Convert bottom right styled coordinate to a width/height style coordinate used by canvas system.
									echo // Draw the current prop.
									('
										context.beginPath();
										context.rect(' . $convertedPos . ');
										context.fillStyle = "rgba(0,255,0,0.5)";
										context.fill();
									');
								}

								function invertColor($color)
								{
									$col = explode(",", $color);
									$col = array(255-$col[0], 255-$col[1], 255-$col[2]);
									return $col[0] . "," . $col[1] . "," . $col[2];	
								}

								for($i = 0; $i < getValue("playercount"); $i++) // Loop through and draw each player.
								{
									$posX = convertCoord(explode(" ", tableStringToArray(getValue("playerpositions"), TRUE)[$i])[1]) + convertCoord($mapInfoJSON[getValue("map")][1]);
									$posY = convertCoord(explode(" ", tableStringToArray(getValue("playerpositions"), TRUE)[$i])[0])  + convertCoord($mapInfoJSON[getValue("map")][2]);
									$name = tableStringToArray(getValue("playernames"), FALSE)[$i];
									$color = str_ireplace(" ", ",", tableStringToArray(getValue("playercolors"), TRUE)[$i]);
									$colorInverted =  invertColor($color);
									$alive = tableStringToArray(getValue("playeralive"), TRUE)[$i];
									echo // Draw player.
									('
											var addX = 0; // Amount to add to converted X coordinate before drawing.
											var addY = 0; // Amount to add to converted Y coordinate before drawing.	
																		
											context.font = "bold 8pt Arial"; // Set font for name tag.
											context.textAlign = "center";

											if('. $posX . ' + (context.measureText(' . $name . ').width / 2) >= 600) // If name is off of the right edge of the screen...
											{
												addX = -(' . $posX . ' + (context.measureText(' . $name . ').width / 2) - 600); // Subtract distance off of edge.
											}

											if('. $posX . ' - (context.measureText(' . $name . ').width / 2) <= 0) // If name is off the left edge of the screen...
											{
												addX = ((context.measureText(' . $name . ').width / 2) - ' . $posX . '); // Add distance off of edge.
											}									

											if((' . $posY . ' - 5 - (3*2)) <= 5) // If name is off the top edge of screen...
											{
												addY = 12; // Draw name under player dot.
											}
											else
											{
												addY = -5; // Draw name in normal location.
											}
											
											context.beginPath(); // Name tag background drop shadow.
											context.globalAlpha = 0.50;
											context.rect('. $posX . ' + addX - ((context.measureText(' . $name . ').width + 5) / 2) + 1.65,'. $posY . ' + addY - 9 + 1.65, context.measureText(' . $name . ').width + 5, 13);											
											context.fillStyle = "rgb(0,0,0)";
											context.fill();									
											context.globalAlpha = 1.0;

											context.beginPath(); // Name tag background.
											context.globalAlpha = 0.85;
											context.rect('. $posX . ' + addX - ((context.measureText(' . $name . ').width + 5) / 2),'. $posY . ' + addY - 9, context.measureText(' . $name . ').width + 5, 13);											
											context.fillStyle = "rgb('. $colorInverted .')";
											context.fill();									
											context.globalAlpha = 1.0;
											
											context.beginPath(); // Name tag drop shadow.
											context.fillStyle = "rgb(0,0,0)";
											context.fillText('. $name . ','. $posX . ' + addX+1,'. $posY . ' + addY+1);

											context.beginPath(); // Name tag name.
											context.fillStyle = "rgb('. $color .')";
											context.fillText('. $name . ','. $posX . ' + addX,'. $posY . ' + addY);
											
											context.beginPath(); // Player dot.
											context.arc('. $posX .','. $posY .',3,0, 2*Math.PI, false);
											context.fillStyle = "rgb('. $color .')";
											context.fill();
											context.lineWidth = 0.25;
											context.strokeStyle = "rgb(0,0,0)";
											context.stroke();

											if(!'. $alive .') // Draw an X over player dot if they are dead.
											{
												context.beginPath(); // Red X.
												context.fillStyle = "#800000";
												context.font = "bold 16pt Courier";			
												context.textAlign = "center";
												context.fillText("x",'. $posX . ','. $posY . ' + 5);							
											}
									');
								}
							?>
							</script>
						</div> <!--End Left Column-->
						
						<?php 
						}
						else
						{
						?>
							<div class="col-md-8 error", style="width: 615px;">
								ERROR MAP NOT INSTALLED
							</div>	
						<?php
						}
					}
				?>

				<?php if((htmlspecialchars($_GET["infobox"]) == "true")) { // If ?infobox=true...?> 
					<div class="col-md-4 well infoColumn">
						<div class = "infoWrapper">
							<p>Host Name: <?php echo(getValue("hostname")) ?></p>
							<p>IP: <?php  echo(getValue("hostip")) ?> </p>
							<p>Current Map: <?php  echo(getValue("map")) ?> </p>
							<p>Player Count: <?php  echo(getValue("playercount")) ?> </p>
							<p>Last Contact: <?php  echo(date("m/d/Y g:i:sa", getValue("servertime"))) ?> </p>
							<div class = "table-responsive">
								<table class = "table table-hover well playerBlock">
									<?php
										if(getValue("playercount") > 0)
										{
											echo
											('
												<caption>Players</caption>
												<thead>
													<tr>
														<th>Name</th>
														<th>SteamID</th>
													</tr>
												</thead>
												<tbody>
											');
											
											$ch = curl_init(); // Use cURL for avatar retrevial (fast).
											curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
											
											for($i = 0; $i < getValue("playercount"); $i++)
											{											
												curl_setopt($ch, CURLOPT_URL,"http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $apiKey . "&steamids=" . tableStringToArray(getValue("playerid64s"), TRUE)[$i]);
												$jsond = curl_exec($ch);
												$json = json_decode($jsond, true);			

												if(isset($json['response']['players'][0]['avatar'])) // If avatar can be loaded...
												{
													$avatarUrl = $json['response']['players'][0]['avatar'];	
												}
												else
												{
													$avatarUrl = "no-avatar.jpg";
												}

												echo // List avatar and name.
												('
													<tr>
														<td background = "' . $avatarUrl . '", class="player-table-icon"> <span class="player-table-name pull-left", style="color:rgb('. str_ireplace(" ", ",", tableStringToArray(getValue("playercolors"), TRUE)[$i]) .');"> ' . tableStringToArray(getValue("playernames"), TRUE)[$i] . ' </span> </td>'
												);
												
												if(tableStringToArray(getValue("playerids"), TRUE)[$i] != "BOT") // If player is an robot...
												{
													echo // List SteamID.
													('													
															<td><a href = "http://steamcommunity.com/profiles/'. tableStringToArray(getValue("playerid64s"), TRUE)[$i] . '">' . tableStringToArray(getValue("playerids"), TRUE)[$i] . '</a></td>
														</tr>
													');		
												}																			
												else
												{
													echo
													('													
															<td><i>BOT</i></td>
														</tr>
													');		
												}
											}
											curl_close($ch); // Close cURL object.										
											echo('</tbody>');
										}
										else
										{
											echo('<thead><caption>There are no players in the server</caption></thead>');
										}
									?>
								</table>
							</div> <!--End table wrapper-->
						</div> <!--End infoWrapper-->
					</div> <!--End right column-->
				<?php } ?>
			</div>
		</div>
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/bootstrap.min.js"></script>	
	</body>	
</html>
