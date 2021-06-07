<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perfil=Perfil::orderBy('nombre')->paginate(5);
        return view('perfiles.index', compact('perfil'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('perfiles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //1.-Validamos
        $request->validate([
            'nombre'=>['required','string','min:3','max:50','unique:perfils,nombre'],
            'descripcion'=>['required','string','min:10','max:200']
        ]);
        //2.- Procesador
        try{
            Perfil::create($request->all());
        }catch(\Exception $ex){
            return redirect()->route('perfiles.index')->with("mensaje","Error con la BBDD".$ex->getMessage());
        }
        return redirect()->route('perfiles.index')->with("mensaje","Perfil creado");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function show(Perfil $perfile)
    {
        return view('perfiles.mostrar', compact('perfile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfil $perfile)
    {
        return view('perfiles.edit', compact('perfile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perfil $perfile)
    {
        //1.-Validamos
        $request->validate([
            'nombre'=>['required','string','min:3','max:50','unique:perfils,nombre,'.$perfile->id],
            'descripcion'=>['required','string','min:10','max:200']
        ]);
        //2.- Procesador
        try{
            Perfil::create($request->all());
        }catch(\Exception $ex){
            return redirect()->route('perfiles.index')->with("mensaje","Error con la BBDD".$ex->getMessage());
        }
        return redirect()->route('perfiles.index')->with("mensaje","Perfil actualizado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perfil $perfile)
    {
        try{
            $perfile->delete();
        }catch(\Exception $ex){
            return redirect()->route('perfiles.index')->with("mensaje","Error con la BBDD");
        }
        return redirect()->route('perfiles.index')->with("mensaje","Perfil borrado");

    }
}
