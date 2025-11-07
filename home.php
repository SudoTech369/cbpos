<style>


/* Product card styling */
.product-item {
    display: flex;
    flex-direction: column;
    max-width: 400px;
    height: 450px;
    margin: auto;
    border-radius: 35px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.2);
    overflow: hidden;
    background: #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-item:hover {
    box-shadow: 0 0 20px rgba(0, 123, 255, 0.5);
    transform: translateY(-5px);
}

/* 🔥 Hover: stronger shadow + subtle lift */
 .product-item:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    transform: translateY(-5px);
} 

    .h5 {
        font-size: 1.25rem;
        font-weight: 600;
        position: relative;
    }
/* Blur effect applied when caption is active */
.carousel-item.about-slide.active::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    backdrop-filter: blur(6px);   /* actual blur */
    background: rgba(0, 0, 0, 0.3); /* dim overlay */
    z-index: 1;
}

/* Container for the text */
.about-container {
    position: relative;
    z-index: 2; /* above blur */
    display: inline-block;
    background: rgba(255, 255, 255, 0.15); /* subtle translucent box */
    backdrop-filter: blur(4px); /* glassy look */
    padding: 20px 30px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    max-width: 80%;
    margin: auto;
}

/* Typing title */
.typing {
    font-size: 2.5rem;
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
    border-right: 3px solid #fff;
    width: 0;
}

/* Typing paragraph */
.typingg {
    font-size: 1rem;
    font-weight: normal;
    white-space: nowrap;
    overflow: hidden;
    border-right: 2px solid #fff;
    width: 0;
}

/* Animations */
@keyframes typing-title {
    from { width: 0; }
    to { width: 8ch; } /* "About Us" */
}
@keyframes typing-text {
    from { width: 0; }
    to { width: 60ch; } /* adjust for sentence */
}
@keyframes blink {
    50% { border-color: transparent; }
}

.typing.active {
    animation: typing-title 2s steps(8, end) forwards, blink 0.75s step-end infinite;
}
.typingg.active {
    animation: typing-text 3s steps(60, end) forwards, blink 0.75s step-end infinite;
}


/* Default caption hidden */
.carousel-item .custom-caption {
    position: absolute;
    bottom: 20%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    background: rgba(0,0,0,0.6);
    padding: 20px 30px;
    border-radius: 12px;
    opacity: 0;
    transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
    color: #fff;
    max-width: 70%;
}

/* Show on hover */
#carouselExampleControls:hover .carousel-item.active .custom-caption {
    opacity: 1;
    transform: translateX(-50%) translateY(-10px); /* slight lift effect */
}

/* Title and text styling */
.carousel-item .custom-caption h2 {
    font-size: 2rem;
    margin-bottom: 10px;
    font-weight: bold;
}
.carousel-item .custom-caption p {
    font-size: 1rem;
    margin: 0;
}


/* Force carousel full viewport width */
    #carouselExampleControls {
        width: 99.2vw !important;             /* span full screen */
        margin-left: calc(-50vw + 50%);      /* break out of container */
        position: relative;
        overflow: hidden;                    /* prevent side scroll */
    }

   
    /* Images */
    .carousel-item > img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;        /* keep aspect and crop */
        display: block;
    }
    #carouselExampleControls .carousel-inner {
        height: 590px !important;
        width: 100% !important; 
    }
    .carousel-item {
    
        transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out !important; /* faster */
    }   
.product-item {
    display: flex;
    flex-direction: column;
    max-width: 300px;              /* smaller width */
    height: 380px;                 /* smaller height */
    margin: auto;
    border-radius: 20px;           /* slightly smaller curves */
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    overflow: hidden;
    background: #583e3eff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
/* Image container fixed height */
.product-item .product-holder {
    flex: 1;
    width: 100%;
    height: 200px;                /* reduced height */
    position: relative;
    overflow: hidden;
}

/* All images stacked on top */
.product-item .product-holder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;   /* 🔥 stack images */
    top: 0;
    left: 0;
    opacity: 0;
    transition: opacity 0.6s ease;  /* smooth fade */
}

/* Default cover image visible */
.product-item .product-holder img.product-cover {
    opacity: 1;
    z-index: 1;
}
.product-item:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    transform: translateY(-4px);
}


/* Hover zoom effect */
.product-item:hover .product-cover {
    transform: scale(1.08);
}

/* Text below image */
.product-item .card-body {
    flex: 0 0 auto;
    padding: 8px;
    font-size: 0.9rem;
    background: #fff;
}
/* Product title styling */
.product-item .card-body h5 {
    font-weight: 600;
    text-align: center;
    font-size: 1rem;
    margin: 0;
}

 

   
    /* Toolbar for grid filter */
    .grid-toolbar {
        text-align: center;
        margin: 20px 0;
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
    /* Variant marquee container */
.variant-marquee {
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    height: 24px;
    margin-top: 5px;
    display: none; /* hidden by default */
}

/* Scrolling text */
.variant-marquee span {
    display: inline-block;
    padding-left: 100%;
    animation: scroll-left 8s linear infinite;
    font-size: 0.9rem;
    color: #444;
}

/* Show + speed up on hover */
.product-item:hover .variant-marquee {
    display: block;
}
.product-item:hover .variant-marquee span {
    animation-duration: 2s;
}

@keyframes scroll-left {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-100%);
    }
}
/* Header fixed */
#main-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 90px;                  /* default bigger size */
    background: transparent;       /* transparent on load */
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    z-index: 9999;
    transition: all 0.3s ease;
}

/* Shrink + white on scroll */
#main-header.scrolled {
    background: #fcebebff !important; /* turns white */
    height: 65px;                   /* shrink height */
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Logo resizing inside header */
#main-header img.logo {
    height: 60px;
    transition: height 0.3s ease;
}
#main-header.scrolled img.logo {
    height: 40px;
}

/* Nav links color change */
#main-header nav a {
    color: #000 !important;  /* always black */
    transition: color 0.3s ease;
}
#main-header.scrolled nav a {
    color: #000 !important; /* turn dark when bg is white */
}

</style>


<?php 
$brands = isset($_GET['b']) ? json_decode(urldecode($_GET['b'])) : array();
?>
<section class="py-0">
    <div class="container">
        <div class="row">
            <!-- Main content -->
            <div class="col-lg-12 py-2">
                <!-- Carousel -->
                <div class="row">
                    <div class="container-fluid px-0 ">
                        <div id="carouselExampleControls" class="carousel slide carousel-fade" data-ride="carousel" data-interval="2000">
                            <div class="carousel-inner">
                                <?php 
                                $upload_path = "uploads/banner";
                                if(is_dir(base_app.$upload_path)): 
                                    $file = scandir(base_app.$upload_path);
                                    $_i = 0;
                                    foreach($file as $img):
                                        if(in_array($img, array('.', '..'))) continue;
                                        $_i++;
                                ?>
                                <div class="carousel-item h-100 <?php echo $_i == 1 ? "active" : '' ?>">
    <img src="<?php echo validate_image($upload_path.'/'.$img) ?>" class="d-block w-100 h-100" alt="<?php echo $img ?>">

    <!-- 🔥 Caption overlay -->
    <div class="carousel-caption custom-caption">
  <h2 class="typing">CANVAS</h2>
  <p class="typingg">BUY</p>
</div>

</div>

                                <?php endforeach; endif; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Toolbar filter -->
                <div class="grid-toolbar">
                    <span></span>
                    <button id="btnGrid2" onclick="setGrid(2)"><i class="bi bi-grid-fill"></i></button>
                    <button id="btnGrid3" onclick="setGrid(3)" class="active"><i class="bi bi-grid-3x3-gap-fill"></i></button>
                    <button id="btnGrid4" onclick="setGrid(4)"><i class="bi bi-grid-3x3-gap"></i></button>
                </div>

                <!-- Products grid -->
                <div class="container px-4 px-lg-5 mt-3">
                    <div id="productsRow" class="row gx-4 gx-lg-4 row-cols-1 row-cols-md-3">
                        <?php 
                        $where = "";
                        if(count($brands) > 0)
                            $where = " AND p.brand_id IN (".implode(",", $brands).")";
                        
                        $products = $conn->query("SELECT p.*, b.name as bname, c.category 
                            FROM products p 
                            INNER JOIN brands b ON p.brand_id = b.id 
                            INNER JOIN categories c ON p.category_id = c.id 
                            WHERE p.status = 1 {$where} 
                            ORDER BY RAND()");
                        
                        // Fetch all into array
                        $product_list = [];
                        while($row = $products->fetch_assoc()){
                            $product_list[] = $row;
                        }

                        // Move last to front
                        if(!empty($product_list)){
                            $last = array_pop($product_list);
                            array_unshift($product_list, $last);
                        }

                        // Render
                        foreach($product_list as $row):
                            $upload_path = base_app.'/uploads/products/product_'.$row['id'];
                            $img = "";
                            if(is_dir($upload_path)){
                                $fileO = scandir($upload_path);
                                if(isset($fileO[2]))
                                    $img = "uploads/products/product_".$row['id']."/".$fileO[2];
                            }

                            foreach($row as $k => $v){
                                $row[$k] = trim(stripslashes($v));
                            }

                            $inventory = $conn->query("SELECT DISTINCT(price) FROM inventory WHERE product_id = ".$row['id']." ORDER BY price ASC");
                            $inv = array();
                            while($ir = $inventory->fetch_assoc()){
                                $inv[] = format_num($ir['price']);
                            }

                            $price = '';
                            if(isset($inv[0])) $price .= $inv[0];
                            if(count($inv) > 1) $price .= " ~ ".$inv[count($inv) - 1];
                        ?>
                        <div class="col mb-5">
                            <a class="card product-item text-reset text-decoration-none" 
   href=".?p=sub_products&id=<?php echo md5($row['id']) ?>">

                            <div class="product-holder">
    <?php 
    $upload_path = base_app.'/uploads/products/product_'.$row['id'];
    $images = [];
    if(is_dir($upload_path)){
        $files = array_diff(scandir($upload_path), ['.','..']);
        foreach($files as $file){
            $images[] = "uploads/products/product_".$row['id']."/".$file;
        }
    }
    ?>

    <?php if(!empty($images)): ?>
        <!-- First image = cover -->
        <img class="product-cover" src="<?php echo validate_image($images[0]) ?>" alt="Product Image" />
        <!-- Other images = variant slideshow -->
        <?php for($i=1; $i<count($images); $i++): ?>
            <img class="variant-image" src="<?php echo validate_image($images[$i]) ?>" alt="Variant Image" />
        <?php endfor; ?>
    <?php endif; ?>
</div>

<div class="card-body">
    <h5 ><?php echo $row['name'] ?></h5>
    <!-- <span><b>Price:</b> <?php echo $row['price'] ?></span>
    <p class="m-0"><small><b>Brand:</b> <?php echo $row['bname'] ?></small></p>
    <p class="m-0"><small><b>Category:</b> <?php echo $row['category'] ?></small></p> -->
</div>

                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
<script>
    function setGrid(cols) {
        const row = document.getElementById("productsRow");
        row.className = `row gx-4 gx-lg-4 row-cols-1 row-cols-md-${cols}`;

        // toggle active button
        document.querySelectorAll(".grid-toolbar button").forEach(btn => btn.classList.remove("active"));
        document.getElementById("btnGrid" + cols).classList.add("active");
    }
</script>
<script>
document.querySelectorAll(".product-item").forEach(card => {
    let interval;
    const variants = card.querySelectorAll(".variant-image");
    const cover = card.querySelector(".product-cover");

    card.addEventListener("mouseenter", () => {
        if (variants.length > 0) {
            let index = 0; 

            // Reset: hide all
            variants.forEach(v => v.style.opacity = 0);

            // Show first variant immediately
            variants[index].style.opacity = 1;

            // THEN hide cover
            if (cover) cover.style.opacity = 0;

            // Start cycling
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

$('#carouselExampleControls').on('slid.bs.carousel', function () {
    // Reset animations
    document.querySelectorAll('.typing, .typingg').forEach(el => {
        el.classList.remove('active');
        void el.offsetWidth; // force reflow
        el.classList.add('active');
    });
});
window.addEventListener('scroll', () => {
    const header = document.getElementById('main-header');
    if(window.scrollY > 50){
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

<style>


/* Product card styling */
/* .product-item {
    display: flex;
    flex-direction: column;
    max-width: 400px;
    height: 450px;
    margin: auto;
    border-radius: 35px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.2);
    overflow: hidden;
    background: #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-item:hover {
    box-shadow: 0 0 20px rgba(0, 123, 255, 0.5);
    transform: translateY(-5px);
}

/* 🔥 Hover: stronger shadow + subtle lift */
/* .product-item:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    transform: translateY(-5px);
} */ */

    .h5 {
        font-size: 1.25rem;
        font-weight: 600;
        position: relative;
    }
/* Blur effect applied when caption is active */
.carousel-item.about-slide.active::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    backdrop-filter: blur(6px);   /* actual blur */
    background: rgba(0, 0, 0, 0.3); /* dim overlay */
    z-index: 1;
}

/* Container for the text */
.about-container {
    position: relative;
    z-index: 2; /* above blur */
    display: inline-block;
    background: rgba(255, 255, 255, 0.15); /* subtle translucent box */
    backdrop-filter: blur(4px); /* glassy look */
    padding: 20px 30px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    max-width: 80%;
    margin: auto;
}

/* Typing title */
.typing {
    font-size: 2.5rem;
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
    border-right: 3px solid #fff;
    width: 0;
}

/* Typing paragraph */
.typingg {
    font-size: 1rem;
    font-weight: normal;
    white-space: nowrap;
    overflow: hidden;
    border-right: 2px solid #fff;
    width: 0;
}

/* Animations */
@keyframes typing-title {
    from { width: 0; }
    to { width: 8ch; } /* "About Us" */
}
@keyframes typing-text {
    from { width: 0; }
    to { width: 60ch; } /* adjust for sentence */
}
@keyframes blink {
    50% { border-color: transparent; }
}

.typing.active {
    animation: typing-title 2s steps(8, end) forwards, blink 0.75s step-end infinite;
}
.typingg.active {
    animation: typing-text 3s steps(60, end) forwards, blink 0.75s step-end infinite;
}


/* Default caption hidden */
.carousel-item .custom-caption {
    position: absolute;
    bottom: 20%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    background: rgba(0,0,0,0.6);
    padding: 20px 30px;
    border-radius: 12px;
    opacity: 0;
    transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
    color: #fff;
    max-width: 70%;
}

/* Show on hover */
#carouselExampleControls:hover .carousel-item.active .custom-caption {
    opacity: 1;
    transform: translateX(-50%) translateY(-10px); /* slight lift effect */
}

/* Title and text styling */
.carousel-item .custom-caption h2 {
    font-size: 2rem;
    margin-bottom: 10px;
    font-weight: bold;
}
.carousel-item .custom-caption p {
    font-size: 1rem;
    margin: 0;
}


/* Force carousel full viewport width */
    #carouselExampleControls {
        width: 99.2vw !important;             /* span full screen */
        margin-left: calc(-50vw + 50%);      /* break out of container */
        position: relative;
        overflow: hidden;                    /* prevent side scroll */
    }

    /* Inner wrapper */
    #carouselExampleControls .carousel-inner {
        height: 450px !important;            /* fixed height */
        width: 100% !important;
    }
    /* Images */
    .carousel-item > img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;        /* keep aspect and crop */
        display: block;
    }
    #carouselExampleControls .carousel-inner {
        height: 450px !important;
        width: 100% !important; 
    }
    .carousel-item {
    
        transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out !important; /* faster */
    }   
.product-item {
    display: flex;
    flex-direction: column;
    max-width: 300px;              /* smaller width */
    height: 380px;                 /* smaller height */
    margin: auto;
    border-radius: 20px;           /* slightly smaller curves */
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    overflow: hidden;
    background: #583e3eff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
/* Image container fixed height */
.product-item .product-holder {
    flex: 1;
    width: 100%;
    height: 200px;                /* reduced height */
    position: relative;
    overflow: hidden;
}

/* All images stacked on top */
.product-item .product-holder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;   /* 🔥 stack images */
    top: 0;
    left: 0;
    opacity: 0;
    transition: opacity 0.6s ease;  /* smooth fade */
}

/* Default cover image visible */
.product-item .product-holder img.product-cover {
    opacity: 1;
    z-index: 1;
}
.product-item:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    transform: translateY(-4px);
}


/* Hover zoom effect */
.product-item:hover .product-cover {
    transform: scale(1.08);
}

/* Text below image */
.product-item .card-body {
    flex: 0 0 auto;
    padding: 8px;
    font-size: 0.9rem;
    background: #fff;
}
/* Product title styling */
.product-item .card-body h5 {
    font-weight: 600;
    text-align: center;
    font-size: 1rem;
    margin: 0;
}

 

   
    /* Toolbar for grid filter */
    .grid-toolbar {
        text-align: center;
        margin: 20px 0;
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
    /* Variant marquee container */
.variant-marquee {
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    height: 24px;
    margin-top: 5px;
    display: none; /* hidden by default */
}

/* Scrolling text */
.variant-marquee span {
    display: inline-block;
    padding-left: 100%;
    animation: scroll-left 8s linear infinite;
    font-size: 0.9rem;
    color: #444;
}

/* Show + speed up on hover */
.product-item:hover .variant-marquee {
    display: block;
}
.product-item:hover .variant-marquee span {
    animation-duration: 2s;
}

@keyframes scroll-left {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-100%);
    }
}
/* Header fixed */
#main-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 90px;                  /* default bigger size */
    background: transparent;       /* transparent on load */
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    z-index: 9999;
    transition: all 0.3s ease;
}

/* Shrink + white on scroll */
#main-header.scrolled {
    background: #fcebebff !important; /* turns white */
    height: 65px;                   /* shrink height */
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Logo resizing inside header */
#main-header img.logo {
    height: 60px;
    transition: height 0.3s ease;
}
#main-header.scrolled img.logo {
    height: 40px;
}

/* Nav links color change */
#main-header nav a {
    color: #000 !important;  /* always black */
    transition: color 0.3s ease;
}
#main-header.scrolled nav a {
    color: #000 !important; /* turn dark when bg is white */
}

</style>


<?php 
$brands = isset($_GET['b']) ? json_decode(urldecode($_GET['b'])) : array();
?>
<section class="py-0">
    <div class="container">
        <div class="row">
            <!-- Main content -->
            <div class="col-lg-12 py-2">
                <!-- Carousel -->
                <div class="row">
                    <div class="container-fluid px-0 ">
                        <div id="carouselExampleControls" class="carousel slide carousel-fade" data-ride="carousel" data-interval="2000">
                            <div class="carousel-inner">
                                <?php 
                                $upload_path = "uploads/banner";
                                if(is_dir(base_app.$upload_path)): 
                                    $file = scandir(base_app.$upload_path);
                                    $_i = 0;
                                    foreach($file as $img):
                                        if(in_array($img, array('.', '..'))) continue;
                                        $_i++;
                                ?>
                                <div class="carousel-item h-100 <?php echo $_i == 1 ? "active" : '' ?>">
    <img src="<?php echo validate_image($upload_path.'/'.$img) ?>" class="d-block w-100 h-100" alt="<?php echo $img ?>">

    <!-- 🔥 Caption overlay -->
    <div class="carousel-caption custom-caption">
  <h2 class="typing">CANVAS</h2>
  <p class="typingg">BUY</p>
</div>

</div>

                                <?php endforeach; endif; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Toolbar filter -->
                <div class="grid-toolbar">
                    <span></span>
                    <button id="btnGrid2" onclick="setGrid(2)"><i class="bi bi-grid-fill"></i></button>
                    <button id="btnGrid3" onclick="setGrid(3)" class="active"><i class="bi bi-grid-3x3-gap-fill"></i></button>
                    <button id="btnGrid4" onclick="setGrid(4)"><i class="bi bi-grid-3x3-gap"></i></button>
                </div>

                <!-- Products grid -->
                <div class="container px-4 px-lg-5 mt-3">
                    <div id="productsRow" class="row gx-4 gx-lg-4 row-cols-1 row-cols-md-3">
                        <?php 
                        $where = "";
                        if(count($brands) > 0)
                            $where = " AND p.brand_id IN (".implode(",", $brands).")";
                        
                        $products = $conn->query("SELECT p.*, b.name as bname, c.category 
                            FROM products p 
                            INNER JOIN brands b ON p.brand_id = b.id 
                            INNER JOIN categories c ON p.category_id = c.id 
                            WHERE p.status = 1 {$where} 
                            ORDER BY RAND()");
                        
                        // Fetch all into array
                        $product_list = [];
                        while($row = $products->fetch_assoc()){
                            $product_list[] = $row;
                        }

                        // Move last to front
                        if(!empty($product_list)){
                            $last = array_pop($product_list);
                            array_unshift($product_list, $last);
                        }

                        // Render
                        foreach($product_list as $row):
                            $upload_path = base_app.'/uploads/products/product_'.$row['id'];
                            $img = "";
                            if(is_dir($upload_path)){
                                $fileO = scandir($upload_path);
                                if(isset($fileO[2]))
                                    $img = "uploads/products/product_".$row['id']."/".$fileO[2];
                            }

                            foreach($row as $k => $v){
                                $row[$k] = trim(stripslashes($v));
                            }

                            $inventory = $conn->query("SELECT DISTINCT(price) FROM inventory WHERE product_id = ".$row['id']." ORDER BY price ASC");
                            $inv = array();
                            while($ir = $inventory->fetch_assoc()){
                                $inv[] = format_num($ir['price']);
                            }

                            $price = '';
                            if(isset($inv[0])) $price .= $inv[0];
                            if(count($inv) > 1) $price .= " ~ ".$inv[count($inv) - 1];
                        ?>
                        <div class="col mb-5">
                            <a class="card product-item text-reset text-decoration-none" 
   href=".?p=sub_products&id=<?php echo md5($row['id']) ?>">

                            <div class="product-holder">
    <?php 
    $upload_path = base_app.'/uploads/products/product_'.$row['id'];
    $images = [];
    if(is_dir($upload_path)){
        $files = array_diff(scandir($upload_path), ['.','..']);
        foreach($files as $file){
            $images[] = "uploads/products/product_".$row['id']."/".$file;
        }
    }
    ?>

    <?php if(!empty($images)): ?>
        <!-- First image = cover -->
        <img class="product-cover" src="<?php echo validate_image($images[0]) ?>" alt="Product Image" />
        <!-- Other images = variant slideshow -->
        <?php for($i=1; $i<count($images); $i++): ?>
            <img class="variant-image" src="<?php echo validate_image($images[$i]) ?>" alt="Variant Image" />
        <?php endfor; ?>
    <?php endif; ?>
</div>

<div class="card-body">
    <h5 ><?php echo $row['name'] ?></h5>
    <!-- <span><b>Price:</b> <?php echo $row['price'] ?></span>
    <p class="m-0"><small><b>Brand:</b> <?php echo $row['bname'] ?></small></p>
    <p class="m-0"><small><b>Category:</b> <?php echo $row['category'] ?></small></p> -->
</div>

                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
<script>
    function setGrid(cols) {
        const row = document.getElementById("productsRow");
        row.className = `row gx-4 gx-lg-4 row-cols-1 row-cols-md-${cols}`;

        // toggle active button
        document.querySelectorAll(".grid-toolbar button").forEach(btn => btn.classList.remove("active"));
        document.getElementById("btnGrid" + cols).classList.add("active");
    }
</script>
<script>
document.querySelectorAll(".product-item").forEach(card => {
    let interval;
    const variants = card.querySelectorAll(".variant-image");
    const cover = card.querySelector(".product-cover");

    card.addEventListener("mouseenter", () => {
        if (variants.length > 0) {
            let index = 0; 

            // Reset: hide all
            variants.forEach(v => v.style.opacity = 0);

            // Show first variant immediately
            variants[index].style.opacity = 1;

            // THEN hide cover
            if (cover) cover.style.opacity = 0;

            // Start cycling
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

$('#carouselExampleControls').on('slid.bs.carousel', function () {
    // Reset animations
    document.querySelectorAll('.typing, .typingg').forEach(el => {
        el.classList.remove('active');
        void el.offsetWidth; // force reflow
        el.classList.add('active');
    });
});
window.addEventListener('scroll', () => {
    const header = document.getElementById('main-header');
    if(window.scrollY > 50){
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});


</script>


 