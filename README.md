# Autotech Website

> Trang web chuyên nghiệp cho công ty **Autotech** — chuyên về công nghệ tự động hóa và biến tần.
> Hỗ trợ **PHP 8.x + MySQL (PDO)** với admin panel đầy đủ tính năng.

---

## 📁 Cấu trúc file

```
web_autotech.net.vn/
├── index.php               # Trang chủ động (PHP, thay thế index.html)
├── index.html              # Giữ lại làm fallback
├── contact_submit.php      # Xử lý form liên hệ (POST)
├── inverter.html           # Trang biến tần
├── .htaccess               # Apache: DirectoryIndex, bảo mật
│
├── config/
│   ├── config.example.php  # Mẫu cấu hình (copy → config.php)
│   └── config.php          # Cấu hình thực (gitignored, tự tạo)
│
├── includes/
│   ├── db.php              # PDO singleton
│   ├── auth.php            # Quản lý session admin
│   ├── csrf.php            # CSRF token
│   └── helpers.php         # Utilities (h(), redirect(), flash(), ...)
│
├── database/
│   └── schema.sql          # Schema MySQL + seed dữ liệu mặc định
│
├── admin/
│   ├── login.php           # Đăng nhập admin
│   ├── logout.php          # Đăng xuất
│   ├── index.php           # Dashboard
│   ├── assets/
│   │   └── admin.css       # CSS admin panel
│   ├── includes/
│   │   ├── layout.php      # Header + sidebar chung
│   │   └── layout_end.php  # Footer chung
│   ├── categories/         # CRUD danh mục
│   ├── products/           # CRUD sản phẩm + upload ảnh
│   └── contacts/           # Danh sách + xem + xóa liên hệ
│
└── assets/
    ├── css/style.css       # CSS trang public
    ├── js/main.js          # JS trang public
    └── uploads/            # Ảnh sản phẩm upload (gitignored)
```

---

## ✨ Tính năng

| Tính năng | Mô tả |
|---|---|
| 🇻🇳 / 🇺🇸 Đa ngôn ngữ | Chuyển đổi Tiếng Việt ↔ Tiếng Anh bằng một nút bấm |
| 📱 Responsive | Hiển thị tốt trên mobile, tablet, desktop |
| 🔍 Chuẩn SEO | Meta tags, Open Graph, heading hierarchy |
| 🗄️ Admin panel | Đăng nhập bảo mật, CRUD sản phẩm/danh mục, xem liên hệ |
| 🔒 Bảo mật | CSRF protection, prepared statements (PDO), password_hash |
| 📤 Upload ảnh | jpg/png/webp, tối đa 5MB, validate MIME type |
| 📝 Form liên hệ | Lưu vào MySQL, admin xem và quản lý |

---

## 🚀 Hướng dẫn cài đặt (Shared Hosting / PA Việt Nam)

### Bước 1 — Upload code lên hosting

Upload toàn bộ thư mục lên `public_html` (hoặc thư mục website) qua FTP/File Manager.

### Bước 2 — Tạo database MySQL

1. Đăng nhập **cPanel** → **MySQL Databases**
2. Tạo database mới, tạo user, gán quyền `ALL PRIVILEGES`
3. Vào **phpMyAdmin**, chọn database vừa tạo, tab **SQL**
4. Copy và chạy nội dung file `database/schema.sql`

### Bước 3 — Cấu hình kết nối

```bash
cp config/config.example.php config/config.php
```

Mở `config/config.php` và điền thông tin thực:

```php
define('DB_HOST',    'localhost');
define('DB_NAME',    'ten_database');
define('DB_USER',    'ten_user');
define('DB_PASS',    'mat_khau');
define('APP_URL',    'https://autotech.net.vn');
define('APP_ENV',    'production');
define('SESSION_SECURE', true); // false nếu chưa có HTTPS
```

### Bước 4 — Đăng nhập admin lần đầu

- URL: `https://yourdomain.com/admin/login.php`
- Username mặc định: `admin`
- Password mặc định: `Admin@2026`

> ⚠️ **QUAN TRỌNG**: Đổi mật khẩu ngay sau lần đăng nhập đầu tiên!

Để thay mật khẩu, chạy lệnh SQL sau trong phpMyAdmin:

```sql
UPDATE admin_users
SET password_hash = '$2y$12$...' -- thay bằng hash từ password_hash()
WHERE username = 'admin';
```

Hoặc tạo hash trong PHP:
```php
echo password_hash('MatKhauMoiCuaBan', PASSWORD_BCRYPT);
```

### Bước 5 — Kiểm tra

- Trang chủ: `https://yourdomain.com/` (hoặc `index.php`)
- Admin: `https://yourdomain.com/admin/`

---

## 🔧 Sử dụng Admin Panel

### Quản lý danh mục
Vào **Admin → Danh mục** để thêm/sửa/xóa danh mục sản phẩm.

### Quản lý sản phẩm
Vào **Admin → Sản phẩm** để:
- Thêm sản phẩm mới với tên song ngữ, mô tả, giá và ảnh
- Bật/tắt hiển thị sản phẩm trên trang chủ
- Upload ảnh (jpg/png/webp, tối đa 5MB)

### Xem tin nhắn liên hệ
Vào **Admin → Liên hệ** để xem danh sách tin nhắn từ khách hàng.
Nhấn vào tên để xem chi tiết (tin nhắn sẽ được đánh dấu đã đọc tự động).

---

## ✏️ Chỉnh sửa thông tin liên hệ

Mở `index.php` và tìm các placeholder sau:

| Placeholder | Thay bằng |
|---|---|
| `[Địa chỉ công ty]` | Địa chỉ thực của công ty |
| `[Số điện thoại]` | Số điện thoại thực |
| `[Email]` | Địa chỉ email thực |

---

## 🛠️ Công nghệ sử dụng

- **PHP 8.x** — Backend, PDO, session
- **MySQL** — Database
- **HTML5 / CSS3 / JavaScript** (thuần) — Frontend
- **Font Awesome 6** — Icons (CDN)
- **Google Fonts** — Inter font (CDN)

---

## 🔒 Bảo mật

- Mật khẩu admin được hash bằng `password_hash()` (bcrypt)
- Tất cả query dùng PDO prepared statements (chống SQL injection)
- CSRF token trên mọi form POST
- Input được validate và sanitize server-side
- `config/config.php` và `assets/uploads/*` được gitignore
- Header bảo mật cơ bản trong `.htaccess`

---

## 📞 Liên hệ hỗ trợ

Để được hỗ trợ thêm, vui lòng liên hệ qua GitHub Issues.

---

© 2026 Autotech. All rights reserved.

