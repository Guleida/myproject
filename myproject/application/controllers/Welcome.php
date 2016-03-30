<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome/header');
		$this->load->view('welcome/landing');
		$this->load->view('welcome/footer');
	}

	public function about()
	{
		$this->load->view('welcome/header');
		$this->load->view('welcome/landing');
		$this->load->view('welcome/footer');
	}

	public function contact()
	{
		$this->load->view('welcome/header');
		$this->load->view('welcome/landing');
		$this->load->view('welcome/footer');
	}

}