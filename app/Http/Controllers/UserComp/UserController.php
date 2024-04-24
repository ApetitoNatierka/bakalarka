<?php

namespace App\Http\Controllers\UserComp;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

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

        $address_values['name'] = '';

        $address = Address::create($address_values);

        $incoming_fields_['address_id'] = $address->id;

        $user = User::create($incoming_fields_);

        $cart_values['user_id'] = $user->get_id();
        Cart::create($cart_values);

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

    public function add_new_user_line(Request $request) {
        $incoming_fields_ = $request->validate([
            'name' => ['required', 'min:3', 'max:15', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'phone_number' => ['required', 'string'],
            'company_position' => ['required', 'string'],
            'password' => ['required', 'min:8', 'max:200', Rule::unique('users', 'password')],
            'company_id' => ['required'],
        ]);

        $incoming_fields_['password'] = bcrypt($incoming_fields_['password']);

        $address_vales['name'] = '';

        $address = Address::create($address_vales);

        $incoming_fields_['address_id'] = $address->id;


        $user_line = User::create($incoming_fields_);

        return response()->json([
            'message' => 'User line added successfully',
            'user_line' => $user_line,
        ]);

    }

    public function add_new_user(Request $request) {
        $incoming_fields_ = $request->validate([
            'name' => ['required', 'min:3', 'max:15', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'phone_number' => ['required', 'string'],
            'company_position' => ['required', 'string'],
            'password' => ['required', 'min:8', 'max:200', Rule::unique('users', 'password')],
        ]);

        $incoming_fields_['password'] = bcrypt($incoming_fields_['password']);

        $address_vales['name'] = '';

        $address = Address::create($address_vales);

        $incoming_fields_['address_id'] = $address->id;


        $user_line = User::create($incoming_fields_);

        return response()->json([
            'message' => 'User added successfully',
            'user_line' => $user_line,
        ]);

    }

    public function modify_user_line(Request $request) {
        $validatedData = $request->validate([
            'user_line_id' => ['required'],
            'name' => ['required', 'string'],
            'email' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'company_position' => ['required', 'string'],
        ]);

        $user_line = User::find($validatedData['user_line_id']);

        unset($validatedData['user_line_id']);

        $user_line->update($validatedData);

        return response()->json(['message' => 'User line modified successfully']);

    }

    public function delete_user_line(Request $request) {
        $validatedData = $request->validate([
            'user_line_id' => ['required'],
        ]);

        $user_line = User::find($validatedData['user_line_id']);

        $user_line->delete();

        return response()->json(['message' => 'User line deleted successfully']);
    }

    public function select_users(Request $request) {
        $search_term = $request->search_term;
        $users = User::where('name', 'like', '%' . $search_term . '%')->get();

        return response()->json(['users' => $users]);
    }

    public function get_users() {
        $users = User::all();

        return view('users', ['users' => $users]);
    }

}
