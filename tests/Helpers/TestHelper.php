<?php

namespace Tests\Helpers;

use Illuminate\Testing\TestResponse;

trait TestHelper
{

    protected function getTokenString(string $token): string
    {
        return explode('|', $token)[1];
    }

    public function unauthorizedCheck(TestResponse $response): void
    {
        $response
            ->assertStatus(401)
            ->assertJsonStructure(['message']);
    }

    public function errorCheck(TestResponse $response, int $status = 500): void
    {
        $response
            ->assertStatus($status)
            ->assertJsonStructure(['status', 'message', 'errors']);
    }

    protected function successCheck(TestResponse $response, int $status = 200): void
    {
        $response
            ->assertStatus($status)
            ->assertJsonStructure(['status', 'message', 'data']);
    }

    protected function dataCheck(TestResponse $response, array $expData = []): void
    {
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => $expData
        ]);
    }


    protected function validationErrorCheck(TestResponse $response, array $expErrors = []): void {
        $response
            ->assertStatus(422)
            ->assertJsonStructure(['status', 'message', 'errors']);

        $errors = $response->json()['errors'];

        if (!empty($expErrors)) {
            $this->assertCount(count($expErrors), $errors);
        }

        foreach ($errors as $error) {
            $this->assertTrue(is_array($error));
            $this->assertArrayHasKey('field', $error);
            $this->assertArrayHasKey('message', $error);

            $field = $error['field'];
            if (!empty($expErrors)) {
                $this->assertEquals(in_array($field, array_keys($expErrors)), true);
                if (!empty($expErrors[$field])) {
                    $this->assertEquals($error['message'], $expErrors[$field]);
                }
            }
        }
    }


    protected function compareArrayKeys(array $expected, array $actual): void
    {
        foreach ($expected as $field) {
            $this->assertArrayHasKey($field, $actual);
        }
    }
}
