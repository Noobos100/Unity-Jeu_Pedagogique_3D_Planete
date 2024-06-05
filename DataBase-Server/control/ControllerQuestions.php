<?php

namespace control;

use data\DataAccess;
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
     * @param DataAccess $data An instance of DataAccess.
     * @param int $howManyQCU The number of multiple-choice questions.
     * @param int $howManyInterac The number of interactive questions.
     * @param int $howManyVraiFaux The number of true/false questions.
     * @return string JSON containing random questions.
     */
    public function getJsonRandomQs(PartieChecking $questionService, DataAccess $data, int $howManyQCU = 0, int $howManyInterac = 0, int $howManyVraiFaux = 0): string {
        $numQs = $questionService->getRandomQs($howManyQCU, $howManyInterac, $howManyVraiFaux, $data);
        $tmp = new \stdClass();
        $tmp->list = $numQs;
        return json_encode($tmp);
    }

    /**
     * Generates JSON containing attributes of all questions.
     */
    public function getJsonAttributesAllQ(PartieChecking $questionService, DataAccess $data): string {
        $json = json_encode($questionService->getAllQ($data), JSON_UNESCAPED_UNICODE);
        return preg_replace('/null/', '-1', $json);
    }


    /**
     * Generates JSON containing attributes of a specific question.
     *
     * @param int $numQues The number of the question.
     * @param PartieChecking $questionService An instance of PartieChecking service.
     * @param DataAccess $data An instance of DataAccess.
     * @return string JSON containing attributes of the question.
     */
    public function getJsonAttributesQ(int $numQues, PartieChecking $questionService, DataAccess $data): string {
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
     * @param DataAccess $data An instance of DataAccess.
     * @return void
     */
    public function addFinishedQuestion(int $numQues, int $idParty, string $dateDeb, string $dateFin, bool $isCorrect, PartieChecking $questionService, DataAccess $data): void {
        $questionService->addQuestionAnswer($numQues, $idParty, $dateDeb, $dateFin, $isCorrect, $data);
    }

    /**
     *  Updates QCU type questions
     *
     * @param int $numQues
     * @param string $question
     * @param string $option1
     * @param string $option2
     * @param string $option3
     * @param string $option4
     * @param string $correct
     * @param PartieChecking $questionService
     * @param DataAccess $data
     * @return void
     */
    public function updateQQCU(int $numQues, string $question, string $option1, string $option2, string $option3, string $option4, string $correct, PartieChecking $questionService, DataAccess $data): void
    {
        $questionService->updateQCU($numQues, $question, $option1, $option2, $option3, $option4, $correct, $data);
    }

    /**
     * @param mixed $Num_Ques
     * @param mixed $question
     * @param string $orbit
     * @param string $rotation
     * @param mixed $correct
     * @param PartieChecking $partieChecking
     * @param DataAccess $data
     * @return void
     */
    public function updateQVraiFaux(mixed $Num_Ques, mixed $question, string $orbit, string $rotation, string $correct, PartieChecking $partieChecking, DataAccess $data): void
    {
        $partieChecking->updateQVraiFaux($Num_Ques, $question, $orbit, $rotation, $correct, $data);
    }

    /**
     * @param mixed $Num_Ques
     * @param mixed $question
     * @param string $orbit
     * @param string $rotation
     * @param string $rotationMargin
     * @param string $orbitMargin
     * @param PartieChecking $partieChecking
     * @param DataAccess $data
     * @return void
     */
    public function updateQInterac(mixed $Num_Ques, mixed $question, string $orbit, string $rotation, string $rotationMargin, string $orbitMargin, PartieChecking $partieChecking, DataAccess $data): void
    {
        $partieChecking->updateQInterac($Num_Ques, $question, $orbit, $rotation, $rotationMargin, $orbitMargin, $data);
    }

    /**
     * @param int $numQues
     * @param PartieChecking $questionService
     * @param DataAccess $data
     * @return void
     */
    public function deleteQuestion(int $numQues, PartieChecking $questionService, DataAccess $data): void
    {
        $questionService->deleteQuestion($numQues, $data);
    }

    /**
     * @param string $question
     * @param string|null $orbit
     * @param string|null $rotation
     * @param string $correct
     * @param PartieChecking $partieChecking
     * @param DataAccess $data
     * @return void
     */
    public function addQVraiFaux(string $question, ?string $orbit, ?string $rotation, string $correct, PartieChecking $partieChecking, DataAccess $data): void
    {
        $partieChecking->addQVraiFaux($question, $orbit, $rotation, $correct, $data);
    }

    /**
     * @param string $question
     * @param string $option1
     * @param string $option2
     * @param string $option3
     * @param string $option4
     * @param string $correct
     * @param PartieChecking $partieChecking
     * @param DataAccess $data
     * @return void
     */
    public function addQCU(string $question, string $option1, string $option2, string $option3, string $option4, string $correct, PartieChecking $partieChecking, DataAccess $data): void
    {
        $partieChecking->addQCU($question, $option1, $option2, $option3, $option4, $correct, $data);
    }

    /**
     * @param string $question
     * @param string $orbit
     * @param string $rotation
     * @param string $rotationMargin
     * @param string $orbitMargin
     * @param PartieChecking $partieChecking
     * @param DataAccess $data
     * @return void
     */
    public function addQInterac(string $question, string $orbit, string $rotation, string $rotationMargin, string $orbitMargin, PartieChecking $partieChecking, DataAccess $data): void
    {
        $partieChecking->addQInterac($question, $orbit, $rotation, $rotationMargin, $orbitMargin, $data);
    }
}