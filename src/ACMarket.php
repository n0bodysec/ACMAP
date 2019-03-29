<?php
namespace n0bodysec\ACMarket;
if (!defined('ACMARKET_LOADED')) exit; // do not change!
class ACMarket
{
	/**
	 * Exception related vars.
	 */
	public $ErrorInfo = '';
	protected $ErrCount = 0;
	protected $exceptions = false;
	protected $isWrong = false; // Used to stop executing code.
	
	/**
	 * ACMarket related vars.
	 */
	protected $BASE_URL = "https://apiv2.acmarket.net/";
	protected $SEARCH_URL = "https://search.acmarket.net/";
	protected $UPDATE_URL = "https://update.acmarket.net/";
	protected $USER_BASE_URL = "https://user.acmarket.net/";
	protected $VIRUS_SCAN_URL = "https://scan.acmarket.net/";
	protected $UserAgent = 'ACMarket_321';
	
	/**
	 * Constructor.
	 *
	 * @param bool $exceptions Should we throw external exceptions?
	 */
	public function __construct($exceptions = null)
	{	
		if (null !== $exceptions)
			$this->exceptions = (bool) $exceptions;
		
		if (!function_exists('curl_version'))
		{
			$this->throwError("Cannot find cURL!");
			$this->isWrong = true;
		}
	}

	/**
	 * Destructor.
	 */
	//public function __destruct() { }
	
	protected function setError($msg)
	{
		++$this->ErrCount;
		if (empty($this->ErrorInfo))
			$this->ErrorInfo = $msg;
		else $this->ErrorInfo .= $msg;
	}
	
	protected function throwError($errorMsg)
	{
		$this->setError($errorMsg);
		if ($this->exceptions) throw new Exception($errorMsg);
		//return $errorMsg;
	}
	
	protected function cURL($url, $opts = null)
	{
		if ($this->isWrong) return $this->throwError("Fatal error! isWrong is true.");
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		
		if (null !== $opts)
			curl_setopt_array($ch, $opts);
		
		$result = curl_exec($ch);
		
		// Throw errors
		if (curl_errno($ch)) $this->throwError(curl_error($ch));
		
		curl_close($ch);
		return $result;
	}
	
	public function decode($json, $arr = false) {
		return json_decode($json, $arr);
	}
	
	public function searchApp($key, $isbook = false)
	{
		$options = array
		(
			CURLOPT_POSTFIELDS => "key=" . $key
		);
		
		return $this->cURL($this->SEARCH_URL . ($isbook ? 'book.php' : ''), $options);
	}
	public function searchBook($key) { return $this->searchApp($key, true); }
	
	public function getAppInfo($id, $isbook = false)
	{
		$options = array
		(
			CURLOPT_POSTFIELDS => "id=" . $id
			//CURLOPT_USERAGENT => $this->UserAgent // TODO: Only if $isbook == false
		);
		if (!$isbook) array_push($options, CURLOPT_USERAGENT, $this->UserAgent);
		
		return $this->cURL($this->BASE_URL . ($isbook ? 'book_detail.php' : 'detail.php'), $options);
	}
	public function getBookInfo($id) { return $this->getAppInfo($id, true); }
	
	public function getCategories() {
		return $this->cURL($this->BASE_URL . 'category.php');
	}
	
	public function getMenu() {
		return $this->cURL($this->BASE_URL . 'menu.php');
	}
	
	public function getDailyApp() {
		return $this->cURL($this->BASE_URL . 'appoftheday.php');
	}
	
	public function getCouponDetail($id)
	{
		$options = array
		(
			CURLOPT_POSTFIELDS => "appid=" . $id
		);
		
		return $this->cURL($this->USER_BASE_URL . 'card_list.php', $options);
	}
	
	public function reportApp($link)
	{
		$options = array
		(
			CURLOPT_POSTFIELDS => "link=" . $link
		);
		
		return $this->cURL($this->VIRUS_SCAN_URL . 'report.php', $options);
	}
	public function reportBook($link) { return $this->reportApp($link); }
	
	public function getReports($link)
	{
		$options = array
		(
			CURLOPT_POSTFIELDS => "link=" . $link
		);
		
		return $this->cURL($this->VIRUS_SCAN_URL . 'scan.php', $options);
	}
}
