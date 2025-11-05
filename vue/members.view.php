<head>
    <title>Members</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather%20Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../vue/css/header.css" rel="stylesheet" />
    <link href="../vue/css/table.css" rel="stylesheet" />
    <link href="../vue/css/basic.css" rel="stylesheet" />
    <link href="../vue/css/input.css" rel="stylesheet" />
    <link href="../vue/css/editPass.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


</head>

<body>
    <?php
    include("../vue/header.php");
    ?>
    <form class="centerArea" id="form" action="../controleur/members.php" method="post">

        <div class="indexWelcome">
            <!-- A modifié si on retire le welcome pour les admins -->
            <input type="search" name="filter" placeholder="Type here to filter results" class="searchBar" id="filterMembers">
        </div>
        <div class="tableContainer">
            <table class="tableAdmin">
                <thead>
                    <tr>
                        <th>Username ID</th>
                        <th>Name</th>
                        <th>Company</th>
                        <?= $thStatus ?>
                        <th>Analysis History</th>
                        <th>Account Access</th>
                        <?= $thBlock ?>
                    </tr>
                </thead>
                <tbody id="listMembers">
                    <?= $tbody ?>
                </tbody>
            </table>
        </div>
        <div class="dispButton">
            <a class="aButton settingsButton thirdWidth" href="./index.php">Home</a>
            <input type="submit" class="aButton settingsButton thirdWidth" value="Add Member" name="add">
        </div>
    </form>


    <div class="coverModal absoluteHidden">
        <div class="modal">
            <h1>Are you sure you want to block this account ?</h1>
            <p>You can't revert this action.</p>
            <div class="buttonDiv">
                <button class="aButton redBack" type="submit" onclick="endModal(false)">Cancel</button>
                <button class="aButton" type="submit" onclick="endModal(true)">Confirm</button>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="./util/dataFilter.js"></script>
    <script src="./util/header.js"></script>
    <script>
        const mod = $('.coverModal');
        let idx = 0;
        
        function modal(event, id) {
            console.log(id)
            event.preventDefault();
            idx = id;
            mod.toggleClass('absoluteHidden',false)
            // mod.classList.toggle('absoluteHidden',false)
        }

        function endModal(val) {
            mod.toggleClass('absoluteHidden',true)
            if(val) {
                // Etant donné que lorsqu'on affiche la popup, on retire la soumission du formulaire, il faut la gérer.
                // Pour ce faire, nous créons un faux formulaire avec : $_POST['block'] = id de l'user à delete
                // Puis on submit.
                let form = document.createElement("form");
                form.method = "POST";
                form.action = "../controleur/members.php";

                let input = document.createElement("input");
                input.type = "hidden";
                input.name = "block"; 
                input.value = `${idx}`;

                form.appendChild(input);
                document.body.appendChild(form);

                form.submit();
            } 
        }
    </script>
</body>