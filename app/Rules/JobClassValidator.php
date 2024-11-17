<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class JobClassValidator implements ValidationRule
{
    protected array $validJobsList;

    public function __construct(array $validJobsList)
    {
        $this->validJobsList = $validJobsList;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $value['className'])) {
            $fail('Wrong Job class name format', 101);
        }
        if (!class_exists($value['classNameWithNameSpace'])) {
            $fail('Wrong Job class', 102);
        }
        if (!in_array($value['className'], $this->validJobsList, true)) {
            $fail('Restricted Job', 103);
        }
    }
}
