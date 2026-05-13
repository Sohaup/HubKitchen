<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\Application;
use PostApi\modules\HR\domain\entities\Job;
use PostApi\modules\HR\helpers\types\ApplicationStatusType;

class JobMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (!isset($this->identityMap[$id])) {
            $getJobQuery = $this->db->prepare("SELECT * FROM HR.opining_jobs WHERE id = ?");
            $getJobQuery->execute([$id]);
            $JobRawData = $getJobQuery->fetch(PDO::FETCH_ASSOC);
            if ($JobRawData) {
                $job = new Job();
                $job->setId($JobRawData['id']);
                $job->setTitle($JobRawData['title']);
                $deparmentMapper = new DepartmentMapper($this->db);
                $department = $deparmentMapper->findOne($JobRawData['department_id']);
                $job->setDepartment($department);
                $this->identityMap[$id] = $job;
            }
        }
        return $this->identityMap[$id];
    }
    public function findAll()
    {
        $getJobsQuery = $this->db->prepare("SELECT * FROM HR.opining_jobs");
        $getJobsQuery->execute([]);
        $jobsRawData = $getJobsQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($jobsRawData as $JobRawData) {
            if (!isset($this->identityMap[$JobRawData['id']])) {
                $job = new Job();
                $job->setId($JobRawData['id']);
                $job->setTitle($JobRawData['title']);
                $deparmentMapper = new DepartmentMapper($this->db);
                $department = $deparmentMapper->findOne($jobsRawData['department_id']);
                $job->setDepartment($department);
                $this->identityMap[$JobRawData['id']] = $job;
            }
        }
        return $this->identityMap;
    }
    public function create(Job $job)
    {
        $createJobQuery = $this->db->prepare("INSERT INTO HR.opining_jobs(title , department_id ) VALUES(? , ?) RETURNING id");
        $createJobQuery->execute([$job->getTitle(), $job->getDepartment()->getId()]);
        $jobId = $createJobQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $job->setId($jobId);
        $this->identityMap[$jobId] = $job;
    }
    public function update(Job $job)
    {
        if (isset($this->identityMap[$job->getId()])) {
            $updateJobQuery = $this->db->prepare("UPDATE HR.opining_jobs SET title = ? , department_id = ? WHERE id = ?");
            $updateJobQuery->execute([$job->getTitle(), $job->getDepartment()->getId(), $job->getId()]);
            $this->identityMap[$job->getId()] = $job;
        }
    }
    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteJobQuery = $this->db->prepare("DELETE FROM HR.opining_jobs WHERE id = ?");
            $deleteJobQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
    public function assignApplicationToJob(Application $application, Job $job)
    {
        $assignApplicationToJobQuery = $this->db->prepare("INSERT INTO HR.job_application(application_id , job_id , status) VALUES(? , ? , ? ) ");
        $assignApplicationToJobQuery->execute([$application->getId(), $job->getId(), ApplicationStatusType::APPLIED]);
        $job->addApllication($application);
        return $job;
    }
    public function removeApplicationFromJob(Application $application, Job $job)
    {
        $removeApplicationFromJobQuery = $this->db->prepare("DELETE FROM HR.job_application WHERE application_id = ? AND job_id  = ?");
        $removeApplicationFromJobQuery->execute([$application->getId(), $job->getId()]);
        $job->removeApplication($application);
    }
}
