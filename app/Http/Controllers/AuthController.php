<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function verificationNotice()
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('profile.edit')->with('success', 'Email подтверждён.');
    }

    public function sendVerification(Request $request)
    {
        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withErrors(['email' => 'Не удалось отправить письмо. Проверьте настройки почты или повторите позже.']);
        }

        return back()->with('success', 'Письмо отправлено повторно.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = strtolower($credentials['email']).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors([
                'email' => 'Слишком много попыток. Повторите через '.RateLimiter::availableIn($throttleKey).' сек.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $guestSessionId = $request->session()->getId();
            $request->session()->regenerate();
            CartController::mergeGuestCartForUser(Auth::id(), $guestSessionId, $request->session()->getId());

            return redirect()->intended(route('home'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors(['email' => 'Неверный email или пароль.'])->onlyInput('email');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $throttleKey = 'register|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors(['email' => 'Слишком много регистраций. Повторите позже.'])->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
            'terms_consent' => ['accepted'],
            'privacy_consent' => ['accepted'],
        ]);

        RateLimiter::hit($throttleKey, 3600);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $guestSessionId = $request->session()->getId();
        Auth::login($user);
        $request->session()->regenerate();
        CartController::mergeGuestCartForUser($user->id, $guestSessionId, $request->session()->getId());
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('profile.edit')->with('info', 'Аккаунт создан, но письмо подтверждения не отправлено. Проверьте настройки почты.');
        }

        // If the visitor came from a protected page, Laravel keeps that URL in
        // the session. This is especially important for the configured store
        // administrator: after registration they should arrive in the admin
        // area instead of having to start the sign-in flow again.
        $defaultDestination = $user->isAdmin()
            ? route('admin.dashboard')
            : route('verification.notice');

        return redirect()->intended($defaultDestination)
            ->with('success', 'Аккаунт создан. Подтвердите email по ссылке в письме.');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        $key = 'password-reset|'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors(['email' => 'Слишком много запросов. Повторите позже.']);
        }

        RateLimiter::hit($key, 900);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->string('email')->toString()]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
