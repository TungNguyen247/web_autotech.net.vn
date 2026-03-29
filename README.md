# Autotech Website

> Trang web chuyên nghiệp cho công ty **Autotech** — chuyên về công nghệ tự động hóa và biến tần.

## 🌐 Demo

Mở file `index.html` trong trình duyệt để xem trang web, hoặc triển khai lên GitHub Pages.

---

## 📁 Cấu trúc file

```
web_autotech.net.vn/
├── index.html              # Trang chính (single-page)
├── assets/
│   ├── css/
│   │   └── style.css       # CSS chính (responsive, gradient tím-xanh)
│   └── js/
│       └── main.js         # JavaScript (ngôn ngữ, scroll, form, ...)
└── README.md
```

---

## ✨ Tính năng

| Tính năng | Mô tả |
|---|---|
| 🇻🇳 / 🇺🇸 Đa ngôn ngữ | Chuyển đổi Tiếng Việt ↔ Tiếng Anh bằng một nút bấm |
| 📱 Responsive | Hiển thị tốt trên mobile, tablet, desktop |
| 🔍 Chuẩn SEO | Meta tags, Open Graph, heading hierarchy, alt text, canonical URL |
| 🎨 Gradient tím-xanh | Giao diện hiện đại theo màu thương hiệu |
| 🍔 Mobile menu | Hamburger menu cho màn hình nhỏ |
| 🎞️ AOS Animation | Hiệu ứng fade khi scroll đến section |
| 📝 Form validation | Kiểm tra dữ liệu form liên hệ theo thời gian thực |
| ⬆️ Back to top | Nút quay về đầu trang |
| 📌 Sticky navbar | Navbar cố định khi scroll, có shadow |

---

## 🚀 Triển khai lên GitHub Pages

1. Vào **Settings** của repository
2. Chọn **Pages** ở menu bên trái
3. Trong mục **Branch**, chọn `main` và thư mục `/ (root)`
4. Nhấn **Save**
5. Trang web sẽ có địa chỉ: `https://TungNguyen247.github.io/web_autotech.net.vn`

---

## ✏️ Cách chỉnh sửa thông tin

### Thay thế thông tin liên hệ

Mở file `index.html` và tìm kiếm các placeholder sau, thay bằng thông tin thực:

| Placeholder | Thay bằng |
|---|---|
| `[Địa chỉ công ty]` | Địa chỉ thực của công ty |
| `[Số điện thoại]` | Số điện thoại thực |
| `[Email]` | Địa chỉ email thực |

### Thay đổi màu sắc

Mở `assets/css/style.css`, tìm phần `:root` ở đầu file:

```css
:root {
  --color-purple: #6C3CE1;  /* Màu tím chính */
  --color-blue:   #1A73E8;  /* Màu xanh chính */
}
```

### Thêm/sửa nội dung song ngữ

Mỗi phần tử có nội dung song ngữ đều dùng thuộc tính `data-vi` và `data-en`:

```html
<span data-vi="Trang chủ" data-en="Home">Trang chủ</span>
```

---

## 🛠️ Công nghệ sử dụng

- **HTML5** — Cấu trúc trang, semantic tags
- **CSS3** — Responsive design, CSS variables, flexbox, grid, gradient
- **JavaScript** (thuần, không framework) — Tương tác, ngôn ngữ, animation
- **Font Awesome 6** — Icons (CDN)
- **Google Fonts** — Inter font (CDN)

---

## 📞 Liên hệ hỗ trợ

Để được hỗ trợ thêm, vui lòng liên hệ qua GitHub Issues.

---

© 2026 Autotech. All rights reserved.

