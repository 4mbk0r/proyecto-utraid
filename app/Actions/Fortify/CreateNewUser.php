<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make(
            $input,
            [
                'nombre' => ['required', 'string', 'max:255'],
                'ap_paterno' => ['required', 'string', 'max:255'],
                'ap_materno' => ['required', 'string', 'max:255'],
                'ci' => ['required', 'string', 'max:255', 'unique:users'],
                'cargo' => ['required', 'string', 'max:255'],
                'expedido' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'password' => $this->passwordRules(),
                'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
            ],
            [
                'username.unique' => 'Ya existe usuario con estos datos',
                'ci.unique' => 'Ya existe usuario con esta cedula de identidad datos',
                'nombre.required' => 'Se requiere nombre',
                'email.unique' => 'Email ya existe use otro',
                'email.required' => 'Se requiere email',
                'cargo.required' => 'Se require cargo',
                'expedido.required' => 'Se requiere Deparatamento de expedicion',
                'password.required' => 'Se require contraseña'
            ]
        )->validate();

        try {
            $reps = User::create([
                'nombre' => $input['nombre'],
                'ap_paterno' => $input['ap_paterno'],
                'ap_materno' => $input['ap_materno'],
                'ci' => $input['ci'],
                'cargo' => $input['cargo'],
                'expedido' => $input['expedido'],
                'email' => $input['email'],
                'celular' => $input['celular'],
                'username' => $input['username'],
                'password' => Hash::make($input['password']),
            ]);
            if($reps){
                $dato = [
                    'id_usuario' => $input['ci'],
                    'id_establecimiento' => $input['establecimiento'],
                ];
                DB::table('contratos')->insert($dato);
            }
            return $reps;
        } catch (QueryExecuted $e) {

            return $e;
        }
    }
}
