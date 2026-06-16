<?php
namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\EvolutionCritiriaMapper;
use PostApi\modules\HR\domain\entities\EvolutionCritiria;
use PostApi\shared\templates\DB_Trait;

class EvolutionCritiriaRepository {
    private EvolutionCritiriaMapper $evolutionCritiriaMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->evolutionCritiriaMapper = new EvolutionCritiriaMapper($this->postgre->pdo);
    }

    public function findOne(int $id)
    {
        return $this->evolutionCritiriaMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->evolutionCritiriaMapper->findAll();
    }

    public function create(EvolutionCritiria $evolutionCritiria)
    {
        $this->evolutionCritiriaMapper->create($evolutionCritiria);
    }

    public function update(EvolutionCritiria $evolutionCritiria)
    {
        $this->evolutionCritiriaMapper->update($evolutionCritiria);
    }

    public function delete(int $id)
    {
        $this->evolutionCritiriaMapper->delete($id);
    }
}