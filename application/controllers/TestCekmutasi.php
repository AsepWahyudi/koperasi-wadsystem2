<?php

class TestCekmutasi extends CI_Controller
{
	public function mutasiBank()
	{
		$this->load->library('Cekmutasi/cekmutasi');

		$mutasi = $this->cekmutasi->bank()->mutation([
			'date'		=> [
				'from'	=> date('Y-m-d') . ' 00:00:00',
				'to'	=> date('Y-m-d') . ' 23:59:59'
			]
		]);

		//print_r($mutasi);

		$list = $this->cekmutasi->bank()->list();

		//echo json_encode($list);
		//print_r($list);
		$data = json_decode(json_encode($list), true);
        		//print_r($data['data']);
        		$step = json_encode($data['data']);
        		echo $step;

	}

	public function mutasiPayPal()
	{
		$this->load->library('Cekmutasi/cekmutasi');

		$mutasi = $this->cekmutasi->paypal()->mutation([
			'date'		=> [
				'from'	=> date('Y-m-d') . ' 00:00:00',
				'to'	=> date('Y-m-d') . ' 23:59:59'
			]
		]);

		print_r($mutasi);
	}

	public function mutasiOVO()
	{
		$this->load->library('Cekmutasi/cekmutasi');

		$mutasi = $this->cekmutasi->ovo()->mutation([
			'date'		=> [
				'from'	=> date('Y-m-d') . ' 00:00:00',
				'to'	=> date('Y-m-d') . ' 23:59:59'
			]
		]);

		print_r($mutasi);

		$list = $this->cekmutasi->ovo()->transferBankList(['source_number' => '085271486398']);
		print_r($list);
	}

	public function mutasiGoPay()
	{
		$this->load->library('Cekmutasi/cekmutasi');

		$mutasi = $this->cekmutasi->gopay()->mutation([
			'date'		=> [
				'from'	=> date('Y-m-d') . ' 00:00:00',
				'to'	=> date('Y-m-d') . ' 23:59:59'
			]
		]);

		print_r($mutasi);

		$list = $this->cekmutasi->gopay()->list();

		//echo json_encode($list);
		//print_r($list);
		$data = json_decode(json_encode($list), true);
        		//print_r($data['data']);
        		$step = json_encode($data['data']);
        		echo $step;
	}

	public function balance()
	{
		$this->load->library('Cekmutasi/cekmutasi');

		$balance = $this->cekmutasi->balance();

		print_r($balance);
	}

	public function handleCallback()
	{
		$this->load->library('Cekmutasi/cekmutasi');

		$ipn = $this->cekmutasi->catchIPN();

		print_r($ipn);
	}

	public function checkIP()
	{
		$this->load->library('Cekmutasi/cekmutasi');

		$ipn = $this->cekmutasi->checkIP();

		print_r($ipn);
	}
}