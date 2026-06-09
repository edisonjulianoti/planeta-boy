<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cpf implements ValidationRule
{
    /**
     * Valida CPF brasileiro com dígitos verificadores.
     */
    public function validate(string , mixed , Closure ): void
    {
         = preg_replace('/\D/', '', );

        if (strlen() !== 11) {
            ('O CPF deve ter 11 dígitos.');
            return;
        }

        // Rejeitar sequências iguais (111.111.111-11, etc.)
        if (preg_match('/^(\d)\1{10}$/', )) {
            ('O CPF informado é inválido.');
            return;
        }

        // Validar 1º dígito verificador
         = 0;
        for ( = 0;  < 9; ++) {
             += (int) [] * (10 - );
        }
         =  % 11;
         =  < 2 ? 0 : 11 - ;

        if ((int) [9] !== ) {
            ('O CPF informado é inválido.');
            return;
        }

        // Validar 2º dígito verificador
         = 0;
        for ( = 0;  < 10; ++) {
             += (int) [] * (11 - );
        }
         =  % 11;
         =  < 2 ? 0 : 11 - ;

        if ((int) [10] !== ) {
            ('O CPF informado é inválido.');
        }
    }
}

