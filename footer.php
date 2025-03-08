<?php require_once 'footer.php'; ?>

<!-- Add Font Awesome CDN for icons (Place this in your <head> section or before the footer) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
footer {
    background: #2c3e50;
    color: white;
    padding: 20px 0;
    margin-top: 50px;
}

footer .container {
    display: flex;
    flex-direction: column;
}

footer .contact-info,
footer .social-links {
    flex: 1;
    min-width: 250px;
    margin: 10px;
}

footer h3 {
    margin-bottom: 10px;
}

footer ul {
    list-style: none;
    padding: 0;
}

footer a {
    color: white;
    text-decoration: none;
}

footer a:hover {
    text-decoration: underline;
}

footer .bottom-text {
    text-align: center;
    margin-top: 20px;
    border-top: 1px solid #34495e;
    padding-top: 20px;
}

.social-links {
    display: inline-box;
}

.social-links a {
    margin: 0 10px;
}

.social-links i {
    font-size: 40px;
    transition: transform 0.3s;
}

.social-links a:hover i {
    transform: scale(1.1);
}
</style>

<footer>
    <div class="container">
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
            <div class="contact-info">
                <h3>Contact Us</h3>
                <p>Email: support@parceltrack.com</p>
                <p>Phone: +91 1234567890</p>
            </div>
            <div class="social-links">
                <h3>Follow Us</h3>
                <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="bottom-text">
            <p>&copy; 2024 ParcelTrack. All rights reserved.</p>
        </div>
    </div>
</footer>
