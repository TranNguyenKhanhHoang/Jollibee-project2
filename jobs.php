<?php
$pageTitle = "NovaCart | Jobs"; 
include "includes/header.inc";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="NovaCart Recruitment Website">
    <meta name="keywords" content="NovaCart, Recruitment, E-Commerce">
    <meta name="author" content="Your Team">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NovaCart | Jobs</title>

    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/jobstyles.css">
</head>

<body>
    <!-- ================= HEADER ================= -->
    <header class="header">
        <div class="container">
            <a href="index.php" class="logo">
                NovaCart
            </a>

            <nav class="navbar">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="jobs.php" class="active">Jobs</a></li>
                    <li><a href="apply.php">Apply</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>

               


                <!-- Search Bar Form -->
                <div class="search-container">
                    <form method="get" action="jobs.php">
                        <label for="job_title"><strong>Search Job :</strong></label>
                        <input type="text" id="job_title" name="job_title" value="<?php echo isset($_GET['job_title']) ? htmlspecialchars($_GET['job_title']) : ''; ?>" required />

                        
                        <input type="submit" value="Search" style="display: none;" />

                        
                </div>

                

            </nav>

            <!-- JOB LISTS  -->

        <div class="card-job">
            <?php
                require_once "settings.php";
                mysqli_report(MYSQLI_REPORT_OFF);
                $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

                if ($conn) {
                    // Xử lý logic tìm kiếm theo tên công việc (title)
                    if (isset($_GET['job_title']) && !empty(trim($_GET['job_title']))) {
                        $search_title = mysqli_real_escape_string($conn, trim($_GET['job_title']));
                        $query = "SELECT * FROM jobs WHERE title LIKE '%$search_title%'";
                    } else {
                        $query = "SELECT * FROM jobs";
                    }

                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<article class="card">';
                            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                            echo '<ul>';
                            echo '<li>Reference Number: ' . htmlspecialchars($row['reference_number']) . '</li>';
                            echo '<li>Short Description: ' . htmlspecialchars($row['description']) . '</li>';
                            echo '<li>Salary: ' . htmlspecialchars($row['salary']) . '</li>';
                            echo '<li>Reporting Line: ' . htmlspecialchars($row['reporting_line']) . '</li>';
                            echo '<li>' . htmlspecialchars($row['skill_1']) . '</li>';
                            echo '<li>' . htmlspecialchars($row['skill_2']) . '</li>';
                            echo '</ul>';
                            echo '<a href="apply.php">Apply now →</a>';
                            echo '</article>';
                        }
                    } else {
                        echo '<p>No matching jobs found.</p>';
                    }

                    mysqli_close($conn);
                } else {
                    echo '<p>Unable to connect to the database.</p>';
                }
            ?>
                
        </div>


    </header>

    <!-- ================= PAGE HERO BANNER ================= -->
    <section class="page-banner">
        <div class="container">
            <h1>NovaCart Jobs</h1>
            <p>Join the team building the future of online shopping</p>
        </div>
    </section>

    <!-- ================= MAIN ================= -->
    <main>
        <!-- ASIDE -->
        <aside class="sidebar-container">
            <div class="career-tips">
                <h3>Application Tips</h3>
                <p>Stand out from the crowd! Make sure your CV includes:</p>
                <ul>
                    <li>Links to your GitHub repository and live web projects.</li>
                    <li>Demonstrated knowledge of WCAG 2.1 web accessibility.</li>
                    <li>Clear examples of responsive layout development.</li>
                </ul>
            </div>

            <div class="aside-widget">
                <h3>Quick Checklist</h3>
                <p>Before submitting your application, double-check that you have:</p>
                <ul>
                    <li>Updated contact details & LinkedIn profile.</li>
                    <li>PDF format resume (under 5MB).</li>
                    <li>Cover letter tailored to NovaCart values.</li>
                </ul>
            </div>

            <div class="aside-widget">
                <h3>Why Join Us?</h3>
                <p>We care about your growth and work-life balance:</p>
                <ul>
                    <li><span class="tag-badge">Flex</span> Hybrid work schedule</li>
                    <li><span class="tag-badge">Perks</span> Annual learning stipend</li>
                    <li><span class="tag-badge">Health</span> Full medical coverage</li>
                </ul>
            </div>

            <div class="aside-widget">
                <h3>Referral Program</h3>
                <p>Know someone great for our team? Refer a friend and earn bonuses!</p>
                <ul>
                    <li>Applicable to all open positions.</li>
                    <li>Attractive referral rewards upon passing probation.</li>
                    <li>Email referrals to <a href="mailto:referrals@novacart.com">referrals@novacart.com</a></li>
                </ul>
            </div>

            <div class="job-sidebar">
                <h3>Questions About a Role?</h3>
                <p>Our recruitment team is here to help you throughout the application process.</p>
                <p><strong>Email us:</strong> <a href="mailto:careers@novacart.com">careers@novacart.com</a></p>
                <p><strong>Office Hours:</strong> Mon - Fri, 9:00 AM - 5:00 PM</p>
            </div>
        </aside>

        <!-- job lists -->

        <section class="open-positions">


            <h2>Open Positions</h2>

            <div class="card-job">

                <article class="card">

                    <!-- JOB 1 -->

                    <h3>Front-End Web Developer</h3>

                    <ul>
                        <li>Reference Number: FE102</li>
                        <li>Short Description:
                            We are seeking a talented Front-End Web Developer to build, optimize, and maintain
                            responsive,
                            accessible customer-facing web interfaces for our online retail platform.</li>
                        <li>Salary: $1,200 – $1,800 / month</li>
                        <li>Reporting Line: Reports to the Technical Lead / Engineering Manager</li>

                        <li>Professional Communication and Presentation </li>
                         <li>Teamwork Framework</li>
                    </ul>

                    <a href="apply.php">
                        Apply now →
                    </a>

                </article>

                <!-- JOB 2 -->

                <article class="card">

                    <h3>UX/UI Designer</h3>

                    <ul>
                        <li>Reference Number: FE102</li>
                        <li>Short Description:
                            We are seeking a talented Front-End Web Developer to build, optimize, and maintain
                            responsive,
                            accessible customer-facing web interfaces for our online retail platform.</li>
                        <li>Salary: $1,200 – $1,800 / month</li>
                        <li>Reporting Line: Reports to the Technical Lead / Engineering Manager</li>
                        <li>Professional Communication and Presentation</li>
                        <li>Teamwork Framework</li>
                    </ul>
                    <a href="apply.php">
                        Apply now →
                    </a>

                </article>

                <!-- JOB 3-->

                <article class="card">
                    <h3>Product Listing Specialist</h3>
                    <ul>
                        <li>Reference Number: FE102</li>
                        <li>Short Description:
                            We are seeking a talented Front-End Web Developer to build, optimize, and maintain
                            responsive,
                            accessible customer-facing web interfaces for our online retail platform.</li>
                        <li>Salary: $1,200 – $1,800 / month</li>
                        <li>Reporting Line: Reports to the Technical Lead / Engineering Manager</li> <li>Professional Communication and Presentation</li>
                         <li>Teamwork Framework</li>
                    </ul>

                    <a href="apply.php">
                        Apply now →
                    </a>

                </article>

            </div>
        </section>



        <!-- Recruitment Process -->
        <section class="rp">
            <h2>Recruitment Process</h2>
            <ol>
                <li>Online Application</li>
                <li>HR & Technical Interview</li>
                <li>Technical Test</li>
                <li>Final Offer & Onboarding</li>
            </ol>
        </section>

 
    </main>

    <!-- ================= FOOTER ================= -->
    <footer>
        <div class="container">
            <p>&copy; 2026 NovaCart Pty Ltd.</p>
            <p>
                Email:
                <a href="mailto:careers@novacart.com">
                    careers@novacart.com
                </a>
            </p>
            <p>
                <a href="#">GitHub Repository</a>
                |
                <a href="#">Live Website</a>
            </p>
        </div>
    </footer>
</body>

</html>
