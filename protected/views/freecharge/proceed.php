<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
        //  var_dump($mbk_post);
        //exit;
        ?>
        <h4>Processing ...</h4>
        <form  method="post" enctype='application/json' action="<? echo $txn_url ?>" name="frmTransaction1" id="frmTransaction1">
            <?php
           
            foreach ($frc_post as $name => $value) :
                ?>
                <input 
                    type="hidden"  
                    name="<?= $name ?>" 
                    value="<?= $value; ?>">
                <?php endforeach; ?>
                <? 
              
                /* ?>
                  <input name="submitted" value="Submit" type="submit" />
                  <? */
                ?>

        </form>
        <script type="text/javascript">
            // self executing function
            (function () {
                // auto submit form
                document.getElementById("frmTransaction1").submit();
            })();
        </script>
    </body>
</html>