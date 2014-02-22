<?php
    /**
     *
     * @package scf-framework
     * @author Steve
     * @date 2/6/14
     */
    if( $_GET['tour'] == 'on' ): ?>

        <script>
            $(function () {
                $('#wfc-guided-tour').joyride({
                    autoStart       : true,
                    preStepCallback: function(index, tip){
                        if (index == 1) {
                            $('li.wfc-web-property').first().addClass('open');
                            console.log(index);
                            console.log(tip);
                        }

                    },
                    postStepCallback: function (index, tip) {
                        if (index == 2) {
                            $(this).joyride('set_li', false, 1);
                        }
                    }
                });
                $('#collapseOne,#collapseTwo').removeClass('collapse').addClass('in');
                //$('li.wfc-web-property').first().addClass('open');
            });
        </script>
        <?php
        $guided_tour_array = array(
            "nav-wfc-properties" => array("Here you'll select which web property you want to work with.", ""),
            "nav-wfc-templates"  => array("You can edit the different templates here!", ""),
            "wfc-documentation"  => array("Click for additional documentation.", ""),
            "wfc-logout"         => array("Click to logout of the application.", "")
        );
        ?>
        <ol id="wfc-guided-tour">
            <?php foreach( $guided_tour_array as $tour_k => $tour_v ): ?>
                <li data-class="<?php echo $tour_k; ?>" data-text="Next" data-options="<?php echo $tour_v[1]; ?>">
                <p><?php echo $tour_v[0]; ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>