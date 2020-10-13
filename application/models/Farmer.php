<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Entity.php');

class Farmer extends Entity {
	private $Name;                  // Name of farmer
	private $EntityType;            // Human, Sheep, Cow, Bunny etc

	/*
		Constructor sets name, EntityType, rule
		@author Sandeep
		@param name
		@return none
	 */
	public function __construct($name)
	{
		// set the rule
		parent::__construct(15);

		$this->Name = $name;
		$this->EntityType = "Farmer";
	}

	/*
		Returns Name
		@author Sandeep
		@param none
		@return name
	 */
	public function GetName()
	{
		return $this->Name;
	}

	/*
		Returns EntityType
		@author Sandeep
		@param none
		@return EntityType
	 */
	public function GetEntityType()
	{
		return $this->EntityType;
	}

}
