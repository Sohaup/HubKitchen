<?php

namespace PostApi\modules\CS\app\controllers;

use Exception;

use PostApi\modules\CS\domain\services\customer\CreateCustomerAction;
use PostApi\modules\CS\domain\services\customer\UpdateCustomerAction;
use PostApi\modules\CS\domain\services\customer\DeleteCustomerAction;
use PostApi\modules\CS\domain\services\customer\GetCustomerCollectionAction;
use PostApi\modules\CS\domain\services\customer\GetCustomerItemAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class CustomerController implements ApiControllerContract
{
    public function index(Request $request)
    {        
        try {           
            $serin = GetCustomerCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {    
        $body = $request->body;
        if (!isset($body['user_id'] , $body['country'])) {
            return ViewError::viewProplem('create customer error', 'missing required paramters', 1, 'missing required paramters user_id , country', 400);
        }
        try {           
            $customer = CreateCustomerAction::execute($body);
            $serin = GetCustomerItemAction::execute($customer->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {        
        try {           
            $serin = GetCustomerItemAction::execute($id);            
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function update(Request $request,string $id)
    {      
        $body = $request->body;
        try {
            UpdateCustomerAction::execute($id , $body);
            http_response_code(200);
            return Json::toJson(['message' => 'customer updated successfuly']);
        } catch (Exception $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function delete(string $id)
    {       
        try {
            DeleteCustomerAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'customer deleted']);
        } catch (Exception $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, $error->getMessage(), 500);
        }
    }
}
