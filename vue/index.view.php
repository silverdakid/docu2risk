<head>
    <title>Index</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather%20Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../vue/css/header.css" rel="stylesheet" />
    <link href="../vue/css/table.css" rel="stylesheet" />
    <link href="../vue/css/basic.css" rel="stylesheet" />
    <link href="../vue/css/input.css" rel="stylesheet" />

</head>

<body>
    <?php
    include("../vue/header.php");
    ?>
    <div class="centerArea">
        <div class= "indexWelcome">
            <!-- A modifié si on retire le welcome pour les admins -->
            <h1><span class="indexTitle">Welcome</span><br/><span class="indexName"><?=$userLName." ".$userFName?></span></h1>
            <?=$searchBar?>
        </div>
            <?=$tableAdmin;?>
            <?=$tableUser;?>
    </div>
    <?=$inc?>
    <script src="./util/header.js"></script>
</body>