# කාර්ය කළමනාකරණ යෙදුම — ආරම්භ කිරීමේ පියවර

> 🇯🇵 මුල් පිටපත (ජපන් භාෂාවෙන්): [README.md](<../../../../1年生_前期_ITシステム基礎/番外編/検証/todo_app/README.md>) <!-- original-ja-link -->

මෙය අමතර පාඩම් මාලාවේ පරීක්ෂණ සඳහා වන නියැදි පද්ධතියකි.

## පූර්ව අවශ්‍යතා

- Python 3.x ස්ථාපනය කර තිබීම
- MySQL ක්‍රියාත්මක වෙමින් තිබීම (Mac: `brew services start mysql`)
- `todo_app` දත්ත සමුදාය සාදා තිබීම (පහත බලන්න)

---

## පළමු වර සැකසුම

### 1. දත්ත සමුදාය සාදන්න

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Python පැකේජ ස්ථාපනය කරන්න

#### Mac

Mac හි `pip3 install` කෙලින්ම ධාවනය කළහොත් දෝෂයක් ඇතිවේ.
**අතථ්‍ය පරිසරයක් (venv)** භාවිත කර ස්ථාපනය කරන්න.

```bash
# todo_app ෆෝල්ඩරය තුළ ධාවනය කරන්න
python3 -m venv venv

# අතථ්‍ය පරිසරය සක්‍රිය කරන්න
source venv/bin/activate

# සක්‍රිය කළ පසු ස්ථාපනය කරන්න (prompt එකේ (venv) පෙනේ නම් හරි)
pip install fastapi uvicorn sqlalchemy pymysql
```

#### Windows

Windows හි අතථ්‍ය පරිසරයක් නොමැතිව කෙලින්ම ස්ථාපනය කළ හැකිය.

```cmd
pip install fastapi uvicorn sqlalchemy pymysql
```

අතථ්‍ය පරිසරයක් භාවිත කිරීමට කැමති නම් (නිර්දේශිතයි):

```cmd
python -m venv venv
venv\Scripts\activate
pip install fastapi uvicorn sqlalchemy pymysql
```

---

## සේවාදායකය ආරම්භ කිරීම

#### Mac

**ටර්මිනලය විවෘත කරන සෑම වාරයකම අතථ්‍ය පරිසරය සක්‍රිය කළ යුතුය.**

```bash
# ① todo_app ෆෝල්ඩරයට යන්න
cd /path/to/todo_app

# ② අතථ්‍ය පරිසරය සක්‍රිය කරන්න
source venv/bin/activate

# ③ සේවාදායකය ආරම්භ කරන්න
uvicorn main:app --reload
```

#### Windows

```cmd
rem ① todo_app ෆෝල්ඩරයට යන්න
cd \path\to\todo_app

rem ② සේවාදායකය ආරම්භ කරන්න (අතථ්‍ය පරිසරය භාවිත කරන්නේ නම් පළමුව venv\Scripts\activate ධාවනය කරන්න)
uvicorn main:app --reload
```

සාර්ථකව ආරම්භ වූ විට පෙනෙන පණිවිඩය:
```
INFO:     Uvicorn running on http://127.0.0.1:8000
INFO:     Application startup complete.
```

**④ බ්‍රවුසරයේ පහත URL විවෘත කරන්න**

| URL | විස්තරය |
|-----|------|
| http://localhost:8000 | කාර්ය කළමනාකරණ යෙදුම (තිරය) |
| http://localhost:8000/docs | Swagger UI (API පරීක්ෂණ තිරය) |

> සේවාදායකය **ක්‍රියාත්මක වෙමින් තිබියදීම** බ්‍රවුසරය විවෘත කරන්න.
> ටර්මිනලය වැසුවහොත් සේවාදායකය නැවතී, තිරයද නොපෙන්වයි.

---

## ගැටලු නිරාකරණය

**`ERROR: [Errno 48] Address already in use` පෙන්වන්නේ නම්**

8000 port එක වෙනත් ක්‍රියාවලියක් භාවිත කරයි. පහත ක්‍රමවලින් විසඳිය හැකිය.

```bash
# ක්‍රමය ①: භාවිතයේ ඇති ක්‍රියාවලිය නවත්වන්න
pkill -f uvicorn

# ක්‍රමය ②: වෙනත් port එකකින් ආරම්භ කරන්න
uvicorn main:app --reload --port 8001
# → http://localhost:8001 හරහා පිවිසෙන්න
```

---

## ගොනු ව්‍යුහය

```
todo_app/
├── main.py          ආරම්භක ගොනුව
├── database.py      DB සම්බන්ධතා සැකසුම්
├── models.py        වගු අර්ථ දැක්වීම
├── schemas.py       දත්ත ආකෘති අර්ථ දැක්වීම
├── routers/
│   └── tasks.py     CRUD API
└── static/
    └── index.html   Frontend තිරය
```

---

## දත්ත සමුදා සම්බන්ධතා සැකසුම් වෙනස් කිරීම

`database.py` හි සම්බන්ධතා string එක ඔබේ පරිසරයට ගැළපෙන ලෙස වෙනස් කරන්න.

```python
# Mac (මුරපදයක් නැත)
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"

# Mac (මුරපදයක් ඇත)
DATABASE_URL = "mysql+pymysql://root:ඔබේ_මුරපදය@localhost/todo_app"

# Windows (XAMPP)
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
```

---

## සවිස්තරාත්මක පැහැදිලි කිරීම් සහිත ලේඛන

`../番外編/` ෆෝල්ඩරයේ ඇති ලේඛන බලන්න.

| ගොනුව | විස්තරය |
|---------|------|
| 00_概要とシステム設計.md | සමස්ත පද්ධතිය පිළිබඳ පැහැදිලි කිරීම |
| 01_MySQL環境構築.md | MySQL සැකසීමේ පියවර |
| 02_Python_OOP入門.md | Class සහ OOP පැහැදිලි කිරීම |
| 03_FastAPI入門.md | FastAPI මූලික කරුණු |
| 04_データベース連携.md | SQLAlchemy පැහැදිලි කිරීම |
| 05_CRUD_API実装.md | API ක්‍රියාත්මක කිරීම පිළිබඳ පැහැදිලි කිරීම |
| 06_フロントエンド連携.md | HTML/JS පැහැදිලි කිරීම |
