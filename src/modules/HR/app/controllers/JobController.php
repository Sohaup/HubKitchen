<?php
namespace PostApi\modules\HR\app\controllers;

use Override;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use Error;
use PostApi\modules\HR\domain\services\jobs\CreateJobAction;
use PostApi\modules\HR\domain\services\jobs\DeleteJobAction;
use PostApi\modules\HR\domain\services\jobs\GetJobCollectionAction;
use PostApi\modules\HR\domain\services\jobs\GetJobItemAction;
use PostApi\modules\HR\domain\services\jobs\UpdateJobAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\ViewError;
use PostApi\modules\HR\domain\services\jobs\AssignApplicationToJobAction;
use PostApi\modules\HR\domain\services\jobs\RemoveApplicationFromJobAction;
use PostApi\shared\helpers\fecade\Chache;

class JobController implements ApiControllerContract {
    #[Override]
    public function index()
    {
        $serin = GetJobCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetJobItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display job error ", title: "incorrect paramter", status: true, detail: "there is no corresponding job for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['title'], $params['department_id'])) {
            return ViewError::viewProplem("creating job error", "missing required paramters error", 1, "missing required paramters title , department_id", 400);
        }
        try {
            $job = CreateJobAction::execute();
            $serin = GetJobItemAction::execute((int)$job->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create job error ", title: "incorrect paramter", status: true, detail: "there is no corresponding department for this id", statusCode: 400);
        }
    }

    #[Override]
    public function update(string $id)
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['title'], $params['department_id'])) {
            return ViewError::viewProplem("updating job error", "missing required paramters error", 1, "missing required paramter title , department_id", 400);
        }
        try {
            UpdateJobAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => "update job successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update job error ", title: "incorrect paramter", status: true, detail: "there is no corresponding department or job for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteJobAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => "delete job successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete job error ", title: "incorrect paramter", status: true, detail: "there is no corresponding job for this id", statusCode: 400);
        }
    }

    public function assignApplicationToJob()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['application_id'], $params['job_id'])) {
            return ViewError::viewProplem("assigning application error", "missing required paramters error", 1, "missing required paramters application_id, job_id", 400);
        }        
        try {
            $job = AssignApplicationToJobAction::execute((int)$params['application_id'], (int)$params['job_id']);
            http_response_code(200);
            return Json::toJson(['message' => "assign application to job successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "assign application error ", title: "incorrect paramter", status: true, detail: "there is no corresponding job or application for this id", statusCode: 400);
        }
    }

    public function removeApplicationFromJob()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['application_id'], $params['job_id'])) {
            return ViewError::viewProplem("removing application error", "missing required paramters error", 1, "missing required paramters application_id, job_id", 400);
        }
        try {
            RemoveApplicationFromJobAction::execute((int)$params['application_id'], (int)$params['job_id']);
            http_response_code(200);
            return Json::toJson(['message' => "remove application from job successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "remove application error ", title: "incorrect paramter", status: true, detail: "there is no corresponding job or application for this id", statusCode: 400);
        }
    }
}