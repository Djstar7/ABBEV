<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

/**
 * Récupération de mot de passe du dashboard web (admin/producteur uniquement).
 * Le mobile utilise l'OTP et n'est pas concerné.
 */
class AdminPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_admin_recoit_un_lien_de_reinitialisation(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@abbev.tv']);

        $res = $this->post(route('admin.password.email'), ['email' => 'admin@abbev.tv']);

        $res->assertSessionHas('status');
        Notification::assertSentTo($admin, AdminResetPasswordNotification::class);
    }

    public function test_un_producteur_recoit_aussi_un_lien(): void
    {
        Notification::fake();
        $prod = User::factory()->create(['role' => 'producer', 'email' => 'prod@abbev.tv']);

        $this->post(route('admin.password.email'), ['email' => 'prod@abbev.tv']);

        Notification::assertSentTo($prod, AdminResetPasswordNotification::class);
    }

    public function test_un_utilisateur_mobile_ne_recoit_rien_mais_message_generique(): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => 'user', 'email' => 'mobile@abbev.tv']);

        $res = $this->post(route('admin.password.email'), ['email' => 'mobile@abbev.tv']);

        // Message générique (anti-énumération) mais AUCUN email envoyé.
        $res->assertSessionHas('status');
        Notification::assertNothingSent();
    }

    public function test_email_inexistant_ne_divulgue_rien(): void
    {
        Notification::fake();

        $res = $this->post(route('admin.password.email'), ['email' => 'inconnu@abbev.tv']);

        $res->assertSessionHas('status');
        Notification::assertNothingSent();
    }

    public function test_reinitialisation_avec_token_valide_change_le_mot_de_passe(): void
    {
        $admin = User::factory()->create([
            'role'     => 'admin',
            'email'    => 'reset@abbev.tv',
            'password' => Hash::make('ancien-mot-de-passe'),
        ]);
        $token = Password::createToken($admin);

        $res = $this->post(route('admin.password.update'), [
            'token'                 => $token,
            'email'                 => 'reset@abbev.tv',
            'password'              => 'nouveau-mot-de-passe',
            'password_confirmation' => 'nouveau-mot-de-passe',
        ]);

        $res->assertRedirect(route('admin.login'));
        $res->assertSessionHas('success');
        $this->assertTrue(Hash::check('nouveau-mot-de-passe', $admin->fresh()->password));
    }

    public function test_token_invalide_est_refuse(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email' => 'reset2@abbev.tv']);

        $res = $this->post(route('admin.password.update'), [
            'token'                 => 'token-bidon',
            'email'                 => 'reset2@abbev.tv',
            'password'              => 'nouveau-mot-de-passe',
            'password_confirmation' => 'nouveau-mot-de-passe',
        ]);

        $res->assertSessionHasErrors('email');
    }

    public function test_le_lien_pointe_vers_la_route_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email' => 'link@abbev.tv']);
        $notification = new AdminResetPasswordNotification('un-token-de-test');

        $mail = $notification->toMail($admin);
        $actionUrl = $mail->actionUrl;

        $this->assertStringContainsString('/admin/reset-password/un-token-de-test', $actionUrl);
        $this->assertStringContainsString('email=link%40abbev.tv', $actionUrl);
    }
}
