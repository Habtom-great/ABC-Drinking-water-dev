<?php include 'header.php'; ?>

<style>
    /* About Page Styles */
    .about-page {
        font-family: 'Poppins', sans-serif;
        line-height: 2.8;
        background-color: #f8f9fa;
        padding: 50px 20px;
    }

    .about-page h1 {
        font-size: 3em;
        color: #007BFF;
        text-align: center;
        margin-bottom: 20px;
    }

    .about-page p {
        font-size: 1.2em;
        color: #555;
        text-align: center;
        max-width: 800px;
        margin: 0 auto 20px;
    }

    .about-page img {
        display: block;
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 30px auto;
    }

    .about-products {
        background-color: #fff;
        padding: 50px 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .about-products h2 {
        font-size: 2.5em;
        color: #007BFF;
        margin-bottom: 20px;
    }

    .about-products p {
        font-size: 1.1em;
        color: #555;
        margin-bottom: 20px;
    }

    .about-products .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007BFF;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .about-products .btn:hover {
        background-color: #0056b3;
    }
</style>

<section class="about-page">
    <h1>About ABC Company</h1>
    <p>
        ABC Water Company is a leading provider of premium water products, committed to excellence, sustainability,
        and customer satisfaction. With over two decades of experience, we have become a trusted name in delivering
        purified water solutions to homes, businesses, and industries.
    </p>
    <img src="assets/images/child drinking.jpeg" alt="About ABC Company">
</section>

<section class="about-products">
    <h2>Our Water Products</h2>
    <p>
        At ABC Water Company, we offer a wide range of products designed to meet your hydration needs. From bottled
        water in various sizes to advanced water dispensers, our products are crafted with precision and care to
        ensure purity and freshness. Explore our range today and discover the difference in quality.
    </p>
    <p>
        We also provide customized water solutions for industries, ensuring high standards of hygiene and safety.
        Whether you need bulk water supply, innovative packaging, or tailored services, ABC Water Company is your
        trusted partner.
    </p>
    <a href="products.php" class="btn">View Our Products</a>
</section>

<?php include 'footer.php'; ?>
