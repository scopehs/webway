<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class GiceProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'openid',
        'groups',
    ];

    /**
     * @inheritdoc
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://esi.goonfleet.com/oauth/authorize', $state);
    }

    /**
     * @inheritdoc
     */
    protected function getTokenUrl()
    {
        return 'https://esi.goonfleet.com/oauth/token';
    }

    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Authorization' => 'Basic '.base64_encode($this->clientId.':'.$this->clientSecret)],
            'body'    => $this->getTokenFields($code),
        ]);

        // dd($response->getBody() . "hi");
        return json_decode($response->getBody(), true);
    }

    /**
     * Get the access token response for the given code.
     *
     * @param  string  $code
     * @return array
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function getTokenFields($code)
    {
        $fields = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUrl,
        ];

        return $fields;
    }

    /**
     * @inheritdoc
     */
    protected function getUserByToken($token)
    {
        // dd($token);
        $response = $this->getHttpClient()->get('https://esi.goonfleet.com/oauth/userinfo', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        // dd(json_decode($response->getBody(), true));

        return json_decode($response->getBody(), true);
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        // if ($this->hasInvalidState()) {
        //     throw new InvalidStateException();
        // }

        $response = $this->getAccessTokenResponse($this->getCode());
        $test = $response['id_token'];
        $info = explode('.', $test);
        $information = base64_decode($info[1]);

        return json_decode($information);
        // return $response;
        // dd($response);
        $this->user = $this->mapUserToObject($this->getUserByToken(
            $token = Arr::get($response, 'access_token')
        ));

        return $this->user->setToken($token)
            ->setRefreshToken(Arr::get($response, 'refresh_token'))
            ->setExpiresIn(Arr::get($response, 'expires_in'));
    }

    /**
     * @inheritdoc
     */
    protected function mapUserToObject(array $user)
    {

        // dd($user);
        // Deprecated: Fields added to keep backwards compatibility in 4.0. These will be removed in 5.0
        // $user['id'] = Arr::get($user, 'sub');
        // $user['verified_email'] = Arr::get($user, 'email_verified');
        // $user['link'] = Arr::get($user, 'profile');

        return (new User())->setRaw($user)->map([
            'id' => Arr::get($user, 'sub'),
            'nickname' => Arr::get($user, 'username'),
            'name' => Arr::get($user, 'name'),
        ]);
    }
}
