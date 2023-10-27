<?php

namespace Tests\Feature;

use App\Jobs\SendEmailJob;
use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SendMailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Email sending
     */
    public function test_send_mail(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;
        $token = str_replace("|", "%7C", $token);

        $messageBody = "Test body for the test email";
        $messageSubject = "Subject";
        $toEmailAddress = "test@test.com";

        $res = $this->postJson("api/{$user->id}/send?api_token={$token}", [
            "messageBody" => $messageBody,
            "messageSubject" => $messageSubject,
            "toEmailAddress" => $toEmailAddress
        ]);

        Mail::assertQueued(SendMail::class);

        $res->assertStatus(200);
    }

    /**
     * Test Email sending
     */
    public function test_send_mail_failed_validation(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;
        $token = str_replace("|", "%7C", $token);

        $messageBody = "";
        $messageSubject = "Subject";
        $toEmailAddress = "test@test.com";

        $res = $this->postJson("api/{$user->id}/send?api_token={$token}", [
            "messageBody" => $messageBody,
            "messageSubject" => $messageSubject,
            "toEmailAddress" => $toEmailAddress
        ]);

        Mail::assertNotQueued(SendMail::class);

        $res->assertStatus(422);
        $res->assertInvalid(['messageBody']);
    }


    public function test_send_mail_with_proper_details()
    {

        Mail::fake();

        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;
        $token = str_replace("|", "%7C", $token);

        $messageBody = "Test body for the test email";
        $messageSubject = "Subject";
        $toEmailAddress = "test@test.com";

        $res = $this->postJson("api/{$user->id}/send?api_token={$token}", [
            "messageBody" => $messageBody,
            "messageSubject" => $messageSubject,
            "toEmailAddress" => $toEmailAddress
        ]);

        Mail::assertQueued(SendMail::class, function (SendMail $mail) use ($messageSubject, $toEmailAddress) {
            return $mail->hasTo($toEmailAddress) &&
            $mail->hasSubject($messageSubject);
        });

        $res->assertStatus(200);
    }

}
