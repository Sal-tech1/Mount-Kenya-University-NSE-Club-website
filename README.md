# Mount Kenya University — NSE MKU Club Platform

Welcome to the official web development repository for the **Nairobi Securities Exchange (NSE) MKU Club** website. This platform serves as an interactive educational hub, virtual trading practice environment, and digital resource center for club members and aspiring retail investors.

---

## 1. Project Overview & Features

Our goal is to build an interactive, multi-tiered web platform featuring:
*   **Public Hub:** Homepage with introductory video, Club History, Executive Committee profiles, and Gallery.
*   **Learning Hub:** Three structured modules (**Beginner, Intermediate, Advanced**) with interactive quizzes and course completion features.
*   **Resource Centre:** A digital library hosting recorded webinars, meeting minutes, investment books, and official club documents.
*   **Virtual Portfolio Tracker:** A practice environment for users to log and monitor hypothetical NSE stock purchases.
*   **Dashboards:** Real-time/summarized market overviews and economic indicators (e.g., GDP growth rates).
*   **Membership Portal:** Differentiated access for registered club members vs. non-registered public users.

---

## 2. Directory Structure

To keep our codebase organized and prevent conflicts, work is divided into specific modular folders:

```text
/Mount-Kenya-University-NSE-Club-website
├── .gitignore                 # Files excluded from Git tracking
├── config.example.php         # Safe database connection template
├── index.php                  # Main public landing page
├── README.md                  # Team onboarding & workflow guide
├── /assets
│   ├── /css                   # Stylesheets (Brand theme & colors)
│   ├── /js                    # Front-end interactive scripts
│   └── /images                # Logos, team portraits, gallery photos
├── /includes
│   ├── db.php                 # Centralized PDO database wrapper
│   ├── header.php             # Shared site navigation & header
│   └── footer.php             # Shared universal footer
├── /modules
│   ├── /learning              # Structured courses & interactive quiz UI
│   ├── /resources             # Digital library & document repository
│   ├── /dashboards            # Economic & stock market indicators
│   └── /portal                # Member login, registration & profile views
└── /sql
    └── schema.sql             # Relational MySQL database structure


## 3. Team Work Division & Responsibilities

To leverage our collective strengths efficiently, development tasks are partitioned as follows:

### **Salem Nyoike** — Technical Lead & Full-Stack Architect
*   **Primary Scope:** Core systems, backend logic, database, and repository management.
*   **Key Responsibilities:**
    *   Manage Git workflow, review Pull Requests, and maintain codebase integrity.
    *   Design and update the MySQL schema (`/sql/schema.sql`).
    *   Develop the centralized PDO connection (`/includes/db.php`).
    *   Build authentication, session controls, and security for the Membership Portal (`/modules/portal/`).
    *   Connect database queries to front-end UI modules.

### **Malcolm Ajore** — Front-End Developer (Public & Brand Identity)
*   **Primary Scope:** Public-facing pages and visual branding.
*   **Key Responsibilities:**
    *   Build the main landing page (`index.php`), including the promotional video player container.
    *   Design the **Club History** and **Executive Committee Profiles** pages.
    *   Build the **Gallery** layout and integrate social media/Telegram banners.
    *   Maintain the primary stylesheet (`/assets/css/style.css`) using official NSE MKU Club brand colors.

### **Lazaro Otieno** — Front-End Developer (Learning Hub & Quizzes)
*   **Primary Scope:** Educational modules and interactive testing UI.
*   **Key Responsibilities:**
    *   Design the user interface for the **Beginner, Intermediate, and Advanced** learning tiers (`/modules/learning/index.php`).
    *   Build the interactive **Quiz UI** (`quiz.php`), including question cards, radio selection inputs, and score displays.
    *   Ensure learning layouts are clean, readable, and responsive across mobile devices.

### **Roy Omollo & Supporting Team** — Front-End & Content Integration
*   **Primary Scope:** Digital library, dashboards, and content management.
*   **Key Responsibilities:**
    *   Build the **Resource Centre** UI (`/modules/resources/index.php`) for PDF downloads, books, and financial statements.
    *   Build the front-end interface for the **Market & Economic Dashboards** (`/modules/dashboards/index.php`) using clean layout cards and static charts.
    *   Format written club educational materials and meeting minutes into standard HTML/PHP templates.

---

## 4. Local Setup Instructions

Follow these exact steps to run the application on your local development machine:

1.  **Clone the Repository:**
    ```bash
    git clone [https://github.com/Sal-tech1/Mount-Kenya-University-NSE-Club-website.git](https://github.com/Sal-tech1/Mount-Kenya-University-NSE-Club-website.git)
    cd Mount-Kenya-University-NSE-Club-website
    ```

2.  **Configure Environment Credentials:**
    *   Copy the `config.example.php` template to create your local config file:
        *   **Windows:** `copy config.example.php config.php`
        *   **Mac/Linux:** `cp config.example.php config.php`
    *   Open `config.php` and update the `$host`, `$dbname`, `$username`, and `$password` variables to match your local PHP/MySQL environment (e.g., XAMPP, WAMP, or MAMP).

3.  **Setup the Database:**
    *   Open your local database manager (e.g., phpMyAdmin).
    *   Create a new database matching the `$dbname` you set in `config.php`.
    *   Import the `/sql/schema.sql` file to generate the required tables.

> ⚠️ **SECURITY WARNING:** **NEVER** modify `config.example.php` with real or live server passwords, and **NEVER** force-add `config.php` to Git. The `.gitignore` file is configured to block `config.php` automatically to protect our database credentials.

---

## 5. Mandatory Git Branching Workflow

**Do not push directly to the `main` branch.** All changes must go through a Pull Request (PR) reviewed by the Technical Lead.

1.  **Always pull the latest changes before starting work:**
    ```bash
    git checkout main
    git pull origin main
    ```

2.  **Create a dedicated feature branch for your task:**
    ```bash
    # Example: git checkout -b feature/learning-hub-ui
    git checkout -b feature/your-feature-name
    ```

3.  **Stage and commit your work with descriptive messages:**
    ```bash
    git add .
    git commit -m "Add responsive grid layout to the Resource Centre"
    ```

4.  **Push your feature branch to GitHub:**
    ```bash
    git push origin feature/your-feature-name
    ```

5.  **Open a Pull Request:**
    *   Go to the repository on GitHub.
    *   Click **Compare & pull request** next to your pushed branch.
    *   Add a brief description of what you built and tag **Salem Nyoike** for review and merging. 
