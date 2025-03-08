<?php require_once 'header.php'; ?>

<style>
.hero-section {
    padding: 80px 20px;
    background: linear-gradient(rgba(44, 62, 80, 0.85), rgba(44, 62, 80, 0.85)), url('/images/courier-background.jpg') no-repeat center center;
    background-size: cover;
    color: white;
    text-align: center;
}

.features {
    padding: 50px 20px;
    background-color: #f4f4f4;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.feature-card {
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    text-align: center;
}

.cta-button {
    display: inline-block;
    background: #e74c3c;
    color: white;
    padding: 15px 30px;
    border-radius: 5px;
    text-decoration: none;
    margin-top: 20px;
    transition: background 0.3s ease;
}

.cta-button:hover {
    background: #c0392b;
}

.track-section {
    margin: 40px auto;
    text-align: center;
}
</style>

<div class="hero-section">
    <div class="container">
        <h1 style="font-size: 3em; margin-bottom: 20px;">Welcome to ParcelTrack</h1>
        <p style="font-size: 1.4em; margin-bottom: 30px;">Reliable, Fast, and Secure Parcel Tracking Solutions</p>
        <a href="login.php" class="cta-button">Get Started</a>
    </div>
</div>

<div class="features">
    <div class="feature-grid">
        <div class="feature-card">
            <h3>Real-Time Tracking</h3>
            <p>Get real-time parcel updates, with accurate delivery times and status.</p>
        </div>
        <div class="feature-card">
            <h3>Secure Shipping</h3>
            <p>Rest assured, your parcel will be handled with the utmost care.</p>
        </div>
        <div class="feature-card">
            <h3>Fast Delivery</h3>
            <p>Experience swift delivery services that meet your needs.</p>
        </div>
        <div class="feature-card">
            <h3>Easy Returns</h3>
            <p>Hassle-free returns process for your convenience.</p>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
