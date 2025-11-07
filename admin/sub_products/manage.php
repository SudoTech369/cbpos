<?php
// Load config
require_once('../config.php');

// Fetch sub-product if editing
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * FROM `sub_products` WHERE id = '{$_GET['id']}' AND delete_flag = 0");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = stripslashes($v);
        }
    }
}
?>
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($id) ? "Update " : "Create New " ?> Sub Product</h3>
    </div>
    <div class="card-body">
        <form action="" id="sub-product-form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

            <!-- Link to parent Product -->
            <div class="form-group">
                <label for="product_id" class="control-label">Parent Product</label>
                <select name="product_id" id="product_id" class="custom-select select2" required>
                    <option value=""></option>
                    <?php
                    $qry = $conn->query("SELECT * FROM `products` WHERE delete_flag = 0 ORDER BY `name` ASC");
                    while ($row = $qry->fetch_assoc()):
                    ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($product_id) && $product_id == $row['id'] ? 'selected' : '' ?>>
                            <?php echo $row['name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Sub-product name -->
            <div class="form-group">
                <label for="name" class="control-label">Sub Product Name</label>
                <input type="text" name="name" id="name" class="form-control rounded-0" required value="<?php echo isset($name) ? $name : '' ?>">
            </div>

            <!-- Description / Specs -->
            <div class="form-group">
                <label for="description" class="control-label">Description / Specs</label>
                <textarea name="description" id="description" cols="30" rows="2" class="form-control form no-resize summernote"><?php echo isset($description) ? $description : ''; ?></textarea>
            </div>

            <!-- Price -->
            <div class="form-group">
                <label for="price" class="control-label">Price</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control rounded-0" required value="<?php echo isset($price) ? $price : '' ?>">
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status" class="control-label">Status</label>
                <select name="status" id="status" class="custom-select select2">
                    <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <!-- Images -->
            <div class="form-group">
                <label for="" class="control-label">Images</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="img[]" multiple accept=".png,.jpg,.jpeg" onchange="displayImg(this, $(this))">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>

            <?php 
            if (isset($id)):
                $upload_path = "uploads/sub_products/sub_product_" . $id;
                if (is_dir(base_app . $upload_path)):
                    $files = scandir(base_app . $upload_path);
                    foreach ($files as $img):
                        if (in_array($img, ['.', '..'])) continue;
            ?>
                        <div class="d-flex w-100 align-items-center img-item">
                            <span><img src="<?php echo base_url . $upload_path . '/' . $img ?>" width="150px" height="100px" style="object-fit:cover;" class="img-thumbnail" alt=""></span>
                            <span class="ml-4">
                                <button class="btn btn-sm btn-default text-danger rem_img" type="button" data-path="<?php echo base_app . $upload_path . '/' . $img ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </span>
                        </div>
            <?php 
                    endforeach;
                endif;
            endif;
            ?>
        </form>
    </div>

    <div class="card-footer">
        <button class="btn btn-flat btn-primary" form="sub-product-form">Save</button>
        <a class="btn btn-flat btn-default" href="?page=sub_products">Cancel</a>
    </div>
</div>

<script>
function displayImg(input, _this) {
    var fnames = []
    Object.keys(input.files).map(k => {
        fnames.push(input.files[k].name)
    })
    _this.siblings('.custom-file-label').html(fnames.join(", "))
}

function delete_img($path){
    start_loader()
    $.ajax({
        url: _base_url_+'classes/Master.php?f=delete_img',
        data:{path:$path},
        method:'POST',
        dataType:"json",
        error:err=>{
            console.log(err)
            alert_toast("An error occurred while deleting the image","error");
            end_loader()
        },
        success:function(resp){
            $('.modal').modal('hide')
            if(typeof resp =='object' && resp.status == 'success'){
                $('[data-path="'+$path+'"]').closest('.img-item').hide('slow',function(){
                    $(this).remove()
                })
                alert_toast("Image successfully deleted","success");
            }else{
                alert_toast("An error occurred","error");
            }
            end_loader()
        }
    })
}

$(document).ready(function(){
    $('.rem_img').click(function(){
        _conf("Are you sure to delete this image permanently?",'delete_img',["'"+$(this).attr('data-path')+"'"])
    })

    $('.select2').select2({placeholder:"Please select here",width:"100%"})

    $('#sub-product-form').submit(function(e){
        e.preventDefault();
        var _this = $(this)
        $('.err-msg').remove();
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=save_sub_product",
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error:err=>{
                console.log(err)
                alert_toast("An error occurred",'error');
                end_loader();
            },
            success:function(resp){
                if(typeof resp =='object' && resp.status == 'success'){
                    location.href = "./?page=sub_products";
                }else if(resp.status == 'failed' && !!resp.msg){
                    var el = $('<div>')
                    el.addClass("alert alert-danger err-msg").text(resp.msg)
                    _this.prepend(el)
                    el.show('slow')
                    $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                    if(!!resp.id)
                        $('[name="id"]').val(resp.id)
                    end_loader()
                }else{
                    alert_toast("An error occurred",'error');
                    end_loader();
                    console.log(resp)
                }
            }
        })
    })

    $('.summernote').summernote({
        height: 200,
        toolbar: [
            ['style', [ 'style' ]],
            ['font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontsize', [ 'fontsize' ]],
            ['color', [ 'color' ]],
            ['para', [ 'ol', 'ul', 'paragraph' ]],
            ['table', [ 'table' ]],
            ['view', [ 'undo', 'redo', 'codeview', 'help' ]]
        ]
    })
})
</script>
