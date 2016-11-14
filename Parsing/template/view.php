<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Posts from bash.im</title>

    <!-- Bootstrap -->
    <link href="Parsing/main/css/bootstrap.min.css" rel="stylesheet">
    <link href="Parsing/main/css/my_style.css" type="text/css" rel="stylesheet">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

</head>
<body>
<div class="jumbotron">
    <div class="container">
        <?php for ($i = $start; $i < $end; $i++): ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="thumbnail">
                        <p><?php echo $this->data[$i] ?></p>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
        <div class="row text-center">
            <?php echo $pagination; ?>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../main/js/bootstrap.min.js"></script>
</body>
</html>