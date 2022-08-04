<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function createAnnonce(Request $request)
    {

        $input = $request->all();
        $request->validate([
            'description' => 'required',
        ]);

        $file = $request->file('image');
        if ($request->hasFile('image')) {

            $name = time() . '.' . $file->getClientOriginalExtension();
            $data = [
                'description' => $input['description'],
                'url' => $name
            ];

            $annonce = Annonce::create($data);

            if ($annonce) {
                $file->move(public_path('upload/pictures/'), $name);
                return redirect(route('annonces'))->with('success', 'Image has been uploaded')->with('image', $name);
            }
        }
    }
}
