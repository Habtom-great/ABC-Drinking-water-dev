<?php include 'header.php'; ?>

<section class="contact-us">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We’d love to hear from you! Please fill out the form below, and our team will get back to you as soon as possible.</p>

        <div class="contact-info">
        
        <div class="form-container">
            <form action="process_contact.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" placeholder="Write your message here" required></textarea>

                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
    </div>
</section>

<style>
    .contact-us {
        
        padding: 60px 20px;
        background-color: #f8f9fa;
        text-align: center;
    }

    .contact-us h1 {
        margin-top:40px;  
        font-size: 2.5rem;
        color: #007BFF;
        margin-bottom: 20px;
    }

    .contact-us p {
        font-size: 1.2rem;
        color: #555;
        margin-bottom: 40px;
    }

    .contact-info {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .info-item {
        flex: 1;
        max-width: 300px;
        margin: 10px;
        text-align: center;
    }

    .info-item i {
        font-size: 2rem;
        color: #007BFF;
        margin-bottom: 10px;
    }

    .info-item h3 {
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 5px;
    }

    .info-item p {
        font-size: 1rem;
        color: #555;
    }

    .form-container {
        max-width: 600px;
        margin: 0 auto;
        text-align: left;
    }

    form {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    form label {
        display: block;
        font-size: 1rem;
        color: #333;
        margin-bottom: 8px;
    }

    form input,
    form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
    }

    form button {
        width: 100%;
        padding: 10px;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #0056b3;
    }
</style>

<?php include 'footer.php'; ?>
