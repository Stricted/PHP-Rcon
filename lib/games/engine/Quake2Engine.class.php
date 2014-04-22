<?php
namespace phpRcon\games\engine;

/**
 * Quake2
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
abstract class Quake2Engine extends AbstractEngine {
	/**
	 * protocol
	 * @var	string
	 */
	protected $protocol = 'udp';
	
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
		if (empty($this->data2)) {
			$this->data2 = $this->command("\xFF\xFF\xFF\xFFstatus\x00");
		}
		$data = $this->data2;
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
