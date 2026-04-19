<?php

namespace PostApi\modules\auth\helpers\adapters;

use Error;
use League\OAuth2\Client\Provider\Google;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\config\Env;
use PostApi\shared\helpers\fecade\Urls;
use PostApi\shared\helpers\fecade\ViewError;

class GoogleProvider
{
    public static function getProvider()
    {
        try {
            Env::configureEnv();
            $provider = new Google([
                'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
                'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
                'redirectUri'  => Urls::transformRouteUrl("/login/google")
            ]);
            return $provider;
        } catch (Error $error) {
            echo ViewError::viewProplem("google authtication error" , "internal error" , 1 , $error->getMessage() , 500 );
        }
    }
}
