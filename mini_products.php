<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('./config.php');

$sub_product_id = isset($_GET['id']) ? $_GET['id'] : '';
if(!$sub_product_id) {
    echo "<h3 class='text-center py-5'>Invalid Sub Product.</h3>";
    exit;
}

// Fetch Sub Product details
$sub = $conn->query("SELECT * FROM sub_products WHERE md5(id) = '{$sub_product_id}' AND delete_flag = 0");
if($sub->num_rows == 0){
    echo "<h3 class='text-center py-5'>Sub Product not found.</h3>";
    exit;
}
$sub_row = $sub->fetch_assoc();
$sub_id = $sub_row['id'];

// Fetch Mini Products linked to this Sub Product
$mini_products = $conn->query("
    SELECT mp.*, sp.name AS sub_name
    FROM mini_products mp
    INNER JOIN sub_products sp ON mp.sub_product_id = sp.id
    WHERE mp.sub_product_id = {$sub_id} AND mp.delete_flag = 0
    ORDER BY RAND()
");

if($mini_products->num_rows == 0){
    echo "<h3 class='text-center py-5'>No mini products available for <b>{$sub_row['name']}</b>.</h3>";
    exit;
}
?>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* ---------- MINI PRODUCT DESIGN ---------- */
.product-item {
    display: flex;
    flex-direction: column;
    max-width: 300px;
    height: 400px;
    margin: auto;
    border-radius: 18px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    overflow: hidden;
    background: #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-item:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    transform: translateY(-4px);
}

/* Image section */
.product-holder {
    flex: 1;
    height: 200px;
    position: relative;
    overflow: hidden;
}
.product-holder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.product-item:hover .product-holder img {
    transform: scale(1.08);
}
.discount-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #e53935;
    color: #fff;
    font-size: 0.85rem;
    padding: 4px 8px;
    border-radius: 6px;
}

/* Card body */
.card-body {
    flex: 0 0 auto;
    padding: 12px;
    text-align: center;
    background: #f9f9f9;
}
.card-body h5 {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 6px;
    color: #333;
}
.price {
    font-size: 0.9rem;
    margin: 5px 0;
}
.price .old {
    text-decoration: line-through;
    color: #999;
    margin-right: 6px;
}
.price .new {
    font-weight: bold;
    color: #e53935;
}

/* Toolbar */
.grid-toolbar {
    text-align: center;
    margin: 25px 0;
}
.grid-toolbar button {
    padding: 6px 12px;
    margin: 0 5px;
    border: none;
    border-radius: 6px;
    background: #444;
    color: #fff;
    cursor: pointer;
    transition: 0.2s;
    font-size: 1.2rem;
}
.grid-toolbar button:hover {
    background: #000;
}
.grid-toolbar button.active {
    background: #007bff;
}
</style>

<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4 text-dark fw-bold">
            Mini Products of <span class="text-primary"><?php echo htmlspecialchars($sub_row['name']) ?></span>
        </h2>

        <!-- Toolbar -->
        <div class="grid-toolbar">
            <button id="btnGrid2" onclick="setGrid(2)"><i class="bi bi-grid-fill"></i></button>
            <button id="btnGrid3" onclick="setGrid(3)" class="active"><i class="bi bi-grid-3x3-gap-fill"></i></button>
            <button id="btnGrid4" onclick="setGrid(4)"><i class="bi bi-grid-3x3-gap"></i></button>
        </div>

        <!-- Grid -->
        <div id="productsRow" class="row gx-4 gx-lg-4 row-cols-1 row-cols-md-3">
            <?php while($row = $mini_products->fetch_assoc()): 
                $upload_path = base_app.'uploads/mini_products/mini_product_'.$row['id'];
                $images = [];
                if(is_dir($upload_path)){
                    $files = array_diff(scandir($upload_path), ['.','..']);
                    foreach($files as $file){
                        $images[] = "uploads/mini_products/mini_product_".$row['id']."/".$file;
                    }
                }

                // Price + Discount logic
                $price = isset($row['price']) ? floatval($row['price']) : 0;
                $discount = isset($row['discount']) ? floatval($row['discount']) : 0;
                $final_price = $price - ($price * ($discount / 100));
            ?>
            <div class="col mb-5">
                <div class="card product-item text-reset text-decoration-none">
                    <div class="product-holder">
                        <?php if($discount > 0): ?>
                            <div class="discount-badge"><?php echo $discount ?>% OFF</div>
                        <?php endif; ?>

                        <?php if(!empty($images)): ?>
                            <img src="<?php echo validate_image($images[0]) ?>" alt="Mini Product Image">
                        <?php else: ?>
                            <img src="assets/no-image.png" alt="No Image">
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <h5><?php echo htmlspecialchars($row['name']) ?></h5>
                        <p class="price">
                            <?php if($discount > 0): ?>
                                <span class="old">KES <?php echo number_format($price, 2) ?></span>
                                <span class="new">KES <?php echo number_format($final_price, 2) ?></span>
                            <?php else: ?>
                                <span class="new">KES <?php echo number_format($price, 2) ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<script>
// Grid size control
function setGrid(cols) {
    const row = document.getElementById("productsRow");
    row.className = `row gx-4 gx-lg-4 row-cols-1 row-cols-md-${cols}`;
    document.querySelectorAll(".grid-toolbar button").forEach(btn => btn.classList.remove("active"));
    document.getElementById("btnGrid" + cols).classList.add("active");
}
</script>
