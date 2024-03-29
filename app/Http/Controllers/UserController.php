<?php

namespace App\Http\Controllers;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use function Laravel\Prompts\password;

class UserController extends Controller
{
    public function register(Request $request): RedirectResponse
    {
        $incoming_fields_ = $request->validate([
            'name' => ['required', 'min:3', 'max:15', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:200', 'confirmed', Rule::unique('users', 'password')],
        ]);

        $incoming_fields_['password'] = bcrypt($incoming_fields_['password']);

        $address_vales['name'] = '';

        $address = Address::create($address_vales);

        $incoming_fields_['address_id'] = $address->id;


        $user = User::create($incoming_fields_);


        auth()->login($user);

        return redirect('/');
    }

    public function get_register(): View
    {
        return view('register');
    }

    public function sign_in(Request $request): RedirectResponse
    {
        $incoming_fields_ = $request->validate([
            'loginname' => ['required'],
            'loginpassword' => ['required'],
        ]);

        if (auth()->attempt(['name' => $incoming_fields_['loginname'], 'password' => $incoming_fields_['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return redirect('/sign_in')->with('loginError', 'Invalid login credentials.');
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();
        return redirect('/');
    }

    public function get_sign_in(): View
    {
        return view('sign_in');
    }

    public function get_user_profile(): View
    {
        $user = Auth::user();

        return view('user_profile', ['user' => $user]);
    }

    public function modify_user_info(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'min:3', 'max:15'],
            'email' => ['required', 'email'],
            'phone_number' => ['required'],
        ]);

        $user = User::find(auth()->id());
        $user->update($validatedData);

        return response()->json(['message' => 'User data saved successfully']);
    }
}
