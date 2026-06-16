<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\ApplicationTemplate;

class ApplicationTemplateMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getApplicationTemplateQuery = $this->db->prepare("SELECT * FROM HR.appraisal_template WHERE id = ?");
        $getApplicationTemplateQuery->execute([$id]);
        $applicationTemplateRawData = $getApplicationTemplateQuery->fetch(PDO::FETCH_ASSOC);
        if ($applicationTemplateRawData) {
            $applicationTemplate = new ApplicationTemplate($applicationTemplateRawData['id'], $applicationTemplateRawData['title'], $applicationTemplateRawData['description']);
            $this->identityMap[$applicationTemplateRawData['id']] = $applicationTemplate;
            return $applicationTemplate;
        }
    }

    public function findAll()
    {
        $getApplicationsTemplateQuery = $this->db->prepare("SELECT * FROM HR.appraisal_template");
        $getApplicationsTemplateQuery->execute([]);
        $applicationsTemplateRawData = $getApplicationsTemplateQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($applicationsTemplateRawData as $applicationTemplateRawData) {
            $applicationTemplate = new ApplicationTemplate($applicationTemplateRawData['id'], $applicationTemplateRawData['title'], $applicationTemplateRawData['description']);

            if (!isset($this->identityMap[$applicationTemplateRawData['id']])) {
                $this->identityMap[$applicationTemplateRawData['id']] = $applicationTemplate;
            }
        }
        return $this->identityMap;
    }

    public function create(ApplicationTemplate $applicationTemplate)
    {
        $createApplicationTemplateQuery = $this->db->prepare("INSERT INTO HR.appraisal_template(title , description) VALUES(? , ? ) RETURNING id ");
        $createApplicationTemplateQuery->execute([$applicationTemplate->getTitle() , $applicationTemplate->getDescription()]);
        $applicationTemplateId = $createApplicationTemplateQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $applicationTemplate->setId($applicationTemplateId);
        $this->identityMap[$applicationTemplate->getId()] = $applicationTemplate;
    }

    public function update(ApplicationTemplate $applicationTemplate)
    {
        if (isset($this->identityMap[$applicationTemplate->getId()])) {
            $updateApplicationTemplateQuery = $this->db->prepare("UPDATE HR.appraisal_template SET title = ? , description = ? WHERE id = ?");
            $updateApplicationTemplateQuery->execute([$applicationTemplate->getTitle() , $applicationTemplate->getDescription() , $applicationTemplate->getId()]);
            $this->identityMap[$applicationTemplate->getId()] = $applicationTemplate;
        }
    }

    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteApplicationQuery = $this->db->prepare("DELETE FROM HR.appraisal_template WHERE id = ?");
            $deleteApplicationQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
