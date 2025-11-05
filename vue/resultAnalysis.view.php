<head>
    <title>Analysis Result</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather%20Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../vue/css/header.css" rel="stylesheet" />
    <link href="../vue/css/settings.css" rel="stylesheet" />
    <link href="../vue/css/basic.css" rel="stylesheet" />
    <link href="../vue/css/table.css" rel="stylesheet" />
    <link href="../vue/css/input.css" rel="stylesheet" />
    <script type="text/javascript" src="../vue/js/gauge.js"></script>
</head>

<body>
    <?php
    include("../vue/header.php");
    ?>

    <div class="madAround padAroundLil blue">
        <h1 class="noMarginExceptBot">Results for
            <?= $companyName ?>
        </h1>
        <div class="flexResult">
            <div>
                <img src="../vue/css/assets/world.svg" />
                <h2>Headquarters</h2>
                <br />
                <p><?= $country ?></p>
            </div>
            <div>
                <img src="../vue/css/assets/calendar.svg" />
                <h2>Creation date</h2>
                <br />
                <p><?= $date ?></p>
            </div>
            <div>
                <img src="../vue/css/assets/roue.svg" />
                <h2>Sector of activity</h2>
                <br />
                <p>Banking Group</p>
            </div>
        </div>
    </div>

    <div class="resultDiv fitHeight gridResult padSides gray">
        <span>Global Score : </span>
        <div class="scoreOutput" style="gap: 40px;">
            <div style="display:flex; flex-direction: row; align-items: center;">
                <div class="circleResult <?= $color ?>Result">
                    <?= $score ?>
                </div>
                <span class="<?= $color ?>"><?= $notation ?></span>
            </div>
            <canvas id="foo"></canvas>
            <script>
                var opts = {
                    angle: 0, // The span of the gauge arc
                    lineWidth: 0.2, // The line thickness
                    radiusScale: 1, // Relative radius
                    pointer: {
                        length: 0.49, // // Relative to gauge radius
                        strokeWidth: 0.033, // The thickness
                        color: '#000000' // Fill color
                    },
                    limitMax: false, // If false, max value increases automatically if value > maxValue
                    limitMin: false, // If true, the min value of the gauge will be fixed
                    staticZones: [{
                            strokeStyle: "#ff0000",
                            min: 29,
                            max: 50
                        }, // Red 
                        {
                            strokeStyle: "#ffbb00",
                            min: 19,
                            max: 29
                        }, // Yellow
                        {
                            strokeStyle: "#24FF00",
                            min: 0,
                            max: 19
                        }
                    ],
                    staticLabels: {
                        font: "12px sans-serif", // Specifies font
                        labels: [0, 19, 29, 50, <?= $score ?>], // Print labels at these values
                        color: "#000000", // Optional: Label text color
                        scoreColor: "#<?= $colorHex ?>",
                        score: <?= min($score, 50) ?>,
                        fractionDigits: 0 // Optional: Numerical precision. 0=round off.
                    },
                    strokeColor: '#E0E0E0', // to see which ones work best for you
                    highDpiSupport: true, // High resolution support

                };
                var target = document.getElementById('foo'); // your canvas element
                var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
                gauge.maxValue = 50; // set max gauge value
                gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
                gauge.animationSpeed = 87; // set animation speed (32 is default value)
                gauge.set(<?= min($score, 50) ?>); // set actual value
            </script>
        </div>
    </div>
    <div style="display: flex; justify-content:space-between; width: 40%; margin: auto;">
        <a class="aButton settingsButton thirdWidth" href="<?= $backUrl ?>"><?= $backButton ?></a>
        <button type="button" id="button-show-div" class="aButton settingsButton thirdWidth">See details<img src="../vue/css/assets/eye.svg" style="width: 30px; height: auto; padding-left: 15px;" /></button>
    </div>

    <div id="question-table" class="tableContainer scrollTable absoluteHidden" style="margin: 0 auto 0 auto; width: 50% !important; max-height: 25vh !important;">
        <table class="tableAdmin" style="height: 100%;">
            <thead class="padBot">
                <tr>
                    <th>Question</th>
                    <th style="width: 12%;">Score</th>
                </tr>
            </thead>
            <tbody id="listAnalysis">
                <?= $questionListHTML ?>
            </tbody>
        </table>
    </div>
    <div id="export-div" style="display: flex; flex-direction: row-reverse; width: 40%; margin: auto; height: fit-content;" class="absoluteHidden">
        <a id="button-show-div" class="aButton settingsButton thirdWidth" href='<?= $downloadURL ?>'>Export data (CSV)</a>
    </div>

    <script src="./util/header.js"></script>
    <script src="./util/resultAnalysis.js"></script>
</body>