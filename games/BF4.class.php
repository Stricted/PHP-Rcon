<?php
require_once("games/BF3.class.php");

/**
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class BF4 extends BF3 {
	/**
	 * get players from gameserver
	 *
	 * @return	array
	 */
	public function getPlayers () {
		$response = $this->command('listPlayers all');
		$players = array();
		
		if ($response[0] == 'OK') {
			for ($i = 13; $i < count($response); $i += 10) {
				$player = array();
				$player[$response[2]] = $response[$i];
				/*$player[$response[3]] = $response[$i+1]; GUID is empty, because we are not logged in */
				$player[$response[4]] = $response[$i+2];
				$player[$response[5]] = $response[$i+3];
				$player[$response[6]] = $response[$i+4];
				$player[$response[7]] = $response[$i+5];
				$player[$response[8]] = $response[$i+6];
				$player[$response[9]] = $response[$i+7];
				$player[$response[10]] = $response[$i+8];
				$player[$response[11]] = $response[$i+9];
					
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
		$maps = array(
				/* Default Maps */
				"MP_Abandoned" => "Zavod 311",
				"MP_Damage" => "Lancang Dam",
				"MP_Flooded" => "Flood Zone",
				"MP_Journey" => "Golmud Railway",
				"MP_Naval" => "Paracel Storm",
				"MP_Prison" => "Operation Locker",
				"MP_Resort" => "Hainan Resort",
				"MP_Siege" => "Siege of Shanghai",
				"MP_TheDish" => "Rogue Transmission",
				"MP_Tremors" => "Dawnbreaker",
				/* China Rising */
				"XP1_001" => "Silk Road",
				"XP1_002" => "Altai Range",
				"XP1_003" => "Guilin Peaks",
				"XP1_004" => "Dragon Pass",
				/* Second Assault */
				"XP0_Caspian" => "Caspian Border 2014",
				"XP0_Firestorm" => "Operation Firestorm 2014",
				"XP0_Metro" => "Operation Metro 2014",
				"XP0_Oman" => "Gulf of Oman 2014"
				);
		
		if (array_key_exists($id, $maps)) {
			return $maps[$id];
		}
		
		return $id;
	}
	
	/**
	 * translate mode code to mode name
	 *
	 * @param	string	$id
	 * @return	string
	 */
	public function getModeName ($id) {
		$modes = array(
				"ConquestLarge0" => "Conquest Large",
				"ConquestSmall0" => "Conquest Small",
				"RushLarge0" => "Rush",
				"SquadDeathMatch0" => "Squad Deathmatch",
				"TeamDeathMatch0" => "TDM",
				"Domination0" => "Domination",
				"CaptureTheFlag0" => "CTF",
				"AirSuperiority0" => "Air Superiority",
				"Obliteration" => "Obliteration",
				"Elimination0" => "Defuse"
				);
		
		if (array_key_exists($id, $modes)) {
			return $modes[$id];
		}
		
		return $id;
	}
}
