<head>
    <title>Settings</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather%20Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../vue/css/header.css" rel="stylesheet" />
    <link href="../vue/css/settings.css" rel="stylesheet" />
    <link href="../vue/css/basic.css" rel="stylesheet" />
    <link href="../vue/css/input.css" rel="stylesheet" />
    <link href="../vue/css/editPass.css" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <?php
    include("../vue/header.php");
    ?>
    <form class="padAround" action="../controleur/editPass.php" method="post">
        <div class="padAround settingsUpperDiv">
            <div class="padAround editPassDiv">
                <div><label for="oldPass">Old Password</label></div>
                <input type="password" class="searchBar fullWidth larger" placeholder="Password" name="oldPass" id="oldPass" required>
                <div class="infoPassDiv">
                    <label for="newPass1">New Password</label>
                    <div> <!-- A mettre dans le fichier infoIcon.php plus tard pour l'inclure partout où nécessaire -->
                        <div class="tooltip">....
                            <span class="tooltiptext tooltippass smaller">
                                <h3>Password Rules</h3>
                                <p>The password must contain at least 12 characters with at least two digits, two special characters and upper-cases and lower-cases letters.</p>
                            </span>
                        </div>
                    </div>
                </div>
                <input pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]{2})(?=.*?[#?!@$%^&*-]{2}).{12,}" type="password" class="searchBar fullWidth larger" placeholder="Password" name="newPass1" id="newPass1" required >
                <div><label for="newPass2">Confirm new Password</label></div>
                <input pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]{2})(?=.*?[#?!@$%^&*-]{2}).{12,}" type="password" class="searchBar fullWidth larger" placeholder="Password" name="newPass2" id="newPass2" required>
            </div>
            <div class="settingsButtonDiv">
                <a class="aButton settingsButton halfWidth redBack" href="./settings.php">Cancel</a>
                <p class="errorMsg"><?= $errorMsg; ?></p>
                <input type="submit" name="submitForm" class="aButton settingsButton halfWidth right" style="justify-self:right;" value="Confirm">
            </div>
        </div>
        </div>
        <script src="./util/header.js"></script>
        <script>
            // To-do : Vérifier que new pass1 et new pass2 soient identiques en JS (puis en PHP), bloquer submit tant que différents.
            // Régler les pattern dans les inputs qui ne fonctionnent pas.
        </script>
</body>