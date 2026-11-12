# Ứng dụng quản lý task — Hướng dẫn khởi động

> 🇯🇵 Bản gốc (tiếng Nhật): [README.md](<../../../../1年生_前期_ITシステム基礎/番外編/検証/todo_app/README.md>) <!-- original-ja-link -->

Đây là hệ thống mẫu dùng để kiểm chứng trong phần bổ sung.

## Điều kiện cần có

- Đã cài Python 3.x
- MySQL đang chạy (Mac: `brew services start mysql`)
- Đã tạo database `todo_app` (xem bên dưới)

---

## Thiết lập lần đầu

### 1. Tạo database

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Cài đặt các package Python

#### Mac

Trên Mac, nếu chạy trực tiếp `pip3 install` sẽ bị lỗi.
Các em hãy cài đặt bằng **môi trường ảo (venv)**.

```bash
# Chạy bên trong thư mục todo_app
python3 -m venv venv

# Kích hoạt môi trường ảo
source venv/bin/activate

# Cài đặt sau khi kích hoạt (dấu nhắc lệnh có (venv) ở đầu là OK)
pip install fastapi uvicorn sqlalchemy pymysql
```

#### Windows

Trên Windows có thể cài đặt trực tiếp mà không cần môi trường ảo.

```cmd
pip install fastapi uvicorn sqlalchemy pymysql
```

Nếu muốn dùng môi trường ảo (khuyến nghị):

```cmd
python -m venv venv
venv\Scripts\activate
pip install fastapi uvicorn sqlalchemy pymysql
```

---

## Khởi động server

#### Mac

**Mỗi lần mở Terminal đều phải kích hoạt lại môi trường ảo.**

```bash
# ① Di chuyển đến thư mục todo_app
cd /path/to/todo_app

# ② Kích hoạt môi trường ảo
source venv/bin/activate

# ③ Khởi động server
uvicorn main:app --reload
```

#### Windows

```cmd
rem ① Di chuyển đến thư mục todo_app
cd \path\to\todo_app

rem ② Khởi động server (nếu dùng môi trường ảo thì chạy venv\Scripts\activate trước)
uvicorn main:app --reload
```

Màn hình hiển thị khi khởi động thành công:
```
INFO:     Uvicorn running on http://127.0.0.1:8000
INFO:     Application startup complete.
```

**④ Mở các URL sau trên trình duyệt**

| URL | Nội dung |
|-----|------|
| http://localhost:8000 | Ứng dụng quản lý task (giao diện) |
| http://localhost:8000/docs | Swagger UI (màn hình test API) |

> Hãy mở trình duyệt **trong khi vẫn để server chạy**.
> Nếu đóng Terminal thì server sẽ dừng và màn hình cũng không hiển thị được nữa.

---

## Xử lý sự cố

**Khi hiển thị `ERROR: [Errno 48] Address already in use`**

Cổng 8000 đang bị một tiến trình khác sử dụng. Có thể giải quyết bằng các cách sau.

```bash
# Cách ①: Dừng tiến trình đang sử dụng cổng
pkill -f uvicorn

# Cách ②: Khởi động bằng cổng khác
uvicorn main:app --reload --port 8001
# → Truy cập bằng http://localhost:8001
```

---

## Cấu trúc file

```
todo_app/
├── main.py          File khởi động
├── database.py      Cấu hình kết nối DB
├── models.py        Định nghĩa bảng
├── schemas.py       Định nghĩa định dạng dữ liệu
├── routers/
│   └── tasks.py     CRUD API
└── static/
    └── index.html   Giao diện frontend
```

---

## Thay đổi cấu hình kết nối database

Hãy sửa chuỗi kết nối trong `database.py` cho phù hợp với môi trường của em.

```python
# Mac (không có mật khẩu)
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"

# Mac (có mật khẩu)
DATABASE_URL = "mysql+pymysql://root:mật_khẩu_của_bạn@localhost/todo_app"

# Windows (XAMPP)
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
```

---

## Tài liệu giải thích chi tiết

Hãy tham khảo các tài liệu trong thư mục `../番外編/`.

| File | Nội dung |
|---------|------|
| 00_概要とシステム設計.md | Giải thích toàn bộ hệ thống |
| 01_MySQL環境構築.md | Các bước cài đặt MySQL |
| 02_Python_OOP入門.md | Giải thích về class và OOP |
| 03_FastAPI入門.md | Kiến thức cơ bản về FastAPI |
| 04_データベース連携.md | Giải thích về SQLAlchemy |
| 05_CRUD_API実装.md | Giải thích cách triển khai API |
| 06_フロントエンド連携.md | Giải thích về HTML/JS |
