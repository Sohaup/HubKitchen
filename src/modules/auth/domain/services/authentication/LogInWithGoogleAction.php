<?php

namespace PostApi\modules\auth\domain\services\authentication;

use Exception;
use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\auth\helpers\adapters\GoogleProvider;
use PostApi\modules\auth\helpers\templates\LogInTemplate;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\helpers\fecade\Redirect;
use PostApi\shared\helpers\fecade\Session;

class LogInWithGoogleAction extends LogInTemplate
{
    public function handleLogIn(): User
    {
        Session::startSession();
        $request = new Request();
        $params = $request->params;
        $provider = GoogleProvider::getProvider();
        if (!empty($params['error'])) {
            exit('Got error: ' . htmlspecialchars($params['error'], ENT_QUOTES, 'UTF-8'));
        } elseif (empty($params['code'])) {
            $authUrl = $provider->getAuthorizationUrl();
            Session::setSession('oauth2state', $provider->getState());
            Redirect::ToRoute($authUrl);
            exit;
        } elseif (empty($params['state']) || ($params['state'] !== Session::getSession('oauth2state'))) {
            Session::deleteSession('oauth2state');
            exit('Invalid state');
        } else {
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $params['code']
            ]);
            try {
                /** @var \League\OAuth2\Client\Provider\GoogleUser $ownerDetails */
                $ownerDetails = $provider->getResourceOwner($token);
                $googleUser = GetGoogleUserAction::execute($ownerDetails->getId());
                if (!$googleUser) {
                    $googleUser = CreateGoogleUserAction::execute($ownerDetails);
                }
                return $googleUser;
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
}
