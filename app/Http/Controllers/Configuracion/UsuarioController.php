<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = User::when($request->buscar, function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->buscar}%")
                  ->orWhere('email', 'LIKE', "%{$request->buscar}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('configuracion.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('configuracion.usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'rol' => 'required|in:ADMIN,CAPTURISTA',
        ]);

        User::create([
            'name' => strtoupper($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'estado' => 1,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario registrado correctamente.');
    }

    public function edit(User $usuario)
    {
        return view('configuracion.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|min:6',
            'rol' => 'required|in:ADMIN,CAPTURISTA',
        ]);

        $datos = [
            'name' => strtoupper($request->name),
            'email' => $request->email,
            'rol' => $request->rol,
        ];

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        if (auth()->id() == $usuario->id) {
            return back()->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $usuario->estado = !$usuario->estado;
        $usuario->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}