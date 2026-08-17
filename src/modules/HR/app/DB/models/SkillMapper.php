<?php

namespace PostApi\modules\HR\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\HR\domain\entities\Skill;

class SkillMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getSkillQuery = $this->db->prepare("SELECT * FROM HR.skills WHERE id = ?");
            $getSkillQuery->execute([$id]);
            $skillRawData = $getSkillQuery->fetch(PDO::FETCH_ASSOC);
            if ($skillRawData) {
                $skill = new Skill();
                $skill->setId($skillRawData['id']);
                $skill->setName($skillRawData['name']);
                $this->identityMap[$id] = $skill;
                return $skill;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function findAll()
    {
        try {
            $getSkillsQuery = $this->db->prepare("SELECT * FROM HR.skills");
            $getSkillsQuery->execute([]);
            $skillsRawData = $getSkillsQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($skillsRawData as $skillRawData) {
                $skill = new Skill();
                $skill->setId($skillRawData['id']);
                $skill->setName($skillRawData['name']);
                $this->identityMap[$skillRawData['id']] = $skill;
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function create(Skill $skill)
    {
        try {
            $createSkillQuery = $this->db->prepare("INSERT INTO HR.skills(name) VALUES (?) RETURNING id ");
            $createSkillQuery->execute([$skill->getName()]);
            $skillId = $createSkillQuery->fetch(PDO::FETCH_ASSOC)['id'];
            $skill->setId($skillId);
            $this->identityMap[$skillId] = $skill;
            return $skill;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Skill $skill)
    {
        try {
            if (isset($this->identityMap[$skill->getId()])) {
                $updateSkillQuery = $this->db->prepare("UPDATE HR.skills SET name = ? WHERE id = ?");
                $updateSkillQuery->execute([$skill->getName(), $skill->getId()]);
                $this->identityMap[$skill->getId()] = $skill;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function delete(int $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteSkillQuery = $this->db->prepare("DELETE FROM HR.skills WHERE id = ?");
                $deleteSkillQuery->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
