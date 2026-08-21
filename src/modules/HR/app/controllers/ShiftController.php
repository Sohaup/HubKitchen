<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use Override;
use PostApi\modules\HR\app\DB\repositories\ShiftRepository;
use PostApi\modules\HR\domain\services\shifts\CreateShiftAction;
use PostApi\modules\HR\domain\services\shifts\DeleteShiftAction;
use PostApi\modules\HR\domain\services\shifts\GetShiftCollectionAction;
use PostApi\modules\HR\domain\services\shifts\GetShiftItemAction;
use PostApi\modules\HR\domain\services\shifts\UpdateShiftAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ShiftController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        try {
            $shiftRepository = new \PostApi\modules\HR\app\DB\repositories\ShiftRepository();
            $body = $request->body;
            $critiria = [];
            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['shift_name'])) {
                $critiria['shift_name'] = $body['shift_name'];
            }
            if (isset($body['is_active'])) {
                $critiria['is_active'] = $body['is_active'];
            }
            if (isset($body['is_overnight'])) {
                $critiria['is_overnight'] = $body['is_overnight'];
            }
            if (!empty($critiria)) {
                $shifts = $shiftRepository->findBy($critiria);
                $serin = GetShiftCollectionAction::execute($shifts);
            } else {
                $shifts = $shiftRepository->findAll();
                $serin = GetShiftCollectionAction::execute($shifts);
            }
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (\Throwable $err) {
            return ViewError::viewProplem("display shift error", "paramter error", 1, "internal server error", 500);
        }
    }

    #[Override]
    public function get(string $id)
    {
        $shiftRepository = new ShiftRepository();
        try {
            $shift = $shiftRepository->findOne($id);
            $serinJosn = GetShiftItemAction::execute($shift);
            http_response_code(200);
            return Json::toJson($serinJosn);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display shift error ", title: "incorrect paramter", status: true, detail: "there is no  corresponding shift for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {       
        $params = $request->body;
        if (!isset($params['shift_name'], $params['start_time'], $params['end_time'], $params['break_duration_minutes'], $params['is_overnight'], $params['is_active'])) {
            return ViewError::viewProplem("creating shift error", "missing required paramters error", 1, "missing required paramter name ", 400);
        }

        $shift = CreateShiftAction::execute($params);
        http_response_code(201);
        $serinJson = GetShiftItemAction::execute($shift);
        return Json::toJson($serinJson);
    }

    #[Override]
    public function update(Request $request,string $id)
    {       
        $params = $request->body;
        if (!isset($params['shift_name'], $params['start_time'], $params['end_time'], $params['break_duration_minutes'], $params['is_overnight'], $params['is_active'])) {
            return ViewError::viewProplem("updating shift error", "missing required paramters error", 1, "missing required paramters  ", 400);
        }
        try {
            UpdateShiftAction::execute($id , $params);
            http_response_code(200);
            return Json::toJson(["message"=> "updated successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "updating shift error ", title: "incorrect paramter", status: true, detail: "there is no  corresponding shift for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteShiftAction::execute($id);
            http_response_code(200);
            return Json::toJson(["message"=>"deleted successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "deleting shift error ", title: "incorrect paramter", status: true, detail: "there is no  corresponding shift for this id", statusCode: 400);
        }
    }
}
