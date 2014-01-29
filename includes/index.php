<?php


    //scf_get_property_domain( 23653514, "UA-23653514-1", $analytics );

?>

<div id="container" class="">
    <div class="row toolbar">
        Connected as <strong><?php echo $_SESSION['email']; ?></strong>
        <span class="separator"></span>
        <?php echo $revoke; ?>
    </div>
    <div class="row row-offcanvas row-offcanvas-right">
        <?php 
        require_once('sidebar.php'); 
        ?>
        <div class="col-xs-12 col-sm-8 main-content">
            <?php
                if( isset($_POST['code']) && intval( $_POST['code'] ) > 0 ){
                    $html = file_get_contents( PROP_DIR.DS.$_SESSION['properties'][intval($_POST['code'])].DS.intval($_POST['code']).DS.'template.tpl' );
                    echo $html;
                }
                else if(isset($_GET['view']) && $_GET['view']=='templates')
                {
                    $html = file_get_contents( TPL_DIR.DS.$_SESSION['email'].DS.$name );
                    echo $html;
                }
            ?>
        </div>
    </div>
    <footer>
        <p>&copy; Web Full Circle 2013</p>
    </footer>
</div><!--/.container-->
<div class="modal fade" id="view_site_template" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form role="form" method="POST" action="./index.php?export" data-action="./index.php?export" id="form_" class="view_data" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="month">Month </label>
                        <select class="wfc-select form-control" name="month">
                            <?php
                                foreach( $months as $k => $m ){
                                    echo '
                            <option
                            '.($k == date( 'n' ) - 1 ? 'selected="selected"' : '').' value="'.$k.'">'.$m.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Year </label>
                        <select class="wfc-select form-control" name="year">
                            <?php
                                for( $i = 2007; $i <= date( 'Y' ); $i++ ){
                                    echo '
                            <option
                            '.($i == date( 'Y' ) ? 'selected="selected"' : '').' value="'.$i.'">'.$i.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="code" id="code" class="wfc-input form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" data-action="ajax-right" data-target="tplValues" data-where="#form_" data-names="code,month,year" class="form-control btn btn-default">View Template</button>
                        <button type="submit" data-action="ajax-right" data-target="customTpl" data-where="#form_" data-names="code" class="form-control btn btn-primary">Template only</button>
                        <button type="submit" class="exportPdf form-control btn btn-info">Export PDF</button>
                        <button type="submit" data-action="ajax-right" data-target="email" data-where="#form_" data-names="code,month,year"  class="form-control btn btn-danger">Send PDF</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Modal -->
<div class="modal fade" id="create_new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form role="form" method="POST" class="new" id="modalform" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" id="name" name="name" class="wfc-input form-control" placeholder="Enter Name" value=""/>
                    </div>
                    <div class="form-group">
                        <label for="logo_upload">File input</label>
                        <input type="file" class="wfc-upload form-control" name="logo_upload" id="logo_upload">
                        <input type="hidden" name="logo">
                        <p class="help-block">Upload a logo</p>
                    </div>
                    <div class="form-group">
                        <input type="text" id="url" name="url" class="wfc-input form-control" placeholder="Enter URL" value=""/>
                    </div>
                    <div class="form-group">
                        <input type="text" id="code" readonly="readonly" class="wfc-input form-control" name="codenew" value=""/>
                    </div>
                    <div class="form-group">
                        <label for="template">Template </label>
                        <select class="wfc-select form-control" name="template">
                            <?php
                                $t = scandir( TPL_DIR.DS.$_SESSION['email'] );
                                foreach( $t as $v ){
                                    if($v != '.' && $v != '..' )
                                        if( substr( $v, -3 ) == 'tpl' && $v != '.' && $v != '..' ){
                                            echo '<option value="'.$v.'">'.substr( $v, 0, -4 ).'</option>';
                                        }
                                    
                                }
                            ?>
                        </select>
                    </div>
                    <button type="submit" data-where="#modalform" data-target="new" data-names="name,codenew,url,template,logo" class="form-control btn btn-default">Submit</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->