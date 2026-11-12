# MySQL орчин бэлтгэх

> 🇯🇵 Эх хувь (япон хэлээр): [01_MySQL環境構築.md](<../../1年生_前期_ITシステム基礎/番外編/01_MySQL環境構築.md>) <!-- original-ja-link -->

## Энэ удаагийн зорилго
- Өөрийн OS-д тохируулан MySQL-ийг эхлүүлж чаддаг болох
- `todo_app` өгөгдлийн санг үүсгэж чаддаг болох
- Python-оос MySQL-тэй холбогдоход хэрэгтэй мэдээллийг (холболтын мөр) ойлгох

---

## OS-оос хамаарч алхам өөр өөр байна

| OS | Ашиглах хэрэгсэл | Онцлог |
|----|-----------|------|
| Windows | XAMPP | GUI-ээр удирдах боломжтой, анхлан суралцагчдад тохиромжтой |
| Mac | Homebrew + MySQL | Терминалаар удирдах стандарт арга |

---

## Windows — XAMPP ашиглах

### 1. XAMPP татаж авах, суулгах

1. [https://www.apachefriends.org/jp/index.html](https://www.apachefriends.org/jp/index.html)-ийг нээнэ
2. 「Windows 向け XAMPP」(XAMPP for Windows)-ийг татаж авна
3. Суулгагчийг ажиллуулна (суулгах байршил нь анхдагч `C:\xampp` байхад OK)

### 2. MySQL-ийг эхлүүлэх

1. Start цэснээс "XAMPP Control Panel"-ийг нээнэ
2. `MySQL` мөрийн "Start" товчийг дарна
3. Ногоон болвол амжилттай эхэлсэн гэсэн үг

```
┌─────────────────────────────────────┐
│  XAMPP Control Panel                │
│                                     │
│  Apache  [Start] [Admin] ...        │
│  MySQL   [Start] [Admin] ...  ← энд │
│  ...                                │
└─────────────────────────────────────┘
         ↓ Start-ийг дарна

│  MySQL   [Stop]  [Admin] ...  ← ногоон болвол OK
```

### 3. Өгөгдлийн сан үүсгэх (phpMyAdmin)

1. Хөтчөөр `http://localhost/phpmyadmin`-ийг нээнэ
2. Зүүн цэсний дээд хэсэгт байгаа 「新規作成」(New)-ийг дарна
3. Өгөгдлийн сангийн нэрийн талбарт `todo_app` гэж оруулна
4. Эрэмбэлэх дүрэм (Collation, тэмдэгтийн кодчилол)-ээр `utf8mb4_unicode_ci`-г сонгоно
5. 「作成」(Create) товчийг дарна

```
phpMyAdmin дэлгэц
┌─────────────────────────────────────────────┐
│  新しいデータベースを作成する:               │
│  [todo_app        ] [utf8mb4_unicode_ci ▼]  │
│                                   [作成]    │
└─────────────────────────────────────────────┘
```

### 4. Холболтын мэдээллийг шалгах

XAMPP-ийн анхдагч тохиргоогоор дараах байдалтай байна.

| Зүйл | Утга |
|------|-----|
| Хост | localhost |
| Хэрэглэгч | root |
| Нууц үг | (хоосон) |
| DB нэр | todo_app |

**`database.py`-ийн холболтын мөр (Windows-ийн хувьд):**

```python
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
#                                   ↑ нууц үггүй (хоёр цэгийн ард хоосон)
```

---

## Mac — Homebrew + MySQL ашиглах

### 1. Homebrew суулгах (суулгаагүй бол)

Терминал нээгээд дараахыг ажиллуулна:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Суулгасныг шалгах:

```bash
brew --version
# Homebrew 4.x.x гэх мэт гарч ирвэл OK
```

### 2. MySQL суулгах

```bash
brew install mysql
```

### 3. MySQL-ийг эхлүүлэх

```bash
# MySQL-ийг эхлүүлнэ
brew services start mysql

# Эхэлсэн эсэхийг шалгана
brew services list
# mysql  started  ... гэж гарч ирвэл OK
```

### 4. Анхны тохиргоо (нууц үг тохируулах)

```bash
mysql_secure_installation
```

Асуултуудад хариулна:

```
VALIDATE PASSWORD component: No (нууц үгийн найдвартай байдлыг шалгана. Эхний удаад n гэж оруулахад болно)
New password: (дурын нууц үг оруулна → мартахгүйн тулд тэмдэглээрэй!)
Re-enter new password: (ижил нууц үгийг дахин оруулна)
Remove anonymous users: Yes (y гэж оруулна)
Disallow root login remotely: Yes (y гэж оруулна)
Remove test database: Yes (y гэж оруулна)
Reload privilege tables: Yes (y гэж оруулна)
```

### 5. Өгөгдлийн сан үүсгэх

```bash
# MySQL-д нэвтэрнэ (тохируулсан нууц үгээ оруулна)
mysql -u root -p

# Амжилттай нэвтэрвэл дараах prompt гарч ирнэ
# mysql>
```

MySQL-ийн prompt дээр дараахыг ажиллуулна:

```sql
-- Өгөгдлийн сан үүсгэнэ
CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Үүссэнийг шалгана
SHOW DATABASES;
-- todo_app гарч ирвэл OK

-- MySQL-ээс гарна
EXIT;
```

### 6. Холболтын мэдээллийг шалгах

| Зүйл | Утга |
|------|-----|
| Хост | localhost |
| Хэрэглэгч | root |
| Нууц үг | 4-р алхамд тохируулсан нууц үг |
| DB нэр | todo_app |

**`database.py`-ийн холболтын мөр (Mac-ийн хувьд):**

```python
DATABASE_URL = "mysql+pymysql://root:таны_нууц_үг@localhost/todo_app"
#                                   ↑ 4-р алхамд тохируулсан нууц үгээр солино
```

---

## MySQL-ийн үндсэн командууд (лавлагаа)

| Үйлдэл | Windows (XAMPP Shell) | Mac (терминал) |
|------|----------------------|----------------|
| MySQL-д нэвтрэх | XAMPP Control Panel → Shell → `mysql -u root` | `mysql -u root -p` |
| Өгөгдлийн сангийн жагсаалт | `SHOW DATABASES;` | `SHOW DATABASES;` |
| Хүснэгтийн жагсаалт | `USE todo_app; SHOW TABLES;` | `USE todo_app; SHOW TABLES;` |
| MySQL зогсоох | Control Panel → Stop | `brew services stop mysql` |

---

## Асуудал шийдвэрлэх

**"Холбогдож чадсангүй" гэж гарвал**

- Windows: XAMPP Control Panel дээр MySQL "Running (ногоон)" болсон эсэхийг шалгана
- Mac: `brew services list`-ээр mysql `started` болсон эсэхийг шалгана

**"Access denied for user 'root'" гэж гарвал**

- Windows: `database.py` доторх нууц үг хоосон байгаа эсэхийг шалгана (`root:@localhost`)
- Mac: Нууц үгээ зөв оруулсан эсэхийг шалгана

---

> Өмнөх алхам → [00_概要とシステム設計.md](./00_概要とシステム設計.md)
> Дараагийн алхам → [02_Python_OOP入門.md](./02_Python_OOP入門.md)
