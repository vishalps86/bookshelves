<?php  _load_js($js);  ?>

<div class="static-content-inner">
  <div class="static-content">
    <div class="page-content">
     
      <div class="page-heading">
        <h1>Bookshelves</h1>
		  
        <div class="clearfix"></div>
      
      </div>
      <div class="container-fluid">
        <div data-widget-group="group1" class="ui-sortable">
          <div class="row">
            <div class="col-sm-12 user-listing">
              <div class="panel panel-default listing">
                <div class="form-data mb mt">
                  <form method="post" id="grid-filter">
                    <div class="">
                      <div class="row">
					  <div class="col-sm-10" style='padding-left:30px'>
						
						<select class="shelves form-control" name="shelve_id" id="shelve_id" style="width:150px;;float:left;">
                            <option value="">Select Shelves</option>
							<?php foreach($shelves as $s) { ?>
								<option value="<?php echo $s['shelve_id']?>" <?php if($shelve_id == $s['shelve_id']) { ?> selected <?php } ?>><?php echo $s['name']?></option>							
							
							<?php } ?>
                         </select>
                          
						<div class="clearfix"></div>
						 </div>
						  <div class="col-sm-2 manage-users-right" style="text-align: right;">
                       
                        <div class="search-section ">
                          <div class="search-section__area">
                           
                            <div class="input-group">
                              <input type="text" name="search_box" placeholder='search' id="search_box" class="input form-control" value="<?php echo $search_box; ?>">
                                  
                            </div>
                          </div>
                        </div>
                         
                        
               <div class="clearfix"></div>    
</div>
<div class="clearfix"></div>
                        
                        <!-- <div class="col-sm-12">
                        
                        </div> -->
                       

                      </div>
                     
                    <div class="hidden-fields hidden">
                      <?php echo _grid_hidden_fields($cur_page, $callback, $orderby, $order); ?>
                    </div>
                  </form>
                  <div class="clearfix"></div>
                </div>
                <div class="panel-body no-padding clearfix">
                  <div class="table-responsive">
                    <table id='examples' class='table'>
                      <thead>
                        <tr>
                          <th>Book Id</th>
                          <th>Book Name</th>
                          <th>Author</th>
                          <th>Detail</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php         
                          if ($result) :
                              foreach ($result  as  $val) {                              
                        ?>
                        <tr>
						 
                          <td><?php echo $val['book_id']; ?></td>
                          <td><?php echo $val['book_name']; ?></td>
                          <td><?php echo $val['author']; ?></td>
                          <td>
                            <?php echo _actions_icon('/bookshelves/view/' . $val['book_id'], 'view', 'page_ajaxify', '', 'bookshelves/index'); ?> &nbsp;
							
                          </td>
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
