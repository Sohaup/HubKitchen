<?php

namespace PostApi\modules\HR\domain\entities;

class AppraiselResult
{
    private ?int $id;
    private ApplicationCycle $cycle;
    private Employee $employee;
    private EvolutionCritiria $critiria;
    private float $score;
    private string $mangerComments;
    public function __construct(?int $id = null, ApplicationCycle $cycle, EvolutionCritiria $critiria, Employee $employee, float $score, string $mangerComments)
    {
        $this->id = $id ?? 0;
        $this->cycle = $cycle;
        $this->critiria = $critiria;
        $this->employee = $employee;
        $this->score = $score;
        $this->mangerComments = $mangerComments;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setCycle(ApplicationCycle $cycle)
    {
        $this->cycle = $cycle;
    }
    public function getCycle()
    {
        return $this->cycle;
    }

    public function setEmployee(Employee $employee)
    {
        $this->employee = $employee;
    }
    public function getEmployee()
    {
        return $this->employee;
    }

    public function setCritiria(EvolutionCritiria $critiria)
    {
        $this->critiria = $critiria;
    }
    public function getCritiria()
    {
        return $this->critiria;
    }

    public function setScore(float $score)
    {
        $this->score = $score;
    }
    public function getScore()
    {
        return $this->score;
    }

    public function setManagerComments(string $mangerComments)
    {
        $this->mangerComments = $mangerComments;
    }
    public function getManagerComments()
    {
        return $this->mangerComments;
    }
}
