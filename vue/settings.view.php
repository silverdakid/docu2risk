<head>
    <title>Settings</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather%20Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../vue/css/header.css" rel="stylesheet" />
    <link href="../vue/css/settings.css" rel="stylesheet" />
    <link href="../vue/css/basic.css" rel="stylesheet" />
    <link href="../vue/css/input.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <?php
    include("../vue/header.php");
    ?>
    <form class="padAround" action="../controleur/settings.php" method="post">
        <button type="submit" disabled style="display: none" aria-hidden="true"></button> <!--Submit invisible pour éviter de presser la touche ENTER-->
        <div class="padAround settingsUpperDiv">
            <div class="padAround settingsDiv">
                <?= $htmlInput; ?>
            </div>
            <div class="settingsButtonDiv">
                <?= $btn ?>
            </div>
        </div>
        </div>

        <script src="./util/header.js"></script>
        <script src="./util/settings.js"></script>
</body>