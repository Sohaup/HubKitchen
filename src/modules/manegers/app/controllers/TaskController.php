<?php

namespace PostApi\modules\manegers\app\controllers;

use Error;
use Exception;

use PostApi\modules\manegers\domain\services\task\CreateTaskAction;
use PostApi\modules\manegers\domain\services\task\UpdateTaskAction;
use PostApi\modules\manegers\domain\services\task\DeleteTaskAction;
use PostApi\modules\manegers\domain\services\task\GetTaskCollectionAction;
use PostApi\modules\manegers\domain\services\task\GetTaskItemAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class TaskController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $taskRepository = new \PostApi\modules\manegers\app\DB\repositories\TaskRepository();
            $critiria = [];

            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['name'])) {
                $critiria['name'] = $body['name'];
            }
            if (isset($body['description'])) {
                $critiria['description'] = $body['description'];
            }
            if (isset($body['maneger_id'])) {
                $critiria['maneger_id'] = $body['maneger_id'];
            }
            if (isset($body['department_id'])) {
                $critiria['department_id'] = $body['department_id'];
            }

            if (!empty($critiria)) {
                $tasks = $taskRepository->findBy($critiria);
            } else {
                $tasks = $taskRepository->findAll();
            }

            $serin = GetTaskCollectionAction::execute($tasks);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {        
        $body = $request->body;
        if (!isset($body['name'], $body['description'], $body['maneger_id'], $body['department_id'])) {
            return ViewError::viewProplem('create task error', 'missing required paramters', 1, 'missing required paramters name, description, maneger_id, department_id', 400);
        }
        try {
            $task = CreateTaskAction::execute($body);
            $serin = GetTaskItemAction::execute($task->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetTaskItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no task for this id", 400);
        }
    }

    public function update(Request $request,string $id)
    {        
        $body = $request->body;
        if (!isset($body['name'], $body['description'], $body['maneger_id'], $body['department_id'])) {
            return ViewError::viewProplem('update task error', 'missing required paramters', 1, 'missing required paramters name, description, maneger_id, department_id', 400);
        }
        try {
            UpdateTaskAction::execute($id , $body);
            http_response_code(200);
            return Json::toJson(['message' => 'task updated successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no task for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteTaskAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'task deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no task for this id", 400);
        }
    }
}
