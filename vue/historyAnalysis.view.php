<head>
    <title>Analysis History</title>
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
    <?=$title; ?>
    <div class="padAround padTOP grid1fr" style="justify-items: center;">
        <input type="search" name="filter" id="filter" placeholder="Type here to filter results" class="searchBar">
        <div class="tableContainer fullWidth">
            <table class="tableAdmin" style="height: 100%;">
                <thead class="padBot">
                    <tr>
                        <?=$plHtml?>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Date</th>
                        <th>Score</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody id="listAnalysis">
                    <?=$tbody;?>
                </tbody>
            </table>
        </div>
        <form action="../controleur/historyAnalysis.php" method="post" class="none">
            <?=$retour?>
        </form>
    </div>
    <script src="./util/header.js"></script>
    <script type="text/javascript" src="./util/dataFilter.js"></script>
</body>

<!-- To-do : Style -> .CSS -->