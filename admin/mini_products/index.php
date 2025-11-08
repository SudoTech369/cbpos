<?php
require_once('../config.php');
?>
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title">Mini Products List</h3>
        <div class="card-tools">
            <a href="?page=mini_products/manage" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-plus"></i> Add New</a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Main Product</th>
                        <th>Sub Product</th>
                        <th>Mini Product</th>
                        <th>Description</th>
                        <th>Date Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("
                        SELECT m.*, p.name AS product_name, s.name AS sub_product_name
                        FROM mini_products m
                        INNER JOIN products p ON m.product_id = p.id
                        INNER JOIN sub_products s ON m.sub_product_id = s.id
                        WHERE m.delete_flag = 0
                        ORDER BY m.date_created DESC
                    ");
                    while ($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td>
                            <?php if(!empty($row['image_path'])): ?>
                                <img src="<?= base_url.$row['image_path'] ?>" alt="Image" style="width:50px;height:50px;border-radius:5px;">
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['product_name'] ?></td>
                        <td><?= $row['sub_product_name'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td><?= $row['date_created'] ?></td>
                        <td>
                            <a href="?page=mini_products/manage&id=<?= $row['id'] ?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                            <button type="button" class="btn btn-sm btn-danger delete_data" data-id="<?= $row['id'] ?>"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(function(){
    $('.delete_data').click(function(){
        _conf("Are you sure to delete this mini product permanently?", "delete_mini_product", [$(this).attr('data-id')])
    })
})

function delete_mini_product(id){
    $.ajax({
        url: '../classes/Master.php?f=delete_mini_product',
        method: 'POST',
        data: {id:id},
        dataType: 'json',
        error: err => {
            alert("An error occurred");
            console.log(err);
        },
        success: function(resp){
            if(resp.status == 'success'){
                location.reload();
            } else {
                alert(resp.msg || "Failed to delete mini product");
            }
        }
    })
}
</script>
