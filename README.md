# Programming Language Lookup Database

A web-based reference tool for browsing programming language syntax, functions, operators, and data structures. Built with PHP, MySQL, HTML, CSS, and JavaScript.

**Live site:** mbarbrack.rhody.dev/Programming_Language_Lookup/

---

## Project Structure

```
Programming_Language_Lookup/
    index.php               # Public browse and compare interface
    .env                    # Environment variables and forms
    credentials (not committed)
    includes/
        database-connection.php   # PDO connection setup
    admin/
        login.php           # Admin login page
        index.php           # Admin dashboard with insert 
        logout.php          # Session destroy and redirect
```

---

## Features

**Public side**
- Browse functions, operators, and data structures by language, category, and sort order
- Compare two languages side by side for any selected item
- Results rendered dynamically via JavaScript fetch requests to PHP endpoints

**Admin side**
- Session-based login protected by credentials stored in a .env file
- Insert new languages, functions, operators, data structures, and implementations
- Dropdowns populated from the live database
- Multi-step inserts wrapped in transactions to prevent partial data

---

## Database

- Database: mbarbrac_CSC436_Project
- Public user: mbarbrac_webpage_user (SELECT only)
- Admin user: mbarbrac_webpage_admin (ALL PRIVILEGES)

**Tables:** language, function_table, operator, data_structure, implementation, function_implementation, operator_implementation, structure_implementation

**Views:** vw_function_lookup, vw_operator_lookup, vw_structure_lookup

---

## Setup

### Local (XAMPP)

1. Place the Programming_Language_Lookup folder in `C:\xampp\htdocs\`
2. Create a `.env` file in the Programming_Language_Lookup root using the template below
3. Start XAMPP and visit `http://localhost/Programming_Language_Lookup/index.php`

### cPanel Deployment

1. Upload all files to `public_html/Programming_Language_Lookup/` via cPanel File Manager
2. Make sure `.env` is in the Programming_Language_Lookup root
3. Visit `mbarbrack.rhody.dev/Programming_Language_Lookup/index.php`

---

## .env Template

```
DB_TYPE=mysql
DB_SERVER=000.000.0.000
DB_NAME=mbarbrac_CSC436_Project
DB_PORT=3306
DB_CHARSET=utf8mb4

DB_USERNAME=mbarbrac_webpage_user
DB_PASSWORD=your_public_password

ADMIN_DB_USERNAME=mbarbrac_webpage_admin
ADMIN_DB_PASSWORD=your_admin_password

ADMIN_USERNAME=admin
ADMIN_PASSWORD=your_admin_password
```

Do not commit the .env file to version control.

---

## Security

- All queries use PDO prepared statements with bound parameters
- PDO configured with ATTR_EMULATE_PREPARES set to false
- All output passed through htmlspecialchars before rendering
- Admin routes check for an active session before rendering any content
- Two separate database users limit access by role

---

## Group Members

- Matt Barbrack
- Matt Petrarca
- Hudson Byers
- Ryan Megna
- Victor Paulino