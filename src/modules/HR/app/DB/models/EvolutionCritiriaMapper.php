<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\EvolutionCritiria;

class EvolutionCritiriaMapper
{
    private array $identityMap = [];
    private ApplicationTemplateMapper $applicationTemplateMapper;
    public function __construct(private PDO $db)
    {
        $this->applicationTemplateMapper = new ApplicationTemplateMapper($this->db);
    }
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        $getEvolutionCritiriaQuery = $this->db->prepare("SELECT * FROM HR.evolution_critiria WHERE id = ?");
        $getEvolutionCritiriaQuery->execute([$id]);
        $evolutionCritiriaRawData = $getEvolutionCritiriaQuery->fetch(PDO::FETCH_ASSOC);
        if ($evolutionCritiriaRawData) {
            $template = $this->applicationTemplateMapper->findOne($evolutionCritiriaRawData['template_id']);
            $evolutionCritiria = new EvolutionCritiria($evolutionCritiriaRawData['id'], $evolutionCritiriaRawData['critiria'], $evolutionCritiriaRawData['weight'], $template);
            $this->identityMap[$evolutionCritiriaRawData['id']];
            return $evolutionCritiria;
        }
    }

    public function findAll()
    {
        $getEvolutionCritiriaQuery = $this->db->prepare("SELECT * FROM HR.evolution_critiria");
        $getEvolutionCritiriaQuery->execute([]);
        $evolutionsCritiriaRawData = $getEvolutionCritiriaQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($evolutionsCritiriaRawData as $evolutionCritiriaRawData) {
            $template = $this->applicationTemplateMapper->findOne($evolutionCritiriaRawData['template_id']);
            $evolutionCritiria = new EvolutionCritiria($evolutionCritiriaRawData['id'], $evolutionCritiriaRawData['critiria'], $evolutionCritiriaRawData['weight'], $template);
            if (!isset($this->identityMap[$evolutionCritiriaRawData['id']])) {
                $this->identityMap[$evolutionCritiriaRawData['id']] = $evolutionCritiria;
            }
        }
        return $this->identityMap;
    }

    public function create(EvolutionCritiria $evolutionCritiria)
    {
        $createEvolutionCritiriaQuery = $this->db->prepare("INSERT INTO HR.evolution_critiria(template_id , critiria , weight) VALUES(? , ? , ?) RETURNING id ");
        $createEvolutionCritiriaQuery->execute([$evolutionCritiria->getTemplate()->getId(), $evolutionCritiria->getCritiria(), $evolutionCritiria->getWeight()]);
        $evolutionCritiriaId = $createEvolutionCritiriaQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $evolutionCritiria->setId($evolutionCritiriaId);
        $this->identityMap[$evolutionCritiria->getId()] = $evolutionCritiria;
    }

    public function update(EvolutionCritiria $evolutionCritiria)
    {
        $updateEvolutionCritiriaQuery = $this->db->prepare("UPDATE HR.evolution_critiria SET template_id = ? , critiria = ? , weight = ? WHERE id = ?");
        $updateEvolutionCritiriaQuery->execute([$evolutionCritiria->getTemplate()->getId(), $evolutionCritiria->getCritiria(), $evolutionCritiria->getWeight(), $evolutionCritiria->getId()]);
        $this->identityMap[$evolutionCritiria->getId()] = $evolutionCritiria;
    }

    public function delete(int $id)
    {
        $deleteApplicationQuery = $this->db->prepare("DELETE FROM HR.evolution_critiria WHERE id = ?");
        $deleteApplicationQuery->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
