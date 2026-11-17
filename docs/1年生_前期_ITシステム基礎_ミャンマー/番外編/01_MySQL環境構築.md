# MySQL ပတ်ဝန်းကျင် တည်ဆောက်ခြင်း

> 🇯🇵 မူရင်း (ဂျပန်ဘာသာဖြင့်): [01_MySQL環境構築.md](<../../1年生_前期_ITシステム基礎/番外編/01_MySQL環境構築.md>) <!-- original-ja-link -->

## ဒီတစ်ခါရဲ့ ပန်းတိုင်
- ကိုယ့် OS နဲ့ ကိုက်ညီအောင် MySQL ကို စတင်နိုင်မယ်
- `todo_app` database ကို ဖန်တီးနိုင်မယ်
- Python ကနေ MySQL ကို ချိတ်ဆက်ဖို့ လိုအပ်တဲ့ အချက်အလက်（connection string）ကို သိလာမယ်

---

## OS အလိုက် အဆင့်တွေ ကွာပါတယ်

| OS | သုံးမယ့် tool | ထူးခြားချက် |
|----|-----------|------|
| Windows | XAMPP | GUI နဲ့ ကိုင်တွယ်လို့ရလို့ စတင်လေ့လာသူတွေအတွက် သင့်တော်တယ် |
| Mac | Homebrew + MySQL | Terminal နဲ့ ကိုင်တွယ်တဲ့ စံနည်းလမ်း |

---

## Windows — XAMPP ကို သုံးခြင်း

### 1. XAMPP ကို download・install လုပ်ပါ

1. [https://www.apachefriends.org/jp/index.html](https://www.apachefriends.org/jp/index.html) ကို ဖွင့်ပါ
2. 「Windows 向け XAMPP」（Windows အတွက် XAMPP）ကို download လုပ်ပါ
3. Installer ကို run လုပ်ပါ（install လုပ်မယ့်နေရာက default `C:\xampp` ဆိုရင် OK）

### 2. MySQL ကို စတင်ပါ

1. Start menu ကနေ「XAMPP Control Panel」ကို ဖွင့်ပါ
2. `MySQL` အတန်းက「Start」ခလုတ်ကို click လုပ်ပါ
3. အစိမ်းရောင် ဖြစ်သွားရင် စတင်တာ အောင်မြင်ပါပြီ

```
┌─────────────────────────────────────┐
│  XAMPP Control Panel                │
│                                     │
│  Apache  [Start] [Admin] ...        │
│  MySQL   [Start] [Admin] ...  ← ဒီနေရာ│
│  ...                                │
└─────────────────────────────────────┘
         ↓ Start ကို နှိပ်ပါ

│  MySQL   [Stop]  [Admin] ...  ← အစိမ်းဖြစ်ရင် OK
```

### 3. Database ဖန်တီးပါ（phpMyAdmin）

1. Browser မှာ `http://localhost/phpmyadmin` ကို ဖွင့်ပါ
2. ဘယ်ဘက် menu ရဲ့ အပေါ်ပိုင်းက「新規作成」（အသစ်ဖန်တီးရန်）ကို click လုပ်ပါ
3. Database name မှာ `todo_app` လို့ ရိုက်ထည့်ပါ
4. Collation（character encoding）ကို `utf8mb4_unicode_ci` ရွေးပါ
5. 「作成」（ဖန်တီးရန်）ခလုတ်ကို click လုပ်ပါ

```
phpMyAdmin screen
┌─────────────────────────────────────────────┐
│  新しいデータベースを作成する:               │
│  [todo_app        ] [utf8mb4_unicode_ci ▼]  │
│                                   [作成]    │
└─────────────────────────────────────────────┘
```

「新しいデータベースを作成する」က「database အသစ် ဖန်တီးမယ်」、「作成」က「ဖန်တီးရန်」ကို ဆိုလိုပါတယ်။

### 4. ချိတ်ဆက်မှု အချက်အလက်ကို စစ်ဆေးပါ

XAMPP ရဲ့ default setting မှာ အောက်ပါအတိုင်း ဖြစ်ပါတယ်။

| အကြောင်းအရာ | တန်ဖိုး |
|------|-----|
| Host | localhost |
| User | root |
| Password | （ကွက်လပ်） |
| DB အမည် | todo_app |

**`database.py` ရဲ့ connection string（Windows အတွက်）：**

```python
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
#                                   ↑ password မရှိ（colon နောက်မှာ ဘာမှမရှိ）
```

---

## Mac — Homebrew + MySQL ကို သုံးခြင်း

### 1. Homebrew ကို install လုပ်ပါ（မ install ရသေးရင်）

Terminal ကို ဖွင့်ပြီး အောက်ပါကို run လုပ်ပါ：

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Install ဖြစ်မဖြစ် စစ်ဆေးခြင်း：

```bash
brew --version
# Homebrew 4.x.x လိုမျိုး ပေါ်ရင် OK
```

### 2. MySQL ကို install လုပ်ပါ

```bash
brew install mysql
```

### 3. MySQL ကို စတင်ပါ

```bash
# MySQL ကို စတင်တယ်
brew services start mysql

# စတင်ပြီးကြောင်း စစ်ဆေးတယ်
brew services list
# mysql  started  ... လို့ ပေါ်ရင် OK
```

### 4. ကနဦး setting（password သတ်မှတ်ခြင်း）

```bash
mysql_secure_installation
```

မေးခွန်းတွေကို တစ်ခုချင်း ဖြေသွားပါ：

```
VALIDATE PASSWORD component: No（password ခိုင်မာမှု စစ်ဆေးချက်။ ပထမဆုံးအကြိမ်မှာ n ဆိုရင် OK）
New password: （ကြိုက်တဲ့ password ကို ရိုက်ထည့်ပါ → မမေ့အောင် မှတ်ထားပါ！）
Re-enter new password: （password အတူတူကို ထပ်ရိုက်ပါ）
Remove anonymous users: Yes（y ကို ရိုက်ပါ）
Disallow root login remotely: Yes（y ကို ရိုက်ပါ）
Remove test database: Yes（y ကို ရိုက်ပါ）
Reload privilege tables: Yes（y ကို ရိုက်ပါ）
```

### 5. Database ဖန်တီးပါ

```bash
# MySQL ကို login ဝင်ပါ（သတ်မှတ်ထားတဲ့ password ကို ရိုက်ပါ）
mysql -u root -p

# Login အောင်မြင်ရင် အောက်ပါ prompt ပေါ်လာမယ်
# mysql>
```

MySQL prompt မှာ အောက်ပါကို run လုပ်ပါ：

```sql
-- Database ဖန်တီးတယ်
CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ဖန်တီးပြီးကြောင်း စစ်ဆေးတယ်
SHOW DATABASES;
-- todo_app ပေါ်ရင် OK

-- MySQL ကနေ ထွက်တယ်
EXIT;
```

### 6. ချိတ်ဆက်မှု အချက်အလက်ကို စစ်ဆေးပါ

| အကြောင်းအရာ | တန်ဖိုး |
|------|-----|
| Host | localhost |
| User | root |
| Password | အဆင့် 4 မှာ သတ်မှတ်ခဲ့တာ |
| DB အမည် | todo_app |

**`database.py` ရဲ့ connection string（Mac အတွက်）：**

```python
DATABASE_URL = "mysql+pymysql://root:သင့်password@localhost/todo_app"
#                                   ↑ အဆင့် 4 မှာ သတ်မှတ်ခဲ့တဲ့ password နဲ့ အစားထိုးပါ
```

---

## MySQL အခြေခံ command များ（ကိုးကားရန်）

| လုပ်ဆောင်ချက် | Windows（XAMPP Shell） | Mac（Terminal） |
|------|----------------------|----------------|
| MySQL login | XAMPP Control Panel → Shell → `mysql -u root` | `mysql -u root -p` |
| Database စာရင်း | `SHOW DATABASES;` | `SHOW DATABASES;` |
| Table စာရင်း | `USE todo_app; SHOW TABLES;` | `USE todo_app; SHOW TABLES;` |
| MySQL ရပ်ခြင်း | Control Panel → Stop | `brew services stop mysql` |

---

## ပြဿနာ ဖြေရှင်းခြင်း

**「接続できない」（ချိတ်ဆက်လို့ မရဘူး）လို့ ပေါ်တဲ့အခါ**

- Windows: XAMPP Control Panel မှာ MySQL က「Running（အစိမ်း）」ဖြစ်နေသလား စစ်ပါ
- Mac: `brew services list` မှာ mysql က `started` ဖြစ်နေသလား စစ်ပါ

**「Access denied for user 'root'」လို့ ပေါ်တဲ့အခါ**

- Windows: `database.py` ထဲက password ကွက်လပ် ဖြစ်နေသလား စစ်ပါ（`root:@localhost`）
- Mac: Password ကို မှန်မှန်ကန်ကန် ရိုက်ထည့်ထားသလား စစ်ပါ

---

> ရှေ့တစ်ဆင့် → [00_概要とシステム設計.md](./00_概要とシステム設計.md)
> နောက်တစ်ဆင့် → [02_Python_OOP入門.md](./02_Python_OOP入門.md)
