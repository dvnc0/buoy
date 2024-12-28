<?php
namespace Buoy;

interface Feature_Validator {
	/**
	 * Validate the feature
	 * 
	 * @return bool
	 */
	public function validate(): bool;
}