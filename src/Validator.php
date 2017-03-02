<?php

namespace Validator;

// use Validator\Interfaces\RuleInterface;
use Validator\Rules\Rule;

class Validator
{
    public $rules;
    public $errors;

    /**
     *
     * @param [Array] $rules [description]
     */
    function __construct(Rule $rules)
    {
        $this->rules = $rules;
    }

    /**
    * [ A function that makes a validation ]
    * @param  [Array] $fields [description]
    * @param  [Array] $rules  [description]
    * @return [Array]         [description]
    */
    public function make($fields, $rules)
    {
        # Validating each field by its rules
        foreach ($fields as $field => $value) {

            # Retrieves every field rules
            $field_rules = explode('|', $rules[$field]);

            # Verifying each field rules
            $this->errors[$field] = $this->rules->verify($field_rules, $value, $field);

            # Removes empty fields from Errors array
            if(is_null($this->errors[$field])) {
                unset($this->errors[$field]);
            }
        }

        # Returns errors (if exists).
        return $this->errors;
    }
}
