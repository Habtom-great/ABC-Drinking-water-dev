<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Compact Footer</title>

 <!-- Link to Bootstrap CSS -->
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

 <!-- Font Awesome (for social icons) -->
 <script src="https://kit.fontawesome.com/a076d05399.js"></script>

 <style>
 footer {
  background: linear-gradient(135deg, #1d3557, #457b9d);
  padding: 20px 0;
  color: #fff;
 }

 footer .container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
 }

 footer .col-md-4 {
  flex: 1;
  margin: 10px;
  max-width: 300px;
 }

 footer .footer-logo {
  width: 100px;
  margin-bottom: 15px;
 }

 footer h5 {
  font-size: 18px;
  color: #f1faee;
  margin-bottom: 10px;
  font-weight: bold;
  text-transform: uppercase;
 }

 footer .social-links {
  list-style: none;
  padding-left: 0;
 }

 footer .social-links li {
  display: inline-block;
  margin-right: 10px;
 }

 footer .social-links a {
  color: #f1faee;
  text-decoration: none;
  font-size: 20px;
  transition: color 0.3s ease;
 }

 footer .social-links a:hover {
  color: #e63946;
 }

 footer .footer-info p {
  font-size: 14px;
  margin: 5px 0;
  color: #f1faee;
 }

 footer .footer-info a {
  color: #e63946;
  text-decoration: none;
  transition: color 0.3s ease;
 }

 footer .footer-info a:hover {
  color: #f1faee;
 }

 footer .footer-bottom {
  text-align: center;
  padding-top: 15px;
  font-size: 14px;
  color: #f1faee;
  margin-top: 20px;
 }

 footer .footer-bottom p {
  margin-bottom: 0;
 }

 /* Responsive Styles */
 @media (max-width: 767px) {
  footer .container {
   flex-direction: column;
   align-items: center;
  }

  footer .col-md-4 {
   max-width: 100%;
   margin: 10px 0;
   text-align: center;
  }

  footer .social-links {
   text-align: center;
  }
 }
 </style>
</head>

<body>

 <!-- Main Content of the Website -->

 <footer>
  <div class="container">
   <!-- Footer Top Section -->
   <div class="col-md-4">
    <img src="assets/images/logo.png" alt="Company Logo" class="footer-logo">
    <p>Your company description here. Delivering quality, trust, and excellence in every service!</p>
   </div>

   <!-- Follow Us Section -->
   <div class="col-md-4">
    <h5>Follow Us</h5>
    <ul class="social-links">
     <li><a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
     <li><a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a></li>
     <li><a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a></li>
     <li><a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
    </ul>
   </div>

   <!-- Contact Us Section -->
   <div class="col-md-4">
    <h5>Contact Us</h5>
    <div class="footer-info">
     <p><strong>Address:</strong> 123 Street, City, Country</p>
     <p><strong>Email:</strong> <a href="mailto:info@example.com">info@example.com</a></p>
     <p><strong>Phone:</strong> +1 (234) 567-890</p>
    </div>
   </div>
  </div>

  <!-- Footer Bottom Section -->
  <div class="footer-bottom">
   <p>&copy; 2025 Your Company Name. All Rights Reserved.</p>
  </div>
 </footer>

 <!-- Link to Bootstrap JS -->
 <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>