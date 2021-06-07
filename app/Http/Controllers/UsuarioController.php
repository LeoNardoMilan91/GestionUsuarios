<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\Perfil;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario=Usuario::orderBy('nomusu')->orderBy('localidad')->paginate(5);
        return view('usuarios.index', compact('usuario'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $misPerfiles=Perfil::getArrayIdNombre();
        return view('usuarios.create', compact('misPerfiles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //1.- Validamos
        $request->validate([
            'nombre'=>['required','string', 'min:3','max:50','unique:usuarios,nombre'],
            'localidad'=>['required','string','min:3','max:50'],
            'email'=>['required', 'string','min:5','max:60','unique:usuarios,email'],
            'perfil_id'=>['required']
        ]);
        //2.- Procesador
        try{
            Usuario::create($request->all());
        }catch(\Exception $ex){
            return redirect()->route('usuarios.index')->with("mensaje", "Error con la BBDD".$ex->getMessage());
        }
        return redirect()->route('usuarios.index')->with("mensaje", "Usuario creado");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        $perfiles=Perfil::getArrayIdNombre();
        return view('usuarios.mostrar', compact('usuario','perfiles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario)
    {
        $misPerfils=Perfil::getArrayIdNombre();
        return view('usuarios.edit',compact('misPerfils','usuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
        //1.- Validamos
        $request->validate([
            'nomusu'=>['required','string', 'min:3','max:50'],
            'localidad'=>['required','string','min:3','max:50'],
            'email'=>['required','string','min:5','max:60','unique:usuarios,email,'.$usuario->id],
        ]);
        //2.- Procesador
        try{
            $usuario->update($request->all());
        }catch(\Exception $ex){
            return redirect()->route('usuarios.index')->with("mensaje", "Error con la BBDD".$ex->getMessage());
        }
        return redirect()->route('usuarios.index')->with("mensaje", "Usuario actualizado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        try{
            $usuario->delete();
        }catch(\Exception $ex){
            return redirect()->route('usuarios.index')->with("mensaje", "Error con la BBDD");
        }
        return redirect()->route('usuarios.index')->with("mensaje","Usuario Borrado");
    }
}
