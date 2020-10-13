<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entity {

	private $Alive;               // 0:Dead 1: Alive
	private $Rule;                // no of turns during which should be fed once at least
	private $CurrentTurn;         // current turn
	private $FeedHistory = [];    // feed history, array of turns on which it was fed

	public function __construct(int $rule)
	{
		$this->Alive = 1;
		$this->Rule = $rule;
	}

	/*
		Sets the CurrentTurn property with some validations
		@author Sandeep
		@param none
		@return none
	 */
	public function SetCurrentTurn(int $turn)
	{
		if(empty($this->FeedHistory))
			$this->CurrentTurn = $turn;
		else {
			$lastFed = end($this->FeedHistory);
			if($lastFed <= $turn) 
				$this->CurrentTurn = $turn;
			else 
				throw new Exception("Error while setting turn");
		}
	}

	/*
		Calculate and sets the Alive property and retrieves Alive property
		@author Sandeep
		@param none
		@return 0 or 1
	 */
	public function GetIsAlive(bool $check=false)
	{
		// check only if alive
		if($check && $this->Alive == 1) { 
			$this->checkDeadOrAlive();
		}

		return $this->Alive;
	}

	/*
		Retrieves FeedHistory property
		@author Sandeep
		@param none
		@return array
	 */
	public function GetFeedHistory()
	{
		return $this->FeedHistory;
	}

	/*
		Feed
		@author Sandeep
		@param current turn
		@return none
	 */
	public function Feed(int $turn)
	{
		$this->FeedHistory[] = $turn;
		$this->CurrentTurn = $turn;
	}

	/*
		Kill entity
		@author Sandeep
		@param current turn
		@return none
	 */
	public function Kill()
	{
		$this->Alive = 0;
	}


	/*
		Calculate and sets the Alive property
		@author Sandeep
		@param none
		@return none
	 */
	private function checkDeadOrAlive()
	{
		// To make sure, is alive is checked at every specified rule
		if($this->CurrentTurn == 0 || $this->CurrentTurn % $this->Rule != 0) 
			return;

		// if not yet fed till current turn = rule, must be dead
		if(empty($this->FeedHistory)) {
			$this->Alive = 0;
			return;
		}

		// check if fed in time, means before rule runs out
		$lastFed = end($this->FeedHistory);

		if($lastFed > ($this->CurrentTurn - $this->Rule))
			$this->Alive = 1;
		else
			$this->Alive = 0;
	}
}
