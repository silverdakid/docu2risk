<head>
    <title>Analysis</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather%20Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="../vue/css/basic.css" rel="stylesheet" />
    <link href="../vue/css/header.css" rel="stylesheet" />
    <link href="../vue/css/input.css" rel="stylesheet" />
    <link href="../vue/css/table.css" rel="stylesheet" />
    <link href="../vue/css/loading.css" rel="stylesheet" />
    <link href="../vue/css/editPass.css" rel="stylesheet" />
    <link href="../vue/css/login.css" rel="stylesheet" />
</head>

<body>

    <div class="loading absoluteHidden">
        <svg class="spinner" viewBox="0 0 50 50">
            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
        </svg>
    </div>

    <?php
    include("../vue/header.php");
    ?>
    <form id="analysisForm" class="padAround gridAnalysis" action="../controleur/analysis.php" method="post" enctype="multipart/form-data" onsubmit="loading()">
        <label for="companyName">Company analyzed</label>
        <input value="<?= $companyName ?>" type="text" placeholder="Company name" id="companyName" name="companyName" class="searchBar selfLeft" required>
        <label></label>
        <div>
            <label for="docInput" class="infoText"><b>Insert different files</b></label>
            <div class="tooltip">....
                <span class="tooltiptext tooltipfiles">
                    Mandatory Files
                    <ul>
                        <li>ICI</li>
                        <li>Wolfsberg</li>
                    </ul>
                    Accepted Files
                    <ul>
                        <li>Invoice</li>
                    </ul>
                </span>
            </div>
        </div>


        <div class="fullWidth">
            <div>
                <div class="padAroundLil">
                    <div for="docInput" class="fileInputDiv" id="dropZone">
                        <input type="file" name="docInput[]" id="docInput" class="fileInput" multiple>
                        <label class="labelCover" for="docInput" id="fileText">INSERT FILES OR DRAG & DROP HERE</label>
                        <div id="filesDiv" class="absoluteHidden"></div>
                    </div>
                </div>
                <br />
                <table id="analysisList" class="tableHistory tableFiles grayWhite">
                    <table>
            </div>
            <!-- Browse left, save right display grid space even -->

        </div>

    </form>
    <div class="spaceButton">
        <input type="submit" id="submitAnalysis" class="aButton absoluteHidden" name="submitAnalysis" value="Send for analysis" disabled>
        <a class="aButton redBack" href="./index.php">Cancel</a>
        <p class="errorLabel errorAnalyse">
            <?= $errorMsg ?>
        </p>
    </div>

    <script src="./util/header.js"></script>
    <script src="./util/analysisFile.js"></script>
    <script>
        function loading() {
            $('.loading').removeClass('absoluteHidden');
        }
    </script>
</body>