<?php
require_once("games/CSGO.class.php");

/**
 * Counter-Strike 1.6
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class CS16 extends CSGO {
	/**
	 * translate map code to map name
	 *
	 * @param	string	$id
	 * @return	string
	 */
	public function getMapName ($id) {
		$maps = array(/* TODO */);
		
		if (array_key_exists($id, $maps)) {
			return $maps[$id];
		}
		
		return $id;
	}
}
