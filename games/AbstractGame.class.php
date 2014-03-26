<?php
/**
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
abstract class AbstractGame {
	/**
	 * rcon socket resource
	 * @var	resource
	 */
	protected $socket = null;
	
	/**
	 * initialize a new instance of this class
	 *
	 * @param	string	$server
	 * @param	integer	$port
	 * @param	boolean	$udp
	 */
	public function __construct ($server, $port, $udp = false) {
		if ($udp) {
			$this->socket = fsockopen("udp://".$server, $port);
		}
		else {
			$this->socket = fsockopen($server, $port);
		}
		
		stream_set_blocking($this->socket, 0);
	}
	
	/**
	 * sends a command to the server
	 *
	 * @param	string	$string
	 * @return	array
	 */
	abstract public function command($string);
	
	/**
	 * close the connection to the server
	 */
	public function close () {
		fclose($this->socket);
	}
	
	/**
	 * recive data from server
	 *
	 * @return	string
	 */
	abstract protected function receive();
	
	/**
	 * check if the read package is complete
	 *
	 * @param	string	$data
	 * @return	boolean
	 */
	protected function containsCompletePacket($data) {
		if (empty($data)) {
			return false;
		}
		
		$meta = stream_get_meta_data($this->socket);
		if (mb_strlen($data) < $meta['unread_bytes']) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * get players from server
	 *
	 * @return	array
	 */
	abstract public function getPlayers();
	
	/**
	 * get max players from server
	 *
	 * @return	integer
	 */
	abstract public function getMaxPlayers();
	
	/**
	 * get player count from server
	 *
	 * @return	integer
	 */
	abstract public function getCurrentPlayerCount();
	
	/**
	 * get current map from server
	 *
	 * @return	string
	 */
	abstract public function getCurrentMap();
	
	/**
	 * get current game mode from server
	 *
	 * @return	string
	 */
	abstract public function getCurrentMode();
	
	/**
	 * get server name from server
	 *
	 * @return	string
	 */
	abstract public function getServerName();
}
