<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\JobDescription;
use PostApi\modules\HR\domain\entities\Shift;
use PostApi\modules\HR\domain\entities\Skill;

class JobDescriptionMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        $getJobDescriptionQuery = $this->db->prepare(
            "SELECT * FROM HR.job_description_view WHERE id = ?"
        );
        $getJobDescriptionQuery->execute([$id]);
        $jobDescriptionRawData = $getJobDescriptionQuery->fetch(PDO::FETCH_ASSOC);
        if ($jobDescriptionRawData) {
            $jobDescription = new JobDescription();
            $jobDescription->setId($jobDescriptionRawData['id']);
            $jobDescription->setName($jobDescriptionRawData['name']);
            $shift = new Shift();
            $shift->setId($jobDescriptionRawData['shift_id']);
            $shift->setShiftName($jobDescriptionRawData['shift_name']);
            $shift->setStartTime($jobDescriptionRawData['shift_start_time']);
            $shift->setEndTime($jobDescriptionRawData['shift_end_time']);
            $shift->setBreakDuration($jobDescriptionRawData['shift_break_duration_by_minutes']);
            $shift->setIsActive($jobDescriptionRawData['shift_is_active']);
            $shift->setIsOverNight($jobDescriptionRawData['shift_is_overnight']);
            $shift->setCreatedAt($jobDescriptionRawData['shift_created_at']);
            $jobDescription->setShift($shift);
            $skillIds = $jobDescriptionRawData['all_skills_id'] ?  explode(",", $jobDescriptionRawData['all_skills_id']) : "";
            $skillsName = $jobDescriptionRawData['all_skills_name'] ? explode(",", $jobDescriptionRawData['all_skills_name']) : "";
            if (!empty($skillIds)) {
                foreach ($skillIds as $index => $skillId) {
                    $skill = new Skill();
                    $skill->setId($skillId);
                    $skill->setName($skillsName[$index]);
                    $jobDescription->addSkill($skill);
                }
            }
            $this->identityMap[$id] = $jobDescription;
        }
        return $this->identityMap[$id];
    }
    public function findAll()
    {
        $getJobsDescriptionQuery = $this->db->prepare("SELECT * FROM HR.job_description_view");
        $getJobsDescriptionQuery->execute([]);
        $jobsDescriptionRawData = $getJobsDescriptionQuery->fetchAll(PDO::FETCH_ASSOC);
        if ($jobsDescriptionRawData) {
            foreach ($jobsDescriptionRawData as $jobDescriptionRawData) {
                if (!isset($this->identityMap[$jobDescriptionRawData['id']])) {
                    $jobDescription = new JobDescription();
                    $jobDescription->setId($jobDescriptionRawData['id']);
                    $jobDescription->setName($jobDescriptionRawData['name']);
                    $shift = new Shift();
                    $shift->setId($jobDescriptionRawData['shift_id']);
                    $shift->setShiftName($jobDescriptionRawData['shift_name']);
                    $shift->setStartTime($jobDescriptionRawData['shift_start_time']);
                    $shift->setEndTime($jobDescriptionRawData['shift_end_time']);
                    $shift->setBreakDuration($jobDescriptionRawData['shift_break_duration_by_minutes']);
                    $shift->setIsActive($jobDescriptionRawData['shift_is_active']);
                    $shift->setIsOverNight($jobDescriptionRawData['shift_is_overnight']);
                    $shift->setCreatedAt($jobDescriptionRawData['shift_created_at']);
                    $jobDescription->setShift($shift);
                    $skillIds = $jobDescriptionRawData['all_skills_id'] ?  explode(",", $jobDescriptionRawData['all_skills_id']) : "";
                    $skillsName = $jobDescriptionRawData['all_skills_name'] ? explode(",", $jobDescriptionRawData['all_skills_name']) : "";
                    if (!empty($skillIds)) {
                        foreach ($skillIds as $index => $skillId) {
                            $skill = new Skill();
                            $skill->setId($skillId);
                            $skill->setName($skillsName[$index]);
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
