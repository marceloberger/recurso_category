<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use Illuminate\Http\Request;

class GeneroController extends Controller
{

    private $rules = [
        'nome' => 'required|max:255',
        'is_active' => 'boolean'
    ];

    public function index()
    {
        return Genero::all();
    }



    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $genero = Genero::create($request->all());
        $genero->refresh();
        return  $genero;
    }


    public function show(Genero $genero)
    {
        return $genero;
    }



    public function update(Request $request, Genero $genero)
    {
        $this->validate($request, $this->rules);

        $genero->update($request->all());
        return  $genero;
    }


    public function destroy(Genero $genero)
    {
        $genero->delete();

        return response()->noContent();
    }
}
