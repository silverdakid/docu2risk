<?php
// to-do: Ajouter champ password, finir add member
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("location: ./login.php");
}
require_once('../modele/classes/userDAO.class.php');
require_once('../modele/classes/roleDAO.class.php');
$userDAO = new UserDAO();
$roleDAO = new RoleDAO();
$htmlInput = "";

// Gestion formulaire :
$idToView = isset($_SESSION['tmpIdView']) ? $_SESSION['tmpIdView'] : $_SESSION['id'];
$mode = isset($_SESSION['tmpMode']) ? $_SESSION['tmpMode'] : null;
$values["firstname"] = (isset($_POST['firstname']) ? trim($_POST['firstname']) : "");
$values["lastname"] = (isset($_POST['lastname']) ? trim($_POST['lastname']) : "");
$values["number"] = (isset($_POST['number']) ? trim($_POST['number']) : "");
$values["mail"] = (isset($_POST['mail']) ? trim($_POST['mail']) : "");
$values["status"] = (isset($_POST['status']) ? ($_POST['status']) : null);
$values["password"] = (isset($_POST['password']) ? ($_POST['password']) : "");
$values["projectid"] = (isset($_POST['projectid']) ? ($_POST['projectid']) : null);
$values["login"] = (isset($_POST['login']) ? ($_POST['login']) : "");

$errorMsg = "";
$btn = '';
$type = (isset($_POST['submitForm']) ? trim($_POST['submitForm']) : null);
$create = (isset($_POST['createMember']) ? trim($_POST['createMember']) : null);

if (isset($type)) {
    // En théorie, HTML empêche les inputs vides grâce à nos critères cependant un individu malicieux peut modifier ces Input.
    if (!isset($values[$type]) || strlen($values[$type]) == 0)
    // Gestion des erreurs à améliorer.
    {
        $errorMsg = "An empty field can't be submitted.";
    } else {
        // Solution peu économique : On envoie à la BDD le nouveau User créé à partie du tableau values.
        // Meilleur : On modifie dans la BDD le champ modifié pour l'utilisateur concerné :

        $userDAO->updateValue($idToView, $values[$type], $type);
        // On édite le champ modifié dans la session :
        $_SESSION[$type] = $values[$type];
    }
} else if (isset($create)) {
    $err = 0;
    foreach ($values as $val) {
        if (!isset($val)) $err += 1;
        else if ($val == "") $err += 1;
    }
    if ($err > 0) {
        $errorMsg = "An empty field can't be submitted.";
    } else {
        $values['projectid'] = intval($values['projectid']);
        $user = new User(0, $values['number'], $values['firstname'], $values['lastname'], $values['login'], password_hash($values['password'], PASSWORD_BCRYPT), $values['mail'], $values['projectid']);
        if ($userDAO->existLogin($values['login'])) {
            $errorMsg = "An user with this username already exist.";
        } else {
            $userDAO->insert($user, $values['status'][0]);
            header('location: ./members.php');
        }
    }
}


$userLName = isset($_SESSION['lastname']) ? ucfirst($_SESSION['lastname']) : 'N/A';
$userFName = isset($_SESSION['firstname']) ? ucfirst($_SESSION['firstname']) : 'N/A';
$userStatus = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';

if (isset($mode) && $mode == 'add') {
    $userLNameF = isset($values['lastname']) ? ($values['lastname']) : "";
    $userFNameF = isset($values['firstname']) ? ($values['firstname']) : "";
    $userStatusF =  isset($values['status'][0]) ? $values['status'][0] : "";
    $userNumber = isset($values['number']) ? ($values['number']) : "";
    $userMail = isset($values['mail']) ? ($values['mail']) : "";
    $loginF = isset($values['login']) ? ($values['login']) : "";
    $projectId = isset($values['projectid']) ? ($values['projectid']) : "";
    if ($userStatus == "Projectleader") $projectId = $_SESSION['projectId'];

    $pass = isset($values['pass']) ? ($values['pass']) : "";
    $btn = '<a class="aButton settingsButton halfWidth redBack" href="./members.php">Cancel</a>';
    $btn .= '<p class="errorMsg">' . $errorMsg . '</p>';
    $btn .= '<input class="aButton settingsButton halfWidth right" type="submit" value="Submit" name="createMember">';
} else {

    if ($idToView != $_SESSION['id']) {
        $user = $userDAO->getById($idToView);
        $userLNameF = ($user->getLastName());
        $userFNameF = ($user->getFirstName());
        $userStatusF = ($roleDAO->getById($idToView));
        $userNumber = ($user->getNumber());
        $userMail = ($user->getMail());

        $btn = '<a class="aButton settingsButton halfWidth" href="./members.php">Back</a>';
    } else {
        $userLNameF = $userLName;
        $userFNameF = $userFName;
        $userStatusF = $userStatus;
        $userNumber = isset($_SESSION['number']) ? ($_SESSION['number']) : 'N/A';
        $userMail = isset($_SESSION['mail']) ? ($_SESSION['mail']) : 'N/A';
        $btn = '<a class="aButton settingsButton halfWidth" href="./index.php">Home</a><p class="errorMsg"><?=$errorMsg;?></p><a class="aButton settingsButton halfWidth right" style="justify-self:right;" href="./editPass.php">Change password</a>';
    }
}

// Liste des champs éditable (ou non) pour génération dynamique :
$arrayInput = [];
$arrayInput[] = array("firstname", "First Name", $userFNameF, true, "text", "\S(.*\S)?", "No spaces at the beginning/end please.");
$arrayInput[] = array("lastname", "Last Name", $userLNameF, true, "text", "\S(.*\S)?", "No spaces at the beginning/end please.");

// Si mode add, faire un radio input.
// JS : si on select PL, alors afficher champ pour soit select projet soit entrer un id projet
// Risque js : S'assurer qu'on ne puisse pas accéder à l'info du select sauf si admin
$arrayInput[] = array("status", "Status", $userStatusF, false, "text", "\S(.*\S)?", "No spaces at the beginning/end please.");
$arrayInput[] = array("number", "Number", $userNumber, true, "tel", "^\+(?:[0-9]●?){6,14}[0-9]$", "Please remove any special characters and spaces, follow this format : +7123456789");
$arrayInput[] = array("mail", "Mail", $userMail, true, "email", "\S(.*\S)?", "No spaces at the beginning/end please.");

if ($mode == 'add') {

    if ($userStatus != "Projectleader") $arrayInput[] = array('projectid', 'Project ID', $projectId, true, "number", "^\d+$", "Only digits are allowed.");
    $arrayInput[] = array('login', 'Login', $loginF, true, "text", "\S(.*\S)?", "No spaces at the beginning/end please.");
    $arrayInput[] = array('password', 'Password', $pass, true, "password", "(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]{2})(?=.*?[#?!@$%^&*-]{2}).{12,}", "Check rules of the password");
}


// Génération dynamique (vue moins longue)
foreach ($arrayInput as $bloc) {
    $htmlInput .= '<label for="' . $bloc[0] . '">' . $bloc[1] . '</label>';
    $htmlInput .= '<div> <span class="colon">:</span>';
    if ($bloc[0] == "status" && $mode == 'add' && $userStatus == 'Admin') {
        $htmlInput .= '<label for="userR">User</label><input style="margin-right: 30px;"required id="userR" type="radio" name="status[]" value="user"' . ((isset($_POST['status']) && $_POST['status'][0] == 'user') ? "checked" : "") . '/><label for="projectR">Project Leader</label><input id="projectR" type="radio" name="status[]" value="projectleader" ' . (((isset($_POST['status'])) && $_POST['status'][0] == 'projectleader') ? "checked" : "") . '/></div>';
    } else if ($bloc[0] == "status" && $mode == 'add' && $userStatus == 'Projectleader') $htmlInput .= "User</div>";
    else {

        // Si on retire le bouton de modif et que l'on permets la modification de plusieurs champs d'emblée :
        // $htmlInput.='<input id="'.$bloc[0].'" type="text" class="settingsTextInput" value="'.$bloc[2].'"'.(!$bloc[3] ? "disabled":"").'></div>';
        // Sinon on désactive le disabled avec TS lorsqu'on input ?
        $htmlInput .= '<input  pattern="' . $bloc[5] . '" title="' . $bloc[6] . '" id="' . $bloc[0] . '" name="' . $bloc[0] . '" type="' . $bloc[4] . '" class="settingsTextInput" value="' . $bloc[2] . '" ' . ($mode != 'add' ? "disabled" : "") . '>';
        $htmlInput .= '</div>';
    }

    if ($bloc[3] && $mode != 'add') {
        $htmlInput .= '<div id="div' . $bloc[0] . '" class="containerEditButton"><div class="editInputDiv"><button type="submit" value="' . $bloc[0] . '" onclick="enableEditSettings(this, `' . $bloc[0] . '`)" class="editInput editInputEdit"></button></div></div>';
    } else if ($bloc[0] == 'password') {
        if ($bloc[0] == 'password' && $mode == 'add') $htmlInput .= '<div><div class="tooltip">....<span class="tooltiptext tooltippass smaller"><h3>Password Rules</h3><p>The password must contain at least 12 characters with at least two digits, two special characters and upper-cases and lower-cases letters.</p></span></div></div>';
    } else {
        $htmlInput .= '<div class="editInputEmpty">&nbsp;</div>';
    }
}

require_once "../vue/settings.view.php";
