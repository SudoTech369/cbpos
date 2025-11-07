<?php
require_once('../config.php');
$qry = $conn->query("
    SELECT 
        s.id,
        s.name AS sub_name,
        s.description,
        s.price,
        s.stock,
        s.status,
        s.date_created,
        p.name AS product_name
    FROM sub_products s
    INNER JOIN products p ON s.product_id = p.id
    WHERE s.delete_flag = 0
    ORDER BY s.date_created DESC
");
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Sub-Products</h3>
        <div class="card-tools">
            <a href="./?page=sub_products/manage" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add New
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered" id="sub-products-list">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sub Product</th>
                    <th>Parent Product</th>
                    <th>Price (KES)</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while($row = $qry->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['sub_name']); ?></td>
                        <td><?= htmlspecialchars($row['product_name']); ?></td>
                        <td><?= number_format($row['price'], 2); ?></td>
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
                            <a href="./?page=sub_products/manage&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                            <button type="button" class="btn btn-sm btn-danger delete_data" data-id="<?= $row['id'] ?>"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(function(){
    $('#sub-products-list').DataTable();

    $('.delete_data').click(function(){
        _conf("Are you sure to delete this sub product?", "delete_sub_product", [$(this).attr('data-id')]);
    });
});

function delete_sub_product(id){
    $.ajax({
        url:'./sub_products/delete_sub_product.php',
        method:'POST',
        data:{id:id},
        dataType:'json',
        success:function(resp){
            if(resp.status == 'success'){
                location.reload();
            } else {
                alert("Failed to delete!");
            }
        }
    });
}
</script>
