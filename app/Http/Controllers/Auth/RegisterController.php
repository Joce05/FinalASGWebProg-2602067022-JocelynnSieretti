<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
        'name' => ['required', 'string', 'max:255'],
        'gender' => ['required', 'in:male,female'],
        'email' => [
            'required', 'string', 'email', 'max:255', 'unique:users',
            'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
        ],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'phonenumber' => ['required', 'numeric', 'regex:/^08[0-9]{8,13}$/'],
        'instagram' => [
            'required',
            'regex:/^https?:\/\/(www\.)?instagram\.com\/[A-Za-z0-9_.]+$/'
        ],
        'hobby' => ['required', 'array', 'min:3'],
        'hobby.*' => ['string'],
        'address' => ['required', 'string', 'max:500'],
    ]);
    }

    public function register(Request $request)
    {
        // Validate the incoming request
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        // Generate registration price and store data
        $registrationPrice = rand(100000, 125000);
        session([
            'registration_price' => $registrationPrice,
            'registration_data' => $request->all()
        ]);

        // Redirect to payment form
        return view('auth.payment', compact('registrationPrice'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'gender' => $data['gender'],
            'hobby' => json_encode($data['hobby']),
            'instagram' => $data['instagram'],
            'phonenumber' => $data['phonenumber'],
            'address' => $data['address'],
            'coin' => 100
        ]);
    }

    // App\Http\Controllers\Auth\RegisterController.php

public function showPaymentForm(Request $request)
{
    // Store registration data in session
    $request->flash();

    $registrationPrice = rand(100000, 125000);
    session(['registration_price' => $registrationPrice]);
    session(['registration_data' => $request->all()]);

    return view('auth.payment', compact('registrationPrice'));
}

public function processPayment(Request $request)
{
    $registrationPrice = session('registration_price');
    $registrationData = session('registration_data');
    $payment = $request->input('payment');

    if ($payment < $registrationPrice) {
        return back()->with('error', 'You are still underpaid.');
    } elseif ($payment > $registrationPrice) {
        $excess = $payment - $registrationPrice;
        $coins = floor($excess / 1000); // Convert to coins (1000 = 1 coin)

        session([
            'excess_payment' => $excess,
            'coins' => $coins
        ]);

        return back()->with([
            'overpaid' => true,
            'excess' => $excess,
            'coins' => $coins
        ]);
    }

    // If payment is exact, create the user
    $validator = $this->validator($registrationData);

    if ($validator->fails()) {
        return redirect()->route('register')
            ->withErrors($validator)
            ->withInput($registrationData);
    }

    $user = $this->create($registrationData);

    // Log the user in
    auth()->login($user);

    return redirect()->route('home')->with('success', 'Registration and payment successful!');
}

public function handleOverpayment(Request $request)
{
    $registrationData = session('registration_data');
    $choice = $request->input('choice');
    $excess = session('excess_payment');
    $coins = session('coins');

    // Create and authenticate user first
    $validator = $this->validator($registrationData);

    if ($validator->fails()) {
        return redirect()->route('register')
            ->withErrors($validator)
            ->withInput($registrationData);
    }

    if ($choice === 'yes') {
        // Create user with coins
        $user = $this->create(array_merge($registrationData, [
            'coin' => $coins
        ]));
    } else {
        // Create user without coins
        $user = $this->create($registrationData);
    }

    // Log the user in
    auth()->login($user);

    // Clear the session data
    session()->forget(['registration_data', 'excess_payment', 'coins']);

    return redirect()->route('home')
        ->with('success', $choice === 'yes' ?
            'Registration successful and coins added to your account!' :
            'Registration successful!');
}

}
