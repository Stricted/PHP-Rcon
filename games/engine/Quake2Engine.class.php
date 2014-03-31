<?php
require_once("games/engine/AbstractEngine.class.php");

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
		if (empty($this->data)) {
			$this->data = $this->command("\xFF\xFF\xFF\xFFstatus\x00");
		}
		
		// @TODO: parse server data
	}
}
