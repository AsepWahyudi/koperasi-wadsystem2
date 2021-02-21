<?php
namespace Cekmutasi\Services;
defined("BASEPATH") or exit("No direct script access allowed");

require_once(dirname(__DIR__).'/Container.php');
require_once(dirname(__DIR__).'/Support/Constant.php');

use Container;
use Cekmutasi\Support\Constant;

class Bank extends Container
{
	private $config = [];

	public function __construct($configs = [])
	{
		parent::__construct();

		$this->config = $configs;
	}

	/**
	*	Get Bank mutation (max 1000)
	*
	*	@param Array Search Filter $filters
	*
	*	@return Object Container::curl()
	*
	**/

	public function mutation($filters = [])
	{
		return $this->curl('/bank/search', Constant::HTTP_POST, [
			'search'	=> $filters
		]);
	}

	/**
	*	Get all registered bank accounts
	*
	*	@return Object Container::curl()
	*
	**/

	public function list()
	{
		return $this->curl('/bank/list', Constant::HTTP_POST);
	}

	/**
	*	Get total balance of registered bank accounts
	*
	*	@return Object Container::curl()
	*
	**/

	public function balance()
	{
		return $this->curl('/bank/balance', Constant::HTTP_POST);
	}

	/**
	*	Get bank account detail
	*
	*	@param Int Bank ID $id
	*
	*	@return Object Container::curl()
	*
	**/

	public function detail(int $id)
	{
		return $this->curl('/bank/detail', Constant::HTTP_POST, [
			'id'	=> intval($id)
		]);
	}
}