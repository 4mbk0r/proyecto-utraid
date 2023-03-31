<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $paciente =  $request['paciente'];
        User::find($paciente['id'])->update($request['paciente']);
        return $request['paciente'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public static function change_password(Request $request)
    {

        $password = $request['password'];
        $paciente = $request['paciente'];
        

        //return $request['password'];
        
        
        $r = strval(auth()->user()->id) == $paciente['id'];
        User::find($paciente['id'])->update(['password' => Hash::make($password['password'])]);
         
        $res = [
            'redireccionar' => $r,

        ];

        return $res;
    }
    public static function default_password(Request $request)
    {

        $password = $request['password'];
        $paciente = $request['paciente'];  
        //return $request['password'];


        User::find($paciente['id'])->update(['password' => Hash::make($password['password'])]);

        return redirect()->back()->with('success', 'La contraseña se ha cambiado correctamente');
    }
    

    
}
