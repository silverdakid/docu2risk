<?php
/**
 * Role
 * Classe Role
 */
class Role {
    private int $idUser;
    private int $idProject;
    private string $role;

    function __construct(int $id=0, string $role='', int $idProject= 0)
    {
        $this->idProject = $idProject;
        $this->idUser = $id;
        $this->role = $role;
    }

    function getIdProject():int {return $this->idProject;}
    function setIdProject(int $id):void {$this->idProject = $id;}
    function getRole():string {return $this->role;}
    function setRole(string $v):void {$this->role = $v;}
    function getIdUser():int {return $this->idUser;}
    function setIdUser(int $v):void {$this->idUser = $v;}

    function __toString() {
        return ucfirst($this->getRole());
    }

}