           
<script src="../plugins/excel/xlsx.core.min.js"></script>  
<script src="../plugins/excel/xls.core.min.js"></script> 

<script>

var number_error = 0; 
    
function ExportToTable(id) {  
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;  
    /*Checks whether the file is a valid excel file*/  
    if (regex.test($("#excelfile").val().toLowerCase())) {  
        var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/  
        if ($("#excelfile").val().toLowerCase().indexOf(".xlsx") > 0) {  
            xlsxflag = true;  
        }  
        /*Checks whether the browser supports HTML5*/  
        if (typeof (FileReader) != "undefined") {  
            var reader = new FileReader();  
            reader.onload = function (e) {  
                var data = e.target.result;  
                /*Converts the excel data in to object*/  
                if (xlsxflag) {  
                    var workbook = XLSX.read(data, { type: 'binary' });  
                }  
                else {  
                    var workbook = XLS.read(data, { type: 'binary' });  
                }  
                /*Gets all the sheetnames of excel in to a variable*/  
                var sheet_name_list = workbook.SheetNames;  

                var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/  
                sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/  
                    /*Convert the cell value to Json*/  
                    if (xlsxflag) {  
                        var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);  
                    }  
                    else {  
                        var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);  
                    }  
                    if (exceljson.length > 0 && cnt == 0) {  
                        BindTable(exceljson,id);  
                        cnt++;  
                    }  
                });  
                $('#exceltable').show();  
            }  
            if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/  
                reader.readAsArrayBuffer($("#excelfile")[0].files[0]);  
            }  
            else {  
                reader.readAsBinaryString($("#excelfile")[0].files[0]);  
            }  
        }  
        else {  
            alert("Sorry! Your browser does not support HTML5!");  
        }  
    }  
    else {  
        alert("Please upload a valid Excel file!");  
    }  
}   


function delete_row(id){
    $(id).closest('tr').remove();
} 


function BindTable(jsondata,id) {
    number_error = 0;
    $("#search_pop").attr('checked',false);
    $("#bodyAdd").html('');

    if($('#stock_group_id').val() != ''){
        material_data = jsondata;
        for (var i = 0; i < jsondata.length; i++) {  
            get_material_row(jsondata[i],i);
        }

        $("#excelfile").val('');
        $('#modalAdd').modal('show');
    }else{
            alert('Please select stock group.');
    }   
}


function get_material_row(material,i){
    $.post( "controllers/getMaterialByCode.php", { 'material_code': $.trim(material.material_code)}, function( data ) {
        if(data != null){ 
            number_error ++;
            $('.number_error').html(number_error);
            $("#bodyAdd").append(
                '<tr class="odd gradeX not-find" >'+ 
                    '<td style="background:#888;">'+   
                        'มีวัตถุดิบรหัส "' + material.material_code + '" นี้ในระบบแล้ว' +
                    '</td>'+
                    '<td style="background:#888;" align="right">'+material.material_name+'</td>'+ 
                    '<td style="background:#888;" >'+
                        '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                            '<i class="fa fa-times" aria-hidden="true"></i>'+
                        '</a>'+
                    '</td>'+
                '</tr>'
            ); 
        }else{
            $("#bodyAdd").append(
                '<tr class="odd gradeX find">'+  
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  name="material_code[]" value="'+material.material_code+'" readonly /></td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  name="material_name[]" value="'+material.material_name+'" readonly /></td>'+ 
                    '<td>'+
                        '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                            '<i class="fa fa-times" aria-hidden="true"></i>'+
                        '</a>'+
                    '</td>'+
                '</tr>'
            );
        } 
    });
}

function search_pop_like(id){ 

    if($(id).is(':checked')){
        $('tr[class="odd gradeX find"]').hide();
        console.log("checked");
    }else{
        $('tr[class="odd gradeX find"]').show();
        console.log("unchecked");
    }
}

function export_error(){
    $('tr[class="odd gradeX find"]').remove();
    var d = new Date();

    var downloadLink = document.createElement("a");
    downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#tb_import').html());
    downloadLink.download = "export-error "+d.getFullYear() +"-"+ (d.getMonth() + 1) +"-"+ d.getDate() +".xls";

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
    //window.open('data:application/vnd.ms-excel,filename=export-error.xls,' + encodeURIComponent($('#tb_import').html()));
    $('#modalAdd').modal('hide');

} 
 
</script>
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header">Material Management</h1>
                </div>
                <div class="col-lg-6" align="right">
                    <?php if($menu['material']['view']==1){ ?> 
                    <a href="?app=material" class="btn btn-primary btn-menu active">วัตถุดิบ / Material</a> 
                    <?PHP }?>
                    <?php if($menu['material_type']['view']==1){ ?> 
                    <a href="?app=material_type" class="btn btn-primary btn-menu">ประเภท / Type</a> 
                    <?PHP }?>
                    <?php if($menu['unit']['view']==1){ ?> 
                    <a href="?app=unit" class="btn btn-primary btn-menu ">หน่วย / Unit</a>
                    <?PHP }?>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-6">
                                    รายการวัตถุดิบ / Material List
                                </div>
                                <div class="col-md-6">
                                <?php if($menu['material']['add']==1){ ?>  
                                    <div class="row"> 
                                        <div class="col-md-12" align="right"> 
                                            <a class="btn btn-success " style="float:right;margin-left:8px;" href="?app=material&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                                        </div>
                                    </div>   
                                <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" method="get" action="index.php?app=material">
                                <input type="hidden" name="app" value="material" />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>ผู้ขาย / Supplier </label>
                                            <select id="supplier_code" name="supplier_code" class="form-control select"  data-live-search="true">
                                                <option value="">ทั้งหมด</option>
                                                <?php 
                                                for($i =  0 ; $i < count($suppliers) ; $i++){
                                                ?>
                                                <option <?php if($suppliers[$i]['supplier_code'] == $supplier_code){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_code'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                                        </div>
                                    </div>
                                     
                                    <!-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label>ประเภท / Type </label>
                                            <select id="material_type_code" name="material_type_code" class="form-control select"  data-live-search="true">
                                                <option value="">ทั้งหมด</option>
                                                <?php 
                                                for($i =  0 ; $i < count($material_type) ; $i++){
                                                ?>
                                                <option <?php if($material_type[$i]['material_type_code'] == $material_type_code){?> selected <?php }?> value="<?php echo $material_type[$i]['material_type_code'] ?>"><?php echo $material_type[$i]['material_type_name'] ?> </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                            <p class="help-block">Example : - .</p>
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>คำค้น <font color="#F00"><b>*</b></font></label>
                                            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                                            <p class="help-block">Example : T001.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" type="submit">Search</button>
                                        <a href="index.php?app=material" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_length" id="dataTables-example_length">
                                        
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div id="dataTables-example_filter" class="dataTables_filter">
                                        
                                    </div>
                                </div>
                            </div>

                             <div class="row" style="margin:0px;">
                                <div class="col-sm-6">
                                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($material),0);?> entries</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_simple_numbers" >
                                        <ul class="pagination">

                                            <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                                <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=material&page=<?PHP echo $page; }?>">Previous</a>
                                            </li>

                                            <?PHP if($page > 0){ ?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=material&page=1">1</a>
                                            </li>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <?PHP } ?>

                                                
                                            <li class="paginate_button active"  >
                                                <a href="index.php?app=material&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                            </li>

                                            <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=material&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                            </li>
                                            <?PHP } ?>
                                           


                                            <?PHP if($page < $page_max){ ?>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=material&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                            </li>
                                            <?PHP } ?>

                                            <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                                <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=material&page=<?PHP echo $page + 2; }?>" >Next</a>
                                            </li>


                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table width="100%" class="table table-striped table-bordered table-hover" >
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>รหัสวัตถุดิบ <br>Material Code</th>
                                                <th>ชื่อวัตถุดิบ <br>Material Name</th>
                                                <th>รายละเอียด <br> Description</th> 
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php 
                                            for($i=$page * $page_size ; $i < count($material) && $i < $page * $page_size + $page_size; $i++){
                                            ?>

                                            <tr class="odd gradeX">
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php echo $material[$i]['material_code']; ?></td>
                                                <td><?php echo $material[$i]['material_name']; ?></td>
                                                <td class="center"><?php echo $material[$i]['material_description']; ?></td> 
                                                <td> 
                                                <?php if($menu['material']['edit']==1){ ?> 
                                                    <a href="?app=material&action=update&code=<?php echo $material[$i]['material_code'];?>">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a> 
                                                <?PHP } ?>
                                                <?php if($menu['material']['delete']==1){ ?> 
                                                    <a href="?app=material&action=delete&code=<?php echo $material[$i]['material_code'];?>" onclick="return confirm('You want to delete material : <?php echo $material[$i]['material_name']; ?>');" style="color:red;">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </a>
                                                <?PHP } ?>
                                                </td>
                                            </tr>
                                        <?
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row" style="margin:0px;">
                                <div class="col-sm-6">
                                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($material),0);?> entries</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_simple_numbers" >
                                        <ul class="pagination">

                                            <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                                <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=material&page=<?PHP echo $page; }?>">Previous</a>
                                            </li>

                                            <?PHP if($page > 0){ ?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=material&page=1">1</a>
                                            </li>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <?PHP } ?>

                                                
                                            <li class="paginate_button active"  >
                                                <a href="index.php?app=material&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                            </li>

                                            <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=material&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                            </li>
                                            <?PHP } ?>
                                           


                                            <?PHP if($page < $page_max){ ?>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=material&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                            </li>
                                            <?PHP } ?>

                                            <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                                <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=material&page=<?PHP echo $page + 2; }?>" >Next</a>
                                            </li>


                                        </ul>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>

<form role="form" method="post"   action="index.php?app=material&action=import"   enctype="multipart/form-data"> 
    <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">เลือกรายการวัตถุดิบ / Choose material</h4>
            </div>

            <div  class="modal-body">
            <div class="row">
                <div class="col-md-offset-8 col-md-4" align="right">
                    <input type="checkbox" id="search_pop" onchange="search_pop_like(this)"  /> แสดงรายการที่มีปัญหาจำนวน <span class="number_error"></span> รายการ
                </div>
            </div>
            <br>

            <div id="tb_import">
                <table width="100%"  class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr> 
                            <th>รหัสวัตถุดิบ</th>
                            <th>ชื่อวัตถุดิบ</th> 
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody id="bodyAdd">

                    </tbody>
                </table>
            </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger"  onclick="export_error()" >Export Error (<span class="number_error"></span>)</button>
                <button type="summit" class="btn btn-primary" > Import material list </button>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</form>