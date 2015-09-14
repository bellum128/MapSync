MapSync = {}

MapSync.version = "0.1"
MapSync.sendInterval = CreateConVar("mapsync_send_interval", 15, {FCVAR_ARCHIVE}, "Interval, in seconds, that MapSync will sync with web server.")
MapSync.url = CreateConVar("mapsync_send_url", "http://127.0.0.1/processmapdata.php", {FCVAR_ARCHIVE}, "Full URL of processmapdata.php file.")
MapSync.key = CreateConVar("mapsync_send_key", "default", {FCVAR_ARCHIVE, FCVAR_PROTECTED}, "Passkey required by web server.")
MapSync.sendProps = CreateConVar("mapsync_send_props", 1, {FCVAR_ARCHIVE}, "Whether to send prop information to web server.")

MapSync.SendTable = {}
MapSync.SendTableJSON = ""
MapSync.PlayerNames = {}
MapSync.PlayerPositions = {}
MapSync.PlayerColors = {}
MapSync.PlayerAlive = {}
MapSync.PlayerIDS = {}
MapSync.PlayerID64S = {}
MapSync.PlayerCount = 0

MapSync.Props = {}
MapSync.PropCount = 0
MapSync.PropsMin = {}
MapSync.PropsMax = {}

function MapSync.SetUpMap()	
	print("======================================================")
	print("MapSync by bellum128 Productions has started.")
	print("======================================================")
	timer.Create("UpdateMap", MapSync.sendInterval:GetInt(), 0, MapSync.UpdateMap)
end
hook.Add("Initialize","SetUpMap",MapSync.SetUpMap)

function MapSync.NotifyRestart()
	print("[MapSync] - A clean restart is recommended to save this setting.") 
end

cvars.AddChangeCallback("mapsync_send_interval", function() timer.Adjust("UpdateMap", MapSync.sendInterval:GetInt(), 0, MapSync.UpdateMap) MapSync.NotifyRestart() end )	
cvars.AddChangeCallback("mapsync_send_key", MapSync.NotifyRestart)	
cvars.AddChangeCallback("mapsync_send_url", MapSync.NotifyRestart)	

function MapSync.UpdateMap()
	--print("Sending map data to server.")
	table.Empty(MapSync.PlayerNames)
	table.Empty(MapSync.PlayerPositions)
	table.Empty(MapSync.PlayerColors)
	table.Empty(MapSync.PlayerAlive)
	table.Empty(MapSync.PlayerIDS)
	table.Empty(MapSync.PlayerID64S)

	MapSync.Props = ents.FindByClass("prop_physics")
	table.Empty(MapSync.PropsMin)
	table.Empty(MapSync.PropsMax)

	for k, v in pairs(player.GetAll()) do
		if(v:IsValid()) then
			table.insert(MapSync.PlayerNames, v:GetName())
			table.insert(MapSync.PlayerPositions, tostring(v:GetPos()))	
			if(evolve) then
				table.insert(MapSync.PlayerColors, tostring(evolve.ranks[v:EV_GetRank()].Color.r .. " " .. evolve.ranks[v:EV_GetRank()].Color.g .. " " .. evolve.ranks[v:EV_GetRank()].Color.b))	
			else
				table.insert(MapSync.PlayerColors, tostring(team.GetColor(v:Team()).r .. " " .. team.GetColor(v:Team()).g .. " " .. team.GetColor(v:Team()).b))	
			end
			
			table.insert(MapSync.PlayerAlive, tostring(v:Alive()))
			table.insert(MapSync.PlayerIDS, tostring(v:SteamID()))
			table.insert(MapSync.PlayerID64S, tostring(v:SteamID64()))
		end		
	end	

	if(MapSync.sendProps:GetInt() != 0) then
		for k, v in pairs(MapSync.Props) do
			table.insert(MapSync.PropsMin, v:LocalToWorld(v:OBBMins()).x .. " " .. v:LocalToWorld(v:OBBMins()).y .. " " .. v:LocalToWorld(v:OBBMins()).z)
			table.insert(MapSync.PropsMax, v:LocalToWorld(v:OBBMaxs()).x .. " " .. v:LocalToWorld(v:OBBMaxs()).y .. " " .. v:LocalToWorld(v:OBBMaxs()).z)
		end
	end

	if(player.GetAll()) then
		MapSync.PlayerCount = #player.GetAll()
	else
		MapSync.PlayerCount = 0
	end

	if(MapSync.Props) then
		MapSync.PropCount = #MapSync.Props
	else
		MapSync.PropCount = 0
	end

	MapSync.SendTable = 	
	{
		["key"]=MapSync.key:GetString(),
		["version"]=MapSync.version,		
		["hostname"]=GetHostName(),
		["hostip"]=GetConVarString("ip"),
		["hostport"]=GetConVarString("hostport"),
		["map"]=game.GetMap(),
		["playercount"]=tostring(MapSync.PlayerCount),
		["playernames"]=table.ToString(MapSync.PlayerNames, nil, false),
		["playerpositions"]=table.ToString(MapSync.PlayerPositions, nil, false),
		["playercolors"]=table.ToString(MapSync.PlayerColors, nil, false),
		["playeralive"]=table.ToString(MapSync.PlayerAlive, nil, false),
		["playerids"]=table.ToString(MapSync.PlayerIDS, nil, false),
		["playerid64s"]=table.ToString(MapSync.PlayerID64S, nil, false),
		["servertime"]=tostring(os.time()),
		["propsmin"]=table.ToString(MapSync.PropsMin, nil, false),
		["propsmax"]=table.ToString(MapSync.PropsMax, nil, false),
		["propcount"]=tostring(MapSync.PropCount)
	}

	MapSync.SendTableJSON = util.TableToJSON(MapSync.SendTable, true)

	http.Post(MapSync.url:GetString(), {["MapSync Data"] = MapSync.SendTableJSON}, nil, function() print("MapSync - Message send failed!") end)
end
concommand.Add("mapsync_send", function() MapSync.UpdateMap() end)