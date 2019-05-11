<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WUXTJS</title>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Nunito:700,600,400|Open+Sans:400,700');

        body {
            display: flex;
            justify-content: center;
            align-items: center;

            height: 100vh;
            margin: 0;
        }

        main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        p {
            display: flex;
            justify-content: center;

            margin: 10px 0 0 0;
            font-family: 'Nunito';
            font-weight: 700;
            font-size: 58px;
            color: #37495c;
        }

        span {
            color: #48b884;
        }

        sup {
            margin-left: 8px;
            font-size: 26px;
        }
    </style>
</head>

<body>
    <main>
        <img src="<?php echo get_template_directory_uri() ?>/wuxt-logo.svg">
        <p>
            WUXT
            <span>JS</span>
            <sup>API</sup>
        </p>
    </main>

</body>

</html>