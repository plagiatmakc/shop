<?php

namespace Tests\Feature;

use function MongoDB\BSON\toJSON;
use Tests\TestCase;
use App\User;
use App\Notifications\ChangeOrderStatus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
//        $user = factory(User::class)->create();
//        $user = User::findOrFail(26);
        $response = $this->get('/cart');

        $response->assertJson(['items' => null, 'totalPrice' => 0, 'totalQuantity' => 0]);
//        $user->delete();
    }

    public function test_user_isAdmin()
    {
        //when user is admin
        $user = User::findOrFail(1);
        $response = $this->actingAs($user)
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/is-admin');

        $response->assertSee('true');
    }

    public function test_user_is_not_admin()
    {
        //when user is not admin
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/is-admin');

        $response ->assertSee('false');
        $user->delete();
    }

    public function test_api_user()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/api/user');

        $response->assertJson([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
            'email' => $user->email,
            'avatar' => $user->avatar
            ]);

        $user->delete();
    }

    public function test_user_can_login_api()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'secret'),
        ]);
        $response = $this->post('/api/login',[
            'email' => $user->email,
            'password' => $password,
            ]);

        $response->assertJsonStructure(
            [
                'token'
        ]
        );
        $user->delete();
    }
}
