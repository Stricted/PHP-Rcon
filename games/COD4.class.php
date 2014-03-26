<?php
require_once("games/AbstractGame.class.php");

/**
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class COD4 extends AbstractGame {
	/**
	 * server data cache (workaround for cod4)
	 * @var	array
	 */
	private $data = array();
	
	/**
	 * initialize a new instance of this class
	 *
	 * @param	string	$server
	 * @param	integer	$port
	 * @param	boolean	$udp
	 */
	public function __construct ($server, $port) {
		parent::__construct($server, $port, "udp");
	}
	
	/**
	 * sends a command to the gameserver
	 *
	 * @param	string	$string
	 * @return	array
	 */
	public function command ($string) {
		if (empty($this->data)) {
			/* getstatus is hardcoded because we cant send other commands without a crash */
			fputs($this->socket, "\xFF\xFF\xFF\xFFgetstatus\x00");
			$this->data = $this->receive();
		}
		
		return $this->data;
	}
	
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
		$data = $this->command('getstatus');
		
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
	 * translate mode code to mode name
	 *
	 * @param	string	$id
	 * @return	string
	 */
	public function getModeName ($id) {
		$modes = array(
				"sd" => "Search &amp; Destroy", 
				"dm" => "Free For All DM",
				"dom" => "Domination",
				"koth" => "Headquarters",
				"sab" => "Sabotage",
				"war" => "Team Deathmatch"
				);
		
		if (array_key_exists($id, $modes)) {
			return $modes[$id];
		}
		
		return $id;
	}
	
	/**
	 * translate map code to map name
	 *
	 * @param	string	$id
	 * @return	string
	 */
	public function getMapName ($id) {
		$maps = array(
				"mp_convoy" => "Ambush",
				"mp_backlot" => "Backlot",
				"mp_bloc" => "Bloc",
				"mp_bog" => "Bog",
				"mp_cargoship" => "WetWork",
				"mp_citystreets" => "District",
				"mp_countdown" => "Countdown",
				"mp_crash" => "Crash",
				"mp_crossfire" => "Crossfire",
				"mp_farm" => "Downpour",
				"mp_overgrown" => "Overgrown",
				"mp_pipeline" => "Pipeline",
				"mp_shipment" => "Shipment",
				"mp_showdown" => "Showdown",
				"mp_strike" => "Strike",
				"mp_vacant" => "Vacant",
				"mp_crash_snow" => "Crash SNOW",
				"mp_broadcast" => "Broadcast ",
				"mp_carentan" => "Chinatown ",
				"mp_creek" => "Creek ",
				"mp_killhouse" => "Killhouse"
				);
		
		if (array_key_exists($id, $maps)) {
			return $maps[$id];
		}
		
		return $id;
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
		$data = $this->command('getstatus');
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
