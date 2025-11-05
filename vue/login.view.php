<head>
    <title>Login</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather%20Sans">
    <link href="../vue/css/login.css" rel="stylesheet" />
    <link href="../vue/css/basic.css" rel="stylesheet" />
    <link href="../vue/css/input.css" rel="stylesheet" />
</head>

<body>
    <div class="circle topLeft" >&nbsp;</div>
    <form class="centerLogin" action="../controleur/login.php" method="post">
        <div>
            <h1>LOGIN</h1>

            <input class="userpassInput" autocomplete="off" name="username" id="username" type="text" placeholder="Username" value="<?= $identifiants['username']?>" required />
            <hr />
            <input class="userpassInput" name="pass" id="pass" type="password" placeholder="Password" value="<?= $identifiants['pass'] ?>" required />
            <hr />

            <p class="errorLabel" style="text-align: center;"> <?= $message ?></p>
            <input type="submit" name="login" value="Login" class="inputLogin" />
        </div>
    </form>
    <div class="circle bottomRight">&nbsp;</div>
</body>