# Elementor Widgets

โฟลเดอร์นี้เก็บ Elementor Widgets ทั้งหมดสำหรับ Website Bio Link Plugin

## Widgets ที่มี:

### 1. Social Links Widget
**ไฟล์:** `social-links-widget.php`  
**Class:** `WBL_Social_Links_Widget`  
**Category:** Website Bio Link  
**Icon:** eicon-social-icons

#### ฟีเจอร์:
- เลือก Social Set จาก Dropdown
- แสดง/ซ่อน Label
- ปรับ Alignment (Left, Center, Right)
- เลือกขนาด Icon (Small, Medium, Large, XLarge)
- ปรับระยะห่างระหว่าง Icons
- 6 รูปแบบ Style (Circle, Rounded, Flat, Minimal, Glass, Gradient)
- ใช้สีแบรนด์หรือกำหนดเอง
- ปรับ Border Radius, Box Shadow, Padding
- Typography Controls สำหรับ Label

#### Controls Sections:

**Content Tab:**
- Social Set Selection
- Layout Settings (Alignment, Size, Gap)

**Style Tab:**
- Icon Style (Style Preset, Brand Colors)
- Custom Colors (Icon Color, Hover Color, Background)
- Advanced (Border Radius, Box Shadow, Padding)
- Label Style (Typography, Color, Spacing)

## การเพิ่ม Widget ใหม่:

1. สร้างไฟล์ใหม่ในโฟลเดอร์นี้ เช่น `my-widget.php`
2. สร้าง Class ที่ extend `\Elementor\Widget_Base`
3. เพิ่มการโหลดใน `includes/class-elementor-loader.php`:

```php
require_once WBL_SOCIAL_PLUGIN_DIR . 'elementor-widgets/my-widget.php';
$widgets_manager->register( new \My_Widget_Class() );
```

## โครงสร้าง Widget:

```php
class My_Widget extends \Elementor\Widget_Base {
    
    // Required Methods
    public function get_name() { }
    public function get_title() { }
    public function get_icon() { }
    public function get_categories() { }
    
    // Controls
    protected function register_controls() { }
    
    // Render
    protected function render() { }
    protected function content_template() { } // Optional
}
```

## Best Practices:

1. ใช้ Text Domain: `website-bio-link`
2. ใช้ Category: `website-bio-link`
3. ตรวจสอบว่าอยู่ใน Editor Mode ก่อนแสดง Notice
4. Sanitize และ Escape ข้อมูลทั้งหมด
5. ใช้ Responsive Controls เมื่อเหมาะสม
6. เพิ่ม Description ให้ Controls ที่ซับซ้อน

---

**หมายเหตุ:** Widgets จะโหลดเฉพาะเมื่อ Elementor ถูกเปิดใช้งานเท่านั้น
