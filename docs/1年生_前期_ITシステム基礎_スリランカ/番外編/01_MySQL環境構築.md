# MySQL පරිසරය සකස් කිරීම

> 🇯🇵 මුල් පිටපත (ජපන් භාෂාවෙන්): [01_MySQL環境構築.md](<../../1年生_前期_ITシステム基礎/番外編/01_MySQL環境構築.md>) <!-- original-ja-link -->

## මෙම පාඩමේ ඉලක්ක
- තමන්ගේ OS එකට ගැළපෙන විදිහට MySQL ආරම්භ කිරීමට හැකි වීම
- `todo_app` database එක සෑදීමට හැකි වීම
- Python වලින් MySQL වලට සම්බන්ධ වීමට අවශ්‍ය තොරතුරු (connection string) තේරුම් ගැනීම

---

## OS එක අනුව පියවර වෙනස් වෙනවා

| OS | භාවිත කරන tool එක | විශේෂත්වය |
|----|-----------|------|
| Windows | XAMPP | GUI එකෙන් මෙහෙයවන්න පුළුවන් නිසා ආරම්භකයින්ට සුදුසුයි |
| Mac | Homebrew + MySQL | Terminal එකෙන් මෙහෙයවන සම්මත ක්‍රමය |

---

## Windows — XAMPP භාවිත කිරීම

### 1. XAMPP download කර install කිරීම

1. [https://www.apachefriends.org/jp/index.html](https://www.apachefriends.org/jp/index.html) විවෘත කරන්න
2. "XAMPP for Windows" download කරන්න
3. Installer එක run කරන්න (install කරන ස්ථානය default `C:\xampp` ලෙස තිබුණාට කමක් නැහැ)

### 2. MySQL ආරම්භ කිරීම

1. Start menu එකෙන් "XAMPP Control Panel" ආරම්භ කරන්න
2. `MySQL` පේළියේ "Start" button එක click කරන්න
3. කොළ පාට වුවහොත් සාර්ථකව ආරම්භ වී ඇත

```
┌─────────────────────────────────────┐
│  XAMPP Control Panel                │
│                                     │
│  Apache  [Start] [Admin] ...        │
│  MySQL   [Start] [Admin] ...  ← මෙතන│
│  ...                                │
└─────────────────────────────────────┘
         ↓ Start ඔබන්න

│  MySQL   [Stop]  [Admin] ...  ← කොළ පාට වුණොත් හරි
```

### 3. Database එක සෑදීම (phpMyAdmin)

1. Browser එකේ `http://localhost/phpmyadmin` විවෘත කරන්න
2. වම් menu එකේ ඉහළ ඇති "New" click කරන්න
3. Database නම ලෙස `todo_app` ඇතුළත් කරන්න
4. Collation (අක්ෂර කේතනය) ලෙස `utf8mb4_unicode_ci` තෝරන්න
5. "Create" button එක click කරන්න

```
phpMyAdmin තිරය
┌─────────────────────────────────────────────┐
│  Create database:                           │
│  [todo_app        ] [utf8mb4_unicode_ci ▼]  │
│                                   [Create]  │
└─────────────────────────────────────────────┘
```

### 4. සම්බන්ධතා තොරතුරු පරීක්ෂා කිරීම

XAMPP හි default සැකසුම් අනුව මේවා පහත පරිදි වේ.

| අයිතමය | අගය |
|------|-----|
| Host | localhost |
| User | root |
| Password | (හිස්ව) |
| DB නම | todo_app |

**`database.py` හි connection string එක (Windows නම්):**

```python
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
#                                   ↑ Password නැත (colon එකට පසුව හිස්)
```

---

## Mac — Homebrew + MySQL භාවිත කිරීම

### 1. Homebrew install කිරීම (තවම install කර නැතිනම්)

Terminal එක විවෘත කර පහත දේ run කරන්න:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Install වුණාද කියලා පරීක්ෂා කිරීම:

```bash
brew --version
# Homebrew 4.x.x වගේ දෙයක් පෙන්නුවොත් හරි
```

### 2. MySQL install කිරීම

```bash
brew install mysql
```

### 3. MySQL ආරම්භ කිරීම

```bash
# MySQL ආරම්භ කරන්න
brew services start mysql

# ආරම්භ වුණාද කියලා පරීක්ෂා කරන්න
brew services list
# mysql  started  ... කියලා පෙන්නුවොත් හරි
```

### 4. මූලික සැකසුම් (password සැකසීම)

```bash
mysql_secure_installation
```

ප්‍රශ්නවලට පිළිතුරු දෙමින් යන්න:

```
VALIDATE PASSWORD component: No (password ශක්තිය පරීක්ෂා කිරීම. මුල් වතාවේ n දුන්නාට කමක් නැහැ)
New password: (කැමති password එකක් ඇතුළත් කරන්න → අමතක නොවෙන්න ලියා තබා ගන්න!)
Re-enter new password: (එම password එකම නැවත ඇතුළත් කරන්න)
Remove anonymous users: Yes (y ඇතුළත් කරන්න)
Disallow root login remotely: Yes (y ඇතුළත් කරන්න)
Remove test database: Yes (y ඇතුළත් කරන්න)
Reload privilege tables: Yes (y ඇතුළත් කරන්න)
```

### 5. Database එක සෑදීම

```bash
# MySQL වලට login වෙන්න (සැකසූ password එක ඇතුළත් කරන්න)
mysql -u root -p

# Login සාර්ථක වුණොත් පහත prompt එක පෙන්වයි
# mysql>
```

MySQL prompt එකේ පහත දේ run කරන්න:

```sql
-- Database එක සාදන්න
CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- හැදුණාද කියලා පරීක්ෂා කරන්න
SHOW DATABASES;
-- todo_app පෙන්නුවොත් හරි

-- MySQL වලින් ඉවත් වෙන්න
EXIT;
```

### 6. සම්බන්ධතා තොරතුරු පරීක්ෂා කිරීම

| අයිතමය | අගය |
|------|-----|
| Host | localhost |
| User | root |
| Password | පියවර 4 දී සැකසූ එක |
| DB නම | todo_app |

**`database.py` හි connection string එක (Mac නම්):**

```python
DATABASE_URL = "mysql+pymysql://root:ඔබගේ_password@localhost/todo_app"
#                                   ↑ පියවර 4 දී සැකසූ password එකෙන් ප්‍රතිස්ථාපනය කරන්න
```

---

## MySQL මූලික විධාන (යොමුව සඳහා)

| ක්‍රියාව | Windows (XAMPP Shell) | Mac (Terminal) |
|------|----------------------|----------------|
| MySQL login | XAMPP Control Panel → Shell → `mysql -u root` | `mysql -u root -p` |
| Database ලැයිස්තුව | `SHOW DATABASES;` | `SHOW DATABASES;` |
| Table ලැයිස්තුව | `USE todo_app; SHOW TABLES;` | `USE todo_app; SHOW TABLES;` |
| MySQL නැවැත්වීම | Control Panel → Stop | `brew services stop mysql` |

---

## ගැටලු විසඳීම

**"සම්බන්ධ වෙන්න බැහැ" කියලා පෙන්වන විට**

- Windows: XAMPP Control Panel එකේ MySQL "Running (කොළ)" තත්ත්වයේ තියෙනවාද කියලා බලන්න
- Mac: `brew services list` එකේ mysql `started` ලෙස තියෙනවාද කියලා බලන්න

**"Access denied for user 'root'" කියලා පෙන්වන විට**

- Windows: `database.py` එකේ password එක හිස්ව තියෙනවාද කියලා බලන්න (`root:@localhost`)
- Mac: Password එක නිවැරදිව ඇතුළත් කරලා තියෙනවාද කියලා බලන්න

---

> පෙර පියවර → [00_概要とシステム設計.md](./00_概要とシステム設計.md)
> ඊළඟ පියවර → [02_Python_OOP入門.md](./02_Python_OOP入門.md)
