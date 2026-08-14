<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\TipoInmuebleRequest;
use App\Models\TipoInmueble;
use Illuminate\Http\Request;

class TipoInmuebleController extends Controller
{
    public function index(Request $request)
    {
        $buscar=$request->buscar;

        $tipos=TipoInmueble::when($buscar,function($q) use($buscar){
            $q->where('nombre','LIKE',"%$buscar%");
        })
        ->orderBy('id','desc')
        ->paginate(10);

        return view('catalogos.tipos-inmueble.index',compact('tipos','buscar'));
    }

    public function create()
    {
        return view('catalogos.tipos-inmueble.create');
    }

    public function store(TipoInmuebleRequest $request)
    {
        TipoInmueble::create([
            'nombre'=>strtoupper($request->nombre),
            'descripcion'=>$request->descripcion,
            'estado'=>1
        ]);

        return redirect()->route('tipos-inmueble.index')
        ->with('success','Registro creado correctamente.');
    }

    public function edit(TipoInmueble $tipos_inmueble)
    {
        $tipo=$tipos_inmueble;

        return view('catalogos.tipos-inmueble.edit',compact('tipo'));
    }

    public function update(TipoInmuebleRequest $request, TipoInmueble $tipos_inmueble)
    {
        $tipos_inmueble->update([
            'nombre'=>strtoupper($request->nombre),
            'descripcion'=>$request->descripcion
        ]);

        return redirect()->route('tipos-inmueble.index')
        ->with('success','Registro actualizado.');
    }

    public function destroy(TipoInmueble $tipos_inmueble)
    {
        $tipos_inmueble->estado=!$tipos_inmueble->estado;

        $tipos_inmueble->save();

        return back()->with('success','Estado actualizado.');
    }
}