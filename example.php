<?php
require_once("games/BF3.class.php");
require_once("games/COD4.class.php");
$bf = new BF3("85.114.154.172", 10101);
$players = $bf->getPlayers();
echo $bf->getServerName()."<br />";
echo $bf->getMapName($bf->getCurrentMap())." - ".$bf->getModeName($bf->getCurrentMode())."<br />";
?>
Players (<?php  echo $bf->getCurrentPlayerCount()."/".$bf->getMaxPlayers(); ?>) :<br />
<table width="100%" border="1">
	<tr>
		<td width="14.29%">name</td>
		<td width="14.29%">team</td>
		<td width="14.29%">squad</td>
		<td width="14.29%">kills</td>
		<td width="14.29%">deaths</td>
		<td width="14.29%">score</td>
		<td width="14.29%">rank</td>
	</tr>
<?php
		if (!empty($players)) {
			foreach ($players as $id => $player) {
				echo "\t<tr>\n";
				echo "\t\t<td>".$player['name']."</td>\n";
				echo "\t\t<td>".$bf->getTeamName($player['teamId'])."</td>\n";
				echo "\t\t<td>".$bf->getSquadName($player['squadId'])."</td>\n";
				echo "\t\t<td>".$player['kills']."</td>\n";
				echo "\t\t<td>".$player['deaths']."</td>\n";
				echo "\t\t<td>".$player['score']."</td>\n";
				echo "\t\t<td>".$player['rank']."</td>\n";
				echo "\t</tr>\n";
			}
		}
	?>
</table>
<br />
<br />
<br />
<?php
$bf = new BF3("85.14.231.67", 47200);
$players = $bf->getPlayers();
echo $bf->getServerName()."<br />";
echo $bf->getMapName($bf->getCurrentMap())." - ".$bf->getModeName($bf->getCurrentMode())."<br />";
?>
Players (<?php  echo $bf->getCurrentPlayerCount()."/".$bf->getMaxPlayers(); ?>) :<br />
<table width="100%" border="1">
	<tr>
		<td width="14.29%">name</td>
		<td width="14.29%">team</td>
		<td width="14.29%">squad</td>
		<td width="14.29%">kills</td>
		<td width="14.29%">deaths</td>
		<td width="14.29%">score</td>
		<td width="14.29%">rank</td>
	</tr>
<?php
		if (!empty($players)) {
			foreach ($players as $id => $player) {
				echo "\t<tr>\n";
				echo "\t\t<td>".$player['name']."</td>\n";
				echo "\t\t<td>".$bf->getTeamName($player['teamId'])."</td>\n";
				echo "\t\t<td>".$bf->getSquadName($player['squadId'])."</td>\n";
				echo "\t\t<td>".$player['kills']."</td>\n";
				echo "\t\t<td>".$player['deaths']."</td>\n";
				echo "\t\t<td>".$player['score']."</td>\n";
				echo "\t\t<td>".$player['rank']."</td>\n";
				echo "\t</tr>\n";
			}
		}
	?>
</table>
<br />
<br />
<br />
<?php
$cod = new COD4("94.250.207.106", 28960);
$players = $cod->getPlayers();
echo $cod->getServerName()."<br />";
echo $cod->getMapName($cod->getCurrentMap())." - ".$cod->getModeName($cod->getCurrentMode())."<br />";
?>
Players (<?php  echo $cod->getCurrentPlayerCount()."/".$cod->getMaxPlayers(); ?>) :<br />
<table width="100%" border="1">
	<tr>
		<td width="14.29%">name</td>
		<td width="14.29%">score</td>
	</tr>
<?php
		if (!empty($players)) {
			foreach ($players as $id => $player) {
				echo "\t<tr>\n";
				echo "\t\t<td>".$player['name']."</td>\n";
				echo "\t\t<td>".$player['score']."</td>\n";
				echo "\t</tr>\n";
			}
		}
	?>
</table>
<br />
<br />
<br />
<?php
$cod = new COD4("144.76.182.172", 28993);
$players = $cod->getPlayers();
echo $cod->getServerName()."<br />";
echo $cod->getMapName($cod->getCurrentMap())." - ".$cod->getModeName($cod->getCurrentMode())."<br />";
?>
Players (<?php  echo $cod->getCurrentPlayerCount()."/".$cod->getMaxPlayers(); ?>) :<br />
<table width="100%" border="1">
	<tr>
		<td width="14.29%">name</td>
		<td width="14.29%">score</td>
	</tr>
<?php
		if (!empty($players)) {
			foreach ($players as $id => $player) {
				echo "\t<tr>\n";
				echo "\t\t<td>".$player['name']."</td>\n";
				echo "\t\t<td>".$player['score']."</td>\n";
				echo "\t</tr>\n";
			}
		}
	?>
</table>