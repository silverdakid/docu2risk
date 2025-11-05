<?php
require_once "../modele/classes/roleDAO.class.php";

/**
 * Connexion
 * Permets de se connecter à la base de donnée et gère le LOGIN
 */

class Connexion
{
    private $db;

    /**
     * __construct
     *
     * Permets de se connecter à la base de donnée
     */
    function __construct()
    {
        $db_config['SGBD'] = 'mysql';
        $db_config['HOST'] = '100.74.7.86';
        $db_config['DB_NAME'] = 'nyxsoft_risk_v';
        $db_config['USER'] = 'skr';
        $db_config['PASSWORD'] = 'skr';

        try {
            $this->db = new PDO(
                $db_config['SGBD'] . ':host=' . $db_config['HOST'] . ';dbname=' . $db_config['DB_NAME'],
                $db_config['USER'],
                // null,
                $db_config['PASSWORD'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
            unset($db_config);
        } catch (Exception $exception) {
            die($exception->getMessage());
        }
    } 
 
 
    /**
     * execSQL 
     *
     * @param  string $req Requête SQL à executer
     * @param  array $valeurs Paramètre de la Requête SQL
     * @return array Colonnes retournées suite à la requête (si colonnes).
     */
    public function execSQL(string $req, array $valeurs = []): array
    {
        $res = $this->db->prepare($req);

        try {
            $res->execute($valeurs);
        } catch (Exception $exception) {
            die($exception->getMessage());
        }

        $allRes = $res->fetchAll(PDO::FETCH_ASSOC);

        return $allRes;
    }

    /**
     * existeUtilisateur
     *
     * @param  array $identifiants 
     * @return bool Est vrai si l'utilisateur existe sinon faux
     */
    public function existeUtilisateur(array $identifiants): bool
    {
        $req = "SELECT * FROM user WHERE login=:identifiant";
        $allRes = $this->execSQL($req, [":identifiant" => $identifiants["username"]]);

        if (count($allRes) > 0) return true;
        else return false;
    }

    /**
     * verifMdp
     *
     * @param  array $identifiants Tableau contenant le login entré par un USER
     * @return bool Renvoie vrai si les infos entrées sont correctes sinon faux
     */
    public function verifMdp(array $identifiants): bool
    {
        $roleDAO = new RoleDAO();
        $req = "SELECT * FROM user WHERE login=:identifiant";
        $allRes = $this->execSQL($req, [":identifiant" => $identifiants["username"]]);

        $verifMdp = password_verify($identifiants["pass"], $allRes[0]['password']);
        if ($verifMdp) {
            $_SESSION['login'] = $identifiants['username'];
            $_SESSION['pass'] = $identifiants['pass'];
            $_SESSION['firstname'] = $allRes[0]['firstname'];
            $_SESSION['lastname'] = $allRes[0]['lastname'];
            $_SESSION['number'] = $allRes[0]['number'];
            $_SESSION['mail'] = $allRes[0]['mail'];
            $_SESSION['id'] = $allRes[0]['id_user'];
            $_SESSION['projectId'] = $allRes[0]['id_project'];

            $tmpRole = $roleDAO->getById($allRes[0]['id_user']);
            if (isset($tmpRole)) $_SESSION['role'] = $tmpRole->getRole();
            else $_SESSION["role"] = "user";

            unset($tmpRole);

            return true;
        } else return false;
    }
}
