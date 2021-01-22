<?php

namespace Microservices;


class UserService
{
    private $endpoint;

    public function __construct()
    {
        $this->endpoint = env('USERS_ENDPOINT');
    }

    public function headers()
    {
        return [
            'Authorization' => request()->headers->get('Authorization')
        ];
    }

    public function request()
    {
        return \Http::withHeaders($this->headers());
    }

    public function getUser(): User
    {
        $response = $this->request()->get($this->endpoint . '/user');

        $json = $response->json();

        return new User($json);
    }

    public function isAdmin()
    {
        return $this->request()->get($this->endpoint . '/admin')->successful();
    }

    public function isInfluencer()
    {
        return $this->request()->get($this->endpoint . '/influencer')->successful();
    }

    public function allows($ability, $arguments)
    {
        \Gate::forUser($this->getUser())->authorize($ability, $arguments);
    }

    public function all($page)
    {
        return $this->request()->get($this->endpoint . '/users?page=' . $page)->json();
    }

    public function get($id): User
    {
        $json = $this->request()->get($this->endpoint . '/users/' . $id)->json();

        return new User($json);
    }

    public function create($data)
    {
        $json = $this->request()->post($this->endpoint . '/users', $data)->json();

        return new User($json);
    }

    public function update($id, $data)
    {
        $json = $this->request()->put($this->endpoint . '/users/' . $id, $data)->json();

        return new User($json);
    }

    public function delete($id)
    {
        return $this->request()->delete($this->endpoint . '/users/' . $id)->successful();
    }
}
