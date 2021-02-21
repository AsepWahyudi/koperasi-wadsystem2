<?php

namespace Cekmutasi\Services;

defined("BASEPATH") or exit("No direct script access allowed");

require_once(dirname(__DIR__).'/Container.php');
require_once(dirname(__DIR__).'/Support/Constant.php');

use Container;
use Cekmutasi\Support\Constant;

class OVO extends Container
{
	private $config = [];

	public function __construct($configs = [])
	{
		parent::__construct();

		$this->config = $configs;
	}

	/**
	*	Get OVO mutation (max 1000)
	*
	*	@param Array Search Filter $filters
	*
	*	@return Object Container::curl()
	*
	**/

	public function mutation($filters = [])
	{
		return $this->curl('/ovo/search', Constant::HTTP_POST, [
			'search'	=> $filters
		]);
	}

	/**
	*	Get all registered ovo accounts
	*
	*	@return Object Container::curl()
	*
	**/

	public function app_list()
	{
		return $this->curl('/ovo/list', Constant::HTTP_POST);
	}

	/**
	*	Get total balance of registered ovo accounts
	*
	*	@return Object Container::curl()
	*
	**/

	public function balance()
	{
		return $this->curl('/ovo/balance', Constant::HTTP_POST);
	}

	/**
	*	Get ovo account detail
	*
	*	@param Int OVO ID $id
	*
	*	@return Object Container::curl()
	*
	**/

	public function detail(int $id)
	{
		return $this->curl('/ovo/detail', Constant::HTTP_POST, [
			'id'	=> intval($id)
		]);
	}

	/**
	*	Get list bank for OVO Transfer
	*
	*	@param String $sourceNumber
	*
	*	@return Object Container::curl()
	*
	**/

	public function transferBankList($sourceNumber)
	{
		return $this->curl('/ovo/transfer/bank-list', Constant::HTTP_POST, [
			'source_number'	=> $sourceNumber
		]);
	}

	/**
	*	Transfer inquiry
	*
	*	@param String $sourceNumber
	*
	*	@param String $bankCode
	*
	*	@param String $destinationNumber
	*
	*	@return Object Container::curl()
	*
	**/

	public function transferBankInquiry($sourceNumber, $bankCode, $destinationNumber)
	{
		return $this->curl('/ovo/transfer/inquiry', Constant::HTTP_POST, [
			'source_number'	=> $sourceNumber,
			'bank_code'	=> $bankCode,
			'destination_number'	=> $destinationNumber
		]);
	}

	/**
	*	Proccess transfer
	*
	*	@param String $uuid
	*
	*	@param String $token
	*
	*	@param String $amount
	*
	*	@param String $note
	*
	*	@return Object Container::curl()
	*
	**/

	public function transferBank($uuid, $token, $amount, $note = '')
	{
		return $this->curl('/ovo/transfer/send', Constant::HTTP_POST, [
			'uuid'	=> $uuid,
			'token'	=> $token,
			'amount'	=> $amount,
			'note'	=> $note
		]);
	}

	/**
	*	Get transfer detail
	*
	*	@param String $uuid
	*
	*	@return Object Container::curl()
	*
	**/

	public function transferBankDetail($uuid)
	{
		return $this->curl('/ovo/transfer/detail', Constant::HTTP_GET, [
			'uuid'	=> $uuid
		]);
	}

	/**
	*	Transfer Inquiry
	*
	*	@param String $sourceNumber
	*
	*	@param String $destinationNumber
	*
	*	@return Object Container::curl()
	*
	**/

	public function transferOVOInquiry($sourceNumber, $destinationNumber)
	{
		return $this->curl('/ovo/transfer-ovo/inquiry', Constant::HTTP_POST, [
			'source_number'	=> $sourceNumber,
			'phone'	=> $destinationNumber
		]);
	}

	/**
	*	Process transfer
	*
	*	@param String $sourceNumber
	*
	*	@param String $destinationNumber
	*
	*	@param Int $amount
	*
	*	@return Object Container::curl()
	*
	**/

	public function transferOVO($sourceNumber, $destinationNumber, $amount)
	{
		return $this->curl('/ovo/transfer-ovo/send', Constant::HTTP_POST, [
			'source_number'	=> $sourceNumber,
			'phone'	=> $destinationNumber,
			'amount'	=> $amount
		]);
	}
}