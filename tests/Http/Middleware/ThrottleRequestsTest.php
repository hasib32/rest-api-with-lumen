<?php

namespace Tests\Http\Middleware;

use App\Http\Middleware\ThrottleRequests;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Testing\DatabaseMigrations;


class ThrottleRequestsTest extends \TestCase
{
    use DatabaseMigrations;

    public function testHandle()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $request = Request::create($this->prepareUrlForRequest('/users/' . $user->uid));
        $middleware = app(ThrottleRequests::class);

        $totalNumberOfRequest = 60;
        for ($i = 1; $i <= 65; $i++) {
            $response = $middleware->handle($request, function () {
                return response()->json(['message' => 'success'], 200);
            }, $totalNumberOfRequest);

            $totalRateLimit = $response->headers->get('X-RateLimit-Limit');
            $rateLimitRemaining = $response->headers->get('X-RateLimit-Remaining');
            $this->assertEquals($totalRateLimit, $totalNumberOfRequest);

            if ($totalRateLimit >= $i) {
                $this->assertEquals($totalRateLimit - $i, $rateLimitRemaining);
                $this->assertTrue($response->isOk());
                $this->assertEquals('{"message":"success"}', $response->getContent());
            } else {
                // for greater than 60 (default limit), it will throttle
                $this->assertNotEquals($totalRateLimit - $i, $rateLimitRemaining);
                $this->assertFalse($response->isOk());
                $this->assertNotEquals($totalRateLimit - $i, $rateLimitRemaining);
                $this->assertEquals('{"status":429,"message":"Too Many Attempts."}', $response->getContent());
            }
        }
    }

    public function testThrottleWorksInEndpointRequest()
    {
        $user = factory(User::class)->create();

        // authenticate
        $this->actingAs($user);

        for ($i = 1; $i <= 65 ; $i++) {
            $this->call('GET', '/users/' . $user->uid);

            // for greater than 60 (default limit), it will throttle
            if ($i > 60) {
                $this->assertResponseStatus(429);
            } else {
                $this->assertResponseStatus(200);
                $this->seeJson(['email' => $user->email]);
            }

        }
    }
}
