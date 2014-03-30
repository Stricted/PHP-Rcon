<?php
require_once("games/engine/AbstractEngine.class.php");

/**
 * Source
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
abstract class SourceEngine extends AbstractEngine {
	/**
	 * protocol
	 * @var	string
	 */
	protected $protocol = 'udp';
	
	/**
	 * variable for playerdata
	 * @var	string
	 */
	protected $data = '';
		
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
			while (($char = $this->splitData('plain')) != chr(0)) {
				$str .= $char;
			}
			return $str;
		}
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
		$server['dedicated'] = (substr($packet, 5, 1) == 'd' ? true : false);
		$server['os'] = (substr($packet, 6, 1) == "l" ? "Linux" : "Windows");
		$server['password'] = ord(substr($packet, 7, 1));
		$server['vac'] = ord(substr($packet, 8, 1));
		
		return $server;
	}
}
