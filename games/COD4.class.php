<?php
require_once("games/AbstractGame.class.php");

/**
 * Call of Duty 4: Modern Warfare 
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class COD4 extends AbstractGame {
	/**
	 * protocol
	 * @var	string
	 */
	protected $protocol = 'udp';
	
	/**
	 * server data cache (workaround for cod4)
	 * @var	array
	 */
	private $data = array();
	
	/**
	 * recive data from gameserver
	 *
	 * @return	string
	 */
	protected function receive () {
		$data = '';
		while (!$this->containsCompletePacket($data)) {
			$data .= fread($this->socket, 8192);
		}
		return explode("\n", $data);
		
	}
		
	/**
	 * get players from gameserver
	 *
	 * @return	array
	 */
	public function getPlayers () {
		if (empty($this->data)) {
			$this->data = $this->command("\xFF\xFF\xFF\xFFgetstatus\x00");
		}
		$data = $this->data;
		$players = array();
		for ($i=2; $i<=count($data); $i++) {
			if (!empty($data[$i])) {
				$tmp = explode(" ", $data[$i], 3);
				$player = array();
				preg_match('/"([\s\S]+)"/i', $tmp[2], $match);
				$player['name'] = $this->stripColors($match[1]);
				$player['score'] = $tmp[0];
				$player['ping'] = $tmp[1];
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
		return $data['sv_maxclients'];
	}
	
	/**
	 * get player count from server
	 *
	 * @return	integer
	 */
	public function getCurrentPlayerCount () {
		return count($this->getPlayers());
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
		return $data['g_gametype'];
	}
	
	/**
	 * get server name from gameserver
	 *
	 * @return	string
	 */
	public function getServerName () {
		$data = $this->getServerData();
		return $this->stripColors($data['sv_hostname']);
	}
	
	/**
	 * replace cod4 color codes
	 *
	 * @param	string	$string
	 * @return	string
	 */
	protected function stripColors ($string) {
		$string = str_replace('^0', '', $string);
		$string = str_replace('^1', '', $string);
		$string = str_replace('^2', '', $string);
		$string = str_replace('^3', '', $string);
		$string = str_replace('^4', '', $string);
		$string = str_replace('^5', '', $string);
		$string = str_replace('^6', '', $string);
		$string = str_replace('^7', '', $string);
		$string = str_replace('^8', '', $string);
		$string = str_replace('^9', '', $string);
		
		return $string;
	}
	
	/**
	 * get server data
	 *
	 * @return	array
	 */
	protected function getServerData () {
		if (empty($this->data)) {
			$this->data = $this->command("\xFF\xFF\xFF\xFFgetstatus\x00");
		}
		$data = $this->data;
		$tmp = explode('\\', $data[1]);
		$ret = array();
		foreach ($tmp as $i => $v) {
			if (fmod($i, 2) == 1) {
				$t = $i + 1;
				
				$ret[$v] = $tmp[$t];
			}
		}
		return $ret;
	}
}
