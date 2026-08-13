<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Override;
use PostApi\modules\sales\app\DB\repositories\CustomerRepository;
use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\modules\sales\app\DB\repositories\ReviewRepository;
use PostApi\modules\sales\domain\entities\Review;
use PostApi\modules\sales\domain\services\review\CreateReviewAction;
use PostApi\modules\sales\domain\services\review\DeleteReviewAction;
use PostApi\modules\sales\domain\services\review\GetReviewCollectionAction;
use PostApi\modules\sales\domain\services\review\GetReviewItemAction;
use PostApi\modules\sales\domain\services\review\UpdateReviewAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ReviewController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        $serin = GetReviewCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetReviewItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display review error ", title: "incorrect paramter", status: true, detail: "there is no corresponding review for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {        
        $params = $request->body;
        if (!isset($params['customer_id'], $params['product_id'], $params['review'])) {
            return ViewError::viewProplem("creating review error", "missing required paramters error", 1, "missing required paramters customer_id, product_id, review", 400);
        }
        try {
            $customerRepo = new CustomerRepository();
            $customer = $customerRepo->findOne($params['customer_id']);
            if (!$customer) {
                return ViewError::viewProplem("creating review error", "invalid customer_id", 1, "customer not found", 400);
            }
            $productRepo = new ProductRepository();
            $product = $productRepo->findOne($params['product_id']);
            if (!$product) {
                return ViewError::viewProplem("creating review error", "invalid product_id", 1, "product not found", 400);
            }
            $review = new Review();
            $review->setCustomer($customer);
            $review->setProduct($product);
            $review->setReview((int)$params['review']);

            $serin = CreateReviewAction::execute($review);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create review error ", title: "incorrect paramter", status: true, detail: "unexpected error creating review", statusCode: 400);
        }
    }

    #[Override]
    public function update(Request $request,string $id)
    {        
        $params = $request->body;
        if (!isset($params['customer_id'], $params['product_id'], $params['review'])) {
            return ViewError::viewProplem("updating review error", "missing required paramters error", 1, "missing required paramters customer_id, product_id, review", 400);
        }
        try {
            $reviewRepository = new ReviewRepository();
            $review = $reviewRepository->findOne((int)$id);
            if (!$review) {
                return ViewError::viewProplem(type: "update review error ", title: "incorrect paramter", status: true, detail: "review not found", statusCode: 400);
            }
            $customerRepo = new CustomerRepository();
            $customer = $customerRepo->findOne($params['customer_id']);
            if (!$customer) {
                return ViewError::viewProplem("updating review error", "invalid customer_id", 1, "customer not found", 400);
            }
            $productRepo = new ProductRepository();
            $product = $productRepo->findOne($params['product_id']);
            if (!$product) {
                return ViewError::viewProplem("updating review error", "invalid product_id", 1, "product not found", 400);
            }
            $review->setCustomer($customer);
            $review->setProduct($product);
            $review->setReview((int)$params['review']);

            UpdateReviewAction::execute($review);
            http_response_code(200);
            return Json::toJson(["message" => "update review successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update review error ", title: "incorrect paramter", status: true, detail: "there is no corresponding review for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteReviewAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(["message" => "delete review successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete review error ", title: "incorrect paramter", status: true, detail: "there is no corresponding review for this id", statusCode: 400);
        }
    }
}
