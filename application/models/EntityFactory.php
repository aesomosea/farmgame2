<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Farmer.php');
require_once('Cow.php');
require_once('Bunny.php');

class EntityFactory {

	public function GetEntity($type, $name)
	{
		if($name == null) {
			return null;
		}

		if(strtolower($type) == 'farmer') {
			return new Farmer($name);
		} else if(strtolower($type) == 'cow') {
			return new Cow($name);
		} else if(strtolower($type) == 'bunny') {
			return new Bunny($name);
		} else {
			throw new Exception("Invalid Entity type requested!");
		}
	}
}