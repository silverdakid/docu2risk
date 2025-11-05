<?php
require_once dirname(__DIR__, 1) . "/connexion.php";
require_once __DIR__ . "/analysis.class.php";

/**
 * Analyse DAO
 * DAO : Permets de manipuler les données de la table ANALYSE
 */
class AnalysisDAO
{
    private Connexion $bd;
    private string $select;

    public function __construct()
    {
        $this->bd = new Connexion();
        $this->select = 'SELECT analysis.id_analysis, analysis.id_user, analysis.name, analysis.country, analysis.date_analysis, analysis.headquarter, analysis.date_creation, analysis.sector_activity, analysis.net_banking_income, SUM(answer.point) as "score" FROM analysis, answer WHERE analysis.id_analysis = answer.id_analysis GROUP BY analysis.id_analysis';
    }

    /**
     * insert
     * Insère une analyse dans la DB et renvoie son ID
     * @param  Analysis $analysis
     * @return string
     */
    public function insert(Analysis $analysis, array $questionsName, array $questionsScore): string
    {
        // On insère l'analyse :

        $this->bd->execSQL(
            "INSERT INTO analysis (id_analysis, id_user, name, country, date_analysis, headquarter, date_creation, sector_activity, net_banking_income) VALUES (:idA, :idU, :name, :country, NOW(), :hq, NOW(), :sector, :income)",
            [
                ":idA" => $analysis->getIdAnalysis(), "idU" => $analysis->getIdUser(), "name" => $analysis->getName(), ":country" => $analysis->getCountry(), ":hq" => $analysis->getIdAnalysis(), ":sector" => $analysis->getSectorActivity(), ":income" => $analysis->getNetBanking()
            ]
        );

        // On insère ses questions-réponses :
        $sqlInsert = "";
        $tmpArr = array();
        $lastId = $this->bd->execSQL("SELECT LAST_INSERT_ID() as lastid;");
        $lastId = $lastId[0]['lastid'];

        foreach ($questionsName as $key => $value) {
            // Récupérer l'identifiant de la question dans la DB :
            $id = $this->bd->execSQL("SELECT id_question FROM questions WHERE question = :q", [":q" => $value]);
            $q = $id[0]["id_question"];
            $k = $questionsScore[$key];
            $tmpArr[] = "($lastId, $q, $k)";
        }

        $sqlInsert = "INSERT INTO answer VALUES " . implode(",", $tmpArr);
        $this->bd->execSQL($sqlInsert);

        return $lastId;
    }



    /**
     * loadQuery
     * Transforme un résultat SQL en tableau d'instance de la classe correspondante
     * @param  array $result
     * @return array
     */
    private function loadQuery(array $result): array
    {
        $analysisList = [];
        foreach ($result as $row) {
            $analysis = new Analysis();
            $analysis->setIdAnalysis($row['id_analysis']);
            $analysis->setIdUser($row['id_user']);
            $analysis->setName($row['name']);
            $analysis->setCountry($row['country']);
            $analysis->setHeadquarter($row['headquarter']);
            $analysis->setDateCreation($row['date_creation']);
            $analysis->setDateAnalysis($row['date_analysis']);
            $analysis->setSectorActivity($row['sector_activity']);
            $analysis->setNetBanking($row['net_banking_income']);
            $analysis->setScore($row['score']);
            // $analysis->setResult($row['result']);
            // Temporairement :
            $analysis->setResult("Low");

            $analysisList[] = $analysis;
        }
        return $analysisList;
    }

    /**
     * getAllByIdUser
     * Renvoie le tableau de toutes les analyses d'un utilisateur
     * @return Analysis[]
     */
    public function getAllByIdUser(int $idUser): array
    {
        return $this->loadQuery($this->bd->execSQL($this->select . " HAVING id_user = :idU ORDER BY id_analysis DESC", [":idU" => $idUser]));
    }

    /**
     * getAll
     * Renvoie le tableau de toutes les analyses
     * @return Analysis[]
     */
    public function getAll(): array
    {
        return $this->loadQuery($this->bd->execSQL($this->select . " ORDER BY analysis.id_analysis DESC "));
    }

    /**
     * getAllById
     * Renvoie le tableau de toutes les analyses d'un utilisateur
     * @return Analysis
     */
    public function getById(int $id): Analysis
    {
        return $this->loadQuery($this->bd->execSQL($this->select . ' HAVING id_analysis = :id', [':id' => $id]))[0];
    }



    /**
     * getByFilter
     * Fonction permettant de récupérer les analyses similaires à une entrée.
     * @param string $val Valeur à rechercher parmi les clés possibles
     * @param ?string $id Identifiant optionnel pour filtrer par rapport à un utilisateur précis (pour les administrateurs/project-leaders)
     * @return Analysis[] Renvoie un tableau d'analyses
     */
    function getByFilter(string $val, ?string $id): array
    {
        $req = $this->select . ' HAVING analysis.id_analysis LIKE :val';
        $req2 =  $this->select . ' HAVING analysis.name LIKE :val';
        $req3 = $this->select . ' HAVING score = :val';
        $req4 =  $this->select . ' HAVING country LIKE :val';
        $req5 = $this->select . ' HAVING analysis.id_user LIKE :val';
        $req6 = 'SELECT analysis.id_analysis, analysis.id_user, analysis.name, analysis.country, analysis.date_analysis, analysis.headquarter, analysis.date_creation, analysis.sector_activity, analysis.net_banking_income, SUM(answer.point) AS "score", user.lastname, user.firstname FROM analysis, answer, user WHERE analysis.id_analysis = answer.id_analysis AND analysis.id_user = user.id_user GROUP BY analysis.id_analysis HAVING lower(concat(user.lastname, user.firstname)) LIKE :val';
        $req7 = 'SELECT analysis.id_analysis, analysis.id_user, analysis.name, analysis.country, analysis.date_analysis, analysis.headquarter, analysis.date_creation, analysis.sector_activity, analysis.net_banking_income, SUM(answer.point) AS "score", company.project_name FROM analysis, answer, user, company WHERE analysis.id_analysis = answer.id_analysis AND analysis.id_user = user.id_user AND company.id_project = user.id_project GROUP BY analysis.id_analysis HAVING lower(company.project_name) LIKE :val';

        $param = [':val' => '%' . $val . '%'];
        $param3 = [':val' => $val];

        if (isset($id)) {
            $req .= ' AND id_user = :id';
            $req2 .= ' AND id_user = :id';
            $req3 .= ' AND id_user = :id';
            $req4 .= ' AND id_user = :id';
            $param[':id'] = $id;
            $param3[':id'] = $id;
        }

        // Pas utile pour les utilisateurs simples :
        if (!isset($id)) {
            $param = [':val' => '%' . strtolower($val) . '%'];
            $res = ($this->loadQuery($this->bd->execSQL($req5, $param)));
            if (count($res) > 0) return $res;

            $res = ($this->loadQuery($this->bd->execSQL($req6, $param)));
            if (count($res) > 0) return $res;

            $res = ($this->loadQuery($this->bd->execSQL($req7, $param)));
            if (count($res) > 0) return $res;
        }

        $res = ($this->loadQuery($this->bd->execSQL($req3, $param3)));
        if (count($res) > 0) return $res;

        $res = ($this->loadQuery($this->bd->execSQL($req, $param)));
        if (count($res) > 0) return $res;

        $res = ($this->loadQuery($this->bd->execSQL($req2, $param)));
        if (count($res) > 0) return $res;

        $res = ($this->loadQuery($this->bd->execSQL($req4, $param)));
        if (count($res) > 0) return $res;


        else return []; // Si aucun résultat malgré les 4 requêtes alors on renvoie un tableau vide.
    }
}
