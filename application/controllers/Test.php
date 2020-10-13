<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Test extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
		$this->load->model('FarmGameModel');
		$this->load->model('EntityFactory');
	}

	/*
		Test case to check if Game model is initialized properly
		@author Sandeep
		@param none
		@return none
	 */
	public function index()
	{

		$this->FarmGameModel->InitGame();

		$game_array = $this->FarmGameModel->GetGameInfo(true);

		// Check after init, turns are 0						
		$expected_result = 0;
		$test = $game_array['turns'];
		$this->unit->run($test, $expected_result, 'init turns test');

		// Check after init, game over is 0						
		$expected_result = 0;
		$test = $game_array['gameOver'];
		$this->unit->run($test, $expected_result, 'gameOver test');

		// Check after init, winStatus is 0						
		$expected_result = 0;
		$test = $game_array['winStatus'];
		$this->unit->run($test, $expected_result, 'win test');

		echo $this->unit->report();
	}

	/*
		Test cases to check if checkDeadOrAlive function is working properly
		@author Sandeep
		@param none
		@return none
	 */
	public function deadOrAliveTest()
	{
		$testArry = [
			// $rule, $current_turn, $feedHistory, expected
			[5, 1, [], 1],
			[5, 2, [], 1],
			[5, 3, [], 1],
			[5, 4, [], 1],
			[5, 5, [], 0],

			// Test if he gets feeding
			[5, 5, [1], 1],
			[5, 10, [1], 0],
			[5, 9, [1], 1],
			[5, 10, [1, 5], 0],
			[5, 10, [1, 6], 1],

		];

		foreach ($testArry as $key => $t) {
			$obj = new Entity($t[0]);
			foreach ($t[2] as $key => $feed) {
				$obj->Feed($feed);
			}

			$obj->SetCurrentTurn($t[1]);

			$test = $obj->GetIsAlive(true);
			$this->unit->run($test, $t[3], 'dead or alive test ' . $key);
		}

		echo $this->unit->report();
	}

	/*
		Test cases to check if IsGameWon function is working properly
		@author Sandeep
		@param none
		@return none
	 */
	public function IsGameWonTest()
	{
		$entites_test_array = [
			[
				[    // Entity
					['name' => 'Farmer', 'alive' => 1],
					['name' => 'Cow', 'alive' => 1],
					['name' => 'Cow', 'alive' => 0],
					['name' => 'Bunny', 'alive' => 1],
					['name' => 'Bunny', 'alive' => 0],
					['name' => 'Bunny', 'alive' => 0],
					['name' => 'Bunny', 'alive' => 0],
				], 
				1,    // expected output, game won or not
				true, // AllMenAlive property of FarmGameModel
			],
			[
				[    // Entity
					['name' => 'Farmer', 'alive' => 1],
					['name' => 'Cow', 'alive' => 0],
					['name' => 'Cow', 'alive' => 1],
					['name' => 'Bunny', 'alive' => 0],
					['name' => 'Bunny', 'alive' => 0],
					['name' => 'Bunny', 'alive' => 0],
					['name' => 'Bunny', 'alive' => 1],
				], 
				1,    // expected output, game won or not
				true, // AllMenAlive property of FarmGameModel
			],
			[
				[    // Entity
					['name' => 'Farmer', 'alive' => 0],
					['name' => 'Cow', 'alive' => 1],
					['name' => 'Cow', 'alive' => 1],
					['name' => 'Bunny', 'alive' => 1],
					['name' => 'Bunny', 'alive' => 1],
					['name' => 'Bunny', 'alive' => 1],
					['name' => 'Bunny', 'alive' => 1],
				], 
				0,     // expected output, game won or not
				false, // AllMenAlive property of FarmGameModel
			],
			[
				[    // Entity
					['name' => 'Farmer', 'alive' => 1],
					['name' => 'Cow', 'alive' => 0],
					['name' => 'Cow', 'alive' => 0],
					['name' => 'Bunny', 'alive' => 1],
					['name' => 'Bunny', 'alive' => 1],
					['name' => 'Bunny', 'alive' => 1],
					['name' => 'Bunny', 'alive' => 1],
				], 
				0,    // expected output, game won or not
				true, // AllMenAlive property of FarmGameModel
			],
			[
				[    // Entity
					['name' => 'Farmer', 'alive' => 1],
					['name' => 'Cow', 'alive' => 1],
					['name' => 'Cow', 'alive' => 1],
					['name' => 'Bunny', 'alive' => 0],
					['name' => 'Bunny', 'alive' => 0],
					['name' => 'Bunny', 'alive' => 0],
					['name' => 'Bunny', 'alive' => 0],
				], 
				0,    // expected output, game won or not
				true, // AllMenAlive property of FarmGameModel
			],
		];

		foreach ($entites_test_array as $key => $testCase) {
			$entities = [];
			foreach ($testCase[0] as $ent) {
				$obj = $this->EntityFactory->GetEntity($ent['name'], $ent['name']);
				if($ent['alive'] == 0)
					$obj->Kill();
				$entities[] = $obj;
			}
			$test = $this->FarmGameModel->IsGameWon($entities);
			$expected_result = $testCase[1];
			$this->unit->run($test, $expected_result, 'IsGameWonTest ' . $key);

			$test = $this->FarmGameModel->AllMenAlive;
			$expected_result = $testCase[2];
			$this->unit->run($test, $expected_result, 'IsGameWonTest AllMenAlive ' . $key);
		}

		echo $this->unit->report();
	}

}

?>