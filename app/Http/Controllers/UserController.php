<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use function Laravel\Prompts\password;

class UserController extends Controller
{
    public function register(Request $request): string
    {
        $incoming_fields_ = $request->validate([
            'name' => ['required', 'min:3', 'max:15', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:200', 'confirmed', Rule::unique('users', 'password')],
        ]);

        $incoming_fields_['password'] = bcrypt($incoming_fields_['password']);
        $user = User::create($incoming_fields_);
        auth()->login($user);

        return redirect('/')->with('mssg', 'Sucessfully registrated');
    }
}
