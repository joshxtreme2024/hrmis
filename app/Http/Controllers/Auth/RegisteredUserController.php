<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\PDSPersonalData;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'name_extension' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        
        $fullName = trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name . ' ' . $request->name_extension);
        
        $fullName = preg_replace('/\s+/', ' ', $fullName);

        $user = User::create([
            'name' => strtoupper($fullName),
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        PDSPersonalData::create([
            'user_id' => $user->id,
            'first_name' => strtoupper($request->first_name),
            'middle_name' => strtoupper($request->middle_name),
            'last_name' => strtoupper($request->last_name),
            'ext_name' => strtoupper($request->name_extension),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
