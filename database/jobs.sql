CREATE TABLE IF NOT EXISTS jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    reference_number VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    salary VARCHAR(100) NOT NULL,
    reporting_line VARCHAR(255) NOT NULL,
    skill_1 VARCHAR(255),
    skill_2 VARCHAR(255)
);


INSERT INTO jobs (title, reference_number, description, salary, reporting_line, skill_1, skill_2) VALUES
('Front-End Web Developer', 'FE102', 'We are seeking a talented Front-End Web Developer to build, optimize, and maintain responsive, accessible customer-facing web interfaces for our online retail platform.', '$1,200 - $1,800 / month', 'Reports to the Technical Lead / Engineering Manager', 'Professional Communication and Presentation', 'Teamwork Framework'),

('UX/UI Designer', 'UX201', 'We are seeking a talented UX/UI Designer to create intuitive and engaging user experiences for our e-commerce platform.', '$1,300 - $1,900 / month', 'Reports to the Head of Design', 'Professional Communication and Presentation', 'Teamwork Framework'),

('Product Listing Specialist', 'PL305', 'We are seeking a detail-oriented Product Listing Specialist to manage product catalogs and optimize listings for our retail platform.', '$1,000 - $1,500 / month', 'Reports to the E-Commerce Operations Manager', 'Professional Communication and Presentation', 'Teamwork Framework');