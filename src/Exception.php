<?php
namespace n0bodysec\ACMarket;
class Exception extends \Exception
{
	public function errorMessage()
	{
		return '<strong>' . htmlspecialchars($this->getMessage()) . "</strong><br />\n";
	}
}