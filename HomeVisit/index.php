<!-- 登入頁面 -->
<!-- 頁首 -->
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>login</title>
    <style type="text/css">
        @media only screen and (min-width: 767px) and (max-width: 1920px) {
            body {
                font-family: "Microsoft JhengHei";
                background-color: #FAFAFA;
            }

            .picture {
                width: 120px;
                height: 120px;
                margin: auto;
            }

            h2 {
                text-align: center;
            }

            .container {
                width: 100%;
                display: flex;
                justify-content: center;
            }

            .login {
                border: 1px #D0D0D0 solid;
                border-radius: 5px;
                margin: 20px 0 0 0;
                padding: 20px 20px;
                line-height: 60px;
                background-color: #FFF;
            }

            .logincontent {
                width: 100%;
                font-size: 18px;
            }

            input {
                height: 30px;
                border: 1px #D0D0D0 solid;
                border-radius: 2px;
            }

            .button {
                text-align: center;
            }

            input[type="submit"] {
                width: 100%;
                height: 35px;
                background-color: #32C855;
                background-image: linear-gradient(#32C855, #29A847);
                color: #FFF;
                border: 2px #2EAD4E solid;
                border-radius: 4px;
                cursor: pointer;
                font-family: "Microsoft JhengHei";
            }

            input[type="submit"]:hover {
                border: 2px #279141 solid;
            }

        }

        /* iphone 678 */
        @media only screen and (max-width: 768px) {
            body {
                font-family: "Microsoft JhengHei";
                background-color: #FAFAFA;
            }

            .picture {
                width: 120px;
                height: 120px;
                margin: auto;
            }

            h2 {
                text-align: center;
            }

            .container {
                width: 100%;
                display: flex;
                justify-content: center;
            }

            .login {
                border: 1px #D0D0D0 solid;
                border-radius: 5px;
                margin: 20px 0 0 0;
                padding: 20px 20px;
                line-height: 50px;
                background-color: #FFF;
            }

            .logincontent {
                width: 100%;
                font-size: 14px;
            }

            input {
                height: 20px;
                border: 1px #D0D0D0 solid;
                border-radius: 2px;
            }

            .button {
                text-align: center;
            }

            input[type="submit"] {
                width: 100%;
                height: 25px;
                background-color: #32C855;
                background-image: linear-gradient(#32C855, #29A847);
                color: #FFF;
                border: 2px #2EAD4E solid;
                border-radius: 4px;
                cursor: pointer;
                font-family: "Microsoft JhengHei";
            }

            input[type="submit"]:hover {
                border: 2px #279141 solid;
            }
        }
    </style>
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        }
        setTimeout("preventBack()", 0);
        window.onunload = function() {
            null
        };
    </script>

</head>

<body>

    <?php
    include "./data_base/data.php";
    include "./data_base/employees.php";

    // 取 員工資料
    $employees = new Employees();
    $stmt = $employees->get_employee();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 設布林值初始值為false
    $boolean = false;

    if (!isset($_SESSION['username']) || $_SESSION['username'] == "") {
        // 判斷 按下登入按鈕
        if (isset($_POST['submit'])) {
            // 則 取帳號密碼的值(去空白值)
            $username = trim($_POST['employee_id']);
            $password = trim($_POST['password']);

            // 判斷 帳密是否有在資料庫中
            for ($i = 0; $i < count($data); $i++) {
                if ($username == $data[$i]["employee_id"] && ($password == $data[$i]["phone"])) {
                    // 若有 則布林值為true 並跳出迴圈
                    $boolean = true;
                    $stmt->level = $data[$i]["level"];
                    break;
                } else {
                    // 若無 則繼續迴圈
                    continue;
                }
            }

            if ($boolean == true) {
                // 登入成功 跳搜尋頁面
                $_SESSION['username'] = $username;
                $_SESSION['level'] = $stmt->level;
                echo "<meta http-equiv=REFRESH CONTENT=0;url=search.php>";
            } else {
                // 登入失敗 跳回登入頁面
                echo "<script>alert('登入失敗!')</script>";
                echo "<meta http-equiv=REFRESH CONTENT=0;url=index.php>";
            }
        }
    }

    if (isset($_GET['logout']) && $_GET['logout'] == "true") {
        unset($_SESSION['username']);
        echo "<meta http-equiv=REFRESH CONTENT=0;url=index.php>";
    }

    ?>
    <!-- 頁面標題 -->
    <nav class="headcontainer">
        <div>
            <h1 style="text-align:center; color:darkorange; font-size:xx-large; text-shadow:2px 2px 2px black;">Home Visit GO!</h1>
        </div>
        <div>
            <h2>登入</h2>
        </div>
    </nav>

    <!-- 登入表單 -->
    <div class="container ">
        <div class="login">
            <div class="picture">
                <img src="./img/bg.png" style="width: 120px; height: 120px;">
            </div>
            <form action="" method="POST" class="form-horizontal">
                <div class="logincontent">
                    <div>
                        <label for="employee_id">帳號：</label>
                        <input type="text" name="employee_id" id="employee_id" required>
                    </div>
                    <div>
                        <label for="password">密碼：</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                </div>

                <div class="button">
                    <input type="submit" name="submit" value="登入">
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>