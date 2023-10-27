<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ListMailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_list_mail(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;
        $token = str_replace("|", "%7C", $token);

        $res = $this->getJson("api/list?api_token={$token}");

        $res->assertStatus(200);
    }
}
