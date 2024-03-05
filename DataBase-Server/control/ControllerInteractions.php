<?php

namespace control;

use service\PartieChecking;
use service\CannotDoException;

class ControllerInteractions
{

    private array $interactionTypes = ["SliderOrbit", "SliderRotation", "DragOrbit", "DragRotation"];

    /**
     * @return string[]
     */
    public function getInteractionTypesAvailable(): array {
        return $this->interactionTypes;
    }

    /**
     * @param string $ipJoueur
     * @param string $nomInteract
     * @param float $valeurInteract
     * @param int $isEval
     * @return void
     */
    public function addInteration(string $nomInteract, float $valeurInteract, int $isEval, string $ipJoueur, string $dateInterac, PartieChecking $interactionService, $data): void {
        if (!$interactionService->addInteraction($nomInteract, $valeurInteract, $isEval, $ipJoueur, $dateInterac, $data)){
            $target = "DataBase Interaction ";
            $action = "Register Interaction";
            $explanation = "Interaction type not correct: " . $nomInteract;
            throw new CannotDoException($target, $action, $explanation);
        }
    }
}