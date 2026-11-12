# Setting Up MySQL

> 🇯🇵 Original (Japanese version): [01_MySQL環境構築.md](<../../1年生_前期_ITシステム基礎/番外編/01_MySQL環境構築.md>) <!-- original-ja-link -->

## Goals for This Lesson
- Start MySQL using the appropriate method for your OS
- Create the `todo_app` database
- Understand the connection string needed to connect to MySQL from Python

---

## The Steps Differ by OS

| OS | Tool | Features |
|----|-----------|------|
| Windows | XAMPP | GUI-based and beginner-friendly |
| Mac | Homebrew + MySQL | The standard approach, using the terminal |

---

## Windows — Using XAMPP

### 1. Download and Install XAMPP

1. Open [https://www.apachefriends.org/jp/index.html](https://www.apachefriends.org/jp/index.html)
2. Download "XAMPP for Windows"
3. Run the installer (the default install location `C:\xampp` is fine)

### 2. Start MySQL

1. Launch "XAMPP Control Panel" from the Start menu
2. Click the "Start" button on the `MySQL` row
3. If the MySQL row turns green, MySQL has started successfully

```
┌─────────────────────────────────────┐
│  XAMPP Control Panel                │
│                                     │
│  Apache  [Start] [Admin] ...        │
│  MySQL   [Start] [Admin] ...  ← here│
│  ...                                │
└─────────────────────────────────────┘
         ↓ Press Start

│  MySQL   [Stop]  [Admin] ...  ← OK once it's green
```

### 3. Create the Database (phpMyAdmin)

1. Open `http://localhost/phpmyadmin` in your browser
2. Click "New" at the top of the left menu
3. Enter `todo_app` as the database name
4. Select `utf8mb4_unicode_ci` as the collation
5. Click the "Create" button

```
phpMyAdmin screen
┌─────────────────────────────────────────────┐
│  Create database:                           │
│  [todo_app        ] [utf8mb4_unicode_ci ▼]  │
│                                   [Create]  │
└─────────────────────────────────────────────┘
```

### 4. Check the Connection Information

XAMPP's default connection settings are as follows.

| Item | Value |
|------|-----|
| Host | localhost |
| User | root |
| Password | (blank) |
| DB name | todo_app |

**Connection string in `database.py` (for Windows):**

```python
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
#                                   ↑ no password (nothing after the colon)
```

---

## Mac — Using Homebrew + MySQL

### 1. Install Homebrew (If Not Installed Yet)

Open the terminal and run the following:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Check the installation:

```bash
brew --version
# OK if it shows something like Homebrew 4.x.x
```

### 2. Install MySQL

```bash
brew install mysql
```

### 3. Start MySQL

```bash
# Start MySQL
brew services start mysql

# Check that it started
brew services list
# OK if it shows mysql  started  ...
```

### 4. Initial Setup (Setting a Password)

```bash
mysql_secure_installation
```

Answer the questions as you go:

```
VALIDATE PASSWORD component: No (password strength check; n is fine the first time)
New password: (enter any password → write it down so you don't forget!)
Re-enter new password: (enter the same password again)
Remove anonymous users: Yes (enter y)
Disallow root login remotely: Yes (enter y)
Remove test database: Yes (enter y)
Reload privilege tables: Yes (enter y)
```

### 5. Create the Database

```bash
# Log in to MySQL (enter the password you set)
mysql -u root -p

# When login succeeds, the following prompt appears
# mysql>
```

At the MySQL prompt, run the following:

```sql
-- Create the database
CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Check that it was created
SHOW DATABASES;
-- OK if todo_app is shown

-- Exit MySQL
EXIT;
```

### 6. Check the Connection Information

| Item | Value |
|------|-----|
| Host | localhost |
| User | root |
| Password | The one you set in Step 4 |
| DB name | todo_app |

**Connection string in `database.py` (for Mac):**

```python
DATABASE_URL = "mysql+pymysql://root:your_password@localhost/todo_app"
#                                   ↑ replace this with the password you set in Step 4
```

---

## Basic MySQL Commands (Reference)

| Action | Windows (XAMPP Shell) | Mac (terminal) |
|------|----------------------|----------------|
| Log in to MySQL | XAMPP Control Panel → Shell → `mysql -u root` | `mysql -u root -p` |
| List databases | `SHOW DATABASES;` | `SHOW DATABASES;` |
| List tables | `USE todo_app; SHOW TABLES;` | `USE todo_app; SHOW TABLES;` |
| Stop MySQL | Control Panel → Stop | `brew services stop mysql` |

---

## Troubleshooting

**If you see "can't connect"**

- Windows: Check that MySQL shows "Running (green)" in the XAMPP Control Panel
- Mac: Check that mysql shows `started` in `brew services list`

**If you see "Access denied for user 'root'"**

- Windows: Check that the password in `database.py` is empty (`root:@localhost`)
- Mac: Check that the password is entered correctly

---

> Previous step → [00_概要とシステム設計.md](./00_概要とシステム設計.md)
> Next step → [02_Python_OOP入門.md](./02_Python_OOP入門.md)
