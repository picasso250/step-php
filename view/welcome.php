<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Step Away From PHP</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .bottom-long {
                margin-bottom: 2em;
            }
        </style>

        <?php if ($_ENV['DEBUG']) echo $GLOBALS['debugbarRenderer']->renderHead() ?>

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            
            <div class="content">
                <div class="bottom-long">距离原生 PHP 和 Laravel 都是一步之遥</div>

                <div class="title m-b-md">
                    Step-PHP
                </div>

                <div class="links">
                    <a href="https://github.com/picasso250/step-php">Documentation/文档</a>
                    <a href="https://github.com/picasso250/step-php">GitHub</a>
                </div>
            </div>
        </div>

        <?php if ($_ENV['DEBUG']) echo $GLOBALS['debugbarRenderer']->render() ?>
        
    </body>
</html>
