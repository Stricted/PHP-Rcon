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