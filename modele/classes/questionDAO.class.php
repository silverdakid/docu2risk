<?php
require_once dirname(__DIR__, 1) . "/connexion.php";
require_once __DIR__ . "/question.class.php";

/**
 * Question DAO
 * DAO : Permets de manipuler les données de la table QUESTIONS
 */
class QuestionDAO
{
    private Connexion $bd;
    private string $select;

    public function __construct()
    {
        $this->bd = new Connexion();
        $this->select = 'SELECT * FROM questions';
    }

    /**
     * insert
     * Insère une question dans la DB
     * @param  Question $question
     * @return void
     */
    public function insert(Question $question): void
    {
        $this->bd->execSQL(
            "INSERT INTO questions (id_question, question, question_point) VALUES (:id, :q, :pts)",
            [
                ":id" => $question->getIdQuestion(), ":q" => $question->getQuestion(), ":pts" => $question->getQuestionPoint()
            ]
        );
    }

    /**
     * updateValue
     * Mets à jour un champ précis, pour une question précise, à une valeur précise.
     * @param  string $id Identifiant de la question
     * @param  string $value Valeur remplaçante
     * @param  string $field Champ concerné
     * @return void
     */
    public function updateValue(string $id, string $value, string $field): void
    {
        $req = "UPDATE questions SET " . $field . " = :val WHERE id_question = :id";
        $this->bd->execSQL($req, [":val" => $value, ":id" => $id]);
    }

    /**
     * loadQuery
     * Transforme un résultat SQL en tableau d'instance de la classe correspondante
     * @param  array $result
     * @return Question[]
     */
    private function loadQuery(array $result): array
    {
        $questions = [];
        foreach ($result as $row) {
            $question = new Question();
            $question->setIdQuestion($row['id_question']);
            $question->setQuestion($row['question']);
            $question->setQuestionPoint($row['question_point']);
            $questions[] = $question;
        }
        return $questions;
    }

    /**
     * getAll
     * Renvoie le tableau de toutes les questions
     * @return Question[]
     */
    public function getAll(): array
    {
        return $this->loadQuery($this->bd->execSQL($this->select));
    }

    /**
     * getAllByAnalysisID
     * Renvoie le tableau nom/score des résultats d'une analyse
     * @param int $id
     * @return Question[]
     */
    public function getAnalysisQuestions(int $id): array
    {
        $req = "SELECT answer.id_question, questions.question, answer.point as question_point FROM answer, questions WHERE answer.id_analysis = :id AND questions.id_question = answer.id_question";

        return $this->loadQuery($this->bd->execSQL($req, [':id' => $id]));
    }
}
