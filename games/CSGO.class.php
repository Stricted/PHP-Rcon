<?php
require_once("games/AbstractGame.class.php");

/**
 * Counter-Strike: Global Offensive
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class CSGO extends AbstractGame {
	/**
	 * protocol
	 * @var	string
	 */
	protected $protocol = 'udp';
	
	/**
	 * variable for playerdata
	 * @var	string
	 */
	private $data = '';
		
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
	
		return $data;
	}
	
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
		for($i=1; $i <= $count; $i++) {
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
	 * split udp package
	 *
	 * @param	string	$type
	 * @return	mixed
	 */
	protected function splitData ($type) {
		if ($type == "byte") {
			$a = substr($this->data, 0, 1);
			$this->data = substr($this->data, 1);
			return ord($a);
		}
		else if ($type == "int32") {
			$a = substr($this->data, 0, 4);
			$this->data = substr($this->data, 4);
			$unpacked = unpack('iint', $a);
			return $unpacked["int"];
		}
		else if ($type == "float32") {
			$a = substr($this->data, 0, 4);
			$this->data = substr($this->data, 4);
			$unpacked = unpack('fint', $a);
			return $unpacked["int"];
		}
		else if ($type == "plain") {
			$a = substr($this->data, 0, 1);
			$this->data = substr($this->data, 1);
			return $a;
		}
		else if ($type == "string") {
			$str = '';
			while(($char = $this->splitData('plain')) != chr(0)) {
				$str .= $char;
			}
			return $str;
		}
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
	public function getCurrentMode() { /* not available */ }
	
	/**
	 * get server name from gameserver
	 *
	 * @return	string
	 */
	public function getServerName() {
		$data = $this->getServerData();
		
		return $data['name'];
	}
	
	/**
	 * translate map code to map name
	 *
	 * @param	string	$id
	 * @return	string
	 */
	public function getMapName ($id) {
		$maps = array(
				/*
				TODO:
				"cs_office" => "",
				"cs_italy" => "",
				"de_dust2" => "",
				"de_train" => "",
				"de_inferno" => "",
				"de_dust" => "",
				"de_aztec" => "",
				"de_nuke" => "",
				"ar_shoots" => "",
				"ar_baggage" => "",
				"de_lake" => "",
				"de_stmarc" => "",
				"de_sugarcane" => "",
				"de_bank" => "",
				"de_safehouse" => "",
				"de_shorttrain" => "",
				"cs_italy" => "",
				"cs_office" => ""
				
				maybe more?
				*/
				);
		
		if (array_key_exists($id, $maps)) {
			return $maps[$id];
		}
		
		return $id;
	}
	
	/**
	 * get server data
	 *
	 * @return	array
	 */
	public function getServerData () {
		$packet = $this->command("\xFF\xFF\xFF\xFFTSource Engine Query\x00");
		$server = array();
		
		$header = substr($packet, 0, 4);
		$response_type = substr($packet, 4, 1);
		$network_version = ord(substr($packet, 5, 1));
		
		$packet_array = explode("\x00", substr($packet, 6), 5);
		$server['name'] = $packet_array[0];
		$server['map'] = $packet_array[1];
		$server['game'] = $packet_array[2];
		$server['description'] = $packet_array[3];
		$packet = $packet_array[4];
		$app_id = array_pop(unpack("S", substr($packet, 0, 2)));
		$server['players'] = ord(substr($packet, 2, 1));
		$server['playersmax'] = ord(substr($packet, 3, 1));
		$server['bots'] = ord(substr($packet, 4, 1));
		$server['dedicated'] = substr($packet, 5, 1);
		$server['os'] = substr($packet, 6, 1);
		$server['password'] = ord(substr($packet, 7, 1));
		$server['vac'] = ord(substr($packet, 8, 1));
		
		return $server;
	}
}