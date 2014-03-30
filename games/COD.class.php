<?php
require_once("games/engine/Quake3Engine.class.php");

/**
 * Call of Duty
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class COD extends Quake3Engine {
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
}