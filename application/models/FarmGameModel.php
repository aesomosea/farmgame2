<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FarmGameModel extends CI_Model {

	public $GameModel;               // All Game Model 
	public $CowCount = 2;            // Total Cow count in game
	public $BunnyCount = 4;          // Total Bunny count in game
	public $TotalTurns = 50;         // Maximum turns allowed
	public $AllMenAlive = false;     // Property if all men are alive


	public function __construct()
	{
		parent::__construct();
		$this->load->model('EntityFactory');
	}
	/*
		Initialize game model
		@author Sandeep
		@param none
		@return none
	 */
	public function InitGame()
	{
		$this->GameModel = array(
			'turns'                    => 0,
			'gameOver'                 => 0,
			'winStatus'                => 0,
			'entities' => []
		);


		$entities = [];
		$entities[] = $this->EntityFactory->GetEntity('Farmer', 'Farmer 1');


		// add cows
		for($i = 0; $i < $this->CowCount; $i++)
			$entities[] = $this->EntityFactory->GetEntity('Cow', 'Cow ' . ($i + 1));

		// add bunnies
		for($i = 0; $i < $this->BunnyCount; $i++)
			$entities[] = $this->EntityFactory->GetEntity('Bunny', 'Bunny ' . ($i + 1));

		$this->GameModel['entities'] = $entities;

		$this->SaveGameModel();
	}

	/*
		play next turn
			feed random entity, check if any entity dead, decide if game is over
		@author Sandeep
		@param none
		@return none
	 */
	public function Play()
	{
		$this->GetGameModel();
		if($this->GameModel['gameOver'] == 0) {
			$current_turn = ++$this->GameModel['turns'];
			$ent_array = [];


			// get entities 
			foreach ($this->GameModel['entities'] as $key => $entity) {
				if($entity->GetIsAlive() == 1) {
					$ent_array[] = $key;
				}
			}

			// feed
			$r = mt_rand(0, count($ent_array) - 1);
			$i = $ent_array[$r];
			$this->GameModel['entities'][$i]->Feed($current_turn);

			// check if anyone dead
			for($i = 0; $i < count($this->GameModel['entities']); $i++) {
				$entity = $this->GameModel['entities'][$i];
				$entity->SetCurrentTurn($current_turn);
				$entity->GetIsAlive(true);
			}

			// check if game is over
			$this->IsGameOver();

			// save game
			$this->SaveGameModel();
		}
	}


	/*
		decide if game is over or not also decides who won
		@author Sandeep
		@param none 
		@return 0 = game is not over, 1 = game is over
	 */
	public function IsGameOver()
	{
		$result = $this->IsGameWon($this->GameModel['entities']);

		if($this->GameModel['turns'] == $this->TotalTurns) {
			$this->GameModel['gameOver'] = 1;
			$this->GameModel['winStatus'] = $result;
		} else if(!$this->AllMenAlive) {
			$this->GameModel['gameOver'] = 1;
			$this->GameModel['winStatus'] = 0;

			foreach ($this->GameModel['entities'] as $key => $obj) {
				$this->GameModel['entities'][$key]->Kill();
			}
		}

		return $this->GameModel['gameOver'];
	}

	/*
		decide if game is won or not, also sets if all men are alive or not
		@author Sandeep
		@param entities (array) array of entities
		@return 0 = game is not won, 1 = game is won
	 */
	public function IsGameWon($entities)
	{
		$winCondition = [
			'Farmer' => ['neededCount' => 1, 'actualCount' => []],
			'Cow' => ['neededCount' => 1, 'actualCount' => []],
			'Bunny' => ['neededCount' => 1, 'actualCount' => []]
		];

		foreach ($entities as $key => $obj) {
			if($obj->GetIsAlive() == 1) {
				$winCondition[$obj->GetEntityType()]['actualCount'][] = 1;
			}
		}

		// check if needed men count is alive
		$this->AllMenAlive = $winCondition['Farmer']['neededCount'] <= count($winCondition['Farmer']['actualCount']);

		$won = true;
		foreach ($winCondition as $key => $ent) {
			if(count($ent['actualCount']) < $ent['neededCount'])
				$won = false;
		}
		return $won;
	}

	/*
		Get Game Model from session into class property
		@author Sandeep
		@param none
		@return none
	 */
	private function GetGameModel()
	{
		$this->GameModel = $_SESSION['GameModel'];
		$entities = [];
		foreach ($this->GameModel['entities'] as $key => $entity) {
			$entities[] = unserialize(serialize( $entity) );
		}
		$this->GameModel['entities'] = $entities;
	}

	/*checkDeadOrAlive
		Saves Game Model in session from class property
		@author Sandeep
		@param none
		@return none
	 */

	private function SaveGameModel()
	{
		// $entities = [];
		// foreach ($this->GameModel['entities'] as $key => $entity) {
		// 	$entities[] = serialize($entity);
		// }
		// $this->GameModel['entities'] = $entities;

		$_SESSION['GameModel'] = $this->GameModel;
		// echo "<pre>";print_r($this->GameModel);die("->stoppp");
	}

	/*
		returns Game Model
		@author Sandeep
		@param none
		@return Game model
	 */
	public function GetGameInfo()
	{
		$this->GetGameModel();

		$entities = [];
		foreach ($this->GameModel['entities'] as $key => $obj) {
			$entity = [
				'name'         => $obj->GetName(),
				'alive'        => $obj->GetIsAlive(),
				'EntityType'   => $obj->GetEntityType(),
				'feedHistory'  => $obj->GetFeedHistory(),
			];
// echo "<pre>";print_r($entity);die("->stoppp");
			$entities[] = $entity;		
		}

		$this->GameModel['entities'] = $entities;
		return $this->GameModel;
	}
}

?>