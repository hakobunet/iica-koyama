# टास्क व्यवस्थापन एप — सुरु गर्ने तरिका

> 🇯🇵 मूल प्रति (जापानी भाषामा): [README.md](<../../../../1年生_前期_ITシステム基礎/番外編/検証/todo_app/README.md>) <!-- original-ja-link -->

विशेष अध्यायमा परीक्षणका लागि प्रयोग गरिने नमुना सिस्टम हो।

## पूर्वशर्तहरू

- Python 3.x इन्स्टल भइसकेको होस्
- MySQL चलिरहेको होस् (Mac: `brew services start mysql`)
- `todo_app` database बनाइसकेको होस् (तल हेर्नुहोस्)

---

## पहिलो पटकको सेटअप

### 1. database बनाउने

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Python package इन्स्टल गर्ने

#### Mac

Mac मा `pip3 install` सिधै चलाउँदा error आउँछ।
**virtual environment (venv)** प्रयोग गरेर इन्स्टल गर्नुहोस्।

```bash
# todo_app फोल्डरभित्र चलाउनुहोस्
python3 -m venv venv

# virtual environment सक्रिय गर्ने
source venv/bin/activate

# सक्रिय गरेपछि इन्स्टल गर्ने (prompt मा (venv) देखिएको छ भने ठीक छ)
pip install fastapi uvicorn sqlalchemy pymysql
```

#### Windows

Windows मा virtual environment बिना नै सिधै इन्स्टल गर्न सकिन्छ।

```cmd
pip install fastapi uvicorn sqlalchemy pymysql
```

virtual environment प्रयोग गर्न चाहनुहुन्छ भने (सिफारिस गरिन्छ):

```cmd
python -m venv venv
venv\Scripts\activate
pip install fastapi uvicorn sqlalchemy pymysql
```

---

## सर्भर सुरु गर्ने

#### Mac

**terminal खोल्ने हरेक पटक virtual environment सक्रिय गर्नुपर्छ।**

```bash
# ① todo_app फोल्डरमा जाने
cd /path/to/todo_app

# ② virtual environment सक्रिय गर्ने
source venv/bin/activate

# ③ सर्भर सुरु गर्ने
uvicorn main:app --reload
```

#### Windows

```cmd
rem ① todo_app फोल्डरमा जाने
cd \path\to\todo_app

rem ② सर्भर सुरु गर्ने (virtual environment प्रयोग गर्ने हो भने पहिले venv\Scripts\activate चलाउनुहोस्)
uvicorn main:app --reload
```

सफलतापूर्वक सुरु भएपछि देखिने सन्देश:
```
INFO:     Uvicorn running on http://127.0.0.1:8000
INFO:     Application startup complete.
```

**④ ब्राउजरमा तलका URL खोल्नुहोस्**

| URL | विवरण |
|-----|------|
| http://localhost:8000 | टास्क व्यवस्थापन एप (स्क्रिन) |
| http://localhost:8000/docs | Swagger UI (API परीक्षण स्क्रिन) |

> सर्भरलाई **चलिरहेकै अवस्थामा राखेर** ब्राउजर खोल्नुहोस्।
> terminal बन्द गर्नुभयो भने सर्भर रोकिन्छ र स्क्रिन पनि देखिँदैन।

---

## समस्या समाधान

**`ERROR: [Errno 48] Address already in use` देखियो भने**

port 8000 अर्को process ले प्रयोग गरिरहेको छ। तलका तरिकाले समाधान गर्न सकिन्छ।

```bash
# तरिका①: प्रयोगमा रहेको process बन्द गर्ने
pkill -f uvicorn

# तरिका②: अर्को port मा सुरु गर्ने
uvicorn main:app --reload --port 8001
# → http://localhost:8001 मा पहुँच गर्नुहोस्
```

---

## फाइल संरचना

```
todo_app/
├── main.py          सुरु गर्ने फाइल
├── database.py      DB जडान सेटिङ
├── models.py        table परिभाषा
├── schemas.py       डेटा ढाँचा परिभाषा
├── routers/
│   └── tasks.py     CRUD API
└── static/
    └── index.html   frontend स्क्रिन
```

---

## database जडान सेटिङ परिवर्तन

`database.py` को connection string लाई आफ्नो वातावरण अनुसार परिवर्तन गर्नुहोस्।

```python
# Mac (पासवर्ड नभएको)
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"

# Mac (पासवर्ड भएको)
DATABASE_URL = "mysql+pymysql://root:तपाईंको पासवर्ड@localhost/todo_app"

# Windows (XAMPP)
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
```

---

## विस्तृत व्याख्या सामग्री

`../番外編/` फोल्डरका सामग्री हेर्नुहोस्।

| फाइल | विवरण |
|---------|------|
| 00_概要とシステム設計.md | सम्पूर्ण सिस्टमको व्याख्या |
| 01_MySQL環境構築.md | MySQL सेटअप गर्ने तरिका |
| 02_Python_OOP入門.md | class/OOP को व्याख्या |
| 03_FastAPI入門.md | FastAPI का आधारभूत कुरा |
| 04_データベース連携.md | SQLAlchemy को व्याख्या |
| 05_CRUD_API実装.md | API इम्प्लिमेन्टेसन को व्याख्या |
| 06_フロントエンド連携.md | HTML/JS को व्याख्या |
