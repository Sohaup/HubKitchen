<?php

namespace PostApi\modules\HR\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\HR\domain\entities\Application;
use PostApi\modules\HR\domain\entities\Job;
use PostApi\modules\HR\helpers\types\ApplicationStatusType;

class JobMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        try {
            if (!isset($this->identityMap[$id])) {
                $getJobQuery = $this->db->prepare("SELECT * FROM HR.opining_jobs WHERE id = ?");
                $getJobQuery->execute([$id]);
                $JobRawData = $getJobQuery->fetch(PDO::FETCH_ASSOC);
                if ($JobRawData) {
                    $job = new Job();
                    $job->setId($JobRawData['id']);
                    $job->setTitle($JobRawData['title']);
                    $deparmentMapper = new DepartmentMapper($this->db);
                    if (isset($JobRawData['department_id']) && !is_null($JobRawData['department_id'])) {
                        $department = $deparmentMapper->findOne($JobRawData['department_id']);
                        $job->setDepartment($department);
                    }
                    $applicationsGetQuery = $this->db->prepare("SELECT * FROM HR.job_application WHERE job_id = ?");
                    $applicationsGetQuery->execute([$id]);
                    $applicationIds = $applicationsGetQuery->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($applicationIds as $applicationId) {
                        $applicationMapper = new ApplicationMapper($this->db);
                        $application = $applicationMapper->findOne($applicationId['application_id']);
                        $job->addApllication($application);
                    }
                    $this->identityMap[$id] = $job;
                }
            }
            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function findAll()
    {
        try {
            $getJobsQuery = $this->db->prepare("SELECT * FROM HR.opining_jobs");
            $getJobsQuery->execute([]);
            $jobsRawData = $getJobsQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($jobsRawData as $JobRawData) {
                if (!isset($this->identityMap[$JobRawData['id']])) {
                    $job = new Job();
                    $job->setId($JobRawData['id']);
                    $job->setTitle($JobRawData['title']);
                    $deparmentMapper = new DepartmentMapper($this->db);
                    if (isset($JobRawData['department_id']) && !is_null($JobRawData['department_id'])) {
                        $department = $deparmentMapper->findOne($JobRawData['department_id']);
                        $job->setDepartment($department);
                    }
                    $applicationsGetQuery = $this->db->prepare("SELECT * FROM HR.job_application WHERE job_id = ?");
                    $applicationsGetQuery->execute([$JobRawData['id']]);
                    $applicationIds = $applicationsGetQuery->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($applicationIds as $applicationId) {
                        $applicationMapper = new ApplicationMapper($this->db);
                        $application = $applicationMapper->findOne($applicationId['application_id']);
                        $job->addApllication($application);
                    }
                    $this->identityMap[$JobRawData['id']] = $job;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    
    public function findBy(array $criteria = []): array
    {
        $query = "SELECT * FROM HR.opining_jobs";
        $whereClouses = [];
        $bindings = [];

        if (isset($criteria['id'])) {
            $whereClouses[] = "id = ?";
            $bindings[] = $criteria['id'];
        }

        if (isset($criteria['title'])) {
            $whereClouses[] = "title LIKE ?";
            $bindings[] = "%" . $criteria['title'] . "%";
        }

        if (isset($criteria['department_id'])) {
            $whereClouses[] = "department_id = ?";
            $bindings[] = $criteria['department_id'];
        }

        if (count($whereClouses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClouses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $jobsRawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($jobsRawData as $jobRawData) {
                if (!isset($this->identityMap[$jobRawData['id']])) {
                    $job = new Job();
                    $job->setId($jobRawData['id']);
                    $job->setTitle($jobRawData['title']);
                    $deparmentMapper = new DepartmentMapper($this->db);
                    if (isset($jobRawData['department_id']) && !is_null($jobRawData['department_id'])) {
                        $department = $deparmentMapper->findOne($jobRawData['department_id']);
                        $job->setDepartment($department);
                    }
                    $this->identityMap[$jobRawData['id']] = $job;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

public function create(Job $job)
    {
        try {
            $createJobQuery = $this->db->prepare("INSERT INTO HR.opining_jobs(title , department_id ) VALUES(? , ?) RETURNING id");
            $createJobQuery->execute([$job->getTitle(), $job->getDepartment()->getId()]);
            $jobId = $createJobQuery->fetch(PDO::FETCH_ASSOC)['id'];
            $job->setId($jobId);
            $this->identityMap[$jobId] = $job;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function update(Job $job)
    {
        try {
            if (isset($this->identityMap[$job->getId()])) {
                $updateJobQuery = $this->db->prepare("UPDATE HR.opining_jobs SET title = ? , department_id = ? WHERE id = ?");
                $updateJobQuery->execute([$job->getTitle(), $job->getDepartment()->getId(), $job->getId()]);
                $this->identityMap[$job->getId()] = $job;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function delete(int $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteJobQuery = $this->db->prepare("DELETE FROM HR.opining_jobs WHERE id = ?");
                $deleteJobQuery->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function assignApplicationToJob(Application $application, Job $job)
    {
        try {
            $assignApplicationToJobQuery = $this->db->prepare("INSERT INTO HR.job_application(application_id , job_id , status) VALUES(? , ? , ? ) ");
            $assignApplicationToJobQuery->execute([$application->getId(), $job->getId(), ApplicationStatusType::APPLIED->value]);
            $job->addApllication($application);
            return $job;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function removeApplicationFromJob(Application $application, Job $job)
    {
        try {
            $removeApplicationFromJobQuery = $this->db->prepare("DELETE FROM HR.job_application WHERE application_id = ? AND job_id  = ?");
            $removeApplicationFromJobQuery->execute([$application->getId(), $job->getId()]);
            $job->removeApplication($application);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
