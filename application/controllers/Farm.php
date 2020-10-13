<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Farm extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('FarmGameModel');
	}

	/*
		index page
		@author Sandeep
		@param none
		@return none
	 */
	public function index()
	{
		$this->load->view('index');
	}

	/*
		serves initial page
		@author Sandeep
		@param none
		@return none
	 */
	public function start()
	{
		// reset Game Model
		$this->FarmGameModel->InitGame();
		$this->load->view('start');
	}

	/*
		serves playable UI page
		@author Sandeep
		@param none
		@return none
	 */
	public function play()
	{
		// play next turns till win or lose
		$gameInfo = $this->FarmGameModel->Play();
		$gameInfo = $this->FarmGameModel->GetGameInfo();
		$this->load->view('play', [
			'gameInfo' => $gameInfo,
		]);
	}
}
