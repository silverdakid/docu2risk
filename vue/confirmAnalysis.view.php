<head>
    <title>Analysis Questions</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather%20Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="../vue/css/basic.css" rel="stylesheet" />
    <link href="../vue/css/header.css" rel="stylesheet" />
    <link href="../vue/css/input.css" rel="stylesheet" />
    <link href="../vue/css/table.css" rel="stylesheet" />
    <link href="../vue/css/settings.css" rel="stylesheet" />
    <link href="../vue/css/editPass.css" rel="stylesheet" />
    <link href="../vue/css/login.css" rel="stylesheet" />
    <link href="../vue/css/loading.css" rel="stylesheet" />
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
    <div class="top-right">
        <div class="buttonDiv answers-button">
            <button type="button" class="aButton settingsButton hoverButton">Download files</button>
            <span class="hoverButtonText tooltiptext smaller tooltipfiles tooltippass absoluteHidden">
                <?= $fileDownloadHTML ?>
            </span>
        </div>
    </div>

    <form class="answers-flex" action="../controleur/confirmAnalysis.php" method="post" onsubmit="loading()">
        <div class="infoPassDiv" style="margin-top: 1vh;">
            <h3 style="margin: 0;">Answers</h3>
            <div>
                <div class="tooltip">....
                    <span class="tooltiptext tooltippass smaller">
                        <h3>Color code</h3>
                        <p><b>Green</b> : The question was answered by the User.</p>
                        <p><b>Orange</b> : The question's answer is optional.</p>
                        <p><b>Yellow</b> : The question was answered by AI.</p>
                        <p><b>Red</b> : An answer couldn't be given, adding one is necessary for the analysis.</p>
                    </span>
                </div>
            </div>
        </div>
        <div class="halfWidth scrollable answers-questions">
            <?= $dynamicHTML ?>
        </div>
        <div class="buttonDiv answers-button">
            <a class="aButton settingsButton halfWidth redBack" href="./index.php">Cancel</a>
            <input type="submit" name="submitForm" class="aButton settingsButton halfWidth right" value="Continue">
        </div>
    </form>

    <script src="./util/header.js"></script>
    <script src="./util/analysisHandling.js"></script>
</body>