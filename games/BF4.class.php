<?php
require_once("games/BF3.class.php");

/**
 * Battlefield 4
 *
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
}
