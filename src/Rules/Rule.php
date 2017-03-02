<?php

namespace Validator\Rules;

class Rule
{
    public $rules;
    public $errors;

    /**
    * [__construct description]
    */
    public function __construct(){ }

    /**
    * [Verify description]
    * @param  [Array] $rules [description]
    * @param  [Array] $value [description]
    * @return [type]        [description]
    */
    public function verify($rules, $value, $field)
    {
        # 
        foreach ($rules as $rule) {
            if(!$retult = $this->match($rule, $value, $field)) {
                # returns an error messagem
                return $this->errors[$field];
            }
        }

        return null;
    }

    /**
    * [numeric description]
    * @param  [String] $value [description]
    * @return [String]        [description]
    */
    private function numeric($value)
    {
        # Depois ajustar esse regex
        return (preg_match('/\D\/\./', $value) == null);
    }

    /**
    * [letter_only description]
    * @param  [type] $value [description]
    * @return [type]        [description]
    */
    private function letter_only($value)
    {
        # Depois ajustar esse regex
        return preg_match('/\d\/\./', $value);
    }

    /**
    * [extractNumber description]
    * @param  [type] $rule [description]
    * @return [type]       [description]
    */
    private function extractNumber($rule)
    {
        return preg_replace('/\D/', '', $rule);
    }

    /**
    * [ValidateMax description]
    * @param  [type] $rule  [description]
    * @param  [type] $value [description]
    * @return [type]        [description]
    *
    */
    private function min($rule, $value)
    {
        # Get the integer from rule string
        $rule_max = $this->extractNumber($rule);
        # returns true if the recieved countains  <= than its rule permites
        return (strlen($value) >= $rule_max);
    }

    /**
    * [ValidateMax description]
    * @param  [type] $rule  [description]
    * @param  [type] $value [description]
    * @return [type]        [description]
    *
    */
    private function max($rule, $value)
    {
        # Get the integer from rule string
        $rule_max = $this->extractNumber($rule);
        # returns true if the recieved countains  <= than its rule permites
        return (strlen($value) <= $rule_max);
    }

    /**
    * [isEmail description]
    * @return boolean [description]
    */
    private function isEmail($value)
    {
        #  valida email por regex
        $regex = "//";
        return preg_match($regex, $value);
    }

    /**
    * [cpf description]
    * @param  [String] $value
    * @return [Boolean]
    */
    public function cpf($value)
    {
        return (new CpfRule)->match($value);
    }

    /**
    * [cnpj description]
    * @param  [type] $value [description]
    * @return [type]        [description]
    */
    public function cnpj($value)
    {
        return (new CnpjRule)->match($value);
    }

    public function unspaced($value)
    {
        return !(substr_count($value,' '));
    }

    /**
    * [Compares if two params have the same value, and returns
    * a boolean acording to compararion]
    * @param  [String] $firstValue
    * @param  [String] $secondValue
    * @return [Boolean]
    */
    public function same($firstValue, $secondValue)
    {
        return ($firstValue === $secondValue);
    }

    /**
    * [Validates/Verifies if a string matches with a rule]
    * @param  [String]  $rule  [A string of a single rule]
    * @param  [String]  $value [A String with the value to be validated]
    * @return boolean  [Returns true/false, depending on validation result]
    */
    public function match($rule, $value, $field)
    {
        $_return = true;

        $max = (preg_match('/max/', $rule)) ? 'max['.$this->extractNumber($rule).']' : null;
        $min = (preg_match('/min/', $rule)) ? 'min['.$this->extractNumber($rule).']' : null;

        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $_return = false;
                    $this->errors[$field] = 'Este campo é obrigatório.';
                }
                break;

            case 'letter_only':
                if (!$this->letter_only($value)) {
                    $this->errors[$field] = 'Por favor, digite apenas letras.';
                    $_return = false;
                }
                break;

            case 'number':
                if (!$this->numeric($value)) {
                    $this->errors[$field] = 'Por favor, digite apenas números.';
                    $_return = false;
                }
                break;

            case 'email':
                if(!$this->isEmail($value)) {
                    $this->errors[$field] = 'Este não é um email válido. por favor, digite corretamente.';
                    $_return = false;
                }
                break;

            case 'cpf':
                if(!$this->cpf($value)) {
                    $this->errors[$field] = 'CPF inválido...';
                    $_return = false;
                }
                break;

            case 'cnpj':
                if(!$this->cnpj($value)) {
                    $this->errors[$field] = 'CNPJ inválido...';
                    $_return = false;
                }
                break;

            case $min:
                if(!$this->min($rule, $value)) {
                    $this->errors[$field] = sprintf('Esse campo precisa ter pelo menos %s cacteres. Atualmente possui apenas %d',
                    $this->extractNumber($rule), strlen($value));

                    $_return = false;
                }
                break;

            case 'unspaced':
                if(!$this->unspaced($value)) {
                    $this->errors[$field] = 'Esse campo não pode contér espaços. por favor, digite corretamente.';
                    $_return = false;
                }
                break;

            case $max:
                if(!$this->max($rule, $value)) {
                    $this->errors[$field] = sprintf('Esse campo aceita até %d caracteres, mas foi passado %d',
                    $this->extractNumber($rule), strlen($value));
                    $_return = false;
                }
                break;

            default:
                $_return = true;
                break;
        }

        return $_return;
    }
}
