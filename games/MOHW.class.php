<?php
require_once("games/BF3.class.php");

/**
 * Medal of Honor Warfighter
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class MOHW extends BF3 {
	/**
	 * get players from gameserver
	 *
	 * @return	array
	 */
	public function getPlayers () {
		$response = $this->encodeClientRequest('listPlayers all');
		$players = array();
		
		if ($response[0] == 'OK') {
			for ($i = 10; $i < count($response); $i += 7) {
				$player = array();
				$player['name'] = $response[$i];
				$player['guid'] = $response[$i+1];/* GUID is empty, because we are not logged in */
				$player['teamId'] = $response[$i+2];
				$player['squadId'] = $response[$i+3];
				$player['kills'] = $response[$i+4];
				$player['deaths'] = $response[$i+5];
				$player['score'] = $response[$i+6];
					
				$players[] = $player;
			}
		}
		
		return $players;
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
				"MP_03" => "Somalia Stronghold",
				"MP_05" => "Novi Grad Warzone",
				"MP_10" => "Sarajevo Stadium",
				"MP_12" => "Basilan Aftermatch",
				"MP_13" => "Hara Dunes",
				"MP_16" => "Al Fara Cliffside",
				"MP_18" => "Shogore Valley",
				"MP_19" => "Tungunan Jungle",
				"MP_20" => "Darra Gun Market",
				"MP_21" => "Chitrail Compound"
				);
		
		if (array_key_exists($id, $maps)) {
			return $maps[$id];
		}
		
		return $id;
	}
}
