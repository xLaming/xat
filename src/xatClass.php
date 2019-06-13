<?php
/**
************
* xatClass *
************
* It allows you to access a lot of hidden features in xat
* It may be used by developers to help their projects
* It is only connected to official xat files, not external
* It is fully safe
************
* Autor: xLaming
* Git: github.com/xlaming
* Version: 1.0
**/
class xatClass {
	/* days to xat currency */
	protected $currency = 13.5;
	
	/* static languages */
	protected $languages = [
		'ro' => 'romanian',
		'es' => 'spanish',
		'pt' => 'portuguese',
		'it' => 'italian',
		'de' => 'german',
		'th' => 'thai',
		'ar' => 'arabic',
		'tr' => 'turkish',
		'pl' => 'polish',
		'nl' => 'dutch',
		'hr' => 'croatian',
		'sr' => 'serbian',
		'fr' => 'french',
		'bs' => 'bosnian',
		'n0' => 'international',
		'en' => 'english',
	];
	
	/* static links */
	protected $links = [
		'ad' => 'http://xat.com/json/ad.php',
		'sn' => 'https://xat.com/web_gear/chat/BuyShortName2.php',
		'cp' => 'https://xat.com/web_gear/chat/TransferGroup2.php',
		'p2' => 'https://xat.com/web_gear/chat/pow2.php',
		'pw' => 'http://xat.com/json/powers.php', //domain=.xat.com; HttpOnly
		'cs' => 'http://xat.com/json/GroupSearch.php?s=%s', //domain=.xat.com; HttpOnly
		'ci' => 'https://xat.com/api/roomid.php?d=%s',
		'pp' => 'https://xat.com/web_gear/chat/promotion2.php',
		'pm' => 'https://xat.com/json/promo.php',
		'id' => 'https://xat.com/web_gear/chat/profile.php?name=%s',
		'rg' => 'https://xat.com/web_gear/chat/profile.php?id=%d',
		'gt' => 'https://xat.com/web_gear/chat/gifts.php?id=%d',
		'al' => 'http://www.alexa.com/siteinfo/xat.com?ver=classic'
	];
	
	/* hidden IDs */
	protected $special = [
		7     => 'Darren',
		42    => 'xat',
		100   => 'Sam',
		101   => 'Chris',
		200   => 'Ajuda',
		201   => 'Ayuda',
		804   => 'Bot',
		911   => 'Guy'
	];
	
	/* caching only */
	private $powers = [];
	private $pow2 = [];
	
	/**
	* Get shortname prices
	**/
	public function shortnamePrice(String $value) { 
		$getPage = $this->loadSiteFromUrl(
			$this->links['sn'], 
			[
				'GroupName' => $value, 
				'Quote'     => 1
			]
		);
		
		if (empty($getPage)) { // page is offline
			return false;
		}
		
		$json = json_decode($getPage, false);
		
		if (empty($json->Err)) {
			return (integer) $json->Xats;
		}
		
		return (string) strip_tags(reset($json->Err));
	}
	
	/**
	* Get chat prices
	**/
	public function chatPrice(String $value) {
		$getPage = $this->loadSiteFromUrl(
			$this->links['cp'], 
			[
				'GroupName' => $value, 
				'Quote'     => 1
			]
		);
		
		if (empty($getPage)) { // page is offline
			return false;
		}
		
		$json = json_decode($getPage, false);
		
		if (empty($json->Err)) {
			return (integer) $json->Xats;
		}
		
		return (string) strip_tags(reset($json->Err));
	}
	
	/**
	* Get information about chats
	**/
	public function chatInfo(String $value) {
		$getPage = $this->loadSiteFromUrl(
			sprintf(
				$this->links['ci'], 
				$value
			)
		);

		$json = json_decode($getPage, false);
		
		if (empty($json)) {
			return false;
		}
		
		$data = explode(';=', $json->a);
		$flag = !empty($json->t) ? $json->t : 0;
		$tabs = !empty($json->tabs) ? $json->tabs : 0;
		$list = array();
		
		foreach ($tabs as $t) {
			$list[] = utf8_decode($t->label);
		}
		
		$result = [
			'id'           => $json->id,
			'name'         => $json->g,
			'desc'         => $json->d,
			'inner'        => $data[0],
			'outer'        => !empty($json->gb) ? $json->gb : 0,
			'tabbedchat'   => $data[1],
			'tabbedchatid' => $data[2],
			'language'     => $data[3],
			'radio'        => $data[4],
			'buttons'      => $data[5],
			'tabs'         => implode(', ', $list),
			'botid'        => !empty($json->bot) ? $json->bot : 0,
			'trusted'      => $flag & 256 ? 1 : 0,
		];
		
		return (object) $result;
	}
	
	/**
	* Convert days to xats
	**/
	public function daysToXats(String $value) {
		$result = round($value * $this->currency);
		return (integer) $result;
	}
	
	/**
	* Convert xats to days
	**/
	public function xatsToDays($value) {
		$result = round($value / $this->currency);
		return (integer) $result;
	}
	
	/**
	* Get information about the latest power
	**/
	public function latestPower() {
		if (!$this->setPowers() || !$this->setPow2()) {
			return false;
		}
		
		$pow2   = json_decode($this->pow2, true);
		$powers = json_decode($this->powers, true);
		$pawns  = array(); // caching
		
		foreach ($pow2[7][1] as $k => $v) {
			if ($pow2[0][1]['id'] === $v[0]) {
				$pawns[$k] = $v[1];
			}
		}
		
		$result = [
			'id'      => $pow2[0][1]['id'],
			'name'    => array_search($pow2[0][1]['id'], $pow2[6][1]),
			'smilies' => array_keys($pow2[4][1], $pow2[0][1]['id']),
			'pawns'   => $pawns
		];
		 
		if (!empty($powers[$result['id']])) {
			$xatsOrDays = 
				!empty($powers[$result['id']]['x']) 
					? $powers[$result['id']]['x'] . ' xats' 
					: $powers[$result['id']]['d']  . ' days';
			
			$result['is_epic']  = $powers[$result['id']]['f'] & 8 ? 1 : 0;
			$result['is_game']  = $powers[$result['id']]['f'] & 0x80 ? 1 : 0;
			$result['is_allp']  = $powers[$result['id']]['f'] & 0x401 ? 1 : 0;
			$result['is_group'] = $powers[$result['id']]['f'] & 0x800 ? 1 : 0;
			$result['status']   = $powers[$result['id']]['f'] & 0x2000 ? 'limited' : 'unlimited';
			$result['desc']     = $powers[$result['id']]['d1'];
			$result['price']    = $xatsOrDays;
		}
		
		return (object) $result;
	}
	
	/**
	* Get chat promotion prices
	**/
	public function promotionPrice(String $value) {
		$getPage = $this->loadSiteFromUrl(
			$this->links['pp'], 
			[
				'GroupName' => 'test',
				'XatsDays'  => 'Xats',
				'Hours'     => (float) $value,
				'Lang'      => 'en',
				'Quote'     => 1
			]
		);
		
		if (empty($getPage)) { // page is offline
			return false;
		}
		
		$fixed = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $getPage); //someone missed invisible chars in xat internal's
		$json = json_decode($fixed, false);
		
		if (empty($json->Err)) {
			return (object) [
				'time' => $json->Hours,
				'xats' => $json->Xats,
				'days' => $json->Days
			];
		}
		
		return (string) strip_tags(reset($json->Err));
	}
	
	/**
	* Verify if the banner URL is approved
	**/
	public function verifyBanner(String $value) {
		if (false === filter_var($value, FILTER_VALIDATE_URL)) {
			return false;
		}
		
		$getPage = $this->loadSiteFromUrl(
			$this->links['pp'], 
			[
				'GroupName' => 'test',
				'XatsDays'  => 'Xats',
				'Hours'     => 1,
				'Lang'      => 'en',
				'Quote2'    => 1,
				'AdImg'     => $value
			]
		);

		if (empty($getPage)) { // page is offline
			return false;
		}
		
		$fixed = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $getPage); //someone missed invisible chars in xat internal's
		$json = json_decode($fixed, false);
		
		if (empty($json->Err)) {
			return (integer) 1 === $json->NeedApproval ? 0 : 1; // if return 0 is because banner need to be approved, 1 is already approved
		}
		
		return (string) strip_tags(reset($json->Err));
	}
	
	/**
	* Get power information
	**/
	public function powerInfo(String $value) {
		if (!$this->setPowers()) {
			return false;
		}
		
		$powers = json_decode($this->powers, true); // not obj
		
		
		foreach ($powers as $k => $v) {
			if (strtolower($value) === $v['s']) {
				$xatsOrDays = 
					!empty($powers[$k]['x']) 
						? $powers[$k]['x'] . ' xats' 
						: $powers[$k]['d']  . ' days';
					
				$result = [
					'id'       => $k,
					'name'     => $v['s'],
					'is_epic'  => $powers[$k]['f'] & 8 ? 1 : 0,
					'is_game'  => $powers[$k]['f'] & 0x80 ? 1 : 0,
					'is_allp'  => $powers[$k]['f'] & 0x401 ? 1 : 0,
					'is_group' => $powers[$k]['f'] & 0x800 ? 1 : 0,
					'desc'     => $powers[$k]['f'] & 0x2000 ? 'limited' : 'unlimited',
					'price'    => $xatsOrDays,
				];
				
				return (object) $result;
			}
		}
		
		return false;
	}
	
	/**
	* Get all xat store prices
	**/
	public function storePrices() {
		if (!$this->setPowers()) {
			return false;
		}
		
		$powers = json_decode($this->powers, true); // not obj
		
		$result = array(); // init
		
		foreach ($powers as $k => $v) {
			$xatsOrDays = 
				!empty($powers[$k]['x']) 
					? $powers[$k]['x'] . ' xats' 
					: $powers[$k]['d']  . ' days';
				
				
			if (empty($powers[$k]['f'])) {
				$powers[$k]['f'] = 0;
			}
			
			$result[$k] = [
				'name'     => $v['s'],
				'is_epic'  => $powers[$k]['f'] & 8 ? 1 : 0,
				'is_game'  => $powers[$k]['f'] & 0x80 ? 1 : 0,
				'is_allp'  => $powers[$k]['f'] & 0x401 ? 1 : 0,
				'is_group' => $powers[$k]['f'] & 0x800 ? 1 : 0,
				'desc'     => $powers[$k]['f'] & 0x2000 ? 'limited' : 'unlimited',
				'price'    => $xatsOrDays,
			];
		}
		
		return (object) $result;
	}
	
	/**
	* Get various information about xat powers
	**/
	public function fairtradePrices(String $value) {
		/* This is now deprecated
		 * Please check: https://github.com/xLaming/xat-fairtrade
		 * It is the official xattrade.com's api
		 */
	}
	
	/**
	* Get chats promoted
	**/
	public function chatsPromoted() {
		$getPage = $this->loadSiteFromUrl($this->links['pm']);
		
		if (empty($getPage)) { // page is offline
			return false;
		}
		
		$json = json_decode($getPage, false);
		
		$result = array(); // caching
		
		foreach ($json as $k => $v) {
			$lang = $this->twoToLang($k);
			foreach ($v as $x) {
				if (in_array(strtolower($x->n), ['assistance', 'chat'])) {
					continue;
				}
				
				if (empty($x->t)) {
					$promo = 'autopromo';
				} else if ($x->t > time()) {
					$promo = $x->t - time() . ' seconds';
				} else {
					$promo = 'ended';
				}
				
				$chat = [
					'name' => $x->n,
					'desc' => $x->d,
					'bg'   => $x->a,
					'ends' => $promo
				];
				
				$result[$lang][$x->i] = $chat;
			}
		}
		
		return (object) $result;
	}
	
	/**
	* Convert ID to register
	**/
	public function IdToReg(String $value) {
		if(array_key_exists($value, $this->special)) {
			return (string) $this->special[$value];
		}
		
		$getPage = $this->loadSiteFromUrl(
			sprintf(
				$this->links['rg'], 
				$value
			)
		);

		if (empty($getPage) || strlen($getPage) > 12) {
			return false;
		}
		
		return (string) $getPage;
	}
	
	/**
	* Convert register to ID
	**/
	public function RegToId(String $value) {
		$getPage = $this->loadSiteFromUrl(
			sprintf(
				$this->links['id'], 
				$value
			)
		);
		
		if (empty($getPage)) {
			return false;
		}
		
		return (integer) $getPage;
	}
	
	/**
	* Verify if the chat is delisted
	**/
	public function delistCheck(String $value) {
		$getPage = $this->loadSiteFromUrl(
			$this->links['pp'], 
			[
				'YourEmail' => 'test',
				'password'  => '1234',
				'agree'     => 'ON',
				'XatsDays'  => 'Xats',
				'GroupName' => $value,
				'Lang:'     => 'en',
				'Xats'      => 100,
				'Hours'     => 1,
				'Days'      => 8,
				'Promote'   => 1
			]
		);
		
		if (empty($getPage)) { // page is offline
			return false;
		}
		
		$fixed = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $getPage); //someone missed invisible chars in xat internal's
		$json = json_decode($fixed, false);
		
		$parseError = strip_tags(reset($json->Err));
		
		if (false !== strpos($parseError, '(2)')) { //check for error (2)
			return 1;
		}
		
		return 0;
	}
	
	/**
	* Search for xat chats
	**/
	public function chatSearch(String $value) {
		$getPage = $this->loadSiteFromUrl(
			sprintf(
				$this->links['cs'], 
				$value
			)
		);
		
		if (empty($getPage)) { // page is offline
			return false;
		}

		$json = json_decode($getPage, false);
		
		$result = array();
		
		foreach ($json as $v) {
			$result[$v->g] = [
				'desc' => $v->d,
				'pic'  => $v->a,
			];
		}
		
		return (object) $result;
	}
	
	/**
	* Get countdown from the latest power
	**/
	public function powerReleaseCountdown() {
		$getPage = $this->loadSiteFromUrl($this->links['ad']);

		if (empty($getPage)) {
			return false;
		}
		
		$json = json_decode($getPage, false);
		
		if (1 === $json->t) { // no countdown
			return 'nothing';
		} else if (time() > $json->t) { // already released
			return 'released';
		}
		
		$result = ($json - time()) . ' seconds';
		
		return (string) $result; //will be released in X seconds
	}
	
	/**
	* Load the hug list
	**/
	public function hugList() {
		if (!$this->setPowers() || !$this->setPow2()) {
			return false;
		}
		
		$powers = json_decode($this->powers, true);
		$pow2 = json_decode($this->pow2, false);
		$result = array();
		
		foreach ($pow2[3][1] as $k => $v) {
			if (10000 > $v) {
				$result[$k] = [
					'usage' => '/hug ' . $k,
					'power' => $powers[$v]['s']
				];
			}
		}
		
		return $result;
	}
	
	/**
	* Load the jinx list
	**/
	public function jinxList() {
		if (!$this->setPowers() || !$this->setPow2()) {
			return false;
		}
		
		$powers = json_decode($this->powers, true);
		$pow2 = json_decode($this->pow2, false);
		$result = array();
		
		foreach ($pow2[3][1] as $k => $v) {
			if (10000 < $v) {
				$pid = $v % 10000;
				
				$result[$k] = [
					'usage' => '/jinx ' . $k,
					'power' => $powers[$pid]['s']
				];
			}
		}
		
		return $result;
	}
	
	/**
	* Get gifts from an xat user
	**/
	public function userGifts(String $value) {
		$getPage = $this->loadSiteFromUrl(
			sprintf(
				$this->links['gt'], 
				$value
			)
		);
		
		if (empty($getPage)) { // page is offline
			return false;
		}
		
		$json = json_decode($getPage, false);
		$result = array();
		
		foreach ($json as $k => $v) {
			if (!is_object($v)) {
				continue;
			}
			
			$result[] = [
				'id'      => $v->id,
				'reg'     => $v->n,
				'gift'    => $v->g,
				'time'    => date('H:i:s, d/m/Y', $k),
				'title'   => $v->s,
				'message' => !empty($v->m) ? $v->m : 'private',
			];
		}
		
		return (object) [
			'total' => count($result),
			'gifts' => $result
		];
	}
	
	/**
	* See top five countries where xat is most common
	**/
	public function xatPopularCountries() {
		$getPage = $this->loadSiteFromUrl($this->links['al']);
		
		if (empty($getPage)) {
			return false;
		}
		
        preg_match('/id="visitorPercentage">(.*?)<\//', $getPage, $matches);

        $fixed = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $matches[1]); //someone missed invisible chars in alexa internal's
		$json = json_decode($fixed, true);
        
		$results = array();
		
		if (empty($json)) {
			return false;
		}
		
		foreach($json as $k => $v) {

			$results[$k+1] = [
				'country'     => $v['name'],
				'visitors'    => $v['visitors_percent']. '%'
			];
		}
		
		return (object) $results;		
	}
	
	/**
	* Helper for loading web pages
	**/
	private function loadSiteFromUrl(String $url, $post = array()) {
		$opts = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => false,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POST           => (empty($post) ? false : true),
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT        => 5,
			CURLOPT_MAXREDIRS      => 2,
			CURLOPT_POSTFIELDS     => http_build_query($post)
		];
		$ch = curl_init($url);
		curl_setopt_array($ch, $opts);
		$result = curl_exec($ch);
		curl_close($ch);
		return (string) $result;
	}

	/**
	* Helper for loading static pow2
	**/
	private function setPow2() {
		if (empty($this->pow2)) {
			$this->pow2 = $this->loadSiteFromUrl($this->links['p2']);
		}
		
		if (empty($this->pow2)) {
			return false;
		}		
		
		return true;
	}
	
	/**
	* Helper for loading static powers
	**/
	private function setPowers() {
		if (empty($this->powers)) {
			$this->powers = $this->loadSiteFromUrl($this->links['pw']);
		}
		
		if (empty($this->powers)) {
			return false;
		}		
		
		return true;
	}
	
	/**
	* Convert language code to full language
	**/
	private function twoToLang(String $code) {
		if (array_key_exists($code, $this->languages)) {
			return (string) $this->languages[$code];
		}
		
		return 'unknown';
	}
}
