<?php

require_once 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Validator\Validator;
use Validator\Rules\Rule;
# Regras funcionando :
# max
# min
# required
# cpf
# CNPJ
#

$input = [
    'nome'      => '99s ss9',
    'email'     => 'email@email.com',
    'cpf'       => '23425813862',
    'cnpj'      => '27.516.183/0001-59',
    'endereco'  => 'Endereço de teste'
];

$rules = [
    'nome'      => 'letter_only',
    'email'     => 'required|email',
    'cpf'       => 'required|cpf|number',
    'cnpj'      => 'required|cnpj|number',
    'endereco'  => ''
];

$validator  =  new Validator(new Rule());
$result     =  $validator->make($input, $rules);

echo "Resultado Da validação: ";

echo "<pre>";
print_r($result);
echo "<pre>";

exit;
