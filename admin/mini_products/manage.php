<?php
require_once('../config.php');

// Fetch mini product if editing
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * FROM `mini_products` WHERE id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = stripslashes($v);
        }
    }
}
?>
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($id) ? "Update " : "Create New " ?> Mini Product</h3>
    </div>

    <div class="card-body">
        <form action="" id="mini-product-form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

            <!-- Parent Product -->
            <div class="form-group">
                <label for="product_id" class="control-label">Parent Product</label>
                <select name="product_id" id="product_id" class="custom-select select2" required>
                    <option value=""></option>
                    <?php
                    $qry = $conn->query("SELECT * FROM `products` WHERE delete_flag = 0 ORDER BY `name` ASC");
                    while ($row = $qry->fetch_assoc()):
                    ?>
                        <option value="<?php echo $row['id'] ?>" 
                            <?php echo isset($product_id) && $product_id == $row['id'] ? 'selected' : '' ?>>
                            <?php echo $row['name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Parent Sub Product -->
            <div class="form-group">
                <label for="sub_product_id" class="control-label">Parent Sub Product</label>
                <select name="sub_product_id" id="sub_product_id" class="custom-select select2" required>
                    <option value=""></option>
                    <?php
                    $qry = $conn->query("SELECT * FROM `sub_products` WHERE delete_flag = 0 ORDER BY `name` ASC");
                    while ($row = $qry->fetch_assoc()):
                    ?>
                        <option value="<?php echo $row['id'] ?>" 
                            <?php echo isset($sub_product_id) && $sub_product_id == $row['id'] ? 'selected' : '' ?>>
                            <?php echo $row['name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Mini Product Name -->
            <div class="form-group">
                <label for="name" class="control-label">Mini Product Name</label>
                <input type="text" name="name" id="name" class="form-control rounded-0" required 
                    value="<?php echo isset($name) ? $name : '' ?>">
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="control-label">Description / Specs</label>
                <textarea name="description" id="description" cols="30" rows="3" 
                    class="form-control summernote"><?php echo isset($description) ? $description : ''; ?></textarea>
            </div>

            <!-- Price -->
            <div class="form-group">
                <label for="price" class="control-label">Price (KES)</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control rounded-0" 
                    required value="<?php echo isset($price) ? $price : '' ?>">
            </div>

            <!-- Discount -->
            <div class="form-group">
                <label for="discount" class="control-label">Discount (KES)</label>
                <input type="number" step="0.01" name="discount" id="discount" 
                    class="form-control rounded-0" value="<?php echo isset($discount) ? $discount : 0 ?>">
            </div>

            <!-- Images -->
            <div class="form-group">
                <label for="customFile" class="control-label">Images</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="img[]" multiple accept=".png,.jpg,.jpeg" onchange="displayImg(this, $(this))">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>

            <?php 
            if (isset($id)):
                $upload_path = "uploads/mini_products/mini_product_" . $id;
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
        <button class="btn btn-flat btn-primary" form="mini-product-form">Save</button>
        <a class="btn btn-flat btn-default" href="?page=mini_products">Cancel</a>
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

$(document).ready(function(){
    $('.select2').select2({placeholder:"Please select here",width:"100%"})

    $('#mini-product-form').submit(function(e){
        e.preventDefault();
        var _this = $(this)
        $('.err-msg').remove();
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=save_mini_product",
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
                    location.href = "./?page=mini_products";
                }else if(resp.status == 'failed' && !!resp.msg){
                    var el = $('<div>').addClass("alert alert-danger err-msg").text(resp.msg)
                    _this.prepend(el)
                    el.show('slow')
                    $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                    if(!!resp.id) $('[name="id"]').val(resp.id)
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
            ['font', [ 'bold', 'italic', 'underline', 'clear' ]],
            ['fontsize', [ 'fontsize' ]],
            ['color', [ 'color' ]],
            ['para', [ 'ol', 'ul', 'paragraph' ]],
            ['view', [ 'undo', 'redo', 'codeview', 'help' ]]
        ]
    })
})
</script>
