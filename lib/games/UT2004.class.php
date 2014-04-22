<?php
namespace phpRcon\games;
use phpRcon\games\engine\Unreal2Engine;

/**
 * Unreal Tournament 2004
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class UT2004 extends Unreal2Engine {
	protected $data = array();
		
	/**
	 * get players from gameserver
	 *
	 * @return	array
	 */
	public function getPlayers() {
		$this->data = $this->command("\x79\x00\x00\x00\x02");

		$this->data = substr($this->data, 10);
		$players = array();
		while (strlen($this->data) > 5) { 
			$player = array();
			$player["name"] = $this->splitData('string');
			$player["ping"] = $this->splitData('int32');
			$player["score"] = $this->splitData('int32');
			$this->data = substr($this->data, 9);
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
		
		return $data['maxplayers'];
	}
	
	/**
	 * get player count from server
	 *
	 * @return	integer
	 */
	public function getCurrentPlayerCount() {
		$data = $this->getServerData();
		
		return $data['playercount'];
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
		$data = $this->getServerData();
		
		return $data['game'];
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
