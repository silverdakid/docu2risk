<?php
require_once dirname(__DIR__, 1) . "/connexion.php";
require_once __DIR__ . "/user.class.php";

/**
 * Utilisateur DAO
 * DAO : Permets de manipuler les données de la table USER
 */
class UserDAO
{
    private Connexion $bd;
    private string $select;

    public function __construct()
    {
        $this->bd = new Connexion();
        $this->select = 'SELECT * FROM user';
    }


    /**
     * insert
     * Insère un utilisateur dans la DB
     * @param  User $user
     * @param string $status
     * @return void
     */
    public function insert(User $user, string $status): void
    {
        $this->bd->execSQL(
            "INSERT INTO user (id_user, lastname, firstname, login, password, number, mail, id_project) VALUES (0, :lname, :fname, :log, :pass, :num, :mail, :idp);",
            [
                ":lname" => $user->getLastName(), ":fname" => $user->getFirstName(), ":log" => $user->getLogin(), ":pass" => $user->getPassword(), ":num" => $user->getNumber(), ":mail" => $user->getMail(), ":idp" => $user->getIdProject()
            ]
        );

        $lastId = $this->bd->execSQL("SELECT LAST_INSERT_ID() as lastid;");

        echo print_r($lastId[0]['lastid']);
        var_dump($lastId[0]);
        echo '<br>';

        if($status != "user") {
            $this->bd->execSQL("INSERT INTO role (id_user, role, id_project) VALUES(:id, :status, :idp)", [':id' => $lastId[0]['lastid'], ':status' => $status, ':idp' => $user->getIdProject()]);
        }
    }
    
    /**
     * remove
     * Retire un utilisateur de la base de données
     * @param  int $id
     * @return void
     */
    public function remove(int $id):void {
        $this->bd->execSQL("DELETE FROM user WHERE id_user = :id", [":id"=> $id ]);
    }

    /**
     * updateValue
     * Mets à jour un champ précis, pour un utilisateur précis, à une valeur précise.
     * @param  string $id Identifiant de l'utilisateur
     * @param  string $value Valeur remplaçante
     * @param  string $field Champ concerné
     * @return void
     */
    public function updateValue(string $id, string $value, string $field): void
    {
        $req = "UPDATE user SET " . $field . " = :val WHERE id_user = :id";
        $this->bd->execSQL($req, [":val" => $value, ":id" => $id]);
    }



    /**
     * loadQuery
     * Transforme un résultat SQL en tableau d'instance de la classe correspondante
     * @param  array $result
     * @return array
     */
    private function loadQuery(array $result): array
    {
        $users = [];
        foreach ($result as $row) {
            $user = new User();
            $user->setId($row['id_user']);
            $user->setFirstName($row['firstname']);
            $user->setLastName($row['lastname']);
            $user->setLogin($row['login']);
            $user->setPassword($row['password']);
            $user->setMail($row['mail']);
            $user->setNumber($row['number']);
            $user->setIdProject($row['id_project']);
            $users[] = $user;
        }
        return $users;
    }


    /**
     * getById
     * Renvoie un utilisateur par son identifiant.
     * @param int $id
     * @return User
     */
    public function getById(int $id): User
    {
        $user = new User();
        $users = $this->loadQuery($this->bd->execSQL($this->select . " WHERE id_user=:id", [':id' => $id]));
        if (count($users) > 0) {
            $user = $users[0];
        }

        return $user;
    }


    /**
     * existLogin
     * Renvoie si un utilisateur existe par son login.
     * @param string $login
     * @return User
     */
    public function existLogin(string $login): bool
    {
        $users = $this->loadQuery($this->bd->execSQL($this->select . " WHERE login=:login", [':login' => $login]));
        if (count($users) > 0) return true;
        else return false;
    }
    /**
     * getAll
     * Renvoie le tableau de tous les Users
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->loadQuery($this->bd->execSQL($this->select));
    }

    /**
     * getAllByProject
     * Renvoie le tableau de tous les Users d'un projet
     * @return User[]
     */
    public function getByProjectId(int $id): array
    {
        return $this->loadQuery($this->bd->execSQL($this->select . ' WHERE id_project = :id', [':id' => $id]));
    }

    /**
     * getByFilter
     * Fonction permettant de récupérer les users similaires à une entrée.
     * @param string $val Valeur à rechercher parmi les clés possibles
     * @return User[] Renvoie un tableau d'analyses
     */
    function getByFilter(string $val, ?string $projectId): array
    {
        $req = $this->select . ' WHERE id_user LIKE :val';
        $req2 =  $this->select . ' WHERE lower(concat(lastname, firstname)) LIKE :val';
        $req3 = 'SELECT * FROM user, company WHERE user.id_project = company.id_project AND company.project_name LIKE :val';
        $param = [':val' => "%" . $val . "%"];

        if (isset($projectId)) {
            $req .= ' AND id_project = :id';
            $req2 .= ' AND id_project = :id';
            $req3 .= ' AND company.id_project = :id';
            $param[':id'] = $projectId;
        }

        $res = ($this->loadQuery($this->bd->execSQL($req, $param)));
        if (count($res) > 0) return $res;

        $res = ($this->loadQuery($this->bd->execSQL(strtolower($req2), $param)));
        if (count($res) > 0) return $res;

        $res = ($this->loadQuery($this->bd->execSQL($req3, $param)));
        if (count($res) > 0) return $res;

        else return []; // Si aucun résultat malgré les 4 requêtes alors on renvoie un tableau vide.
    }
}
