
        <?php
        ?>
        <h4>Processing ...</h4>
        <form id="checkout_form" name="checkout_form" method="post" action="<?= $param_list['action'] ?>">
            <?php
            unset($param_list['action']);
            foreach ($param_list as $name => $value) :
                ?>
                <input type="hidden" name="<?= $name ?>" value="<?= $value; ?>">
            <?php endforeach; ?>
			<?php if (!$param_list['hash']) { ?>
                <input type="submit" value="Submit" />
			<?php } ?>
        </form>


        <script type="text/javascript">
            // self executing function
            (
                    function () {
                        // auto submit form
                        document.getElementById("checkout_form").submit();
                    })();
        </script>
