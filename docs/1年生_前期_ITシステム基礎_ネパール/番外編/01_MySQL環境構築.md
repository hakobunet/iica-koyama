# MySQL वातावरण तयारी

> 🇯🇵 मूल प्रति (जापानी भाषामा): [01_MySQL環境構築.md](<../../1年生_前期_ITシステム基礎/番外編/01_MySQL環境構築.md>) <!-- original-ja-link -->

## यस पटकको लक्ष्य
- आफ्नो OS अनुसार MySQL सुरु गर्न सक्ने
- `todo_app` डेटाबेस बनाउन सक्ने
- Python बाट MySQL मा जोडिन चाहिने जानकारी (कनेक्सन स्ट्रिङ) बुझ्ने

---

## OS अनुसार तरिका फरक हुन्छ

| OS | प्रयोग गर्ने टुल | विशेषता |
|----|-----------|------|
| Windows | XAMPP | GUI बाट चलाउन सकिने, सुरुवाती विद्यार्थीका लागि उपयुक्त |
| Mac | Homebrew + MySQL | टर्मिनलबाट चलाउने सामान्य (मानक) तरिका |

---

## Windows — XAMPP प्रयोग गर्ने

### 1. XAMPP डाउनलोड र इन्स्टल गर्ने

1. [https://www.apachefriends.org/jp/index.html](https://www.apachefriends.org/jp/index.html) खोल्ने
2. "XAMPP for Windows" डाउनलोड गर्ने
3. इन्स्टलर चलाउने (इन्स्टल गर्ने ठाउँ डिफल्ट `C:\xampp` नै भए हुन्छ)

### 2. MySQL सुरु गर्ने

1. स्टार्ट मेनुबाट "XAMPP Control Panel" सुरु गर्ने
2. `MySQL` को लाइनमा रहेको "Start" बटन क्लिक गर्ने
3. हरियो भयो भने सफलतापूर्वक सुरु भएको हो

```
┌─────────────────────────────────────┐
│  XAMPP Control Panel                │
│                                     │
│  Apache  [Start] [Admin] ...        │
│  MySQL   [Start] [Admin] ...  ← यहाँ │
│  ...                                │
└─────────────────────────────────────┘
         ↓ Start थिच्ने

│  MySQL   [Stop]  [Admin] ...  ← हरियो भयो भने ठीक छ
```

### 3. डेटाबेस बनाउने (phpMyAdmin)

1. ब्राउजरमा `http://localhost/phpmyadmin` खोल्ने
2. बायाँ मेनुको माथिपट्टि रहेको 「新規作成」(New) क्लिक गर्ने
3. डेटाबेसको नाममा `todo_app` लेख्ने
4. कोलेसन (अक्षर कोड) मा `utf8mb4_unicode_ci` छान्ने
5. 「作成」(Create) बटन क्लिक गर्ने

```
phpMyAdmin स्क्रिन
┌─────────────────────────────────────────────┐
│  नयाँ डेटाबेस बनाउने:                         │
│  [todo_app        ] [utf8mb4_unicode_ci ▼]  │
│                                   [作成]    │
└─────────────────────────────────────────────┘
```

### 4. जडान जानकारी जाँच्ने

XAMPP को डिफल्ट सेटिङमा यस्तो हुन्छ।

| विषय | मान |
|------|-----|
| होस्ट | localhost |
| प्रयोगकर्ता | root |
| पासवर्ड | (खाली) |
| DB को नाम | todo_app |

**`database.py` को कनेक्सन स्ट्रिङ (Windows को हकमा):**

```python
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
#                                   ↑ पासवर्ड छैन (कोलनपछि खाली)
```

---

## Mac — Homebrew + MySQL प्रयोग गर्ने

### 1. Homebrew इन्स्टल गर्ने (इन्स्टल गरिएको छैन भने)

टर्मिनल खोलेर तलको कमान्ड चलाउने:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

इन्स्टल भयो कि भएन जाँच्ने:

```bash
brew --version
# Homebrew 4.x.x जस्तो देखियो भने ठीक छ
```

### 2. MySQL इन्स्टल गर्ने

```bash
brew install mysql
```

### 3. MySQL सुरु गर्ने

```bash
# MySQL सुरु गर्ने
brew services start mysql

# सुरु भयो कि भएन जाँच्ने
brew services list
# mysql  started  ... देखियो भने ठीक छ
```

### 4. प्रारम्भिक सेटिङ (पासवर्ड सेट गर्ने)

```bash
mysql_secure_installation
```

सोधिएका प्रश्नहरूको जवाफ दिँदै जानुहोस्:

```
VALIDATE PASSWORD component: No (पासवर्डको बलियोपन जाँच। पहिलो पटक n भए हुन्छ)
New password: (आफूले चाहेको पासवर्ड लेख्ने → नबिर्सन नोट गर्नुहोस्!)
Re-enter new password: (उही पासवर्ड फेरि लेख्ने)
Remove anonymous users: Yes (y लेख्ने)
Disallow root login remotely: Yes (y लेख्ने)
Remove test database: Yes (y लेख्ने)
Reload privilege tables: Yes (y लेख्ने)
```

### 5. डेटाबेस बनाउने

```bash
# MySQL मा लगइन गर्ने (सेट गरेको पासवर्ड लेख्ने)
mysql -u root -p

# लगइन सफल भएमा तलको प्रम्प्ट देखिन्छ
# mysql>
```

MySQL को प्रम्प्टमा तलको कमान्ड चलाउने:

```sql
-- डेटाबेस बनाउने
CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- बन्यो कि बनेन जाँच्ने
SHOW DATABASES;
-- todo_app देखियो भने ठीक छ

-- MySQL बाट बाहिर निस्कने
EXIT;
```

### 6. जडान जानकारी जाँच्ने

| विषय | मान |
|------|-----|
| होस्ट | localhost |
| प्रयोगकर्ता | root |
| पासवर्ड | चरण 4 मा सेट गरेको पासवर्ड |
| DB को नाम | todo_app |

**`database.py` को कनेक्सन स्ट्रिङ (Mac को हकमा):**

```python
DATABASE_URL = "mysql+pymysql://root:तपाईंको_पासवर्ड@localhost/todo_app"
#                                   ↑ चरण 4 मा सेट गरेको पासवर्डले बदल्ने
```

---

## MySQL का आधारभूत कमान्डहरू (सन्दर्भका लागि)

| काम | Windows (XAMPP Shell) | Mac (टर्मिनल) |
|------|----------------------|----------------|
| MySQL मा लगइन | XAMPP Control Panel → Shell → `mysql -u root` | `mysql -u root -p` |
| डेटाबेसको सूची | `SHOW DATABASES;` | `SHOW DATABASES;` |
| टेबलको सूची | `USE todo_app; SHOW TABLES;` | `USE todo_app; SHOW TABLES;` |
| MySQL रोक्ने | Control Panel → Stop | `brew services stop mysql` |

---

## समस्या समाधान

**"जडान गर्न सकिएन" भन्ने देखिएमा**

- Windows: XAMPP Control Panel मा MySQL "Running (हरियो)" भएको छ कि छैन जाँच्नुहोस्
- Mac: `brew services list` मा mysql `started` भएको छ कि छैन जाँच्नुहोस्

**"Access denied for user 'root'" भन्ने देखिएमा**

- Windows: `database.py` मा पासवर्ड खाली छ कि छैन जाँच्नुहोस् (`root:@localhost`)
- Mac: पासवर्ड सही लेखिएको छ कि छैन जाँच्नुहोस्

---

> अघिल्लो चरण → [00_概要とシステム設計.md](./00_概要とシステム設計.md)
> अर्को चरण → [02_Python_OOP入門.md](./02_Python_OOP入門.md)
