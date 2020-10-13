<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Entity.php');

class Bunny extends Entity {
	private $Name;                  // Name of Bunny
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
		parent::__construct(8);

		$this->Name = $name;
		$this->EntityType = "Bunny";
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
