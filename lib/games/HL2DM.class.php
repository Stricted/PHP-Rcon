<?php
namespace phpRcon\games;
use phpRcon\games\engine\SourceEngine;

/**
 * Half Life 2: Deathmatch
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class HL2DM extends SourceEngine {
	/**
	 * get players from gameserver
	 *
	 * @return	array
	 */
	public function getPlayers() {
		/* request challenge id */
		$data = $this->command("\xFF\xFF\xFF\xFF\x55\xFF\xFF\xFF\xFF");
		$data = substr($data, 5, 4);

		/* request player data */
		$this->data = $this->command("\xFF\xFF\xFF\xFF\x55".$data);

		/* parse playerdata */
		$this->splitData('int32');
		$this->splitData('byte');

		$count = $this->splitData('byte');
		$players = array();
		for ($i=1; $i <= $count; $i++) {
			$player = array();
			$player["index"] = $this->splitData('byte');
			$player["name"] = $this->splitData('string');
			$player["score"] = $this->splitData('int32');
			$player["time"] = date('H:i:s', round($this->splitData('float32'), 0)+82800);
			$players[] = $player;
		}

		return $players;
	}
		
	/**
	 * get maxplayers from gameserver
	 *
	 * @return	integer
	 */
	public function getMaxPlayers() {
		$data = $this->getServerData();
		
		return $data['playersmax'];
	}
	
	/**
	 * get player count from server
	 *
	 * @return	integer
	 */
	public function getCurrentPlayerCount() {
		$data = $this->getServerData();
		
		return $data['players'];
	}
	
	/**
	 * get current map from gameserver
	 *
	 * @return	string
	 */
	public function getCurrentMap() {
		$data = $this->getServerData();
		
		return $data['map'];
	}
	
	/**
	 * get current game mode from gameserver
	 *
	 * @return	string
	 */
	public function getCurrentMode() {
		/* not available */
		return '';
	}
	
	/**
	 * get server name from gameserver
	 *
	 * @return	string
	 */
	public function getServerName() {
		$data = $this->getServerData();
		
		return $data['name'];
	}
}
