<?php
require_once("games/engine/FrostbiteEngine.class.php");

/**
 * Medal of Honor
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class MOH extends FrostbiteEngine {
	/**
	 * get players from gameserver
	 *
	 * @return	array
	 */
	public function getPlayers () {
		$response = $this->encodeClientRequest('listPlayers all');
		$players = array();
		
		if ($response[0] == 'OK') {
			for ($i = 11; $i < count($response); $i += 8) {
				$player = array();
				$player['clanTag'] = $response[$i];
				$player['name'] = $response[$i+1];
				$player['guid'] = $response[$i+2];/* GUID is empty, because we are not logged in */
				$player['teamId'] = $response[$i+3];
				$player['kills'] = $response[$i+4];
				$player['deaths'] = $response[$i+5];
				$player['score'] = $response[$i+6];
				$player['ping'] = $response[$i+7];
					
				$players[] = $player;
			}
		}
		
		return $players;
	}
	
	/**
	 * get maxplayers from gameserver
	 *
	 * @return	integer
	 */
	public function getMaxPlayers () {
		$response = $this->encodeClientRequest('serverInfo');
		if ($response[0] == 'OK') {
			return $response[3];
		}
		else {
			return false;
		}
	}
	
	/**
	 * get player count from server
	 *
	 * @return	integer
	 */
	public function getCurrentPlayerCount () {
		$response = $this->encodeClientRequest('serverInfo');
		if ($response[0] == 'OK') {
			return $response[2];
		}
		else {
			return false;
		}
	}
	
	/**
	 * get current map from gameserver
	 *
	 * @return	string
	 */
	public function getCurrentMap () {
		$response = $this->encodeClientRequest('serverInfo');
		if ($response[0] == 'OK') {
			return $response[5];
		}
		else {
			return false;
		}
	}
	
	/**
	 * get current game mode from gameserver
	 *
	 * @return	string
	 */
	public function getCurrentMode () {
		$response = $this->encodeClientRequest('serverInfo');
		if ($response[0] == 'OK') {
			return $response[4];
		}
		else {
			return false;
		}
	}
	
	/**
	 * get server name from gameserver
	 *
	 * @return	string
	 */
	public function getServerName () {
		$response = $this->encodeClientRequest('serverInfo');
		if ($response[0] == 'OK') {
			return $response[1];
		}
		else {
			return false;
		}
	}
	
	/**
	 * translate map code to map name
	 *
	 * @param	string	$id
	 * @return	string
	 */
	public function getMapName ($id) {
		$id = str_replace("levels/", "", $id);
		$maps = array(
				"mp_01" => "Mazar-i-Sharif Airfield",
				"mp_02" => "Shah-i-Khot",
				"mp_04" => "Helmand Valley",
				"mp_05_domination" => "Kandahar Marketplace",
				"mp_05_overrun" => "Kandahar Marketplace",
				"mp_05_tdm" => "Kandahar Marketplace",
				"mp_06_domination" => "Diwagal Camp",
				"mp_06_overrun" => "Diwagal Camp",
				"mp_06_tdm" => "Diwagal Camp",
				"mp_08_domination" => "Kunar Base",
				"mp_08_overrun" => "Kunar Base",
				"mp_08_tdm" => "Kunar Base",
				"mp_09_domination" => "Kabul City Ruins",
				"mp_09_overrun" => "Kabul City Ruins",
				"mp_09_tdm" => "Kabul City Ruins",
				"mp_10_domination" => "Garmzir Town",
				"mp_10_overrun" => "Garmzir Town",
				"mp_10_tdm" => "Garmzir Town"
				);
		
		if (array_key_exists($id, $maps)) {
			return $maps[$id];
		}
		
		return $id;
	}
}