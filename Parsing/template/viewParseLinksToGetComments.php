<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Posts from "zefirka.net"</title>

    <!-- Bootstrap -->
    <link href="Parsing/main/css/bootstrap.min.css" rel="stylesheet">
    <link href="Parsing/main/css/my_style.css" type="text/css" rel="stylesheet">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

</head>
<body>
<div class="jumbotron">
    <div class="container">
        <div class="row alert alert-info text-center">
            <h1>All the posts with its comments from "zefirka.net"</h1>
        </div>
        <div class="row text-center">
            <?php echo $pagination; ?>
        </div>
        <?php for ($i = $start; $i < $end; $i++): ?>
            <div class="row alert alert-info">
                <div class="col-sm-12 alert alert-warning">
                    <h3><?php echo $this->data[$i]['headline']; ?></h3>
                    <div class="thumbnail alert alert-success text-center">
                        <img src="<?php echo $this->data[$i]['headline_img_src']; ?>" class="img-circle"
                             alt="<?php echo $this->data[$i]['headline']; ?>" width="304" height="236">
                    </div>
                    <?php foreach ($this->data[$i]['comments'] as $key2 => $value2): ?>
                        <div class="thumbnail alert alert-info">
                            <h3>Комментарий № <?php echo ($key2 + 1) . ':'; ?></h3>
                            <h4><?php echo $value2; ?></h4>
                        </div>
                    <?php endforeach; ?>
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