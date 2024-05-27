<?php

namespace control;

use service\PartieChecking;

/**
 * Class ControllerQuestions
 * @package control
 */
class ControllerQuestions
{
    /**
     * Generates JSON containing random questions.
     *
     * @param PartieChecking $questionService An instance of PartieChecking service.
     * @param mixed $data Additional data.
     * @param int $howManyQCU The number of multiple-choice questions.
     * @param int $howManyInterac The number of interactive questions.
     * @param int $howManyVraiFaux The number of true/false questions.
     * @return string JSON containing random questions.
     */
    public function getJsonRandomQs(PartieChecking $questionService, mixed $data, int $howManyQCU = 0, int $howManyInterac = 0, int $howManyVraiFaux = 0): string {
        $numQs = $questionService->getRandomQs($howManyQCU, $howManyInterac, $howManyVraiFaux, $data);
        $tmp = new \stdClass();
        $tmp->list = $numQs;
        return json_encode($tmp);
    }

    /**
     * Generates JSON containing attributes of all questions.
     */
    public function getJsonAttributesAllQ(PartieChecking $questionService, mixed $data): string {
        $json = json_encode($questionService->getAllQ($data), JSON_UNESCAPED_UNICODE);
        return preg_replace('/null/', '-1', $json);
    }


    /**
     * Generates JSON containing attributes of a specific question.
     *
     * @param int $numQues The number of the question.
     * @param PartieChecking $questionService An instance of PartieChecking service.
     * @param mixed $data Additional data.
     * @return string JSON containing attributes of the question.
     */
    public function getJsonAttributesQ(int $numQues, PartieChecking $questionService, mixed $data): string {
        $json = json_encode($questionService->getQAttributes($numQues, $data), JSON_UNESCAPED_UNICODE);
        return preg_replace('/null/', '-1', $json);
    }

    /**
     * Adds a finished question to the database.
     *
     * @param int $numQues The number of the question.
     * @param int $idParty The ID of the party.
     * @param string $dateDeb The start date of the party.
     * @param string $dateFin The end date of the party.
     * @param bool $isCorrect Indicates if the answer is correct.
     * @param PartieChecking $questionService An instance of PartieChecking service.
     * @param mixed $data Additional data.
     * @return void
     */
    public function addFinishedQuestion(int $numQues, int $idParty, string $dateDeb, string $dateFin, bool $isCorrect, PartieChecking $questionService, mixed $data): void {
        $questionService->addQuestionAnswer($numQues, $idParty, $dateDeb, $dateFin, $isCorrect, $data);
    }

    public function updateQQCU(int $numQues, string $question, string $option1, string $option2, string $option3, string $option4, string $correct, PartieChecking $questionService, mixed $data): void {
        $questionService->updateQCU($numQues, $question, $option1, $option2, $option3, $option4, $correct, $data);
    }

}