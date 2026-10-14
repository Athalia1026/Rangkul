<?php

namespace Tests\Feature;

use App\Mail\AdminInvitationMail;
use App\Models\Admin;
use App\Models\AdminInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminInvitationTest extends TestCase
{
    use DatabaseTransactions;

    private function admin(string $tipe): User
    {
        $user = User::create([
            'nama' => 'Uji ' . $tipe,
            'email' => 'uji-' . str_replace(' ', '-', $tipe) . '-' . uniqid() . '@example.com',
            'password' => Hash::make('passwordlama1'),
            'account_type' => 'admin',
            'status' => 'aktif',
        ]);

        Admin::create(['user_id' => $user->id, 'tipe' => $tipe, 'status_akun' => 'aktif']);

        return $user;
    }

    private function createInvitation(string $role = Admin::TIPE_STAFF): array
    {
        Sanctum::actingAs($this->admin(Admin::TIPE_SUPER_ADMIN));

        return $this->postJson('/api/admin/invitations', ['role' => $role])->assertCreated()->json('data');
    }

    private function form(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Calon Staf',
            'email' => 'calon-' . uniqid() . '@example.com',
            'phone' => '081234567890',
            'password' => 'passwordbaru1',
            'password_confirmation' => 'passwordbaru1',
            'agreement' => '1',
        ], $overrides);
    }

    public function test_only_super_admin_can_create_invitation(): void
    {
        Sanctum::actingAs($this->admin(Admin::TIPE_MANAGER));
        $this->postJson('/api/admin/invitations', ['role' => 'staff'])->assertForbidden();

        Sanctum::actingAs($this->admin(Admin::TIPE_SUPER_ADMIN));
        $this->postJson('/api/admin/invitations', ['role' => 'super admin'])->assertJsonValidationErrors('role');
    }

    public function test_old_direct_account_creation_endpoint_is_removed(): void
    {
        Sanctum::actingAs($this->admin(Admin::TIPE_SUPER_ADMIN));

        $this->postJson('/api/admin/accounts', ['nama' => 'X', 'email' => 'x@example.com', 'role' => 'staff'])
            ->assertStatus(405);
    }

    public function test_invitee_registers_with_role_from_invitation_and_link_is_single_use(): void
    {
        $invitation = $this->createInvitation(Admin::TIPE_STAFF);

        $this->get($invitation['url'])->assertOk()->assertSee('Pendaftaran Akun Staff');

        // Mencoba menyelundupkan role lain lewat formulir tidak berpengaruh.
        $form = $this->form(['role' => 'super admin']);
        $this->post($invitation['url'], $form)->assertRedirect(route('login'));

        $user = User::where('email', $form['email'])->firstOrFail();
        $this->assertSame('admin', $user->account_type);
        $this->assertSame(Admin::TIPE_STAFF, $user->admin->tipe);
        $this->assertTrue(Hash::check('passwordbaru1', $user->password));
        $this->assertNotNull(AdminInvitation::find($invitation['id'])->used_at);

        // Link yang sama tidak bisa dipakai lagi.
        $this->get($invitation['url'])->assertForbidden()->assertSee('sudah dipakai');
        $second = $this->form();
        $this->post($invitation['url'], $second)->assertForbidden();
        $this->assertDatabaseMissing('users', ['email' => $second['email']]);
    }

    public function test_tampered_or_unsigned_link_is_rejected(): void
    {
        $invitation = $this->createInvitation(Admin::TIPE_STAFF);
        $manager = $this->createInvitation(Admin::TIPE_MANAGER);

        // Tanda tangan link staf dipakai pada ID undangan manajer.
        $tampered = str_replace($invitation['id'], $manager['id'], $invitation['url']);
        $this->get($tampered)->assertForbidden()->assertSee('tidak valid');

        $unsigned = strtok($invitation['url'], '?');
        $this->get($unsigned)->assertForbidden();
        $this->post($unsigned, $this->form())->assertForbidden();
    }

    public function test_expired_invitation_is_rejected(): void
    {
        $invitation = $this->createInvitation();
        AdminInvitation::whereKey($invitation['id'])->update(['expires_at' => now()->subMinute()]);

        $this->get($invitation['url'])->assertForbidden()->assertSee('kedaluwarsa');
        $this->post($invitation['url'], $this->form())->assertForbidden();
    }

    public function test_sent_invitation_is_bound_to_email(): void
    {
        Mail::fake();
        $invitation = $this->createInvitation();
        $email = 'undangan-' . uniqid() . '@example.com';

        $this->postJson('/api/admin/invitations/' . $invitation['id'] . '/send', ['email' => $email])
            ->assertOk()
            ->assertJsonPath('data.email', $email);
        Mail::assertSent(AdminInvitationMail::class, fn ($mail) => $mail->hasTo($email));

        $this->from($invitation['url'])
            ->post($invitation['url'], $this->form(['email' => 'lain-' . uniqid() . '@example.com']))
            ->assertSessionHasErrors('email');

        $this->post($invitation['url'], $this->form(['email' => $email]))->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', ['email' => $email, 'account_type' => 'admin']);
    }

    public function test_registration_enforces_password_policy(): void
    {
        $invitation = $this->createInvitation();

        $this->from($invitation['url'])
            ->post($invitation['url'], $this->form(['password' => 'pendek', 'password_confirmation' => 'pendek']))
            ->assertSessionHasErrors('password');

        $this->assertNull(AdminInvitation::find($invitation['id'])->used_at);
    }
}
