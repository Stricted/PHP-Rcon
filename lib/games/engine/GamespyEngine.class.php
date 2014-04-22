<?php
namespace phpRcon\games\engine;

/**
 * Gamespy
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
abstract class GamespyEngine extends AbstractEngine {
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
		return $data;
		
	}
	
	/**
	 * get server data
	 *
	 * @return	array
	 */
	protected function getServerData () {
		if (empty($this->data)) {
			$this->data = $this->command("\\status\\");
		}
		
		$tmp = explode('\\', $this->data);
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
