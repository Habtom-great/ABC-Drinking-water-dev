<?php include 'header.php'; ?>

<style>
/* Hero Section Styles */
.hero {
    display: flex;
   
    align-items: center;
    justify-content: space-between;
    padding: 50px 20px;
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/OIP (1).jpeg') center/cover no-repeat;
    color: white;
    min-height: 80vh;
}

.hero-content { 
    
    max-width: 50%;
    text-align: left;
}

.hero-content h1 {
    font-size: 3em;
    font-weight: bold;
    margin-bottom: 20px;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
}

.hero-content p {
    font-size: 1.5em;
    margin-bottom: 30px;
    line-height: 1.6;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
}

.hero-image {
    
    max-width: 40%;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

/* Buttons */
.hero-content a {
    display: inline-block;
    padding: 15px 30px;
    font-size: 1.2em;
    color: white;
    background-color: #007BFF;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.hero-content a:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.hero-content a.active {
    margin-left: 10px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero {
        flex-direction: column;
        text-align: center;
    }

    .hero-content {
        max-width: 100%;
        margin-bottom: 20px;
    }

    .hero-image {
        
        max-width: 80%;
    }
}
</style>

<section class="hero">
    <div class="hero-content">
        <h1>Welcome to ABC Drinking Water Company</h1>
        <p>Your trusted source for clean, safe, and refreshing drinking water.</p>
    </div>
   
</section>
<section class="about-products">
    <a href="products.php" class="btn">Explore Our Products</a>
   
</section >
<?php include 'footer.php'; ?>

 
   