<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class UserTest extends TestCase
{
    public function testCanLogin()
    {
        $user = $this->prepareUser();

        $this->post("api/login", [
            "email" => $user->email,
            "password" => "password",
        ])->assertOk();
    }

    public function testCanNotLogin400BadRequest()
    {
        $this->post("api/login", [
            "email" => fake()->email(),
            "password" => fake()->password(),
        ])->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testCanLogout()
    {
        $user = $this->prepareUser();

        $this->post("api/logout", [], [
            'Authorization' => 'Bearer ' . \JWTAuth::fromUser($user),

        ])->assertOk();
    }

    public function testCanGetUserList()
    {
        $user = $this->prepareUser();

        $this->get("api/users", $this->makeAuthHeader($user))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testCanRegisterUser()
    {
        $userParams = $this->getUsersParams();

        $this->postJson("api/register", $userParams)
            ->assertJson($this->getSuccessAndCreatedCode());
    }

    public function testCanUpdateUser()
    {
        $user = $this->prepareUser();
        $this->put("api/user/update/" . $user->id, [], $this->makeAuthHeader($user))
            ->assertJson($this->getSuccessAndUpdatedCode());
    }

    public function testCanNotUpdate404NotFound()
    {
        $id = fake()->numberBetween(10, 20);
        $this->put("api/user/update/" . $id, [], $this->makeAuthHeader($this->prepareUser()))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testCanDeleteUserById()
    {
        $user = $this->prepareUser();
        $this->delete("api/user/delete/" . $user->id, $this->makeAuthHeader($user))
            ->assertStatus(Response::HTTP_CREATED);
    }

    private function getUsersParams()
    {
        return [
            "name" => "users1",
            "email" => "test22@gmail.com",
            "image" =>  UploadedFile::fake()->image('avatar.jpg'),
            "password" => "password"
        ];
    }
}
