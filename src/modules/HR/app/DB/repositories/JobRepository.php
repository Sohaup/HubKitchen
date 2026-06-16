<?php

namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\JobMapper;
use PostApi\modules\HR\domain\entities\Application;
use PostApi\modules\HR\domain\entities\Job;
use PostApi\shared\templates\DB_Trait;

class JobRepository
{
    private JobMapper $jobMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->jobMapper = new JobMapper($this->postgre->pdo);
    }
    public function findOne(int $id)
    {
        return $this->jobMapper->findOne($id);
    }
    public function findAll()
    {
        return $this->jobMapper->findAll();
    }
    public function create(Job $job)
    {
        $this->jobMapper->create($job);
    }
    public function update(Job $job)
    {
        $this->jobMapper->update($job);
    }
    public function delete(int $id)
    {
        $this->jobMapper->delete($id);
    }
    public function assignApplicationToJob(Application $application, Job $job)
    {
        return $this->jobMapper->assignApplicationToJob($application, $job);
    }
    public function removeApplicationFromJob(Application $application, Job $job)
    {
        $this->jobMapper->removeApplicationFromJob($application, $job);
    }
}
