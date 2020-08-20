<?php
/**
 * Class Usage.
 *
 * @package AmpProject\AmpWP
 */

namespace AmpProject\AmpWP\Documentation\Model;

/**
 * Documentation reference object representing the usage of a function or
 * method.
 *
 * @property Function[] $functions
 * @property Method[]   $methods
 */
final class Usage {

	use LeafConstruction;

	/**
	 * Get an associative array of known keys.
	 *
	 * @return string[]
	 */
	protected function get_known_keys() {
		return [
			'functions',
			'methods',
		];
	}

	/**
	 * Process the functions entry.
	 *
	 * @param array $value Array of function entries.
	 */
	private function process_functions( $value ) {
		$this->functions = [];

		foreach ( $value as $function ) {
			$this->functions[ $function[ 'name' ] ] = new Function_( $value, $this );
		}
	}

	/**
	 * Process the methods entry.
	 *
	 * @param array $value Array of method entries.
	 */
	private function process_methods( $value ) {
		$this->methods = [];

		foreach ( $value as $method ) {
			$this->methods[ $method[ 'name' ] ] = new Method( $value, $this );
		}
	}
}
