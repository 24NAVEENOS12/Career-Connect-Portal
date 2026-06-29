# 🚀 Career Connect Portal

<p align="center">

<img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white"/>

<img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>

<img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white"/>

<img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white"/>

<img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white"/>

<img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black"/>

</p>

<p align="center">

A modern **Full Stack Recruitment Portal** that connects **Candidates** and **Companies** through an easy-to-use recruitment platform.

Companies can publish opportunities, manage applicants and update recruitment status while candidates can build professional profiles, upload resumes, apply for jobs and track their applications.

</p>

---

# 🌐 Live Demo

**Website**

https://careerconnectportal.infinityfreeapp.com

---

# 📑 Table of Contents

* Project Overview
* Key Features
* Candidate Module
* Company Module
* Dashboard Features
* Technology Stack
* Project Architecture
* Folder Structure
* Recruitment Workflow
* Database
* Installation Guide
* Deployment
* Future Enhancements
* Screenshots
* Developer
* License

---

# 📖 Project Overview

Career Connect Portal is a web-based recruitment platform developed using **PHP, MySQL, Bootstrap, HTML, CSS and JavaScript**.

The portal provides two independent modules:

### 👨‍🎓 Candidate

* Register account
* Build profile
* Upload resume
* Apply for opportunities
* Track applications

### 🏢 Company

* Register company
* Post opportunities
* View applicants
* Download resumes
* Select candidates
* Manage recruitment

---

# ✨ Key Features

## ✅ Authentication

* Candidate Registration
* Company Registration
* Secure Login
* Logout
* Session Management
* Password Encryption

---

## 👨‍🎓 Candidate Module

### 1. Registration

* Create Candidate Account
* Secure Password Storage

### 2. Login

* Secure Authentication
* Session Handling

### 3. Profile

* Edit Profile
* Upload Profile Photo
* Upload Resume
* Personal Information
* Education
* Skills
* Portfolio Links

### 4. Dashboard

* Applied Opportunities
* Selected Count
* Under Review Count
* Shortlisted Count
* Rejected Count
* Circular Profile Completion
* Recent Applications

### 5. Opportunities

* Browse Opportunities
* Search Opportunities
* Filter by Type
* Sort by Latest
* Sort by Oldest

### 6. Applications

Track Status

* Applied
* Under Review
* Shortlisted
* Selected
* Rejected

---

# 🏢 Company Module

### 1. Registration

* Company Registration
* Contact Person

### 2. Profile

* Company Logo
* Cover Image
* About Company
* Website
* Industry
* Location

### 3. Dashboard

* Total Opportunities
* Total Vacancies
* Total Applicants
* Total Selected
* Recent Opportunities
* Recent Applicants

### 4. Opportunities

* Create Opportunity
* Edit Opportunity
* Close Opportunity
* Delete Opportunity

### 5. Applicant Management

Company can update applicant status:

* Under Review
* Shortlisted
* Selected
* Rejected

### 6. Resume

* View Resume
* Download Resume

---

# 📊 Dashboard Features

## Candidate Dashboard

✔ Circular Profile Completion

✔ Statistics Cards

✔ Recent Applications

✔ Recommended Opportunities

---

## Company Dashboard

✔ Statistics Cards

✔ Recent Opportunities

✔ Recent Applicants

✔ Recruitment Analytics

---

# 🎨 User Interface

* Responsive Design
* Modern Dashboard
* Bootstrap Layout
* Glassmorphism Cards
* Animated Buttons
* Status Badges
* Gradient Icons
* Circular Progress Indicator
* Responsive Sidebar

---

# 🛠 Technology Stack

| Category        | Technology     |
| --------------- | -------------- |
| Frontend        | HTML5          |
| Styling         | CSS3           |
| Framework       | Bootstrap 5    |
| Icons           | Font Awesome   |
| Scripting       | JavaScript     |
| Backend         | PHP            |
| Database        | MySQL          |
| Server          | Apache (XAMPP) |
| Hosting         | InfinityFree   |
| Version Control | Git & GitHub   |

---

# 🏗 Project Architecture

```
Candidate
        │
        ▼
Candidate Portal
        │
        ▼
PHP Backend
        │
        ▼
MySQL Database
        ▲
        │
Company Portal
        │
        ▼
Company
```

---

# 📂 Folder Structure

```
Career-Connect-Portal

├── assets
│
├── auth
│
├── candidate
│
├── company
│
├── config
│
├── includes
│
├── uploads
│
├── index.php
│
└── README.md
```

---

# 🔄 Recruitment Workflow

```
Candidate Registration

        │

        ▼

Complete Profile

        │

        ▼

Browse Opportunities

        │

        ▼

Apply

        │

        ▼

Company Reviews

        │

        ▼

Under Review

        │

        ▼

Shortlisted

        │

        ▼

Selected / Rejected
```

---

# 🗄 Database

The project uses MySQL with tables including:

* users
* candidate_profiles
* candidate_education
* candidate_skills
* companies
* opportunities
* applications
* rejected_opportunities

---

# ⚙ Installation Guide

## Step 1

Clone Repository

```bash
git clone https://github.com/24NAVEENOS12/Career-Connect-Portal.git
```

---

## Step 2

Move project into

```
xampp/htdocs/
```

---

## Step 3

Start

* Apache
* MySQL

---

## Step 4

Open

```
http://localhost/phpmyadmin
```

Create Database

```
careerconnectportal
```

---

## Step 5

Import

```
careerconnectportal.sql
```

---

## Step 6

Configure

```
config/database.php
```

---

## Step 7

Run

```
http://localhost/Career-Connect-Portal/
```

---

# ☁ Deployment

Hosted using

* InfinityFree
* MySQL Database
* GitHub

---

# 📷 Screenshots

Replace these with your project screenshots.

* Home Page
* Candidate Dashboard
* Company Dashboard
* Opportunities
* Applicants
* Candidate Profile
* Company Profile
* Login Page

---

# 🚀 Future Enhancements

* Admin Panel
* Email Notifications
* OTP Authentication
* Google Login
* AI Resume Screening
* AI Job Recommendation
* Interview Scheduling
* Chat Module
* Company Verification
* Candidate Skill Tests

---

# 👨‍💻 Developer

**Naveen O. S**

Full Stack Developer

GitHub:

https://github.com/24NAVEENOS12

---

# 📄 License

This project is developed for educational and internship purposes.

---

# ⭐ Support

If you found this project useful,

please consider giving it a ⭐ on GitHub.

Your support is greatly appreciated!
