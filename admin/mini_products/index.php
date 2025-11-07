<?php
require_once('../config.php');
$qry = $conn->query("
    SELECT 
        m.id,
        m.sub_product_id,
        m.name AS mini_name,
        m.price,
        m.discount,
        m.image,
        m.description,
        m.delete_flag,
        m.created_at,
        s.name AS sub_product_name
    FROM mini_products m
    INNER JOIN sub_products s ON m.sub_product_id = s.id
    WHERE m.delete_flag = 0
    ORDER BY m.created_at DESC
");
?>

<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">List of Mini Products</h3>
        <div class="card-tools">
            <a href="./?page=mini_products/manage" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add New
            </a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-hover table-bordered" id="mini-products-list">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mini Product</th>
                    <th>Parent Sub-Product</th>
                    <th>Price (KES)</th>
                    <th>Discount (%)</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1; 
                while($row = $qry->fetch_assoc()): 
                ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['mini_name']); ?></td>
                        <td><?= htmlspecialchars($row['sub_product_name']); ?></td>
                        <td><?= number_format($row['price'], 2); ?></td>
                        <td><?= !empty($row['discount']) ? htmlspecialchars($row['discount']) : '—'; ?></td>
                        <td><?= $row['stock']; ?></td>
                        <td>
                            <?php if($row['status'] == 1): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date("Y-m-d H:i", strtotime($row['date_created'])); ?></td>
                        <td>
                            <a href="./?page=mini_products/manage&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete_data" data-id="<?= $row['id'] ?>">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(function(){
    $('#mini-products-list').DataTable();

    $('.delete_data').click(function(){
        _conf("Are you sure to delete this mini product?", "delete_mini_product", [$(this).attr('data-id')]);
    });
});

function delete_mini_product(id){
    $.ajax({
        url:'./mini_products/delete_mini_product.php',
        method:'POST',
        data:{id:id},
        dataType:'json',
        success:function(resp){
            if(resp.status == 'success'){
                location.reload();
            } else {
                alert("Failed to delete!");
            }
        },
        error: function(err){
            console.log(err);
            alert("An error occurred while deleting.");
        }
    });
}
</script>
