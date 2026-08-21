<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Override;
use PostApi\modules\sales\app\DB\repositories\CustomerRepository;
use PostApi\modules\sales\domain\entities\Customer;
use PostApi\modules\sales\domain\services\customer\CreateCustomerAction;
use PostApi\modules\sales\domain\services\customer\DeleteCustomerAction;
use PostApi\modules\sales\domain\services\customer\GetCustomerCollectionAction;
use PostApi\modules\sales\domain\services\customer\GetCustomerItemAction;
use PostApi\modules\sales\domain\services\customer\UpdateCustomerAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class CustomerController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $customerRepository = new CustomerRepository();
            $critiria = [];

            if (isset($body['user_id'])) {
                $critiria['user_id'] = $body['user_id'];
            }
            if (isset($body['stripe_id'])) {
                $critiria['stripe_id'] = $body['stripe_id'];
            }

            if (!empty($critiria)) {
                $customers = $customerRepository->findBy($critiria);
            } else {
                $customers = $customerRepository->findAll();
            }

            $serin = GetCustomerCollectionAction::execute($customers);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display customer error ", title: "incorrect paramter", status: true, detail: "internal server error", statusCode: 500);
        }
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetCustomerItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display customer error ", title: "incorrect paramter", status: true, detail: "there is no corresponding customer for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {       
        $params = $request->body;       
        if (!isset($params['user_id'])) {
            return ViewError::viewProplem("creating customer error", "missing required paramters error", 1, "missing required paramters user_id", 400);
        }
        
        try {
            $customer = new Customer();           
            $customer->setUserId($params['user_id']);
            $serin = CreateCustomerAction::execute($customer);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create customer error ", title: "incorrect paramter", status: true, detail: "unexpected error creating customer", statusCode: 400);
        }
    }

    #[Override]
    public function update(Request $request,string $id)
    {        
        $params = $request->body;
        if (!isset($params['user_id'])) {
            return ViewError::viewProplem("updating customer error", "missing required paramters error", 1, "missing required paramters user_id", 400);
        }
        try {
            $customerRepository = new CustomerRepository();
            $customer = $customerRepository->findOne($id);
            if (!$customer) {
                return ViewError::viewProplem(type: "update customer error ", title: "incorrect paramter", status: true, detail: "customer not found", statusCode: 400);
            }
            $customer->setUserId($params['user_id']);
            UpdateCustomerAction::execute($customer);
            http_response_code(200);
            return Json::toJson(["message" => "update customer successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update customer error ", title: "incorrect paramter", status: true, detail: "there is no corresponding customer for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteCustomerAction::execute($id);
            http_response_code(200);
            return Json::toJson(["message" => "delete customer successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete customer error ", title: "incorrect paramter", status: true, detail: "there is no corresponding customer for this id", statusCode: 400);
        }
    }
}
