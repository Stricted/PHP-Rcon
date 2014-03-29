<?php
require_once("games/AbstractGame.class.php");

/**
 * Battlefield 3
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class BF3 extends AbstractGame {
	/**
	 * get players from gameserver
	 *
	 * @return	array
	 */
	public function getPlayers () {
		$response = $this->encodeClientRequest('listPlayers all');
		$players = array();
		
		if ($response[0] == 'OK') {
			for ($i = 11; $i < count($response); $i += 8) {
				$player = array();
				$player['name'] = $response[$i];
				$player['guid'] = $response[$i+1];/* GUID is empty, because we are not logged in */
				$player['teamId'] = $response[$i+2];
				$player['squadId'] = $response[$i+3];
				$player['kills'] = $response[$i+4];
				$player['deaths'] = $response[$i+5];
				$player['score'] = $response[$i+6];
				$player['rank'] = $response[$i+7];
					
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
		$response = $this->encodeClientRequest('serverinfo');
		if ($response[0] == 'OK') {
			return $response[3];
		}
		else {
			return false;
		}
	}
	
	/**
	 * get player count from server
	 *
	 * @return	integer
	 */
	public function getCurrentPlayerCount () {
		$response = $this->encodeClientRequest('serverinfo');
		if ($response[0] == 'OK') {
			return $response[2];
		}
		else {
			return false;
		}
	}
	
	/**
	 * get current map from gameserver
	 *
	 * @return	string
	 */
	public function getCurrentMap () {
		$response = $this->encodeClientRequest('serverinfo');
		if ($response[0] == 'OK') {
			return $response[5];
		}
		else {
			return false;
		}
	}
	
	/**
	 * get current game mode from gameserver
	 *
	 * @return	string
	 */
	public function getCurrentMode () {
		$response = $this->encodeClientRequest('serverinfo');
		if ($response[0] == 'OK') {
			return $response[4];
		}
		else {
			return false;
		}
	}
	
	/**
	 * get server name from gameserver
	 *
	 * @return	string
	 */
	public function getServerName () {
		$response = $this->encodeClientRequest('serverinfo');
		if ($response[0] == 'OK') {
			return $response[1];
		}
		else {
			return false;
		}
	}
	
	/**
	 * translate map code to map name
	 *
	 * @param	string	$id
	 * @return	string
	 */
	public function getMapName ($id) {
		$maps = array(
				/* Default maps */
				"MP_001" => "Grand Bazaar",
				"MP_003" => "Tehran Highway",
				"MP_007" => "Caspian Border",
				"MP_011" => "Seine Crossing",
				"MP_012" => "Operation Firestorm",
				"MP_013" => "Damavand Peak",
				"MP_017" => "Noshahr Canals",
				"MP_018" => "Kharg Island",
				"MP_Subway" => "Operation Metro",
				/* Back to Karkand Maps */
				"XP1_001" => "Strike at Karkand",
				"XP1_002" => "Gulf of Oman",
				"XP1_003" => "Sharqi Peninsula",
				"XP1_004" => "Wake Island",
				/* Close Quarters Maps */
				"XP2_Palace" => "Donya Fortress",
				"XP2_Office" => "Operation 925",
				"XP2_Factory" => "Scrapmetal",
				"XP2_Skybar" => "Ziba Tower",
				/* Armored Kill Maps */
				"XP3_Valley" => "Death Valley",
				"XP3_Shield" => "Armored Shield",
				"XP3_Desert" => "Bandar Desert",
				"XP3_Alborz" => "Alborz Mountain",
				/* Aftermath Maps */
				"XP4_Parl" => "Azadi Palace",
				"XP4_Quake" => "Epicenter",
				"XP4_FD" => "Markaz Monolith",
				"XP4_Rubble" => "Talah Market",
				/* End Game Maps */
				"XP5_001" => "Operation Riverside",
				"XP5_002" => "Nebandan Flats",
				"XP5_003" => "Kiasar Railroad",
				"XP5_004" => "Sabalan Pipeline"
				);
		
		if (array_key_exists($id, $maps)) {
			return $maps[$id];
		}
		
		return $id;
	}
	
	/**
	 * recive data from gameserver
	 *
	 * @return	string
	 */
	protected function receive () {
		$receiveBuffer = '';	
		while (!$this->containsCompletePacket($receiveBuffer)) {
			$receiveBuffer .= fread($this->socket, 4096);
		}
		
		$packetSize = $this->decodeInt32(mb_substr($receiveBuffer, 4, 4));
		$packet = mb_substr($receiveBuffer, 0, $packetSize);
		
		return $this->decodePacket($packet);
	}
	
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
		
		if (mb_strlen($data) < $this->decodeInt32(mb_substr($data, 4, 4))) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * decode the given data to int32
	 *
	 * @param	integer	$data
	 * @return	integer
	 */
	protected function decodeInt32($data) {
		$decode = unpack('I', mb_substr($data, 0, 4));
		
		return $decode[1];
	}
	
	/**
	 * encode the given data to int32
	 *
	 * @param	integer	$size
	 * @return	integer
	 */
	protected function encodeInt32($size) {
		return pack('I', $size);
	}
	
	/**
	 * encode words
	 *
	 * @param	array	$words
	 * @return	array
	 */
	protected function encodeWords($words) {
		$size = 0;
		$encodedWords = '';
		
		foreach ($words as $word) {
			$encodedWords .= $this->encodeInt32(strlen($word));
			$encodedWords .= $word;
			$encodedWords .= "\x00";
			$size += strlen($word) + 5;
		}
		
		return array($size, $encodedWords);
	}

	/**
	 * decode words
	 *
	 * @param	array	$words
	 * @return	array
	 */
	protected function decodeWords($size, $data) {
		$numWords = $this->decodeInt32($data);
		$words = array();
		$offset = 0;
		while ($offset < $size) {
			$wordLen = $this->decodeInt32(mb_substr($data, $offset, 4));
			$word = mb_substr($data, $offset + 4, $wordLen);
			array_push($words, $word);
			$offset += $wordLen + 5;
		}
		
		return $words;
	}
	
	/**
	 * encode package
	 *
	 * @param	boolean	$isFromServer
	 * @param	boolean	$isResponse
	 * @param	string	$sequence
	 * @param	array	$words
	 * @return	string
	 */
	protected function encodePacket($words) {
		$encodedNumWords = $this->encodeInt32(count($words));
		$encodedWords = $this->encodeWords($words);
		$encodedSize = $this->encodeInt32($encodedWords[0] + 12);
		
		return "\000\000\000\000" . $encodedSize . $encodedNumWords . $encodedWords[1];
	}
	
	/**
	 * decode package
	 *
	 * @param	string	$data
	 * @return	array
	 */
	protected function decodePacket($data) {
		$wordsSize = $this->decodeInt32(mb_substr($data, 4, 4)) - 12;
		$words = $this->decodeWords($wordsSize, mb_substr($data, 12));
		
		return $words;
	}
	
	/**
	 * encode request
	 *
	 * @param	string	$string
	 * @return	string
	 */
	protected function encodeClientRequest($string) {
		// string splitting
		if ((strpos($string, '"') !== false) || (strpos($string, '\'') !== false)) {
			$words = preg_split('/["\']/', $string);

			for ($i=0; $i < count($words); $i++) { 
				$words[$i] = trim($words[$i]);
			}
		} else {
			$words = preg_split('/\s+/', $string);
		}
		
		$packet = $this->encodePacket($words);
		
		return $this->command($packet);
	}
}
