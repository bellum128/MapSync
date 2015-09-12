# MapSync #

----------

MapSync allows you to monitor the location of both players and props in your Garry's Mod server from a web browser. It also displays a nice list of players currently in the server.

MapSync is highly extensible and customizable, allowing you to easily add new maps.

## Features ##
- Map featuring the location of every player and prop on the server.
- List of players and SteamIDs.
- Auto-refresh capability.
- Ability to embed the map, player list, or both into any webpage.
- Easy to add new maps.
- Support for both vanilla colors and [Evolve admin mod](https://github.com/Xandaros/evolve) rank colors.
- Support for most modern web browsers.


## Installation ##
MapSync comes in two modules, the Garry's Mod addon that sends map data to a web server, and of course the web server where the webpage will be hosted on. This web server will require a HTTP service such as Apache, as well as PHP with Curl support. 
</br>


- To install the Garry's Mod addon, simply place the mapsync-addon folder into your server's addons folder and restart your server.
- To install the MapSync web service to your server, place the contents of the mapsync-web folder into your server's root folder.

## Configuration ##
MapSync is easy to configure and use.
</br>

- To allow the map to work properly, you will most likely need to take possession of the mapdata.txt file with your apache user. For example: `chown www-data /var/www/html/mapdata.txt`
- To set the URL of your web server, use the `mapync_send_url` convar to point to your server's `processmapdata.php` file. <br/>[Example: `mapsync_send_url "http://example.com/processmapdata.php"`]
- To set the interval in seconds that the Garry's Mod server will send data to the web server, use the convar `mapsync_send_interval`. Some hosting companies limit the amount of HTTP requests that can be sent to a server, so keep this in mind when deciding on this value.
- A key should be used to prevent nefarious activity. To set this, use the convar `mapsync_send_key` to set a pass key to edit your map, and change the word `default` in the line `$key = "default";` in your `processmapdata.php`  file to your key.
- You can also decide whether to send prop data to the web server using the convar `mapsync_send_props`, as sending a large amount of props can sometimes be taxing on both the web server and game server.
- Optional: To allow the use of player avatars in the player list, you must set an API key in the web server. Do this by  opening the `frame.php` file in a text editor and replacing `XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX` in line 1 with a key generated at [http://steamcommunity.com/dev/apikey](http://steamcommunity.com/dev/apikey).

## Questions Or Comments?
You can contact bellum128 Productions at [http://bellum128.weebly.com/contact.html](http://bellum128.weebly.com/contact.html)