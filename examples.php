<?php
require_once('src/xatClass.php');

/* filter against XSS attacks */
if (!key_exists('t', $_GET)) {
	die('examples.php?t=MODE');
}

$args = key_exists('v', $_GET) ? strip_tags($_GET['v']) : false;
$mode = strip_tags($_GET['t']);
/* end filter */

$xat = new \xatClass(); // init class


switch (strtolower($mode)) {
	case 'shortname':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->shortnamePrice($args);
		break;
		
	case 'chatgroup':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->chatPrice($args);
		break;
		
	case 'dx':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		$page =  $xat->daysToXats($args);
		break;
		
	case 'xd':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->xatsToDays($args);
		break;
		
	case 'promotion':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->promotionPrice($args);
		break;
		
	case 'bannercheck':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->verifyBanner($args);
		break;
		
	case 'powerinfo':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->powerInfo($args);
		break;
		
	case 'id2reg':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->IdToReg($args);
		break;
		
	case 'reg2id':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->RegToId($args);
		break;
		
	case 'delistcheck':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->delistCheck($args);
		break;
	
	case 'chatsearch':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->chatSearch($args);
		break;
	
	case 'chatinfo':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->chatInfo($args);
		break;
		
	case 'gifts':
		if (empty($args)) {
			$page = 'examples.php?t=MODE&v=ARGS';
			break;
		}
		
		$page = $xat->userGifts($args);
		break;
	
	case 'latest':
		$page = $xat->latestPower();
		break;
		
	case 'store':
		$page = $xat->storePrices();
		break;
		
	case 'promoted':
		$page = $xat->chatsPromoted();
		break;
		
	case 'pcd':
		$page = $xat->powerReleaseCountdown();
		break;
		
	case 'hugs':
		$page = $xat->hugList();
		break;
		
	case 'jinx':
		$page = $xat->jinxList();
		break;
		
	case 'countries':
		$page = $xat->xatPopularCountries();
		break; 

	default:
		$page = 'opt not found';
		break;
}
?>

<title>API Examples</title>
<pre><?php print_r($page); ?></pre>
