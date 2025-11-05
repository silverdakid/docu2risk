<header class="navbar">
    <div>
        <p class="title">Docu2Risk</p>
    </div>
    <div id="select-btn" class="select-btn">
        <span id="arrow-dwn" class="arrow-dwn">▼</span>
        <div class="subnav-content">
            <a href="./index.php">Home</a>
            <a href="./questions.php">Questions</a>
            <?php
            if ($_SESSION["role"] == "projectleader") {
                echo "<a href='./members.php'>Members</a>";
                echo "<a href='#'>Member History</a>";
            }
            if ($_SESSION["role"] != "admin") {
                echo "<a href='./analysis.php'>Analysis</a>";
                echo "<a href='./historyAnalysis.php'>History</a>";
            } else {
                echo "<a href='./members.php'>Members</a>";
            }
            ?>
            <a href="./settings.php">Account</a>
        </div>
    </div>
    <div id="account-menu" class="account-menu">
        <p class="title accountMenu" id="title" style="cursor: pointer;">Account</p>
        <div class="subnav-content">
            <ul class="userInfo">
                <li><b>Name</b><?= $userLName ?></li>
                <li><b>Surname</b><?= $userFName ?></li>
                <li><b>Status</b><?= $userStatus ?></li>
            </ul>
            <hr />
            <form action="../controleur/header.php" method="post" class="buttonUser">
                <input class="userInfoInput settings" name="settings" type="submit" value="" />
                <input class="userInfoInput red" name="disconnect" type="submit" value="Disconnect" />
            </form>
        </div>

    </div>
</header>