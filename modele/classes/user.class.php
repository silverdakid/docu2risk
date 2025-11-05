<?php
/**
 * Utilisateur
 * Classe User
 */
class User {
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $login;
    private string $password;
    private string $number;
    private string $mail;
    private int $idProject;

    function __construct(int $id=0, string $number='', string $fname='', string $lname='', string $login = '', string $pass='', string $mail ='', int $idProject= 0)
    {
        $this->id = $id;
        $this->firstName = $fname;
        $this->lastName = $lname;
        $this->login = $login;
        $this->password = $pass;
        $this->number = $number;
        $this->mail = $mail;
        $this->idProject = $idProject;
    }

    function getId():int {return $this->id;}
    function setId(int $id):void {$this->id = $id;}
    function getFirstName():string {return $this->firstName;}
    function setFirstName(string $v):void {$this->firstName = $v;}
    function getLastName():string {return $this->lastName;}
    function setLastName(string $lastName):void {$this->lastName = $lastName;}
    function getLogin():string {return $this->login;}
    function setLogin(string $v):void {$this->login = $v;}
    function getPassword():string {return $this->password;}
    function setPassword(string $v):void {$this->password = $v;}
    function getNumber():string {return $this->number;}
    function setNumber(string $v):void {$this->number = $v;}
    function getMail():string {return $this->mail;}
    function setMail(string $v):void {$this->mail = $v;}
    
    function getIdProject():int {return $this->idProject;}
    function setIdProject(int $v):void {$this->idProject = $v;}

    function __toString(): string{
        return ucfirst($this->lastName) . " " . ucfirst($this->firstName);
    }
}