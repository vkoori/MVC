<?php

namespace Rakit\Validation\Rules;
use Rakit\Validation\Rule;
use kernel\plugins\common;

class Phone extends Rule {
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
		$value = (new common)->convert_nums($value);
		return preg_match("/^0[1-9][0-9]{9}$/", $value) > 0;
	}
}