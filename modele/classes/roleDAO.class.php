<?php
require_once dirname(__DIR__, 1). "/connexion.php";
require_once __DIR__."/role.class.php";

/**
 * Role DAO
 * DAO : Permets de manipuler les données de la table ROLE
 */
class RoleDAO {
    private Connexion $bd;
    private string $select;

    public function __construct()
    {
        $this->bd = new Connexion();
        $this->select = 'SELECT * FROM role';
    }


    /**
     * loadQuery
     * Transforme un résultat SQL en tableau d'instance de la classe correspondante
     * @param  array $result
     * @return Role[]
     */
    private function loadQuery(array $result): array
    {
        $roles = [];
        foreach ($result as $row) {
            $role = new Role();
            $role->setIdUser($row['id_user']);
            $role->setRole($row['role']);
            $role->setIdProject($row['id_project']);
            $roles[] = $role;
        }
        return $roles;
    }
            
    /**
     * getAll
     * Renvoie le tableau de tous les Roles
     * @return Role[]
     */
    public function getAll(): array 
    {
        return $this->loadQuery($this->bd->execSQL($this->select));
    }

    
    /**
     * getById
     * Renvoie le role d'un utilisateur par son identifiant.
     * @param int $id
     * @return Role
     */
    public function getById(int $id):Role {
        $role = new Role();
        $roles = $this->loadQuery($this->bd->execSQL($this->select . " WHERE id_user=:id", [':id' => $id]));
        if (count($roles) > 0) {
            $role = $roles[0];
            return $role;
        }
        else return new Role(0,"user",0);
    }

    
}