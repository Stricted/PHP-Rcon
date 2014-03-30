<?php
require_once("games/engine/AbstractEngine.class.php");

/**
 * Quake3
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
abstract class Quake3Engine extends AbstractEngine {
	/**
	 * protocol
	 * @var	string
	 */
	protected $protocol = 'udp';
	
	/**
	 * server data cache (workaround for cod4)
	 * @var	array
	 */
	protected $data = array();
	
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