<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use Exception;
use Override;
use PostApi\modules\HR\app\DB\repositories\JobDescriptionRepository;
use PostApi\modules\HR\domain\services\Jds\AssignSkillToJobAction;
use PostApi\modules\HR\domain\services\Jds\CreateJobDescritionAction;
use PostApi\modules\HR\domain\services\Jds\DeleteJobDescriptionAction;
use PostApi\modules\HR\domain\services\Jds\GetJobDescriptionCollectionAction;
use PostApi\modules\HR\domain\services\Jds\GetJobDescriptionItemAction;
use PostApi\modules\HR\domain\services\Jds\RemoveSkillFromJobAction;
use PostApi\modules\HR\domain\services\Jds\UpdateJobDescriptionAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class JobDescriptionController implements ApiControllerContract
{
    #[Override]
    public function index()
    {
        $serin = GetJobDescriptionCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $jdRepository = new JobDescriptionRepository();
            $jd = $jdRepository->findOne($id);
            $serin = GetJobDescriptionItemAction::execute($jd);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display job description error ", title: "incorrect paramter", status: true, detail: "there is no corresponding job description for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['name'], $params['shift_id'])) {
            return ViewError::viewProplem("creating job description error", "missing required paramters error", 1, "missing required paramters name  , shift_id", 400);
        }
        try {
            $jd = CreateJobDescritionAction::execute();
            $serin = GetJobDescriptionItemAction::execute($jd);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create job description error ", title: "incorrect paramter", status: true, detail: "there is no corresponding shift for this id", statusCode: 400);
        }
    }

    #[Override]
    public function update(string $id)
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['name'], $params['shift_id'])) {
            return ViewError::viewProplem("updating job description error", "missing required paramters error", 1, "missing required paramter name  , shift_id", 400);
        }
        try {
            UpdateJobDescriptionAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => "update job description successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update job description error ", title: "incorrect paramter", status: true, detail: "there is no corresponding shift for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteJobDescriptionAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => "delete job description successfuly"]);
        } catch(Error $error) {
            return ViewError::viewProplem(type: "delete job description error ", title: "incorrect paramter", status: true, detail: "there is no corresponding shift for this id", statusCode: 400);
        }
    }

    public function assignSkillToJob() {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['jd_id'] , $params['skill_id'])) {
            return ViewError::viewProplem("assigning skill to job description error", "missing required paramters error", 1, "missing required paramters jd_id  , skill_id", 400);
        }
        try {
            AssignSkillToJobAction::execute($params['skill_id'] , $params['jd_id']);
            http_response_code(200);
            return Json::toJson(['message' => "assign skill to job description successfuly"]);
        } catch(Error $error) {
            return ViewError::viewProplem(type: "assign skill to job description error ", title: "incorrect paramter", status: true, detail: "there is no corresponding shift or job description for this id", statusCode: 400);
        }
    }

    public function removeSkillFromJob() {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['jd_id'] , $params['skill_id'])) {
            return ViewError::viewProplem("removing skill fro, job description error", "missing required paramters error", 1, "missing required paramters jd_id  , skill_id", 400);
        }
        try {
            RemoveSkillFromJobAction::execute($params['skill_id'] , $params['jd_id']);
            http_response_code(200);
            return Json::toJson(['message' => "remove skill from job description successfuly"]);
        } catch(Error $error) {
            return ViewError::viewProplem(type: "remove skill from job description error ", title: "incorrect paramter", status: true, detail: "there is no corresponding shift or job description for this id", statusCode: 400);
        }
    }
    
}
