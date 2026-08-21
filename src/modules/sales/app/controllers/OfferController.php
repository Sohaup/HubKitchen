<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Override;
use PostApi\modules\sales\app\DB\repositories\OfferRepository;
use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\modules\sales\domain\entities\Offer;
use PostApi\modules\sales\domain\services\offer\CreateOfferAction;
use PostApi\modules\sales\domain\services\offer\DeleteOfferAction;
use PostApi\modules\sales\domain\services\offer\GetOfferCollectionAction;
use PostApi\modules\sales\domain\services\offer\GetOfferItemAction;
use PostApi\modules\sales\domain\services\offer\UpdateOfferAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class OfferController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $offerRepository = new OfferRepository();
            $critiria = [];

            if (isset($body['product_id'])) {
                $critiria['product_id'] = $body['product_id'];
            }
            if (isset($body['value'])) {
                $critiria['value'] = $body['value'];
            }

            if (!empty($critiria)) {
                $offers = $offerRepository->findBy($critiria);
            } else {
                $offers = $offerRepository->findAll();
            }

            $serin = GetOfferCollectionAction::execute($offers);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display offer error ", title: "incorrect paramter", status: true, detail: "internal server error", statusCode: 500);
        }
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetOfferItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display offer error ", title: "incorrect paramter", status: true, detail: "there is no corresponding offer for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {        
        $params = $request->body;
        if (!isset($params['product_id'], $params['value'])) {
            return ViewError::viewProplem("creating offer error", "missing required paramters error", 1, "missing required paramters product_id, value", 400);
        }
        try {
            $productRepo = new ProductRepository();
            $product = $productRepo->findOne($params['product_id']);
            if (!$product) {
                return ViewError::viewProplem("creating offer error", "invalid product_id", 1, "product not found", 400);
            }
            $offer = new Offer();
            $offer->setProduct($product);
            $offer->setValue((float)$params['value']);

            $serin = CreateOfferAction::execute($offer);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create offer error ", title: "incorrect paramter", status: true, detail: "unexpected error creating offer", statusCode: 400);
        }
    }

    #[Override]
    public function update(Request $request,string $id)
    {        
        $params = $request->body;
        if (!isset($params['product_id'], $params['value'])) {
            return ViewError::viewProplem("updating offer error", "missing required paramters error", 1, "missing required paramters product_id, value", 400);
        }
        try {
            $offerRepository = new OfferRepository();
            $offer = $offerRepository->findOne((int)$id);
            if (!$offer) {
                return ViewError::viewProplem(type: "update offer error ", title: "incorrect paramter", status: true, detail: "offer not found", statusCode: 400);
            }
            $productRepo = new ProductRepository();
            $product = $productRepo->findOne($params['product_id']);
            if (!$product) {
                return ViewError::viewProplem("updating offer error", "invalid product_id", 1, "product not found", 400);
            }
            $offer->setProduct($product);
            $offer->setValue((float)$params['value']);

            UpdateOfferAction::execute($offer);
            http_response_code(200);
            return Json::toJson(["message" => "update offer successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update offer error ", title: "incorrect paramter", status: true, detail: "there is no corresponding offer for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteOfferAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(["message" => "delete offer successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete offer error ", title: "incorrect paramter", status: true, detail: "there is no corresponding offer for this id", statusCode: 400);
        }
    }
}
