<?php
namespace Buoy;

interface Feature_Validator {
	/**
	 * Validate the feature
	 * 
	 * @param string $feature The feature to validate
	 * @param array $args The arguments to validate
	 * @return bool
	 */
	public function validate(string $feature, array $args = []): bool;
}