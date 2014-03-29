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
}