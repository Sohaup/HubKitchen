<?php

namespace PostApi\modules\HR\domain\entities;

class EvolutionCritiria
{
    private ?int $id = 0;
    private ApplicationTemplate $template;
    private string $critiria;
    private int $weight;

    public function __construct(?int $id = null, string $critiria, int $weight, ApplicationTemplate $application)
    {
        $this->id = $id ?? 0;
        $this->critiria = $critiria;
        $this->weight = $weight;
        $this->template = $application;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setTemplate(ApplicationTemplate $template)
    {
        $this->template = $template;
    }
    public function getTemplate()
    {
        return $this->template;
    }

    public function setCritiria(string $critiria)
    {
        $this->critiria = $critiria;
    }
    public function getCritiria()
    {
        return $this->critiria;
    }

    public function setWeight(int $weight)
    {
        $this->weight = $weight;
    }
    public function getWeight()
    {
        return $this->weight;
    }
}
