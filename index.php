<?php include 'header.php'; ?>

<style>
/* ================= HERO SECTION ================= */
.hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 80px 60px;
    background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)),
    url('assets/images/OIP (1).jpeg') center/cover no-repeat;
    color: white;
    min-height: 85vh;
}

/* TEXT AREA */
.hero-content {
    max-width: 55%;
}

.hero-content h1 {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 20px;
    line-height: 1.3;
}

.hero-content p {
    font-size: 18px;
    line-height: 1.7;
    opacity: 0.9;
    margin-bottom: 30px;
}

/* BUTTON */
.hero-content .btn-primary {
    display: inline-block;
    padding: 12px 25px;
    background: #0d6efd;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    transition: 0.3s;
    font-weight: 500;
}

.hero-content .btn-primary:hover {
    background: #084298;
    transform: translateY(-2px);
}

/* SECOND BUTTON */
.hero-content .btn-outline {
    margin-left: 10px;
    padding: 12px 25px;
    border: 1px solid white;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    transition: 0.3s;
}

.hero-content .btn-outline:hover {
    background: white;
    color: black;
}

/* RIGHT SIDE IMAGE (OPTIONAL FUTURE ERP DASHBOARD MOCKUP) */
.hero-image {
    max-width: 40%;
}

/* ================= ABOUT SECTION ================= */
.about-products {
    text-align: center;
    padding: 50px 20px;
}

.about-products a {
    background: #198754;
    color: white;
    padding: 12px 25px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
}

.about-products a:hover {
    background: #146c43;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .hero {
        flex-direction: column;
        text-align: center;
        padding: 50px 20px;
    }

    .hero-content {
        max-width: 100%;
    }

    .hero-image {
        display: none;
    }
}
</style>

<!-- ================= HERO ================= -->
<section class="hero">

    <div class="hero-content">
        <h1>ABC Drinking Water ERP System</h1>
        <p>
            A complete enterprise resource planning system for managing sales, inventory, 
            accounting, HR, and reporting in one centralized platform.
        </p>

        <a href="login.php" class="btn-primary">Login to System</a>
        <a href="products.php" class="btn-outline">View Products</a>
    </div>

</section>

<!-- ================= ABOUT / CTA ================= -->
<section class="about-products">
    <h3>Start Managing Your Business Smarter</h3>
    <p>Track sales, inventory, staff, and financial reports in real time.</p>
    <br>
    <a href="products.php">Explore Our Products</a>
</section>

<?php include 'footer.php'; ?>