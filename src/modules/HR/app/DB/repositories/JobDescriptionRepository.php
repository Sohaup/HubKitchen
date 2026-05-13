<?php

namespace PostApi\modules\HR\app\DB\repositories;

use PDO;
use PostApi\modules\HR\app\DB\models\JobDescriptionMapper;
use PostApi\modules\HR\domain\entities\JobDescription;
use PostApi\modules\HR\domain\entities\Skill;
use PostApi\shared\templates\DB_Trait;

class JobDescriptionRepository
{
    private JobDescriptionMapper $jobDescriptionMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->jobDescriptionMapper = new JobDescriptionMapper($this->postgre->pdo);
    }
    public function findOne(int $id)
    {
        return $this->jobDescriptionMapper->findOne($id);
    }
    public function findAll()
    {
        return $this->jobDescriptionMapper->findAll();
    }
    public function create(JobDescription $jobDescription)
    {
        $this->jobDescriptionMapper->create($jobDescription);
    }
    public function update(JobDescription $jobDescription)
    {
        $this->jobDescriptionMapper->update($jobDescription);
    }
    public function delete(int $id)
    {
        $this->jobDescriptionMapper->delete($id);
    }
    public function assertSkillToJob(Skill $skill, JobDescription $jobDescription)
    {
        return $this->jobDescriptionMapper->assertSkillToJob($skill, $jobDescription);
    }
    public function removeSkillFromJob(Skill $skill, JobDescription $jobDescription)
    {
        $this->jobDescriptionMapper->removeSkillFromJob($skill, $jobDescription);
    }
}
