<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>درباره ما زوبس</title>

    <link href="{{ asset('include/css/main.css') }}" rel="stylesheet">

    <style>
        body{
            margin: 0;
        }
    </style>
</head>

<body style="background-color: #404040;">


    <div class="content">

        <div style="width: 45%;height: 100%;margin-left: 25%;margin-bottom: 10%;">
            <img src="https://zobs.ir/zoobs/include/pic/zoobs-logo-white2.png">
        </div>
        <div class="triangle-up"></div>
        <div class="main_content">
            <div class="main_content_to">
                <?php echo $data ?>
            </div>

        </div>
        <div class="triangle-down"></div>

        <a href="https://instagram.com/zobs_group">
            <div style="width: 10%;height: 100%;margin-left: 45%;margin-bottom: 10%;">
                <img src="https://zobs.ir/zoobs/include/pic/instagram.svg">
            </div>
        </a>


    </div>

</body>
</html>
