<?php

namespace App\Http\Controllers\Auth;

use App\Models\Group;
use App\Models\Image;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function showRegistrationForm(Request $request)
    {
        $this->middleware('guest');

        return view('auth.register');
    }

    public function validateFirstStep(Request $request) {
        $data = $request->all();

        $valid = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|regex:/(.+)@(.+)\.(.+)/i|unique:user',
            'password' => 'required|min:4',
            'password_confirmation' => 'required|min:4|same:password',
            'birthdate' => 'required|date|before:13 years ago'
        ]);

        return $valid->errors();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|regex:/(.+)@(.+)\.(.+)/i|unique:user',
            'password' => 'required|min:4',
            'password_confirmation' => 'required|min:4|same:password',
            'birthdate' => 'required|date|before:13 years ago',
            'priv_stat' => 'required|not_in:0',
            'profile_pic' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'cover_pic' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request)));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(Request $request)
    {
        $data = $request->all();

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'birthdate' => $data['birthdate'],
            'priv_stat' => $data['priv_stat'],
            'profile_pic' => Image::storeAndRegister($request->file('profile_pic')),
            'cover_pic' => Image::storeAndRegister($request->file('cover_pic'))
        ]);
    }
}

