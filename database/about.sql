DROP TABLE IF EXISTS `about`;
CREATE TABLE `about` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_no` tinyint(3) UNSIGNED NOT NULL,
  `full_name` varchar(80) NOT NULL,
  `student_id` varchar(12) NOT NULL,
  `part1_contribution` text NOT NULL,
  `part2_contribution` text NOT NULL,
  `quote` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_about_member_no` (`member_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `about` (`member_no`, `full_name`, `student_id`, `part1_contribution`, `part2_contribution`, `quote`) VALUES
(1, 'Tran Nguyen Khanh Hoang', '105974061',
 'Designed the website layout and navigation structure.\nDeveloped the Home page using HTML5 and CSS3.\nCreated the shared stylesheet and responsive page layout.\nEnsured accessibility and semantic HTML standards.\nHelped with testing, debugging and integration.\nParticipated in meetings and project planning.',
 'Converted the Home page to index.php.\nCreated the shared header.inc, nav.inc and footer.inc and settings.php.\nBuilt the HR manager login and logout (auth.inc, login.php, logout.php).\nBuilt manage.php to view the expressions of interest.',
 'I focused on building the Home page and making sure the website looked clean, consistent and easy to use. I also helped test and improve the final project before submission.'),
(2, 'Pham Nguyen Hai Binh', '106727033',
 'Developed the Jobs page.\nWrote job descriptions and company information.\nAssisted with CSS styling and page validation.',
 'Converted the Jobs page to jobs.php and loaded the job listings from the jobs table in the database.\nBuilt the job search (search_result.php) that finds jobs by title.',
 'I focused on creating the Jobs page and ensuring the job information was accurate and well-presented.'),
(3, 'Nguyen Lan Ngoc Minh', '106727143',
 'Developed the Apply page.\nDesigned and implemented the HTML application form.\nTested form validation and page usability.',
 'Converted the Apply page to apply.php, which sends the form to process_eoi.php.\nCreated the eoi table with a field for every form input and a status field (New, Current, Final).\nAdded server-side validation in process_eoi.php and saved each application in the database.',
 'I created the Apply page and designed the application form while ensuring a user-friendly experience.'),
(4, 'Ho Hai Dang', '106718079',
 'Developed the About page.\nPrepared team member information and project documentation.\nAssisted with GitHub repository management and final testing.',
 'Created the about table and stored the team contributions of Part 1 and Part 2 in the database.\nConverted the About page to about.php and loaded the member data from the database.',
 'I created the About page, organised the project documentation, and helped prepare the final version for submission.');

COMMIT;