<?PHP
/**
* 
*/
class security
{
	// filter username
	function filter_username($data)
	{
		$data = preg_replace("/[^A-Za-z0-9_.-]/", "", $data); #just return "A-Z","a-z","0-9","_-."
		return $data;
	}

	//filter email input
	function filter_email($data)
	{
		$data = trim($data);
  		$data = filter_var($data, FILTER_SANITIZE_EMAIL);
  		$data = mysql_real_escape_string($data);
  		return $data;
	}

	//filter url input
	function filter_url($data)
	{
		$data = trim($data);
  		$data = filter_var($data, FILTER_SANITIZE_URL);
  		$data = mysql_real_escape_string($data);
  		if (preg_match("#https?://#",$data ) === 0) {
    		$data = 'http://'.$data;
		}
  		return $data;
	}

	//filter text 
	function filter_text($data)
	{
		$data = trim($data);
  		$data = preg_replace('!&amp;#([0-9]+);!', '&#\1;', htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
  		$data = mysql_real_escape_string($data);
  		return $data;
	}

	function filter_num($data)
	{
		$data = preg_replace("/[^0-9]/", "", $data); #just return "0-9"
		return $data;
	}

	function filter_limit($data)
	{
		$data = preg_replace("/[^0-9,]/", "", $data); #just return "0-9 ,"
		return $data;
	}

	function filter_md5code($data)
	{
		$data = preg_replace("/[^A-Za-z0-9]/", "", $data); #just return "A-Z","a-z","0-9"
		return $data;
	}
}