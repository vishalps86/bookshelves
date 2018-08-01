<div class="static-content-inner manage-employees manage-users">
  <div class="static-content">
    <div class="page-content">
     
      <div class="page-heading">
        <h1>Top Music Tracks</h1>
		  
        <div class="clearfix"></div>
      
      </div>
      <div class="container-fluid">
        <div data-widget-group="group1" class="ui-sortable">
          <div class="row">
            <div class="col-sm-12 user-listing">
              <div class="panel panel-default listing">
               
                <div class="panel-body no-padding clearfix">
                  <div class="table-responsive">
                    <table id='examples' class='table'>
                      <thead>
                        <tr>
                          <th>Track Name</th>
                          <th>Playcount</th>
                          <th>Artist</th>                          
                        </tr>
                      </thead>
                      <tbody>
                        <?php         
                          if ($tracks) :
                              foreach ($tracks  as  $val) {                              
                        ?>
                        <tr>						 
                          <td><?php echo $val->name; ?></td>
                          <td><?php echo $val->playcount; ?></td>
                          <td><?php echo $val->artist->name; ?></td>
                        </tr>
                        <?php
                          }
                          else :
                          ?>
                        <tr>
                          <td colspan="4">
                            <div class="text-center alert-danger">No Record Found</div>
                          </td>
                        </tr>
                        <?php endif; ?>
                      </tbody>
                     
                    </table>
                  </div>
                </div>
             
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- page-content -->
  </div>
  <!-- static-content -->
</div>
<!-- static-content-wrapper -->
