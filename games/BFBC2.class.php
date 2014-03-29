<?php
require_once("games/BF3.class.php");

/**
 * Battlefield Bad Company 2
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class BFBC2 extends BF3 {
	/**
	 * get players from gameserver
	 *
	 * @return	array
	 */
	public function getPlayers () {
		$response = $this->encodeClientRequest('listPlayers all');
		$players = array();
		
		if ($response[0] == 'OK') {
			for ($i = 12; $i < count($response); $i += 9) {
				$player = array();
				$player['name'] = $response[$i+1];
				$player['guid'] = $response[$i+2];/* GUID is empty, because we are not logged in */
				$player['teamId'] = $response[$i+3];
				$player['squadId'] = $response[$i+4];
				$player['kills'] = $response[$i+5];
				$player['deaths'] = $response[$i+6];
				$player['score'] = $response[$i+7];
				$player['ping'] = $response[$i+8];
					
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
		$id = str_replace("levels/", "", strtolower($id));
		$maps = array(
				"mp_001" => "Panama Canal",
				"mp_003" => "Laguna Alta",
				"mp_005" => "Atacama Desert",
				"mp_006cq" => "Arica Harbor",
				"mp_007" => "White Pass",
				"mp_008cq" => "Nelson Bay",
				"mp_009cq" => "Laguna Presa",
				"mp_012cq" => "Port Valdez",
				"bc1_harvest_day_cq" => "Harvest Day",
				"bc1_oasis_cq" => "Oasis",
				"mp_sp_005cq" => "Heavy Metal",
				"mp_002" => "Valparaiso",
				"mp_004" => "Isla Inocentes",
				"mp_005gr" => "Atacama Desert",
				"mp_006" => "Arica Harbor",
				"mp_007gr" => "White Pass",
				"mp_008" => "Nelson Bay",
				"mp_009gr" => "Laguna Presa",
				"mp_012gr" => "Port Valdez",
				"mp_sp_002gr" => "Cold War",
				"bc1_oasis_gr" => "Oasis",
				"bc1_harvest_day_gr" => "Harvest Day",
				"mp_001sr" => "Panama Canal",
				"mp_002sr" => "Valparaiso",
				"mp_003sr" => "Laguna Alta",
				"mp_005sr" => "Atacama Desert",
				"mp_012sr" => "Port Valdez",
				"mp_sp_002sr" => "Cold War",
				"bc1_oasis_sr" => "Oasis",
				"bc1_harvest_day_sr" => "Harvest Day",
				"mp_004sdm" => "Isla Inocentes",
				"mp_006sdm" => "Arica Harbor",
				"mp_007sdm" => "White Pass",
				"mp_008sdm" => "Nelson Bay",
				"mp_009sdm" => "Laguna Presa",
				"mp_sp_002sdm" => "Cold War",
				"mp_sp_005sdm" => "Heavy Metal",
				"bc1_harvest_day_sdm" => "Harvest Day",
				"bc1_oasis_sdm" => "Oasis"
				);
		
		if (array_key_exists($id, $maps)) {
			return $maps[$id];
		}
		
		return $id;
	}
}