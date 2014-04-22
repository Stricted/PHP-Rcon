<?php
namespace phpRcon\games;
use phpRcon\games\engine\GamespyEngine;

/**
 * Medal of Honor Allied Assault 
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class MOHAA extends GamespyEngine {
	/**
	 * get players from gameserver
	 *
	 * @return	array
	 */
	public function getPlayers () {
		if (empty($this->data)) {
			$this->data = $this->command("\\status\\");
		}
		$data = explode("\\", $this->data);

		$players = array();
		$c = count($data)-4;
		if (isset($data[37]) && $data[37] != "final") {
			for ($i = 37; $i < $c; $i += 8) {
				$player = array();
				$player['name'] = $data[$i+1];
				$player['kills'] = $data[$i+3];
				$player['deaths'] = $data[$i+5];
				$player['ping'] = $data[$i+7];
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
		$data = $this->getServerData();
		return $data['maxplayers'];
	}
	
	/**
	 * get player count from server
	 *
	 * @return	integer
	 */
	public function getCurrentPlayerCount () {
		$data = $this->getServerData();
		return $data['numplayers'];
	}
	
	/**
	 * get current map from gameserver
	 *
	 * @return	string
	 */
	public function getCurrentMap () {
		$data = $this->getServerData();
		return $data['mapname'];
	}
	
	/**
	 * get current game mode from gameserver
	 *
	 * @return	string
	 */
	public function getCurrentMode () {
		$data = $this->getServerData();
		return $data['gamemode'];
	}
	
	/**
	 * get server name from gameserver
	 *
	 * @return	string
	 */
	public function getServerName () {
		$data = $this->getServerData();
		return $data['hostname'];
	}
}
