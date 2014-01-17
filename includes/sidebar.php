<?php
    /**
     *
     * @package ReportHelper
     * @author Steve
     * @date 12/17/13
     */

    $get_properties = new wfc_core_class;
?>
<div class="col-xs-6 col-sm-4  sidebar-menu" id="sidebar">
    <ul class="nav nav-pills nav-stacked">
        <li class="active">
            <a href="#">Sites</a>
        </li>
        <?php
            $months = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            $sites = $get_properties->getSitesList( $analytics );
            $t = scandir( './templates' );
            foreach( $sites as $s ){ ?>
                <li class="dropdown wfc-web-property">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="wfc-property-url"><?php echo $s->getWebsiteUrl(); ?></span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                        <?php if( file_exists( __DIR__.'/../'.$s->getId().'/template.tpl' ) ){
                            ?>
                            <a href="#" data-code="<?php echo $s->getId(); ?>" data-toggle="modal" class="view-site-template" data-target="#view_site_template">Actions</a>
                            <a href="#" data-code="<?php echo $s->getId(); ?>" data-toggle="modal" class="edit-site-template" data-target="">Edit</a>
                            <a href="#" data-code="<?php echo $s->getId(); ?>" data-toggle="modal" class="delete-site-template" data-target="">Delete</a>
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
    <ul class="nav nav-pills nav-stacked">
        <li class="active">
        <a href="#" class="create_new">Create a new template</a>
        </li>
        <?php
            $temp = scandir( './templates/' );
            if( is_array( $temp ) ){
                foreach( $temp as $e ){
                    if( $e != '.' && $e != '..' ){
                        if( substr( $e, -3 ) == 'tpl' ){
                            ?>
                            <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <?php echo $e; ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                <a href="#" data-name="<?php echo $e; ?>" class="template-toolbar-icon template_view">View</a>
                                </li>
                                <li>
                                <a href="#" data-name="<?php echo $e; ?>" class="template-toolbar-icon template_edit">Edit</a>
                                </li>
                                <li>
                                <a href="#" data-name="<?php echo $e; ?>" class="template-toolbar-icon template_delete">Delete</a>
                                </li>
                            </ul>
                            </li>
                        <?php
                        }
                    }
                }
            }
        ?>
    </ul>
</div>
<!--/span-->