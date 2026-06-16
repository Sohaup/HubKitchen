<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\Application;

class ApplicationMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getApplicationQuery = $this->db->prepare("SELECT * FROM HR.applications WHERE id = ?");
        $getApplicationQuery->execute([$id]);
        $applicationRawData = $getApplicationQuery->fetch(PDO::FETCH_ASSOC);
        if ($applicationRawData) {
            $application = new Application();
            $application->setId($applicationRawData['id']);
            $application->setName($applicationRawData['name']);
            $application->setEmail($applicationRawData['email']);
            $application->setPhone($applicationRawData['phone']);
            $application->setCv($applicationRawData['cv']);
            $this->identityMap[$applicationRawData['id']];
            return $application;
        }
    }

    public function findAll()
    {
        $getApplicationsQuery = $this->db->prepare("SELECT * FROM HR.applications ");
        $getApplicationsQuery->execute([]);
        $applicationsRawData = $getApplicationsQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($applicationsRawData as $applicationRawData) {
            $application = new Application();
            $application->setId($applicationRawData['id']);
            $application->setName($applicationRawData['name']);
            $application->setEmail($applicationRawData['email']);
            $application->setPhone($applicationRawData['phone']);
            $application->setCv($applicationRawData['cv']);
            if (!isset($this->identityMap[$applicationRawData['id']])) {
                $this->identityMap[$applicationRawData['id']] = $application;
            }
        }
        return $this->identityMap;
    }

    public function create(Application $application)
    {
        $createApplicationQuery = $this->db->prepare("INSERT INTO HR.applications(name , email , phone , cv ) VALUES(? , ? , ? , ? ) RETURNING id ");
        $createApplicationQuery->execute([$application->getName(), $application->getEmail(), $application->getPhone(), $application->getCv()]);
        $applicationId = $createApplicationQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $application->setId($applicationId);
        $this->identityMap[$application->getId()] = $application;
    }

    public function update(Application $application)
    {

        $updateApplicationQuery = $this->db->prepare("UPDATE HR.applications SET name = ? , email = ? , phone = ? , cv = ? WHERE id = ?");
        $updateApplicationQuery->execute([$application->getName(), $application->getEmail(), $application->getPhone(), $application->getCv(), $application->getId()]);
        $this->identityMap[$application->getId()] = $application;
    }

    public function delete(int $id)
    {

        $deleteApplicationQuery = $this->db->prepare("DELETE FROM HR.applications WHERE id = ?");
        $deleteApplicationQuery->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
