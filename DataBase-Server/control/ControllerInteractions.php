<?php

namespace control;

use data\DataAccess;
use service\PartieChecking;
use service\CannotDoException;

/**
 * Class ControllerInteractions
 * @package control
 */
class ControllerInteractions
{

    /**
     * Array containing available interaction types.
     * @var string[]
     */
    private array $interactionTypes = ["SliderOrbit", "SliderRotation", "DragOrbit", "DragRotation"];

    /**
     * Returns an array of available interaction types.
     *
     * @return string[] An array of available interaction types.
     */
    public function getInteractionTypesAvailable(): array {
        return $this->interactionTypes;
    }

    /**
     * Adds an interaction.
     *
     * @param string $nomInteract The name of the interaction.
     * @param float $valeurInteract The value of the interaction.
     * @param int $isEval The evaluation status of the interaction.
     * @param string $ipJoueur The IP address of the player.
     * @param string $dateInterac The date of the interaction.
     * @param PartieChecking $interactionService An instance of PartieChecking service.
     * @param DataAccess $data An instance of DataAccess.
     * @throws CannotDoException If the interaction type is not correct.
     * @return void
     */
    public function addInteration(string $nomInteract, float $valeurInteract, int $isEval, string $ipJoueur, string $dateInterac, PartieChecking $interactionService, DataAccess $data): void {
        if (!$interactionService->addInteraction($nomInteract, $valeurInteract, $isEval, $ipJoueur, $dateInterac, $data)){
            $target = "DataBase Interaction ";
            $action = "Register Interaction";
            $explanation = "Interaction type not correct: " . $nomInteract;
            throw new CannotDoException($target, $action, $explanation);
        }
    }
}
