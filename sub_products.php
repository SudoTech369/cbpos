<style>
/* ---------- PRODUCT GRID DESIGN ---------- */

.product-item {
    display: flex;
    flex-direction: column;
    max-width: 300px;
    height: 380px;
    margin: auto;
    border-radius: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    overflow: hidden;
    background: #583e3eff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-item:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    transform: translateY(-4px);
}

/* Image section */
.product-item .product-holder {
    flex: 1;
    width: 100%;
    height: 200px;
    position: relative;
    overflow: hidden;
}
.product-item .product-holder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
    transition: opacity 0.6s ease;
}
.product-item .product-holder img.product-cover {
    opacity: 1;
    z-index: 1;
}
.product-item:hover .product-cover {
    transform: scale(1.08);
}

/* Card content */
.product-item .card-body {
    flex: 0 0 auto;
    padding: 10px;
    font-size: 0.9rem;
    background: #fff;
    text-align: center;
}
.product-item .card-body h5 {
    font-weight: 600;
    font-size: 1rem;
    margin: 0;
}

/* Toolbar buttons */
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

/* Header behavior */
#main-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 80px;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    z-index: 9999;
    transition: all 0.3s ease;
}
#main-header.scrolled {
    background: #fcebebff;
    height: 60px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
#main-header img.logo {
    height: 50px;
    transition: height 0.3s ease;
}
#main-header.scrolled img.logo {
    height: 35px;
}
#main-header nav a {
    color: #000 !important;
    transition: color 0.3s ease;
}
</style>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('./config.php');

$product_id = isset($_GET['id']) ? $_GET['id'] : '';
if(!$product_id) {
    echo "<h3 class='text-center py-5'>Invalid parent product.</h3>";
    exit;
}

// Fetch parent product details
$parent = $conn->query("SELECT * FROM products WHERE md5(id) = '{$product_id}'");
if($parent->num_rows == 0){
    echo "<h3 class='text-center py-5'>Parent product not found.</h3>";
    exit;
}
$parent_row = $parent->fetch_assoc();
$pid = $parent_row['id'];

// Fetch sub-products
$sub_products = $conn->query("
    SELECT sp.*, p.name AS parent_name
    FROM sub_products sp
    INNER JOIN products p ON sp.product_id = p.id
    WHERE sp.product_id = {$pid}
    ORDER BY RAND()
");

if($sub_products->num_rows == 0){
    echo "<h3 class='text-center py-5'>No sub-products available for <b>{$parent_row['name']}</b>.</h3>";
    exit;
}
?>

<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4 text-dark fw-bold">
            Sub-Products of <span class="text-primary"><?php echo htmlspecialchars($parent_row['name']) ?></span>
        </h2>

        <!-- Toolbar -->
        <div class="grid-toolbar">
            <button id="btnGrid2" onclick="setGrid(2)"><i class="bi bi-grid-fill"></i></button>
            <button id="btnGrid3" onclick="setGrid(3)" class="active"><i class="bi bi-grid-3x3-gap-fill"></i></button>
            <button id="btnGrid4" onclick="setGrid(4)"><i class="bi bi-grid-3x3-gap"></i></button>
        </div>

        <!-- Grid -->
        <div id="productsRow" class="row gx-4 gx-lg-4 row-cols-1 row-cols-md-3">
            <?php while($row = $sub_products->fetch_assoc()): 
                $upload_path = base_app.'uploads/sub_products/sub_product_'.$row['id'];
                $images = [];
                if(is_dir($upload_path)){
                    $files = array_diff(scandir($upload_path), ['.','..']);
                    foreach($files as $file){
                        $images[] = "uploads/sub_products/sub_product_".$row['id']."/".$file;
                    }
                }
            ?>
            <div class="col mb-5">
    <a href="./?p=mini_products&id=<?php echo md5($row['id']); ?>" 
       class="card product-item text-reset text-decoration-none">



                    <div class="product-holder">
                        <?php if(!empty($images)): ?>
                            <img class="product-cover" src="<?php echo validate_image($images[0]) ?>" alt="Sub-product Image">
                            <?php for($i=1; $i<count($images); $i++): ?>
                                <img class="variant-image" src="<?php echo validate_image($images[$i]) ?>" alt="Variant">
                            <?php endfor; ?>
                        <?php else: ?>
                            <img class="product-cover" src="assets/no-image.png" alt="No Image">
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <h5><?php echo htmlspecialchars($row['name']) ?></h5>
                        <p class="m-0"><small><b>Price:</b> <?php echo isset($row['price']) ? number_format($row['price'], 2) : 'N/A'; ?></small></p>
                    </div>

                        </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<script>
// Grid size control
function setGrid(cols) {
    const row = document.getElementById("productsRow");
    row.className = `row gx-4 gx-lg-4 row-cols-1 row-cols-md-${cols}`;
    document.querySelectorAll(".grid-toolbar button").forEach(btn => btn.classList.remove("active"));
    document.getElementById("btnGrid" + cols).classList.add("active");
}

// Hover image switch
document.querySelectorAll(".product-item").forEach(card => {
    let interval;
    const variants = card.querySelectorAll(".variant-image");
    const cover = card.querySelector(".product-cover");

    card.addEventListener("mouseenter", () => {
        if (variants.length > 0) {
            let index = 0;
            variants.forEach(v => v.style.opacity = 0);
            variants[index].style.opacity = 1;
            if (cover) cover.style.opacity = 0;
            interval = setInterval(() => {
                const prev = index;
                index = (index + 1) % variants.length;
                variants[prev].style.opacity = 0;
                variants[index].style.opacity = 1;
            }, 1500);
        }
    });
    card.addEventListener("mouseleave", () => {
        clearInterval(interval);
        variants.forEach(v => v.style.opacity = 0);
        if (cover) cover.style.opacity = 1;
    });
});

// Header shrink
window.addEventListener('scroll', () => {
    const header = document.getElementById('main-header');
    if (header) {
        if (window.scrollY > 50) header.classList.add('scrolled');
        else header.classList.remove('scrolled');
    }
});
</script>
