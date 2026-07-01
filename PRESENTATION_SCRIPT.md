# EMS — Presentation Script

**Video walkthrough script for PALINDROME Employee Management System**
**Duration:** ~15–20 minutes spoken

---

## 1. Introduction

> Welcome to PALINDROME EMS — a web-based Employee Management System built with Laravel. This system is designed to help organizations manage their workforce efficiently — from attendance tracking and leave management to payroll processing and recruitment — all in one platform with role-based access for admins, department heads, and regular staff.

---

## 2. Landing Page & Public Features

> Let's start with the landing page. When you first visit the site, you see this welcome page. It gives visitors an overview of what PALINDROME EMS offers — employee management, attendance tracking, leave management, document storage, payroll processing, and recruitment.

> Visitors can browse available job openings by clicking 'View Open Positions.' This takes them to the public jobs page where all open vacancies are listed with the department name, location, and job type. Anyone can apply for a position by filling out a simple form with their name, email, phone number, cover letter, and optionally upload a resume. No login required.

---

## 3. Authentication

> Now let's look at authentication. Users can log in with their email and password. The login page also has a 'Remember Me' feature and a link to register.

> New employees can't register on their own — they're created by an admin. When an admin adds a new employee, the system generates a secure setup link that gets shared with the employee. When the employee visits this link, they set their own password. This ensures security right from the start.

> There's also a standard registration page for general users. And once logged in, users can change their password anytime from the profile menu.

---

## 4. Navigation & Role-Based Access

> The system has three distinct roles: Superuser, Department Admin, and Staff.

> Let me show you how the navigation changes based on who you are.

> If you're a regular staff member, your navigation is simple — you see My Panel, My Leave, and My Attendance. That's it. You can only manage your own things.

> If you're a Department Admin, you see a full management dashboard with links to Employees, Documents, Leave, Attendance, Payroll, and Recruitment. You manage your entire department from here. There's also a 'My Panel' toggle to switch to your personal view.

> If you're a Superuser, you see everything an admin sees, plus a Settings link for managing departments. Superusers also get a department switcher dropdown — they can jump between any department instantly.

> At the top right, there's a notification bell showing unread notifications, and a user dropdown with links to edit your profile and change your password.

---

## 5. Dashboard

> The admin dashboard gives you a bird's eye view of your department. You see stat cards for:
> - Total Employees and Active Employees
> - Pending Leave Requests and your own pending leaves
> - Today's attendance status and how many people checked in
> - Latest payroll and total payroll records
> - Active job vacancies and total applications
> - Settings access for superusers

> Each card links directly to the relevant management page for quick navigation.

---

## 6. Personal Panel

> Let's see what a regular staff member sees when they log in. The My Panel page shows their personal information — name, email, staff ID, department, phone number, date of birth, passport number, start date, and leave balance.

> There are quick links to My Leave and My Attendance pages. And right on the panel, there's a document upload section where employees can upload their own documents like contracts or ID copies. They can download or delete their documents anytime.

---

## 7. Employee Management

> Now let's look at the admin features, starting with Employee Management.

> The employee list page shows everyone in the department with their name, email, staff ID, phone number, start date, and status. Admins can view full details, edit, or delete employees.

> When adding a new employee, the admin fills in their details — name, email, staff ID, phone, date of birth, passport number, start date, leave balance, and status. The system auto-generates a secure setup link that the admin can share with the new employee to set their password.

> When an admin updates an employee's profile, the employee gets a notification. When an employee is deleted, all department admins are notified.

---

## 8. Attendance System

> The attendance system is one of our core features, and it works in two ways.

> First: the QR code system. Each day, the admin visits the QR page. The system generates a unique 64-character hex token for the day, and renders it as a QR code. The admin displays this QR code — on a screen or printed. Employees scan the QR with their phone camera. The URL opens in their browser, they log in if needed, and they're checked in automatically. The system records whether the check-in was done via QR. There's also a separate check-out QR code that the admin can generate for end of day.

> For employees who are on their computers, we show the same QR code right on their 'My Attendance' page. They can scan it with their phone or simply click the manual Check In and Check Out buttons.

> The admin can view attendance for any date — seeing who checked in, at what time, and whether it was done normally or via QR. Each record has a status — Present, Late, or Absent.

> There's also a monthly summary view that shows each employee's attendance history — how many days they were present, late, or absent in any given month.

> And admins can manually mark attendance for any employee on any date, making corrections or backdating entries as needed.

---

## 9. Leave Management

> The leave management system handles the complete lifecycle of leave requests.

> Staff members can request leave by selecting start and end dates and optionally adding a reason. The system checks for conflicts — it prevents employees from requesting leave on dates they already have approved leave, and it also prevents overlapping pending requests.

> Once submitted, the leave goes to the department admin with a 'Pending' status. The employee gets a notification confirming their request was submitted, and all department admins are notified about the new request.

> Admins see all leave requests in a table. They can approve, decline, or edit leaves. When a leave is approved, the system automatically adds the dates to the employee's leave record and deducts from their leave balance. When declined, it reverses those changes if the leave was previously approved.

> Employees can edit their pending leaves, but once approved or declined, no further changes are allowed from their side.

> All of this is tracked with notifications — employees are notified when their leave status changes.

---

## 10. Document Management

> Documents are managed from two places.

> From the admin side, the Documents page shows every document uploaded by anyone in the department. Admins can upload documents for any employee, and the employee gets notified. Admins can also download or delete documents.

> From the staff side, employees upload their own documents through their personal panel. When they do, all department admins get notified.

> Supported file types include PDFs, Word documents, Excel sheets, and images — up to 10 megabytes.

---

## 11. Payroll System

> The payroll system handles salary calculation month by month.

> First, admins set each employee's basic salary with an effective from date. This allows salary changes over time while keeping a history.

> For each payroll month, admins can add bonuses or deductions for individual employees — each with a description.

> When ready, the admin clicks 'Calculate Payroll.' The system automatically:
> - Looks up each employee's latest basic salary
> - Sums all bonuses for that month
> - Sums all deductions for that month
> - Calculates net salary as basic plus bonuses minus deductions

> The system creates a payroll record for each employee, and the admin can download individual payslips as PDF files.

---

## 12. Recruitment System

> The recruitment module handles the entire hiring pipeline.

> Admins can create job vacancies with a title, description, location, employment type, closing date, and open or closed status.

> On the public side, visitors browse open vacancies on the jobs page and submit applications online. Each application includes the candidate's name, email, phone, cover letter, and optional resume.

> Back in the admin panel, the admin can view all applications for a vacancy, update the candidate's status through the pipeline — New, Reviewing, Interview, Selected, or Rejected. Admins can also schedule interviews for candidates, with date, type, location, and notes. Multiple interviews can be scheduled for the same candidate.

---

## 13. Notification System

> The notification system keeps everyone informed. Every important action generates a notification:
> - Leave requests submitted, approved, or declined
> - Profile updated by admin
> - Documents uploaded — whether by admin or employee
> - Employee account created

> Notifications appear in the bell icon at the top right with an unread count. Clicking a notification marks it as read and takes you directly to the relevant page — for example, clicking a leave notification takes you straight to that leave request. The user can also mark individual notifications as read or mark all as read at once.

---

## 14. Department Management (Superuser)

> Superusers have access to Settings, where they can manage departments. They can create new departments with a name and optionally assign a department head. When someone is assigned as department head, they automatically get admin privileges for that department.

> Superusers can edit department names or change the department head, and if the head changes, the old head's admin status is automatically removed. Departments can also be deleted, which unassigns all employees from that department.

---

## 15. Profile & Password Management

> Users can edit their own profile — updating their name, phone number, date of birth, passport number, and staff ID. Email cannot be changed for security reasons. And from a separate page, users can change their password by entering their current password first.

---

## 16. Technology Stack

> The system is built with Laravel on the backend using PostgreSQL as the database, hosted on Neon. For the frontend, we use Blade templates with Tailwind CSS and Vite for asset bundling. API endpoints use Laravel Sanctum for token-based authentication. QR codes are rendered client-side using the qrcodejs library. The entire application follows MVC architecture with proper validation through Form Request classes and middleware-based authorization.

---

## 17. Closing

> That wraps up our tour of PALINDROME EMS. To summarize, the system covers:
> - Role-based access with three user tiers
> - Daily attendance via QR scanning or manual check-in
> - Complete leave lifecycle management
> - Employee document management
> - Monthly payroll with automatic calculations and PDF payslips
> - Full recruitment pipeline from job posting to candidate management
> - And a real-time notification system keeping everyone in the loop

> Thank you for watching.
