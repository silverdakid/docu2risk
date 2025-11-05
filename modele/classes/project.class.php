<?php
/**
 * Projet (ou Entreprise)
 * Classe Project (ou Company)
 */
class Project {
    private int $idProject;
    private string $projectName;

    function __construct(int $id=0, string $question='')
    {
        $this->idProject = $id;
        $this->projectName = $question;
    }

    function getIdProject():int {return $this->idProject;}
    function setIdProject(int $id):void {$this->idProject = $id;}
    function getProjectName():string {return $this->projectName;}
    function setProjectName(string $v):void {$this->projectName = $v;}

    function __toString() {
        return ucfirst($this->getProjectName());
    }

}