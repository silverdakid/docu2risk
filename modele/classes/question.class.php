<?php
/**
 * Question
 * Classe Question
 */
class Question {
    private int $idQuestion;
    private string $question;
    private int $questionPoint;

    function __construct(int $id=0, string $question='', int $questionPoint=0)
    {
        $this->idQuestion = $id;
        $this->question = $question;
        $this->questionPoint = $questionPoint;
    }

    function getIdQuestion():int {return $this->idQuestion;}
    function setIdQuestion(int $id):void {$this->idQuestion = $id;}
    function getQuestion():string {return $this->question;}
    function setQuestion(string $v):void {$this->question = $v;}
    function getQuestionPoint():int {return $this->questionPoint;}
    function setQuestionPoint(int $v):void {$this->questionPoint = $v;}

}