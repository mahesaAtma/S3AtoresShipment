<?php 


$users = $obj->getUsers();
$sn=1;

/*
echo '<script type="text/javascript">'; 
echo 'alert("'.$_POST['id'].'");'; 
echo '</script>';
*/

?>

<!-- MAIN CONTENT-->
<div class="main-content">
<div class="section__content section__content--p25">
<div class="container-fluid">
  <!--<a href="index.php?page=sttb_upload" class="au-btn au-btn-icon au-btn--blue pull-right" style='margin-top:-30px;'> <i class="zmdi zmdi-zoom-out"></i> View Record</a>-->

    <table class="" width="100%" style="padding:10px;">
        <tr>
        <td class="pull-left" style="vertical-align:top;padding:20px;">  
        <h5><a href="index.php"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a></h5>
        </td>
        <td class="pull-right" style="vertical-align:top;padding:20px;">  
        <h5><i class="fas fa-check"></i>&nbsp;&nbsp;UPLOAD STTB</h5>
        </td>
        </tr>
    </table>
    <br/>
<hr/>

<div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                    <table class="" width="100%">
                    <tr>
                    <td class="pull-left" style="vertical-align:top">  
                      
                    </td>
                    <td class="pull-right" style="vertical-align:top">  
                    <a href="index.php?page=sttb_insert" class="btn btn-primary btn-sm" > <i class="zmdi zmdi-plus"></i> Add Data</a>
                       
                    </td>

                    </tr>

                    </table>


                   
                    </div>
                    <div class="card-body">

                    <div class="table-responsive table-responsive-data2">
                        <br/>
                       
                    
                
                    <table class="table-responsive" width="100%" style="font-size:13px;">
                        <thead>
                        <tr class="active">
                                <th>No.</th>  
                                <th >Name</th>   
                                <!--<th >Email</th>-->
                                <th >Info</th>
                                <!--<th >Address</th>  -->  
                                <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while($user = pg_fetch_object($users)): ?>   
                        <tr align="left" class="tr-shadow">
                            <td width="5%"><?=$sn++?></td>
                            <td><?=$user->name?></td>
                            <td>  <?=$user->email?><br/>
                              <?=$user->mobile_no?><br/>
                                    <?=$user->address?></td>
                            <td width="15%">
                                <table class="table"width="100%">
                                    <tr>
                                    <td>  
                                        <form action="index.php" method="post">
                                        <input type="hidden" value="<?=$user->id?>" name="id">
                                        <input type="hidden" value="sttb_edit" name="page">

                                        <button style="width:30px;" type="submit" class="btn btn-success btn-sm" name= "edit" ><i class="zmdi zmdi-border-color"></i></button>  &nbsp;&nbsp;
                                        </form>
                                    </td>
                                    <td>  
                                        <form action="index.php" method="post">
                                        <input type="hidden" value="<?=$user->id?>" name="id">
                                        <input type="hidden" value="sttb_delete" name="page">
                                        <button style="width:30px;" type="submit" onClick="return confirm('Please confirm deletion ?');" class="btn btn-danger btn-sm" name= "delete" value="Delete"><i class="zmdi zmdi-delete"></i></button>  
                                        </form>
                                    </td>

                                    </tr>

                                </table>
                                
                                
                            </td>
                        </tr>
                        <?php endwhile; ?>   
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