<?php

namespace PostApi\modules\HR\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\HR\domain\entities\ApplicationTemplate;
use PostApi\modules\HR\domain\entities\EvolutionCritiria;

class EvolutionCritiriaMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getEvolutionCritiriaQuery = $this->db->prepare("SELECT critiria_id , critiria , critiria_weight , template_id , template_title , template_description FROM HR.evoluation_view WHERE critiria_id = ?");
            $getEvolutionCritiriaQuery->execute([$id]);
            $evolutionCritiriaRawData = $getEvolutionCritiriaQuery->fetch(PDO::FETCH_ASSOC);
            if ($evolutionCritiriaRawData) {
                $template = new ApplicationTemplate(id: $evolutionCritiriaRawData['template_id'], title: $evolutionCritiriaRawData['template_title'], description: $evolutionCritiriaRawData['template_description']);
                $evolutionCritiria = new EvolutionCritiria($evolutionCritiriaRawData['critiria_id '], $evolutionCritiriaRawData['critiria'], $evolutionCritiriaRawData['critiria_weight'], $template);
                $this->identityMap[$evolutionCritiriaRawData['critiria_id']] = $evolutionCritiria;
                return $evolutionCritiria;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $getEvolutionCritiriaQuery = $this->db->prepare("SELECT critiria_id , critiria , critiria_weight , template_id , template_title , template_description FROM HR.evoluation_view WHERE critiria_id IS NOT NULL");
            $getEvolutionCritiriaQuery->execute([]);
            $evolutionsCritiriaRawData = $getEvolutionCritiriaQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($evolutionsCritiriaRawData as $evolutionCritiriaRawData) {
                $template = new ApplicationTemplate(id: $evolutionCritiriaRawData['template_id'], title: $evolutionCritiriaRawData['template_title'], description: $evolutionCritiriaRawData['template_description']);
                $evolutionCritiria = new EvolutionCritiria($evolutionCritiriaRawData['critiria_id '], $evolutionCritiriaRawData['critiria'], $evolutionCritiriaRawData['critiria_weight'], $template);
                if (!isset($this->identityMap[$evolutionCritiriaRawData['id']])) {
                    $this->identityMap[$evolutionCritiriaRawData['critiria_id']] = $evolutionCritiria;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    
    public function findBy(array $criteria = []): array
    {
        $query = "SELECT critiria_id , critiria , critiria_weight , template_id , template_title , template_description FROM HR.evoluation_view";
        $whereClouses = [];
        $bindings = [];

        if (isset($criteria['critiria_id'])) {
            $whereClouses[] = "critiria_id = ?";
            $bindings[] = $criteria['critiria_id'];
        }

        if (isset($criteria['critiria'])) {
            $whereClouses[] = "critiria LIKE ?";
            $bindings[] = "%" . $criteria['critiria'] . "%";
        }

        if (isset($criteria['template_id'])) {
            $whereClouses[] = "template_id = ?";
            $bindings[] = $criteria['template_id'];
        }

        if (count($whereClouses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClouses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $evolutionsCritiriaRawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($evolutionsCritiriaRawData as $evolutionCritiriaRawData) {
                $template = new ApplicationTemplate(id: $evolutionCritiriaRawData['template_id'], title: $evolutionCritiriaRawData['template_title'], description: $evolutionCritiriaRawData['template_description']);
                $evolutionCritiria = new EvolutionCritiria($evolutionCritiriaRawData['critiria_id'], $evolutionCritiriaRawData['critiria'], $evolutionCritiriaRawData['critiria_weight'], $template);
                $this->identityMap[$evolutionCritiriaRawData['critiria_id']] = $evolutionCritiria;
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

public function create(EvolutionCritiria $evolutionCritiria)
    {
        try {
            $createEvolutionCritiriaQuery = $this->db->prepare("INSERT INTO HR.evolution_critiria(template_id , critiria , weight) VALUES(? , ? , ?) RETURNING id ");
            $createEvolutionCritiriaQuery->execute([$evolutionCritiria->getTemplate()->getId(), $evolutionCritiria->getCritiria(), $evolutionCritiria->getWeight()]);
            $evolutionCritiriaId = $createEvolutionCritiriaQuery->fetch(PDO::FETCH_ASSOC)['id'];
            $evolutionCritiria->setId($evolutionCritiriaId);
            $this->identityMap[$evolutionCritiria->getId()] = $evolutionCritiria;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(EvolutionCritiria $evolutionCritiria)
    {
        try {
            $updateEvolutionCritiriaQuery = $this->db->prepare("UPDATE HR.evolution_critiria SET template_id = ? , critiria = ? , weight = ? WHERE id = ?");
            $updateEvolutionCritiriaQuery->execute([$evolutionCritiria->getTemplate()->getId(), $evolutionCritiria->getCritiria(), $evolutionCritiria->getWeight(), $evolutionCritiria->getId()]);
            $this->identityMap[$evolutionCritiria->getId()] = $evolutionCritiria;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $deleteApplicationQuery = $this->db->prepare("DELETE FROM HR.evolution_critiria WHERE id = ?");
            $deleteApplicationQuery->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
