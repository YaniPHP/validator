<?php

namespace Validator\Rules;

/**
* PRECISA SER REFATORADA...
*/
class CpfRule
{
        /**
        * [__construct description]
        * @param [type] args [description]
        */
        function __construct() { }

        /**
        * [Digito_verificador description]
        * @param  [type] $numero      [description]
        * @param  [type] $verificador [description]
        * @return [type]              [description]
        */
        private function confere_digito($numero, $verificador)
        {
            return ($numero * 10 % 11) == $verificador;
        }

        /**
        * [calcula os dígitos do cpf conforme
        * a tabela de multiplicadores]
        * @param  [Array] $digitos
        * @param  [integer] $multiplicador
        * @return [integer]              [resultado do cálculo]
        */
        function calcula($digitos, $multiplicador)
        {
            $num_calculado      = 0;
            foreach($digitos as $key => $value) {
                if($multiplicador >= 2 ) {
                    $num_calculado += ($value * $multiplicador );
                    $multiplicador--;
                }
            }

            # Retorna o resultado do cálculo
            return $num_calculado;
        }

        /**
        * [Recebe o cpf e verifica se é válido ou não ]
        * @param  [String]   $cpf
        * @return [boolean]
        */
        public function match($cpf)
        {
            $cpf = $this->formata($cpf);

            # Se Não for número Retorna falso
            if(!is_numeric($cpf)) {
                return false;
            }

            # Verifica se os 11 dígitos são o mesmo número
            # se sim retorna imediatamente falso
            if (substr_count($cpf, $cpf[0]) == 11) {
                return false;
            }

            $multiplicador  = 10;
            $calculado      = 0;
            $retorno        = false;

            # Pega os 2 últimos dígitos
            $d_verificador  = substr($cpf, 9);
            # Transforma a string em array para ser percorrida
            $cpf            = str_split($cpf);
            # Percorre os dígitos do CPF 2 vezes (pois existem 2 dígitos verificadores)
            for ($i=0; $i < count($d_verificador); $i++) {
                #Faz o Cálculo
                $calculado = $this->calcula($cpf, $multiplicador+$i);
                #Verifica se o cálculo é compatível com o dígito verificador
                if($this->confere_digito($calculado, $d_verificador[$i] )) {
                    $retorno = true;
                }
            }

            # Retorna um boolean
            return $retorno;
        }

        /**
        * [formata description]
        * @param  [type] $string [description]
        * @return [type]         [description]
        */
        private  function formata($string) {
            return preg_replace('/-|\.|\//', '', $string);
        }
    }
