# Task Management App — How to Start It

> 🇯🇵 Original (Japanese version): [README.md](<../../../../1年生_前期_ITシステム基礎/番外編/検証/todo_app/README.md>) <!-- original-ja-link -->

This is the sample system for the supplementary lessons (番外編).

## Prerequisites

- Python 3.x is installed
- MySQL is running (Mac: `brew services start mysql`)
- The `todo_app` database has been created (see below)

---

## First-Time Setup

### 1. Create the Database

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Install the Python Packages

#### Mac

On a Mac, running `pip3 install` directly gives you an error.
Use a **virtual environment (venv)** to install the packages.

```bash
# Run inside the todo_app folder
python3 -m venv venv

# Activate the virtual environment
source venv/bin/activate

# Install after activation (check that (venv) appears in your prompt)
pip install fastapi uvicorn sqlalchemy pymysql
```

#### Windows

On Windows, you can install the packages directly without a virtual environment.

```cmd
pip install fastapi uvicorn sqlalchemy pymysql
```

If you want to use a virtual environment (recommended):

```cmd
python -m venv venv
venv\Scripts\activate
pip install fastapi uvicorn sqlalchemy pymysql
```

---

## Starting the Server

#### Mac

**You need to activate the virtual environment every time you open a terminal.**

```bash
# ① Navigate to the todo_app folder
cd /path/to/todo_app

# ② Activate the virtual environment
source venv/bin/activate

# ③ Start the server
uvicorn main:app --reload
```

#### Windows

```cmd
rem ① Navigate to the todo_app folder
cd \path\to\todo_app

rem ② Start the server (if you use a virtual environment, run venv\Scripts\activate first)
uvicorn main:app --reload
```

What you see when it starts successfully:
```
INFO:     Uvicorn running on http://127.0.0.1:8000
INFO:     Application startup complete.
```

**④ Open the following URLs in your browser**

| URL | What it is |
|-----|------|
| http://localhost:8000 | Task management app interface |
| http://localhost:8000/docs | Swagger UI (API testing interface) |

> Open the browser **while the server is still running**.
> If you close the terminal, the server stops and the page will no longer be shown.

---

## Troubleshooting

**If you see `ERROR: [Errno 48] Address already in use`**

Port 8000 is being used by another process. You can fix it like this:

```bash
# Option ①: Stop the process that is using it
pkill -f uvicorn

# Option ②: Start on a different port
uvicorn main:app --reload --port 8001
# → Access it at http://localhost:8001
```

---

## File Structure

```
todo_app/
├── main.py          Startup file
├── database.py      DB connection settings
├── models.py        Table definitions
├── schemas.py       Data format definitions
├── routers/
│   └── tasks.py     CRUD API
└── static/
    └── index.html   Frontend interface
```

---

## Changing the Database Connection Settings

Change the connection string in `database.py` to match your environment.

```python
# Mac (no password)
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"

# Mac (with a password)
DATABASE_URL = "mysql+pymysql://root:あなたのパスワード@localhost/todo_app"

# Windows (XAMPP)
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
```

---

## Detailed Documentation

See the materials in the `../番外編/` folder.

| File | What it covers |
|---------|------|
| 00_概要とシステム設計.md | Explanation of the whole system |
| 01_MySQL環境構築.md | Steps to set up MySQL |
| 02_Python_OOP入門.md | Explanation of classes and OOP |
| 03_FastAPI入門.md | FastAPI basics |
| 04_データベース連携.md | Explanation of SQLAlchemy |
| 05_CRUD_API実装.md | Explanation of the API implementation |
| 06_フロントエンド連携.md | Explanation of HTML/JS |
