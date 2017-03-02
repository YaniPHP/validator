<?php

namespace Validator\Rules;

/**
* PRECISA SER REFATORADA...
*/
class CnpjRule
{
        /**
        * [__construct description]
        * @param [type] args [description]
        */
        function __construct() { }

        /**
        * [formata description]
        * @param  [type] $string [description]
        * @return [type]         [description]
        */
        private  function formata($string) {
            return preg_replace('/-|\.|\//', '', $string);
        }

        /**
        * [Faz a validação de um CNPJ conforme através
        *  das regras de validação]
        * @param  [type] $cnpj [description]
        * @return [type]       [description]
        */
        public function match($cnpj)
        {
            $cnpj = $this->formata($cnpj);

            # Se for numerico retorna falso
            if(!is_numeric($cnpj)) {
                return false;
            }

            # Verifica se os 14 dígitos são o mesmo número
            # se sim retorna imediatamente falso
            if (substr_count($cnpj, $cnpj[0]) == 14) {
                return false;
            }

            # Tabela de MULTICADORES
            $multiplicadores  = [5,4,3,2,9,8,7,6,5,4,3,2];
            #Dígitos se o Dígito verificador
            $digitos          = substr($cnpj, 0, 12);
            # Converte em Array
            $digitos          = str_split($digitos);
            # Recupera os dígitos verificadores
            $dVerificador     = substr($cnpj, 12);

            # Percorre os dígitos do cnpj 2 vezes
            for($i = 0; $i<2; $i++) {

                # Cria a outra tabela de modificadores para o segundo cálculo
                if($i > 0) {
                    $multiplicadores  = [6,5,4,3,2,9,8,7,6,5,4,3,2];

                    # Retorno falso se o resultado do cálculo não for igual ao dígito verificado
                    if(!$verificador) {
                        return false;
                    }
                }

                # Faz o Calculo dos dígitos do cnpj
                $num_calculado    = $this->_calcula($digitos, $multiplicadores);
                # verifica se o resultado do cálculo é compatível com o dígito verificador
                $verificador      = $this->confere_digito_cnpj($num_calculado, $dVerificador[$i]);
                # Acrescenta o dígito verificador para fazer parte do próximo cálculo
                $digitos[] = $verificador;
            }

            # Retorno verdadeiro se os cálculos derem certo
            return true;;
        }

        /**
        * [Verifica se o número passado confere com o dígito verificado]
        * @param  [integer]   $numero      [numero a ser comparado]
        * @param  [int]       $verificador [Dígito verificador]
        * @return [boolean]
        */
        private function confere_digito_cnpj($numero, $verificador)
        {
            # Subrai o valor passado em 11, mas se for menor que 2 coloca zero
            $numero = ($numero % 11) < 2 ? 0 : (11 - $numero);
            # Retorna true se o número passador for igual ao dígito verificador
            return ($numero == $verificador);
        }

        /**
        * [calcula os dígitos do cnpj conforme
        * a tabela de multiplicadores]
        * @param  [Array] $digitos
        * @param  [Array] $multiplicador
        * @return [integer] [retorna o resto da divisão
        * do resultado do cálculo, por 11 ]
        */
        private function _calcula($digitos, $multiplicadores)
        {
            # Inicializa o cálculo
            $num_calculado = 0;
            # Inicializa o contador
            $i             = 0;

            # Percorre os dígitos do cnpj multiplicando-os por cada valor da
            # tabela de multiplicadores e depois soma tudo
            foreach($digitos as $key => $value) {
                $num_calculado += ($value * $multiplicadores[$i] );
                $i++;
            }

            # Divide o resultado do cálculo por 11 e retorna o resto
            return $num_calculado % 11;
        }
    }
