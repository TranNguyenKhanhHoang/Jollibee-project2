<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
/*
 * about.php - About page.
 * The team contributions (Part 1 + Part 2) are loaded from the `about` table.
 * settings.php holds the database details and opens the connection ($conn).
 */
require_once 'includes/settings.php';

// if something is wrong with the query, show a message instead of a PHP error
mysqli_report(MYSQLI_REPORT_OFF);

// get all members from the about table
mysqli_set_charset($conn, 'utf8mb4');
$result = mysqli_query($conn, 'SELECT * FROM about ORDER BY member_no');

// protects the page from HTML/JS stored in the data
function e($text)
{
    return htmlspecialchars((string) $text);
}

// the database stores one bullet per line, this prints them as <li>
function show_list($text)
{
    echo '<ul>';
    foreach (explode("\n", $text) as $line) {
        if (trim($line) != '') {
            echo '<li>' . e(trim($line)) . '</li>';
        }
    }
    echo '</ul>';
}

$pageTitle = 'NovaCart | About Us';
include 'includes/header.inc';
include 'includes/nav.inc';
?>
    <link rel="stylesheet" href="styles/aboutstyles.css">

    <main>
        <section>
            <h1>About NovaCart</h1>
            <p>
                Discover who we are, what we believe in and the people behind our e-commerce and digital retail platform
            </p>
        </section>
        <section>
            <h2>
                Who we are
            </h2>
            <p>
                NovaCart is a fictional e-commerce and digital retail
                platform company created for our COS10026 student project.
                Our concept focuses on making online shopping more
                convenient, accessible and customer-friendly.
            </p>
            <h3>
                What We Do
            </h3>
            <ul>
                <li>Explore e-commerce platform solutions.</li>
                <li>Improve digital shopping experiences.</li>
                <li>Explore customer experience technology.</li>
                <li>Help businesses connect with online customers.</li>
            </ul>
        </section>
        <section>
            <h2>Our Mission and Vision</h2>
            <article>
                <h3>Our Mission</h3>
                <p>
                    To make online retail experiences simple, accessible
                    and enjoyable for businesses and customers.
                </p>
            </article>
            <article>
                <h3>
                    Our Vision
                </h3>
                <p>
                    To create a digital marketplace where technology
                    helps people discover products and connect with
                    businesses easily.
                </p>
            </article>
        </section>
        <section>
            <h2>Meet our team</h2>
            <p>
                We are Jollibee, a team of students working together
                to design and develop the NovaCart recruitment website.
            </p>
            <h3>Group Details</h3>
            <figure>
                <img src="images/groupphoto.jpg" alt="Group photo of the four Jollibee team members" width="500">
                <figcaption>The Jollibee Team collaborating on the NovaCart website</figcaption>
            </figure>
            <section class="team-contribution">
                <h2>Team Contribution</h2>

                <?php if (!$result): ?>
                    <p>Team contributions are not available. Please import database/jollibee_careers.sql into the database.</p>
                <?php else: ?>
                    <?php while ($m = mysqli_fetch_assoc($result)): ?>
                        <div class="member-card">
                            <h3>Member <?= e($m['member_no']) ?>: <?= e($m['full_name']) ?></h3>

                            <p><strong>Student ID:</strong> <?= e($m['student_id']) ?></p>

                            <h4>Project Part 1</h4>
                            <?php show_list($m['part1_contribution']); ?>

                            <h4>Project Part 2</h4>
                            <?php show_list($m['part2_contribution']); ?>

                            <blockquote>
                                "<?= e($m['quote']) ?>"
                            </blockquote>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </section>
            <h2>Our Values</h2>
            <article>
                <h3>Innovation</h3>
                <p>
                    We explore creative ideas to improve digital retail.
                </p>
            </article>
            <article>
                <h3>Customer First</h3>
                <p>
                    We consider customer needs when designing experiences.
                </p>
            </article>
            <article>
                <h3>Integrity</h3>
                <p>
                    We value honesty, responsibility and transparency.
                </p>
            </article>
            <article>
                <h3>Collaboration</h3>
                <p>
                    We believe teamwork helps turn ideas into results.
                </p>
            </article>
        </section>
        <section>
            <h2>Diversity and Inclusion</h2>
            <p>
                NovaCart values different backgrounds, perspectives
                and experiences. We aim to promote respect, fairness,
                accessibility and collaboration.
            </p>

        </section>
        <section>
            <h2>Join Our Team</h2>
            <p>
                Interested in digital retail and technology?
                Explore opportunities and discover how you can
                contribute to NovaCart.
            </p>
        </section>
        <p><a href="jobs.php">Explore Job Opportunities</a></p>
        <p><a href="apply.php">Apply Now</a></p>
    </main>
<?php include 'includes/footer.inc'; ?>
