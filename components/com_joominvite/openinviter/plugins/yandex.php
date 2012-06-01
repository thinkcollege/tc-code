<?php
$_pluginInfo=array(
	'name'=>'Yandex',
	'version'=>'1.0.5',
	'description'=>"Get the contacts from a Yandex account",
	'base_version'=>'1.6.3',
	'type'=>'email',
	'check_url'=>'http://yandex.ru'
	);
/**
 * Yandex Plugin
 * 
 * Imports user's contacts from his Yandex
 * AddressBook.
 * 
 * @author OpenInviter
 * @version 1.0.5
 */
class yandex extends OpenInviter_base
{
	private $login_ok=false;
	public $showContacts=false;
	public $requirement=false;
	public $allowed_domains=array('yandex');
	public $debug_array=array(
			  'main_redirect'=>'window.location.replace(&quot;',
			  'log_in'=>'http://passport.yandex.ru/passport?mode=logout'
	);
	
	/**
	 * Login function
	 * 
	 * Makes all the necessary requests to authenticate
	 * the current user to the server.
	 * 
	 * @param string $user The current user.
	 * @param string $pass The password for the current user.
	 * @return bool TRUE if the current user was authenticated successfully, FALSE otherwise.
	 */
	public function login($user, $pass)
	{
		$this->resetDebugger();
		$this->service='yandex';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		$res = $this->get("http://yandex.ru/",false);
		$res = $this->get("http://mail.yandex.ru/",true);
		$postaction = "https://passport.yandex.ru/passport?mode=auth";
		$postelem = $this->getHiddenElements($res);$postelem["login"]=$user;$postelem["passwd"]=$pass;
		$res = $this->post($postaction, $postelem, true);
		$res =  htmlentities($res);
		if ($this->checkResponse("main_redirect",$res))
			$this->updateDebugBuffer('main_redirect',$postaction,'POST');
		else
			{
			$this->updateDebugBuffer('main_redirect',$postaction,'POST',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$urlRedirect = $this->getElementString($res, '(&quot;', '&quot;)');
		$res = $this->get($urlRedirect, true);
		if ($this->checkResponse("log_in",$res))
			$this->updateDebugBuffer('log_in',$urlRedirect,'GET');
		else
			{
			$this->updateDebugBuffer('log_in',$urlRedirect,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$linkToAddressBook = "http://mail.yandex.ru/classic/abook";
		$this->login_ok = $linkToAddressBook;
		return true;
	}

	/**
	 * Get the current user's contacts
	 * 
	 * Makes all the necesarry requests to import
	 * the current user's contacts
	 * 
	 * @return mixed The array if contacts if importing was successful, FALSE otherwise.
	 */	
	public function getMyContacts()
	{
		if (!$this->login_ok)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else $url = $this->login_ok;
		$res = $this->get($url, true);
		$res.="||exit||";
		$contacts = array();
		$temparr = array();
		while (strstr($res, 'href="compose?to='))
			{
			$res = $this->getElementString($res,'href="compose?to','||exit||');
			$res.= "||exit||";
			$temp = $this->getElementString($res,'=','"');
			array_push($temparr, ($temp));
			}
		foreach ($temparr as $k => $v)
			{
			$v = str_replace("%22", "", $v);
			$v = str_replace("%3E", "", $v);
			$v = str_replace("%20%3C", "|", $v);
			$v = str_replace("%20%20", " ", $v);
			$exp = explode("|", $v);
			$contacts[$exp[1]] = $exp[0];			
			}
		$this->showContacts=true;
		foreach ($contacts as $email=>$name) if (!$this->isEmail($email)) unset($contacts[$email]);
		return $contacts;
		
	}

	/**
	 * Terminate session
	 * 
	 * Terminates the current user's session,
	 * debugs the request and reset's the internal 
	 * debudder.
	 * 
	 * @return bool TRUE if the session was terminated successfully, FALSE otherwise.
	 */	
	public function logout()
		{
		if (!$this->checkSession()) return false;
		$res = $this->get(urldecode("http://passport.yandex.ru/passport?mode=logout&retpath=http%3A%2F%2Fwww.yandex.ru%2F"));
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		}
}
?>