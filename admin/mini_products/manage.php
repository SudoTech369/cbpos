<?php
require_once('../config.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id) {
    $qry = $conn->query("SELECT * FROM mini_products WHERE id = '{$id}' AND delete_flag = 0");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = stripslashes($v);
        }
    }
}
?>
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($id) ? "Edit Mini Product" : "Add Mini Product"; ?></h3>
    </div>
    <div class="card-body">
        <form id="mini-product-form" action="../classes/Master.php?f=save_mini_product" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="product_id">Parent Product</label>
        <select name="product_id" id="product_id" class="form-control" required>
            <option value="">-- Select Parent Product --</option>
            <?php
            $products = $conn->query("SELECT id, name FROM products WHERE delete_flag = 0 ORDER BY name ASC");
            while ($row = $products->fetch_assoc()):
            ?>
                <option value="<?= $row['id'] ?>" <?= isset($product_id) && $product_id == $row['id'] ? 'selected' : '' ?>>
                    <?= $row['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="sub_product_id">Sub Product</label>
        <select name="sub_product_id" id="sub_product_id" class="form-control" required>
            <option value="">-- Select Sub Product --</option>
            <!-- dynamically loaded via AJAX -->
        </select>
    </div>

    <div class="form-group">
        <label for="name">Mini Product Name</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" name="image" id="image" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Save Mini Product</button>
</form>

    </div>
    <div class="card-footer">
        <button class="btn btn-flat btn-primary" form="mini_product_form">Save</button>
        <a class="btn btn-flat btn-default" href="?page=mini_products">Cancel</a>
    </div>
</div>

<script>
$('#mini_product_form').submit(function(e){
    e.preventDefault();
    $.ajax({
        url: '../classes/Master.php?f=save_mini_product',
        data: new FormData(this),
        method: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(resp){
            if(resp.status == 'success'){
                location.href = "?page=mini_products";
            } else {
                alert(resp.msg || "Error saving mini product");
            }
        },
        error: err => {
            alert("An error occurred");
            console.log(err);
        }
    })
})
</script>
<script>
document.getElementById('product_id').addEventListener('change', function() {
    const parentId = this.value;
    const subDropdown = document.getElementById('sub_product_id');
    subDropdown.innerHTML = '<option value="">Loading...</option>';

    if (parentId) {
        fetch(`mini_products/get_sub_products.php?product_id=${parentId}`)
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">-- Select Sub Product --</option>';
                data.forEach(sub => {
                    options += `<option value="${sub.id}">${sub.name}</option>`;
                });
                subDropdown.innerHTML = options;
            })
            .catch(() => {
                subDropdown.innerHTML = '<option value="">Error loading sub-products</option>';
            });
    } else {
        subDropdown.innerHTML = '<option value="">-- Select Sub Product --</option>';
    }
});

 </script>
