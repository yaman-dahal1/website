<?php
// index.php (Main Portfolio File)
require_once 'config/database.php';
include_once 'includes/header.php';

$db = new Database();
$conn = $db->getConnection();

// Handle Contact Form Submission (POST)
$form_status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $name = trim(htmlspecialchars(strip_tags($_POST['name'])));
    $email = trim(htmlspecialchars(strip_tags($_POST['email'])));
    $message = trim(htmlspecialchars(strip_tags($_POST['message'])));
    
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($message)) $errors[] = "Message cannot be empty";
    
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            if ($stmt->execute()) {
                $form_status = 'success';
                // Redirect to avoid resubmission
                header("Location: index.php?status=success");
                exit();
            } else {
                $form_status = 'error';
            }
        } catch (PDOException $e) {
            $form_status = 'error';
        }
    } else {
        $form_status = 'error';
        $error_msg = implode(", ", $errors);
    }
}

// Get status from URL parameter
$show_success = isset($_GET['status']) && $_GET['status'] == 'success';

// Fetch dynamic projects from database
$projects = [];
try {
    $stmt = $conn->query("SELECT id, title, description, image_url, project_link FROM projects ORDER BY id DESC LIMIT 6");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $projects = [];
}
?>

<!-- HERO SECTION (HOME) -->
<section id="home" class="hero">
    <div class="container hero-container">
        <div class="hero-content">
            <div class="hero-badge">✨ Portfolio 2025</div>
            <h1>Alex <span>Morgan</span></h1>
            <div class="typed-text">Creative Developer & UI Architect</div>
            <p>Crafting immersive digital experiences with modern web technologies. Based globally, creating impact through code and design storytelling.</p>
            <div class="hero-buttons">
                <a href="#portfolio" class="btn btn-primary"><i class="fas fa-arrow-down"></i> View Work</a>
                <a href="#contact" class="btn btn-outline">Let's Talk</a>
            </div>
            <div class="social-links-hero">
                <a href="https://facebook.com/#" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                <a href="https://wa.me/1234567890" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>
                <a href="tel:+1234567890"><i class="fas fa-phone-alt"></i></a>
                <a href="#"><i class="fab fa-github"></i></a>
            </div>
        </div>
        <div class="hero-image">
            <div class="image-wrapper">
                <img src="assets/img/profile.jpg" alt="Alex Morgan" onerror="this.src='https://randomuser.me/api/portraits/men/32.jpg'">
            </div>
        </div>
    </div>
</section>

<!-- ABOUT SECTION -->
<section id="about" class="about">
    <div class="container">
        <div class="section-header">
            <h2>About <span>Me</span></h2>
            <div class="divider"></div>
            <p>Passionate creator with a mission to build meaningful digital tools</p>
        </div>
        <div class="about-grid">
            <div class="about-card">
                <i class="fas fa-code"></i>
                <h3>Full Stack Focus</h3>
                <p>Expertise in PHP, JavaScript ecosystems, and modern CSS frameworks to deliver seamless performance.</p>
            </div>
            <div class="about-card">
                <i class="fas fa-mobile-alt"></i>
                <h3>Responsive First</h3>
                <p>Every project is crafted with mobile-first approach and pixel-perfect details across devices.</p>
            </div>
            <div class="about-card">
                <i class="fas fa-database"></i>
                <h3>Dynamic Solutions</h3>
                <p>Database driven backends, secure authentication and real-time data handling.</p>
            </div>
            <div class="about-text">
                <h3>Creative Technologist</h3>
                <p>I'm Alex — a web developer with 6+ years of experience turning complex ideas into elegant interfaces. I've worked with startups and global agencies to deliver high-performance web applications. My code focuses on accessibility, speed, and maintainable architecture.</p>
                <p><i class="fas fa-check-circle"></i> 50+ Projects completed</p>
                <p><i class="fas fa-check-circle"></i> 24/7 support & collaboration</p>
                <a href="#contact" class="btn btn-small">Contact Me <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- DYNAMIC PORTFOLIO SECTION -->
<section id="portfolio" class="portfolio">
    <div class="container">
        <div class="section-header">
            <h2>Featured <span>Projects</span></h2>
            <div class="divider"></div>
            <p>Dynamic content fetched from database — each project is fully manageable</p>
        </div>
        <div class="portfolio-grid">
            <?php if (count($projects) > 0): ?>
                <?php foreach ($projects as $project): ?>
                    <div class="portfolio-item">
                        <div class="portfolio-img">
                            <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                            <div class="portfolio-overlay">
                                <a href="<?php echo htmlspecialchars($project['project_link']); ?>" class="project-link" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                        <div class="portfolio-info">
                            <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                            <p><?php echo htmlspecialchars($project['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-projects">
                    <p>No portfolio items yet. Add projects dynamically via database.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CONTACT SECTION + FORM + EXTERNAL REDIRECTS -->
<section id="contact" class="contact">
    <div class="container">
        <div class="section-header">
            <h2>Get In <span>Touch</span></h2>
            <div class="divider"></div>
            <p>Let's collaborate — I'm just a message away</p>
        </div>
        <div class="contact-wrapper">
            <div class="contact-info">
                <h3>Connect Directly</h3>
                <ul class="contact-details">
                    <li><i class="fas fa-phone-alt"></i> <strong>Call me:</strong> <a href="tel:+1234567890">+1 234 567 890</a> (Direct dial)</li>
                    <li><i class="fab fa-whatsapp"></i> <strong>WhatsApp:</strong> <a href="https://wa.me/1234567890" target="_blank">Chat on WhatsApp <i class="fas fa-external-link-alt"></i></a></li>
                    <li><i class="fab fa-facebook"></i> <strong>Facebook:</strong> <a href="https://facebook.com/#" target="_blank">/alex.morgan.page</a></li>
                    <li><i class="fas fa-envelope"></i> <strong>Email:</strong> hello@alexmorgan.dev</li>
                </ul>
                <div class="availability">
                    <i class="fas fa-clock"></i> Response within 24 hours
                </div>
            </div>
            <div class="contact-form">
                <?php if ($show_success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Thanks for reaching out! Your message has been saved successfully.
                    </div>
                <?php elseif (isset($form_status) && $form_status == 'error'): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo isset($error_msg) ? $error_msg : 'Submission failed. Please try again.'; ?>
                    </div>
                <?php endif; ?>
                <form action="index.php" method="POST" id="contactForm">
                    <div class="input-group">
                        <input type="text" name="name" id="name" placeholder="Your Name" required>
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" id="email" placeholder="Email Address" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="input-group">
                        <textarea name="message" id="message" rows="5" placeholder="Tell me about your project..." required></textarea>
                        <i class="fas fa-comment"></i>
                    </div>
                    <button type="submit" name="submit_contact" class="btn btn-primary btn-submit"><i class="fas fa-paper-plane"></i> Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include_once 'includes/footer.php'; ?>