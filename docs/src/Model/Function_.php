<?php
/**
 * Class Function_.
 *
 * @package AmpProject\AmpWP
 */

namespace AmpProject\AmpWP\Documentation\Model;

/**
 * Documentation reference object representing a function.
 *
 * @property string     $name
 * @property string     $namespace
 * @property Alias_[]   $aliases
 * @property int        $line
 * @property int        $end_line
 * @property Argument[] $arguments
 * @property DocBlock   $doc
 * @property Hook[]     $hooks
 * @property Usage[]    $uses
 */
final class Function_ implements Leaf {

	use LeafConstruction;

	/**
	 * Get an associative array of known keys.
	 *
	 * @return string[]
	 */
	protected function get_known_keys() {
		return [
			'name',
			'namespace',
			//'aliases',
			'line',
			'end_line',
			'arguments',
			'doc',
			'hooks',
			'uses',
		];
	}

	/**
	 * Process the arguments entry.
	 *
	 * @param array $value Array of argument entries.
	 */
	private function process_arguments( $value ) {
		$this->arguments = [];

		foreach ( $value as $argument ) {
			$this->arguments[ $argument[ 'name' ] ] = new Argument( $argument, $this );
		}
	}

	/**
	 * Process a doc-block entry.
	 *
	 * @param array $value Associative array of the doc-block.
	 */
	private function process_doc( $value ) {
		$this->doc = new DocBlock( $value, $this );
	}

	/**
	 * Process the hooks entry.
	 *
	 * @param array $value Array of hook entries.
	 */
	private function process_hooks( $value ) {
		$this->hooks = [];

		foreach ( $value as $hook ) {
			$this->hooks[ $hook[ 'name' ] ] = new Hook( $hook, $this );
		}
	}

	/**
	 * Process the uses entry.
	 *
	 * @param array $value Array of usage entries.
	 */
	private function process_uses( $value ) {
		$this->uses = [];

		foreach ( $value as $use ) {
			$this->uses[ $use[ 'name' ] ] = new Usage( $use, $this );
		}
	}
}
