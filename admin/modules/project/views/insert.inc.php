<script>

     

    function check(){ 
 
        var project_name = document.getElementById("project_name").value;  
        var project_price_per_rai = document.getElementById("project_price_per_rai").value;   
        var project_description = document.getElementById("project_description").value;   
         
        project_name = $.trim(project_name); 
        project_price_per_rai = $.trim(project_price_per_rai);    
        project_description = $.trim(project_description);    

        if(project_name.length == 0){
            alert("Please input project name");
            document.getElementById("project_name").focus();
            return false;    
        }else if(project_price_per_rai.length == 0 ){
            alert("Please input price");
            document.getElementById("project_price_per_rai").focus();
            return false;     
        }else{
            return true;
        } 
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#project_drawing_url').attr('value', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#project_drawing_url').attr('src', '');
        }
    }

    function readURL_logo(input) {

        if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#img_logo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_logo').attr('src', '../upload/default.png');
        }
    }
 

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Project Management</h1>
    </div>
    <div class="col-lg-6" align="right"> 
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                เพิ่มโครงการ / Add Project 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=project&action=add" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row"> 
                            <div class="col-lg-12">
                                
                                    <div class="form-group">
                                        <label>ชื่อโครงการ / Name. <font color="#F00"><b>*</b></font></label>
                                        <input id="project_name" name="project_name" class="form-control">
                                        <p class="help-block">Example : VNMG060404EN...</p>
                                    </div>
                                
                            </div>     
                            <div class="col-lg-6"> 
                                <div class="form-group">
                                    <label>ราคา ( ต่อไร่ ) / Price. <font color="#F00"><b>*</b></font></label>
                                    <input type="number" id="project_price_per_rai" name="project_price_per_rai" class="form-control">
                                    <p class="help-block">Example : 450</p>
                                </div> 
                            </div>     
                        </div> 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รายละเอียดโครงการ / Description </label>
                                    <input id="project_description" name="project_description" type="text" class="form-control">
                                    <p class="help-block">Example : Description...</p>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รูปโครงการ / Project Picture </label>
                                    <img class="img-responsive" id="img_logo" src="../upload/default.png" />
                                    <input accept=".jpg , .png"   type="file" id="project_logo" name="project_logo" onChange="readURL_logo(this);">
                                    <p class="help-block">Example : .jpg or .png </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button  type="submit"  class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>