<?php
    /**
     *
     * @package ReportHelper
     * @author Steve
     * @date 12/17/13
     */

    $get_properties = new wfc_core_class;
?>
<ul class="nav nav-pills nav-stacked nav-wfc-properties-container">
    <li class="active">
    <a data-toggle="collapse" data-parent=".nav-wfc-properties-container" href="#collapseOne" class="nav-wfc-properties collapsed">Sites
        <span class="glyphicon glyphicon-plus pull-right"></span>
    </a>
    <ul class="collapse" id="collapseOne">
        <?php
            $months = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            $sites = $get_properties->getSitesList( $analytics );
            foreach( $sites as $s ){
                ?>
                <li class="dropdown wfc-web-property">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="wfc-property-url"><?php echo $s->getWebsiteUrl(); ?></span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                    <?php if( file_exists( PROP_DIR.DS.$_SESSION['properties'][$s->getId()].DS.$s->getId().DS.'template.tpl' ) ){
                        ?>
                        <a href="#" data-code="<?php echo $s->getId(); ?>" data-toggle="modal" class="view-site-template" data-target="#view_site_template">Actions</a>
                        <a href="#" data-code="<?php echo $s->getId(); ?>" data-toggle="modal" class="edit-site-template" data-action="ajax-right" data-names="code" data-where="this" data-target="new">Edit</a>
                        <a href="#" data-code="<?php echo $s->getId(); ?>" data-toggle="modal" class="delete-site-template" data-action="ajax-right" data-names="code" data-where="this" data-target="delete">Delete</a>
                    <?php } else{ ?>
                        <a href="#" data-code="<?php echo $s->getId(); ?>" data-toggle="modal" class="create-new" data-target="#create_new">New</a>
                    <?php } ?>
                    </li>
                </ul>
                </li>
            <?php
            }//endforeach
        ?>
    </ul>
    </li>
</ul>
<ul class="nav nav-pills nav-stacked nav-wfc-templates-container">
    <li class="active">
        <a data-toggle="collapse" data-parent=".nav-wfc-templates-container" href="#collapseTwo" class="nav-wfc-templates collapsed">Templates
            <span class="glyphicon glyphicon-plus pull-right"></span>
        </a>
        <ul class="collapse" id="collapseTwo">
            <?php
                $temp = scandir( TPL_DIR.DS.$_SESSION['email'] );
                if( is_array( $temp ) ){
                    foreach( $temp as $f ){
                        if( $f != '.' && $f != '..' && substr( $f, -3 ) == 'tpl' ){
                            ?>
                            <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <?php echo $f; ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                <a href="#" data-name="<?php echo $f; ?>" class="template-toolbar-icon template_view" data-action="ajax-right" data-names="name" data-where="this" data-target="tplOnly">View</a>
                                </li>
                                <li>
                                <a href="#" data-name="<?php echo $f; ?>" class="template-toolbar-icon template_edit" data-action="ajax-right" data-names="name" data-where="this" data-target="new">Edit</a>
                                </li>
                                <li>
                                <a href="#" data-name="<?php echo $f; ?>" class="template-toolbar-icon template_delete" data-action="ajax-right" data-names="name" data-where="this" data-target="delete">Delete</a>
                                </li>
                            </ul>
                            </li>
                        <?php
                        }
                    }
                }
            ?>
            <li>
                <a class="create_new" data-target="new" data-action="ajax-right">Create a new template</a>
            </li>
        </ul>
    </li>
</ul>