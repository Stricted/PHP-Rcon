<?php
require_once("games/engine/FrostbiteEngine.class.php");

/**
 * Battlefield 3
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class BF3 extends FrostbiteEngine {
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
				$player['name'] = $response[$i];
				$player['guid'] = $response[$i+1];/* GUID is empty, because we are not logged in */
				$player['teamId'] = $response[$i+2];
				$player['squadId'] = $response[$i+3];
				$player['kills'] = $response[$i+4];
				$player['deaths'] = $response[$i+5];
				$player['score'] = $response[$i+6];
				$player['rank'] = $response[$i+7];
					
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
		$maps = array(
				/* Default maps */
				"MP_001" => "Grand Bazaar",
				"MP_003" => "Tehran Highway",
				"MP_007" => "Caspian Border",
				"MP_011" => "Seine Crossing",
				"MP_012" => "Operation Firestorm",
				"MP_013" => "Damavand Peak",
				"MP_017" => "Noshahr Canals",
				"MP_018" => "Kharg Island",
				"MP_Subway" => "Operation Metro",
				/* Back to Karkand Maps */
				"XP1_001" => "Strike at Karkand",
				"XP1_002" => "Gulf of Oman",
				"XP1_003" => "Sharqi Peninsula",
				"XP1_004" => "Wake Island",
				/* Close Quarters Maps */
				"XP2_Palace" => "Donya Fortress",
				"XP2_Office" => "Operation 925",
				"XP2_Factory" => "Scrapmetal",
				"XP2_Skybar" => "Ziba Tower",
				/* Armored Kill Maps */
				"XP3_Valley" => "Death Valley",
				"XP3_Shield" => "Armored Shield",
				"XP3_Desert" => "Bandar Desert",
				"XP3_Alborz" => "Alborz Mountain",
				/* Aftermath Maps */
				"XP4_Parl" => "Azadi Palace",
				"XP4_Quake" => "Epicenter",
				"XP4_FD" => "Markaz Monolith",
				"XP4_Rubble" => "Talah Market",
				/* End Game Maps */
				"XP5_001" => "Operation Riverside",
				"XP5_002" => "Nebandan Flats",
				"XP5_003" => "Kiasar Railroad",
				"XP5_004" => "Sabalan Pipeline"
				);
		
		if (array_key_exists($id, $maps)) {
			return $maps[$id];
		}
		
		return $id;
	}
}
