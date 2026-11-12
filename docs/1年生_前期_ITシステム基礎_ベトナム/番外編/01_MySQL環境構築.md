# Cài đặt môi trường MySQL

> 🇯🇵 Bản gốc (tiếng Nhật): [01_MySQL環境構築.md](<../../1年生_前期_ITシステム基礎/番外編/01_MySQL環境構築.md>) <!-- original-ja-link -->

## Mục tiêu lần này
- Khởi động được MySQL theo cách phù hợp với hệ điều hành của mình
- Tạo được cơ sở dữ liệu `todo_app`
- Biết thông tin dùng để kết nối MySQL từ Python (chuỗi kết nối)

---

## Các bước sẽ khác nhau tùy hệ điều hành

| Hệ điều hành | Công cụ sử dụng | Đặc điểm |
|----|-----------|------|
| Windows | XAMPP | Thao tác bằng GUI, phù hợp với người mới bắt đầu |
| Mac | Homebrew + MySQL | Cách làm tiêu chuẩn, thao tác bằng terminal |

---

## Windows — Dùng XAMPP

### 1. Tải về và cài đặt XAMPP

1. Mở [https://www.apachefriends.org/jp/index.html](https://www.apachefriends.org/jp/index.html)
2. Tải về "XAMPP cho Windows"
3. Chạy trình cài đặt (thư mục cài đặt để mặc định `C:\xampp` là được)

### 2. Khởi động MySQL

1. Mở "XAMPP Control Panel" từ menu Start
2. Bấm nút "Start" ở dòng `MySQL`
3. Chuyển sang màu xanh lá là khởi động thành công

```
┌─────────────────────────────────────┐
│  XAMPP Control Panel                │
│                                     │
│  Apache  [Start] [Admin] ...        │
│  MySQL   [Start] [Admin] ... ← Đây  │
│  ...                                │
└─────────────────────────────────────┘
         ↓ Bấm Start

│  MySQL   [Stop]  [Admin] ...  ← Xanh lá là OK
```

### 3. Tạo cơ sở dữ liệu (phpMyAdmin)

1. Mở `http://localhost/phpmyadmin` trên trình duyệt
2. Bấm 「新規作成」(Tạo mới) ở phía trên menu bên trái
3. Nhập `todo_app` vào trường tên cơ sở dữ liệu
4. Chọn `utf8mb4_unicode_ci` làm thứ tự đối chiếu (collation)
5. Bấm nút 「作成」(Tạo)

```
Màn hình phpMyAdmin
┌─────────────────────────────────────────────┐
│  Tạo cơ sở dữ liệu mới:                     │
│  [todo_app        ] [utf8mb4_unicode_ci ▼]  │
│                                    [Tạo]    │
└─────────────────────────────────────────────┘
```

### 4. Kiểm tra thông tin kết nối

Với thiết lập mặc định của XAMPP, thông tin như sau.

| Mục | Giá trị |
|------|-----|
| Host | localhost |
| User | root |
| Mật khẩu | (để trống) |
| Tên DB | todo_app |

**Chuỗi kết nối trong `database.py` (trường hợp Windows):**

```python
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
#                                   ↑ Không có mật khẩu (sau dấu hai chấm để trống)
```

---

## Mac — Dùng Homebrew + MySQL

### 1. Cài đặt Homebrew (nếu chưa cài)

Mở terminal và chạy lệnh sau:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Kiểm tra cài đặt:

```bash
brew --version
# Hiển thị kiểu Homebrew 4.x.x là OK
```

### 2. Cài đặt MySQL

```bash
brew install mysql
```

### 3. Khởi động MySQL

```bash
# Khởi động MySQL
brew services start mysql

# Kiểm tra đã khởi động chưa
brew services list
# Hiển thị mysql  started  ... là OK
```

### 4. Thiết lập ban đầu (đặt mật khẩu)

```bash
mysql_secure_installation
```

Lần lượt trả lời các câu hỏi:

```
VALIDATE PASSWORD component: No (kiểm tra độ mạnh mật khẩu. Lần đầu chọn n là được)
New password: (nhập mật khẩu tùy ý → nhớ ghi lại để không quên!)
Re-enter new password: (nhập lại đúng mật khẩu đó)
Remove anonymous users: Yes (nhập y)
Disallow root login remotely: Yes (nhập y)
Remove test database: Yes (nhập y)
Reload privilege tables: Yes (nhập y)
```

### 5. Tạo cơ sở dữ liệu

```bash
# Đăng nhập MySQL (nhập mật khẩu đã đặt)
mysql -u root -p

# Đăng nhập thành công sẽ hiện prompt như sau
# mysql>
```

Chạy các lệnh sau tại prompt của MySQL:

```sql
-- Tạo cơ sở dữ liệu
CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Kiểm tra đã tạo chưa
SHOW DATABASES;
-- Hiển thị todo_app là OK

-- Thoát khỏi MySQL
EXIT;
```

### 6. Kiểm tra thông tin kết nối

| Mục | Giá trị |
|------|-----|
| Host | localhost |
| User | root |
| Mật khẩu | Mật khẩu đã đặt ở bước 4 |
| Tên DB | todo_app |

**Chuỗi kết nối trong `database.py` (trường hợp Mac):**

```python
DATABASE_URL = "mysql+pymysql://root:mật_khẩu_của_bạn@localhost/todo_app"
#                                   ↑ Thay bằng mật khẩu đã đặt ở bước 4
```

---

## Các lệnh thao tác cơ bản với MySQL (tham khảo)

| Thao tác | Windows (XAMPP Shell) | Mac (terminal) |
|------|----------------------|----------------|
| Đăng nhập MySQL | XAMPP Control Panel → Shell → `mysql -u root` | `mysql -u root -p` |
| Danh sách cơ sở dữ liệu | `SHOW DATABASES;` | `SHOW DATABASES;` |
| Danh sách bảng | `USE todo_app; SHOW TABLES;` | `USE todo_app; SHOW TABLES;` |
| Dừng MySQL | Control Panel → Stop | `brew services stop mysql` |

---

## Xử lý sự cố

**Khi hiện thông báo "không kết nối được"**

- Windows: kiểm tra MySQL trong XAMPP Control Panel đã ở trạng thái "Running (xanh lá)" chưa
- Mac: kiểm tra bằng `brew services list` xem mysql đã là `started` chưa

**Khi hiện thông báo "Access denied for user 'root'"**

- Windows: kiểm tra mật khẩu trong `database.py` đã để trống chưa (`root:@localhost`)
- Mac: kiểm tra đã nhập đúng mật khẩu chưa

---

> Bước trước → [00_概要とシステム設計.md](./00_概要とシステム設計.md)
> Bước tiếp theo → [02_Python_OOP入門.md](./02_Python_OOP入門.md)
