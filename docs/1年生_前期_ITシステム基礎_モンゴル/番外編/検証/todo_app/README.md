# Даалгавар удирдах апп — Ажиллуулах заавар

> 🇯🇵 Эх хувь (япон хэлээр): [README.md](<../../../../1年生_前期_ITシステム基礎/番外編/検証/todo_app/README.md>) <!-- original-ja-link -->

Нэмэлт хичээлийн хүрээнд туршиж шалгахад зориулсан жишээ систем юм.

## Урьдчилсан нөхцөл

- Python 3.x суулгасан байх
- MySQL ажиллаж байх (Mac: `brew services start mysql`)
- `todo_app` өгөгдлийн сан үүсгэсэн байх (доорхыг харна уу)

---

## Анхны тохиргоо

### 1. Өгөгдлийн сан үүсгэх

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Python багцуудыг суулгах

#### Mac

Mac дээр `pip3 install`-г шууд ажиллуулбал алдаа гарна.
**Виртуал орчин (venv)** ашиглан суулгаарай.

```bash
# todo_app хавтас дотор ажиллуулна
python3 -m venv venv

# Виртуал орчныг идэвхжүүлнэ
source venv/bin/activate

# Идэвхжүүлсний дараа суулгана (командын мөрөнд (venv) гарч байвал болно)
pip install fastapi uvicorn sqlalchemy pymysql
```

#### Windows

Windows дээр виртуал орчингүйгээр шууд суулгаж болно.

```cmd
pip install fastapi uvicorn sqlalchemy pymysql
```

Виртуал орчин ашиглахыг хүсвэл (зөвлөж байна):

```cmd
python -m venv venv
venv\Scripts\activate
pip install fastapi uvicorn sqlalchemy pymysql
```

---

## Серверийг асаах

#### Mac

**Терминал нээх бүрдээ виртуал орчныг идэвхжүүлэх шаардлагатай.**

```bash
# ① todo_app хавтас руу шилжинэ
cd /path/to/todo_app

# ② Виртуал орчныг идэвхжүүлнэ
source venv/bin/activate

# ③ Серверийг асаана
uvicorn main:app --reload
```

#### Windows

```cmd
rem ① todo_app хавтас руу шилжинэ
cd \path\to\todo_app

rem ② Серверийг асаана (виртуал орчин ашиглах бол эхлээд venv\Scripts\activate-г ажиллуулна)
uvicorn main:app --reload
```

Амжилттай асах үед гарах мэдээлэл:
```
INFO:     Uvicorn running on http://127.0.0.1:8000
INFO:     Application startup complete.
```

**④ Хөтөч дээр дараах URL-ийг нээнэ**

| URL | Агуулга |
|-----|------|
| http://localhost:8000 | Даалгавар удирдах апп (дэлгэц) |
| http://localhost:8000/docs | Swagger UI (API турших дэлгэц) |

> Серверийг **ажиллуулсан хэвээр нь** хөтчөө нээгээрэй.
> Терминалыг хаавал сервер зогсож, дэлгэц ч харагдахаа болино.

---

## Асуудал шийдвэрлэх

**`ERROR: [Errno 48] Address already in use` гэж гарвал**

8000 портыг өөр процесс ашиглаж байна. Дараах аргаар шийдэж болно.

```bash
# Арга ①: Ажиллаж буй процессыг зогсооно
pkill -f uvicorn

# Арга ②: Өөр порт дээр асаана
uvicorn main:app --reload --port 8001
# → http://localhost:8001 хаягаар нэвтэрнэ
```

---

## Файлын бүтэц

```
todo_app/
├── main.py          Асаах файл
├── database.py      DB холболтын тохиргоо
├── models.py        Хүснэгтийн тодорхойлолт
├── schemas.py       Өгөгдлийн хэлбэрийн тодорхойлолт
├── routers/
│   └── tasks.py     CRUD API
└── static/
    └── index.html   Frontend дэлгэц
```

---

## Өгөгдлийн сангийн холболтын тохиргоог өөрчлөх

`database.py`-ийн холболтын мөрийг өөрийн орчинд тааруулан өөрчлөөрэй.

```python
# Mac (нууц үггүй)
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"

# Mac (нууц үгтэй)
DATABASE_URL = "mysql+pymysql://root:таны_нууц_үг@localhost/todo_app"

# Windows (XAMPP)
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
```

---

## Дэлгэрэнгүй тайлбарлах материал

`../番外編/` хавтасны материалуудыг үзнэ үү.

| Файл | Агуулга |
|---------|------|
| 00_概要とシステム設計.md | Системийн ерөнхий тайлбар |
| 01_MySQL環境構築.md | MySQL суулгаж тохируулах алхмууд |
| 02_Python_OOP入門.md | Класс, OOP-ийн тайлбар |
| 03_FastAPI入門.md | FastAPI-ийн үндэс |
| 04_データベース連携.md | SQLAlchemy-ийн тайлбар |
| 05_CRUD_API実装.md | API хэрэгжүүлэлтийн тайлбар |
| 06_フロントエンド連携.md | HTML/JS-ийн тайлбар |
