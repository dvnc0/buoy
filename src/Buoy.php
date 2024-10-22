<?php
declare(strict_types=1);

namespace Buoy;

use Buoy\Exceptions\Buoy_Exception;

class Buoy {
	/**
	 * The instance of Buoy
	 *
	 * @var Buoy
	 */
	protected static $instance;

	/**
	 * The settings of Buoy
	 *
	 * @var array
	 */
	protected array $settings;

	/**
	 * Prevent the instance from being cloned
	 * 
	 * @return void
	 */
	protected function __clone(): void { }

	/**
	 * Prevent from being unserialized
	 * 
	 * @return void
	 */
	public function __wakeup(): void {
		throw new Buoy_Exception('Cannot unserialize Buoy instance.');
	}

	/**
	 * Prevent from being constructed
	 * 
	 * @param array $settings The settings of Buoy
	 */
	protected function __construct(array $settings = []) {
		$this->settings = $settings;
	}

	/**
	 * Get the instance of Buoy
	 * 
	 * @param array $settings The settings of Buoy
	 * @return Buoy
	 */
	public static function init(array $settings): Buoy {
		if (NULL === static::$instance) {
			static::$instance = new self($settings);
		}
		return static::$instance;
	}

	/**
	 * Register a feature
	 *
	 * @param string                $feature_key       The key of the feature
	 * @param callable|array|object $feature_validator The validator of the feature
	 * @return void
	 */
	public static function register(string $feature_key, callable|array|object $feature_validator): void {
		if (NULL === static::$instance) {
			throw new Buoy_Exception('Buoy instance not initialized.');
		}

		static::$instance->settings['features'][$feature_key] = $feature_validator;
	}

	/**
	 * Get the instance of Buoy
	 *
	 * @return Buoy
	 */
	public static function can(): Buoy {
		if (NULL === static::$instance) {
			throw new Buoy_Exception('Buoy instance not initialized.');
		}
		return static::$instance;
	}

	/**
	 * Random split
	 *
	 * @param int $probability The probability of the split
	 * @return bool
	 */
	public static function lotto(int $probability): bool {
		mt_srand(time());
		return (mt_rand(0, 1) * 100) <= $probability;
	}

	/**
	 * Access the feature
	 *
	 * @param string $feature_key_name The key of the feature
	 * @param array  $args             The arguments needed for the validator if any
	 * @return bool
	 */
	public function access(string $feature_key_name, array $args): bool {
		$feature = $this->settings['features'][$feature_key_name] ?? NULL;

		if (NULL === $feature) {
			throw new Buoy_Exception("Feature {$feature_key_name} not found.");
		}

		if ($feature instanceof Feature_Validator) {
			return $feature->validate($feature_key_name, $args);
		}

		if (is_callable($feature)) {
			return $feature(...$args);
		}

		if (is_object($feature) && method_exists($feature, '__invoke')) {
			return $feature(...$args);
		}

		throw new Buoy_Exception("Feature {$feature_key_name} is not callable.");
	}
}