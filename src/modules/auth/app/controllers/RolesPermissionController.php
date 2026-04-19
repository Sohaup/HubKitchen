<?php

namespace PostApi\modules\auth\app\controllers;

use Error;
use PostApi\modules\auth\domain\services\authirization\GrantPermissionAction;
use PostApi\modules\auth\domain\services\authirization\RevokePermissionAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\ViewError;

class RolesPermissionController
{
    public function grant(string $roleId)
    {      
        
        $request = new Request();
        $params = $request->body;
        try {
             if (!isset($params['permission_id'])) {
            return ViewError::viewProplem(
                "granting permission error",
                "missing paramter error",
                1,
                "paramter permission_id is required  ",
                400
            );
        }
        GrantPermissionAction::execute($roleId , $params['permission_id']);
        http_response_code(200);
        return Json::toJson(['message' => 'assigned permission successfuly']);
        } catch(Error $error) {
            return ViewError::viewProplem("granting permission error" ,
             "not valid paramters" ,
              1  ,
              "the role id or the permission id had not an corrosponding entity " ,
              400
        );
        }
       
    }

    public function revoke(string $roleId)
    {
        
        $request = new Request();
        $params = $request->body;
        try {
            if (!isset($params['permission_id'])) {
            return ViewError::viewProplem(
                "granting permission error",
                "missing paramter error",
                1,
                "paramter permission_id is required  ",
                400
            );
        }
        $permissionId = $params['permission_id'];
        RevokePermissionAction::execute($roleId , $permissionId);
        http_response_code(200);
        return Json::toJson(['message'=>'revoked the permission successfuly']);
        } catch(Error $error) {
            return ViewError::viewProplem("revoking permission error" ,
             "not valid paramters" ,
              1  ,
              "the role id or the permission id had not an corrosponding entity " ,
              400
        );
        }
        
    }
}
