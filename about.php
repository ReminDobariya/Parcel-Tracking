<?php require_once 'header.php'; ?>

<style>
.about-container {
    max-width: 850px;
    margin: 60px auto;
    padding: 20px;
    font-family: Arial, sans-serif;
    color: #333;
    line-height: 1.8;
}

.about-section {
    background: #ffffff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-bottom: 40px;
}

.about-section h1,
.about-section h2 {
    color: #2c3e50;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
}

.about-section p,
.about-section ul {
    font-size: 16px;
    color: #555;
    text-align: justify;
}

.about-section ul {
    padding-left: 20px;
    list-style-type: disc;
    text-align: left;
}

.mission-statement {
    font-style: italic;
    font-size: 18px;
    color: #34495e;
    margin-top: 15px;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.team-member {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.team-member:hover {
    transform: translateY(-5px);
}

.team-member img {
    width: 180px;
    height: 180px;
    border-radius: 10%;
    object-fit: cover;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.team-member img:hover {
    transform: scale(1.1);
}

.team-member h3 {
    margin-top: 10px;
    font-size: 18px;
    color: #2c3e50;
    font-weight: bold;
}

.team-member p {
    font-size: 14px;
    color: #666;
}

.team-member p:last-child {
    margin-top: 5px;

}
</style>

<div class="about-container">
    <div class="about-section">
        <h1>Welcome to ParcelTrack</h1>
        <p>Imagine a world where every parcel finds its way seamlessly to your door, no matter where it’s headed. That’s the world we’re creating at ParcelTrack. We’re not just delivering parcels; we’re delivering peace of mind. With unmatched reliability and cutting-edge technology, ParcelTrack brings you the confidence that your delivery is safe, secure, and always on time.</p>
        <p class="mission-statement" style="text-align: center; color: #2c3e50; font-weight: bold;">"We don’t just move packages; we connect people, places, and possibilities."</p>
    </div>

    <div class="about-section">
        <h2>Our Mission</h2>
        <p>Our mission is to set a new standard in logistics by making delivery faster, simpler, and more transparent. We’re passionate about providing reliable shipping solutions that exceed expectations and empower businesses and individuals to bridge distances and build connections. Every package is more than just cargo; it's part of someone’s story, and we make it our goal to deliver that story with excellence.</p>
    </div>

    <div class="about-section">
        <h2>Our Core Values</h2>
        <p>ParcelTrack stands for more than just shipping. Our core values are woven into every part of our service, ensuring we deliver trust and integrity with every package:</p>
        <ul style="list-style-type: disc; padding-left: 20px; text-align: justify;">
            <li><strong>Customer-First Approach</strong>: We go the extra mile to tailor our services to the unique needs of each customer, ensuring satisfaction at every step.</li>
            <li><strong>Innovation</strong>: We stay at the forefront of logistics technology, bringing you smart solutions that save time and give you peace of mind.</li>
            <li><strong>Integrity</strong>: Honesty and accountability are at the heart of our operations, building trust one parcel at a time.</li>
        </ul>
    </div>

    <div class="about-section">
        <h2>Meet Our Team</h2>
        <p style="text-align: center;">Our team is our greatest asset, and every member brings passion and dedication to their role. We are proud to introduce the people driving ParcelTrack forward</p>
        <div class="team-grid">
            <div class="team-member">
                <img src="assets/Umang.jpg" alt="Umang Manvar">
                <h3>Umang Manvar</h3>
                <p>CEO & Founder</p>
                <p>With a visionary approach and years of experience, Umang leads ParcelTrack to redefine logistics excellence.</p>
            </div>
            <div class="team-member">
                <img src="assets/Mia.jpg" alt="Mia Charlotte">
                <h3>Mia Charlotte</h3>
                <p>Operations Director</p>
                <p>Mia oversees every parcel's journey, ensuring smooth, timely, and efficient operations across the board.</p>
            </div>
            <div class="team-member">
                <img src="assets/Vasu.jpg" alt="Vasu Mendapra">
                <h3>Vasu Mendapra</h3>
                <p>Technical Lead</p>
                <p>Vasu’s passion for technology drives our innovations, making ParcelTrack a leader in tracking and delivery.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
