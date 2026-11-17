# Task စီမံခန့်ခွဲမှု App — စတင်လည်ပတ်ပုံ အဆင့်များ

> 🇯🇵 မူရင်း (ဂျပန်ဘာသာဖြင့်): [README.md](<../../../../1年生_前期_ITシステム基礎/番外編/検証/todo_app/README.md>) <!-- original-ja-link -->

ဒါက 番外編（အပိုသင်ခန်းစာ）အတွက် စစ်ဆေးရေး နမူနာ system ပါ။

## လိုအပ်ချက်များ

- Python 3.x ကို install လုပ်ပြီးသား ဖြစ်ရမယ်
- MySQL ကို စတင်ထားပြီးသား ဖြစ်ရမယ်（Mac: `brew services start mysql`）
- Database `todo_app` ကို ဖန်တီးပြီးသား ဖြစ်ရမယ်（အောက်မှာ ကြည့်ပါ）

---

## ပထမဆုံးအကြိမ် setup

### 1. Database ကို ဖန်တီးမယ်

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Python package တွေကို install လုပ်မယ်

#### Mac

Mac မှာ `pip3 install` ကို တိုက်ရိုက် run ရင် error တက်ပါတယ်။
**Virtual environment（venv）** ကို သုံးပြီး install လုပ်ပါ။

```bash
# todo_app folder ထဲမှာ run လုပ်ပါ
python3 -m venv venv

# Virtual environment ကို activate လုပ်တယ်
source venv/bin/activate

# Activate လုပ်ပြီးမှ install လုပ်ပါ（prompt မှာ (venv) ပါနေရင် OK）
pip install fastapi uvicorn sqlalchemy pymysql
```

#### Windows

Windows မှာတော့ virtual environment မသုံးဘဲ တိုက်ရိုက် install လုပ်လို့ရပါတယ်။

```cmd
pip install fastapi uvicorn sqlalchemy pymysql
```

Virtual environment ကို သုံးချင်ရင်（အကြံပြုပါတယ်）：

```cmd
python -m venv venv
venv\Scripts\activate
pip install fastapi uvicorn sqlalchemy pymysql
```

---

## Server ကို စတင်ခြင်း

#### Mac

**Terminal ကို ဖွင့်တိုင်း virtual environment ကို activate လုပ်ဖို့ လိုပါတယ်။**

```bash
# ① todo_app folder ထဲကို ဝင်တယ်
cd /path/to/todo_app

# ② Virtual environment ကို activate လုပ်တယ်
source venv/bin/activate

# ③ Server ကို စတင်တယ်
uvicorn main:app --reload
```

#### Windows

```cmd
rem ① todo_app folder ထဲကို ဝင်တယ်
cd \path\to\todo_app

rem ② Server ကို စတင်တယ်（virtual environment သုံးရင် venv\Scripts\activate ကို အရင် run လုပ်ပါ）
uvicorn main:app --reload
```

အောင်မြင်စွာ စတင်ပြီးတဲ့အခါ ပြသမယ့် စာ：
```
INFO:     Uvicorn running on http://127.0.0.1:8000
INFO:     Application startup complete.
```

**④ Browser မှာ အောက်ပါ URL ကို ဖွင့်ပါ**

| URL | အကြောင်းအရာ |
|-----|------|
| http://localhost:8000 | Task စီမံခန့်ခွဲမှု App（screen） |
| http://localhost:8000/docs | Swagger UI（API စမ်းသပ်တဲ့ screen） |

> Server ကို **run ထားတဲ့ အခြေအနေအတိုင်း** ထားပြီး browser ကို ဖွင့်ပါ။
> Terminal ကို ပိတ်လိုက်ရင် server ရပ်သွားပြီး screen လည်း မပေါ်တော့ပါဘူး။

---

## ပြဿနာ ဖြေရှင်းခြင်း

**`ERROR: [Errno 48] Address already in use` လို့ ပေါ်လာရင်**

Port 8000 ကို တခြား process က သုံးနေပါတယ်။ အောက်ပါနည်းနဲ့ ဖြေရှင်းနိုင်ပါတယ်။

```bash
# နည်းလမ်း①: သုံးနေတဲ့ process ကို ရပ်လိုက်တယ်
pkill -f uvicorn

# နည်းလမ်း②: တခြား port နဲ့ စတင်တယ်
uvicorn main:app --reload --port 8001
# → http://localhost:8001 နဲ့ ဝင်ကြည့်ပါ
```

---

## ဖိုင်ဖွဲ့စည်းပုံ

```
todo_app/
├── main.py          စတင်တဲ့ ဖိုင်
├── database.py      DB ချိတ်ဆက်မှု setting
├── models.py        Table သတ်မှတ်ချက်
├── schemas.py       Data ပုံစံ သတ်မှတ်ချက်
├── routers/
│   └── tasks.py     CRUD API
└── static/
    └── index.html   Frontend screen
```

---

## Database ချိတ်ဆက်မှု setting ကို ပြောင်းခြင်း

`database.py` ထဲက connection string ကို ကိုယ့် environment နဲ့ ကိုက်အောင် ပြောင်းပါ။

```python
# Mac（password မရှိ）
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"

# Mac（password ရှိ）
DATABASE_URL = "mysql+pymysql://root:ကိုယ့်password@localhost/todo_app"

# Windows（XAMPP）
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
```

---

## အသေးစိတ် ရှင်းလင်းချက် သင်ခန်းစာများ

`../番外編/` folder ထဲက သင်ခန်းစာတွေကို ကြည့်ပါ။

| ဖိုင် | အကြောင်းအရာ |
|---------|------|
| 00_概要とシステム設計.md | System တစ်ခုလုံး ရှင်းလင်းချက် |
| 01_MySQL環境構築.md | MySQL တည်ဆောက်ပုံ အဆင့်များ |
| 02_Python_OOP入門.md | Class・OOP ရှင်းလင်းချက် |
| 03_FastAPI入門.md | FastAPI ရဲ့ အခြေခံ |
| 04_データベース連携.md | SQLAlchemy ရှင်းလင်းချက် |
| 05_CRUD_API実装.md | API implementation ရှင်းလင်းချက် |
| 06_フロントエンド連携.md | HTML/JS ရှင်းလင်းချက် |
