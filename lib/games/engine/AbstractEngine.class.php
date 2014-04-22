<?php
namespace phpRcon\games\engine;

/**
 * Abtract Engine class
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
abstract class AbstractEngine {
	/**
	 * protocol
	 * @var	string
	 */
	protected $protocol = 'tcp';
	/**
	 * rcon socket resource
	 * @var	resource
	 */
	protected $socket = null;
	
	/**
	 * server data cache
	 * @var	string
	 */
	protected $data = '';
	
	/**
	 * server data cache #2
	 * @var	array
	 */
	protected $data2 = array();
	
	/**
	 * initialize a new instance of this class
	 *
	 * @param	string	$server
	 * @param	integer	$port
	 * @param	boolean	$udp
	 */
	public function __construct ($server, $port) {
		$this->socket = fsockopen($this->protocol."://".$server, $port);
		
		stream_set_blocking($this->socket, 0);
	}
	
	/**
	 * destructor
	 */
	public function __destruct () {
		$this->close();
	}
	
	/**
	 * sends a command to the server
	 *
	 * @param	string	$string
	 * @return	array
	 */
	public function command ($string) {
		fputs($this->socket, $string);
		$data = $this->receive();
		
		return $data;
	}
	
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
	
	/**
	 * replace color codes
	 *
	 * @param	string	$string
	 * @return	string
	 */
	public function stripColors ($string) {
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
	 * split package
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
			while (($char = $this->splitData('plain')) != chr(0)) {
				$str .= $char;
			}
			return $str;
		}
	}
}
