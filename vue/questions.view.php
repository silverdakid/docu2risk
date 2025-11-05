<head>
    <title>Questions</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather%20Sans">
    <link href="../vue/css/header.css" rel="stylesheet" />
    <link href="../vue/css/settings.css" rel="stylesheet" />
    <link href="../vue/css/basic.css" rel="stylesheet" />
    <link href="../vue/css/input.css" rel="stylesheet" />
    <link href="../vue/css/table.css" rel="stylesheet" />
</head>

<body>
    <?php
    include("../vue/header.php");
    ?>
    <div class="padAround padTOP grid1fr">
        <h1>List of all the questions in the analysis</h1>
        <div class="tableContainer fullWidth scrollTable">
            <table class="tableAdmin infoText leftAlign2N">
                <thead>
                    <tr>
                        <th>Questions</th>
                        <th>Max Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?=$tbody?>
                </tbody>
            </table>
        </div>
        <a class="aButton settingsButton tenthWidth" href="./index.php">Home</a>

    </div>
    <script src="./util/header.js"></script>
    <script src="./util/settings.js"></script>
</body>