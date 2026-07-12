<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

/**
 * Récupération de mot de passe pour le DASHBOARD WEB uniquement (admin +
 * producteur). Le mobile utilise l'OTP par email, ce flux ne le concerne pas.
 *
 * On s'appuie sur le broker natif Laravel (table password_reset_tokens). L'accès
 * est restreint au staff : une demande pour un email non-staff (ou inexistant)
 * renvoie le même message générique afin de ne pas révéler quels comptes existent.
 */
class PasswordResetController extends Controller
{
    private const GENERIC_STATUS = "Si un compte administrateur existe pour cette adresse, un lien de réinitialisation vient d'être envoyé.";

    public function showLinkRequest()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // Non-staff ou inexistant : on ne divulgue rien, message générique.
        if (! $user || ! $user->isStaff()) {
            return back()->with('status', self::GENERIC_STATUS);
        }

        $status = Password::sendResetLink($request->only('email'));

        // Throttle : on renvoie l'erreur réelle (utile), sinon message générique.
        if ($status === Password::RESET_THROTTLED) {
            return back()->withErrors([
                'email' => 'Trop de tentatives. Patientez avant de redemander un lien.',
            ])->onlyInput('email');
        }

        return back()->with('status', self::GENERIC_STATUS);
    }

    public function showReset(Request $request, string $token)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')
                ->with('success', 'Mot de passe réinitialisé. Vous pouvez vous connecter.');
        }

        return back()->withErrors([
            'email' => $this->messageFor($status),
        ])->onlyInput('email');
    }

    private function messageFor(string $status): string
    {
        return match ($status) {
            Password::INVALID_TOKEN => 'Ce lien de réinitialisation est invalide ou a expiré. Redemandez-en un.',
            Password::INVALID_USER  => "Aucun compte ne correspond à cette adresse.",
            default                 => 'Impossible de réinitialiser le mot de passe. Réessayez.',
        };
    }
}
