<?php
namespace phpRcon\games;
use phpRcon\games\engine\SourceEngine;

/**
 * Call of Duty : Modern Warfare 3
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class CODMW3 extends SourceEngine {
	/**
	 * get players from gameserver
	 *
	 * @return	array
	 */
	public function getPlayers() {
		/* CS:GO Server by default returns only max players and server uptime. You have to change server cvar "host_players_show" in server.cfg to value "2" if you want to revert to old format with players list. */
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
			$player["name"] = $this->stripColors($this->splitData('string'));
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
		
		return $this->stripColors($data['name']);
	}
}
