<?php

namespace Rakit\Validation\Rules;
use Rakit\Validation\Rule;

class Greater extends Rule {
	/** @var string */
	protected $message = "The :attribute not accepted";

	/**
     * Given $params and assign $this->params
     *
     * @param array $params
     * @return self
     */
	public function fillParameters(array $params): Rule
    {
        $this->params['fields'] = $params;
        return $this;
    }

	/**
	 * Check the $value is accepted
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function check($value): bool
	{
		if (!is_numeric($value))
			return false;

		$this->requireParameters(['fields']);
        $fields = $this->parameter('fields');

        $max = -INF;
        foreach ($fields as $field) {
        	$val = (float) $this->getAttribute()->getValue($field);
        	if ($val > $max)
        		$max = $val;
        }

		return $value > $max;
	}
}