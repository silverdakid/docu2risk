<?php
session_start();
// préférable : unset tmpIdView à chaque fois qu'on fait back
unset($_SESSION['tmpIdView']);
unset($_SESSION['tmpMode']);


require_once "../modele/classes/userDAO.class.php";
require_once "../modele/classes/projectDAO.class.php";
require_once "../modele/classes/roleDAO.class.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("location: ./login.php");
}

$val = isset($_GET['val']) ? $_GET['val'] : null;   // ID


$userLName = isset($_SESSION['lastname']) ? ucfirst($_SESSION['lastname']) : 'N/A';
$userFName = isset($_SESSION['firstname']) ? ucfirst($_SESSION['firstname']) : 'N/A';
$userStatus = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';

$thStatus = '';
$thBlock = '';
$tbody = "";
$userDAO = new UserDAO();
$projectDAO = new ProjectDAO();
$roleDAO = new RoleDAO();

$allMembers;

if ($userStatus == 'Admin') {
    if (isset($val) && strlen($val) > 0) {
        $allMembers = $userDAO->getByFilter($val, null);
    } else $allMembers = $userDAO->getAll();

    $thStatus = '<th>Status</th>';
    $thBlock = '<th>Block</th>';

    foreach ($allMembers as $user) {
        $tbody .= '<tr><td>'.$user->getId().'</td><td>'.$user.'</td><td>'.$projectDAO->getById($user->getIdProject()).'</td><td>'.$roleDAO->getById($user->getId()).'</td>';
        $tbody .= '<td><input type="submit" value="'.$user->getId().'" name="history" class="inputImage history"></td>';
        $tbody .= '<td><input type="submit" value="'.$user->getId().'" name="access" class="inputImage fleche"></td>';
        $tbody .= '<td><button onclick="modal(event, '.$user->getId().');" value="'.$user->getId().'" id="block'.$user->getId().'"name="block" class="inputImage block"></button></td></tr>';
    }
} else if ($userStatus == 'Projectleader') {
    if (isset($val) && strlen($val) > 0) {
        $allMembers = $userDAO->getByFilter($val, $_SESSION['projectId']);
    } else $allMembers = $userDAO->getByProjectId($_SESSION['projectId']);

    foreach ($allMembers as $user) {
        $tbody .= '<tr><td>'.$user->getId().'</td><td>'.$user.'</td><td>'.$projectDAO->getById($user->getIdProject()).'</td>';
        $tbody .= '<td><input type="submit" value="'.$user->getId().'" name="history" class="inputImage history"></td>';
        $tbody .= '<td><input type="submit" value="'.$user->getId().'" name="access" class="inputImage fleche"></td>';
    }

}

if(isset($_POST['history'])) {
    $_SESSION['tmpIdView'] = $_POST['history'];
    header("location: ./historyAnalysis.php");
} else if(isset($_POST["access"])) {
    $_SESSION['tmpIdView'] = $_POST['access'];
    header("location: ./settings.php");
} else if(isset($_POST["block"])) {
    $_SESSION['tmpIdView'] = $_POST['block'];
    $userDAO->remove($_SESSION['tmpIdView']);
    header("location: ./members.php");
} else if(isset($_POST['add'])) {
    $_SESSION['tmpMode'] = "add";
    header("location: ./settings.php");
}

if(isset($val)) {
    echo $tbody;
} else require_once "../vue/members.view.php";
unset($allMembers);

?>