<?php

namespace Rakit\Validation\Rules;
use Rakit\Validation\Rule;

class Lists extends Rule {
	/** @var string */
	protected $message = "The :attribute not accepted";

	/**
	 * Check the $value is accepted
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function check($value): bool
	{
		return preg_match('/^\d+(,\d+)*$/u', $value) > 0;
	}
}