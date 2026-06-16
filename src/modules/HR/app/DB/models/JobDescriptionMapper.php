<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\JobDescription;
use PostApi\modules\HR\domain\entities\Skill;

class JobDescriptionMapper
{
    private array $identityMap = [];
    private ShiftMapper $shiftMapper;
    private SkillMapper $skillMapper;
    public function __construct(private PDO $db)
    {
        $this->shiftMapper = new ShiftMapper($db);
        $this->skillMapper = new SkillMapper($db);
    }
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        $getJobDescriptionQuery = $this->db->prepare("SELECT * FROM HR.jobs_description WHERE id = ?");
        $getJobDescriptionQuery->execute([$id]);
        $jobDescriptionRawData = $getJobDescriptionQuery->fetch(PDO::FETCH_ASSOC);
        if ($jobDescriptionRawData) {
            $jobDescription = new JobDescription();
            $jobDescription->setId($jobDescriptionRawData['id']);
            $jobDescription->setName($jobDescriptionRawData['name']);
            $shift = $this->shiftMapper->findOne($jobDescriptionRawData['shift_id']);
            $jobDescription->setShift($shift);
            $skillsGetQuery = $this->db->prepare("SELECT * FROM HR.job_skill WHERE jd_id = ?");
            $skillsGetQuery->execute([$id]);
            $skillIds = $skillsGetQuery->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($skillIds)) {
                foreach ($skillIds as $skillId) {
                    $skill = $this->skillMapper->findOne($skillId['skill_id']);
                    $jobDescription->addSkill($skill);
                }
            }

            $this->identityMap[$id] = $jobDescription;
        }
        return $this->identityMap[$id];
    }
    public function findAll()
    {
        $getJobsDescriptionQuery = $this->db->prepare("SELECT * FROM HR.jobs_description");
        $getJobsDescriptionQuery->execute([]);
        $jobsDescriptionRawData = $getJobsDescriptionQuery->fetchAll(PDO::FETCH_ASSOC);
        if ($jobsDescriptionRawData) {
            foreach ($jobsDescriptionRawData as $jobDescriptionRawData) {
                if (!isset($this->identityMap[$jobDescriptionRawData['id']])) {
                    $jobDescription = new JobDescription();
                    $jobDescription->setId($jobDescriptionRawData['id']);
                    $jobDescription->setName($jobDescriptionRawData['name']);
                    $shift = $this->shiftMapper->findOne($jobDescriptionRawData['shift_id']);
                    $jobDescription->setShift($shift);
                    $skillsGetQuery = $this->db->prepare("SELECT * FROM HR.job_skill WHERE jd_id = ?");
                    $skillsGetQuery->execute([$jobDescriptionRawData['id']]);
                    $skillIds = $skillsGetQuery->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($skillIds)) {
                        foreach ($skillIds as $skillId) {
                            $skill = $this->skillMapper->findOne($skillId['skill_id']);
                            $jobDescription->addSkill($skill);
                        }
                    }
                    $this->identityMap[$jobDescriptionRawData['id']] = $jobDescription;
                }
            }
        }
        return $this->identityMap;
    }
    public function create(JobDescription $jobDescription)
    {
        $createJobDescriptionQuery = $this->db->prepare("INSERT INTO HR.jobs_description(name , shift_id) VALUES(? , ?) RETURNING id ");
        $createJobDescriptionQuery->execute([$jobDescription->getName(), $jobDescription->getShift()->getId()]);
        $jobDescriptionId = $createJobDescriptionQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $jobDescription->setId($jobDescriptionId);
        $this->identityMap[$jobDescription->getId()] = $jobDescription;
    }
    public function update(JobDescription $jobDescription)
    {
        if (isset($this->identityMap[$jobDescription->getId()])) {
            $updateJobDescriptionQuery = $this->db->prepare("UPDATE HR.jobs_description SET name = ? , shift_id  = ?  WHERE id = ? ");
            $updateJobDescriptionQuery->execute([$jobDescription->getName(), $jobDescription->getShift()->getId(), $jobDescription->getId()]);
            $this->identityMap[$jobDescription->getId()] = $jobDescription;
        }
    }
    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteJobDescriptionQuery = $this->db->prepare("DELETE FROM HR.jobs_description WHERE id = ?");
            $deleteJobDescriptionQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
    public function assertSkillToJob(Skill $skill, JobDescription $jobDescription)
    {
        $assertSkillToJobQuery = $this->db->prepare("INSERT INTO HR.job_skill(jd_id , skill_id) VALUES(? , ?) RETURNING id ");
        $assertSkillToJobQuery->execute([$jobDescription->getId(), $skill->getId()]);
        $jobDescription->addSkill($skill);
        return $jobDescription;
    }
    public function removeSkillFromJob(Skill $skill, JobDescription $jobDescription)
    {
        $removeSkillFromQuery = $this->db->prepare("DELETE FROM HR.job_skill WHERE jd_id = ? AND skill_id = ?");
        $removeSkillFromQuery->execute([$jobDescription->getId(), $skill->getId()]);
        $jobDescription->removeSkill($skill);
    }
}
