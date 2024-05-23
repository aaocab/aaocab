<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
     //  var_dump($param_list);
        //exit;
        ?>
        <h4>Processing ...</h4>
        <form  method="post" action="<? echo $param_list ?>" name="frmTransactionpaynimo1" id="frmTransactionpaynimo1">
           
        </form>
        <script type="text/javascript">
            // self executing function
            (function () {
                // auto submit form
                 document.getElementById("frmTransactionpaynimo1").submit();
            })();
        </script>
    </body>
</html>