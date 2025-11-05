<?php
require_once dirname(__DIR__, 1) . "/connexion.php";
require_once __DIR__ . "/project.class.php";

/**
 * Analyse DAO
 * DAO : Permets de manipuler les données de la table ANALYSE
 */
class ProjectDAO
{
    private Connexion $bd;
    private string $select;

    public function __construct()
    {
        $this->bd = new Connexion();
        $this->select = 'SELECT * from company';
    }

    /**
     * loadQuery
     * Transforme un résultat SQL en tableau d'instance de la classe correspondante
     * @param  array $result
     * @return array
     */
    private function loadQuery(array $result): array
    {
        $projectList = [];
        foreach ($result as $row) {
            $project = new Project();
            $project->setIdProject($row['id_project']);
            $project->setProjectName($row['project_name']);
            $projectList[] = $project;
        }
        return $projectList;
    }

    /**
     * getById
     * Renvoie le projet correspondant à un ID
     * @return Project
     */
    public function getById(int $idProject): Project
    {
        $project = new Project();
        $projects = $this->loadQuery($this->bd->execSQL($this->select . " WHERE id_project = :id", [":id" => $idProject]));
        if (count($projects) > 0) {
            $project = $projects[0];
        }
        return $project;
    }

    /**
     * getAll
     * Renvoie le tableau de tous les projets
     * @return Project[]
     */
    public function getAll(): array
    {
        return $this->loadQuery($this->bd->execSQL($this->select));
    }
}
