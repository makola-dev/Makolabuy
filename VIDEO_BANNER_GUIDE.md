# Video Banner Guide

## ✅ Yes! You can add MP4 videos as banners!

Your carousel now supports both **images** and **videos**. Videos will automatically:
- ✓ Auto-play when the slide appears
- ✓ Loop continuously
- ✓ Play muted (no sound)
- ✓ Work on mobile devices
- ✓ Be fully responsive

---

## 📹 How to Add a Video Banner

### Step 1: Prepare Your Video
1. **Format**: MP4, WebM, or OGG
2. **Recommended Size**: Under 10MB for fast loading
3. **Recommended Dimensions**: 1920x600 or 1920x800 pixels
4. **Sound**: Muted or no audio (will be muted automatically)
5. **Length**: 5-15 seconds works best for banners

### Step 2: Upload Your Video
Upload your MP4 file to:
```
C:\xampp\htdocs\Makola\assets\img\banners\
```

Example: `banner-video.mp4`

### Step 3: Add to Database
1. Open the file: `add_video_banner.php`
2. Edit the configuration section:
```php
$video_filename = "your-video-name.mp4";  // Your video filename
$banner_title = "Video Promotion";
$banner_subtitle = "Watch Our Latest Offers";
$display_order = 4;  // Position in carousel
```

3. Save the file
4. Run in browser: `http://localhost/Makola/add_video_banner.php`

### Step 4: View Your Carousel
Visit your homepage: `http://localhost/Makola/index.php`

---

## 🎨 Mix Images and Videos

You can have both image banners and video banners in the same carousel!

Example carousel:
1. 🖼️ Welcome Banner (image)
2. 📹 Product Video (MP4)
3. 🖼️ Holi Promotion (image)
4. 📹 Mother's Day Video (MP4)

The carousel will automatically detect the file type and display it correctly.

---

## ⚙️ Video Features

Your video banners come with these HTML5 attributes:

- **autoplay**: Videos start playing automatically when the slide appears
- **muted**: No sound plays (required for autoplay on most browsers)
- **loop**: Videos repeat continuously
- **playsinline**: Works properly on iOS devices
- **poster**: Shows a thumbnail while video loads (optional)

---

## 📱 Mobile Support

Video banners are fully responsive and work on:
- ✓ Desktop browsers
- ✓ Tablets
- ✓ Mobile phones (iOS & Android)
- ✓ All modern browsers

Videos will auto-scale to fit the screen size.

---

## 💡 Tips for Best Results

1. **Keep it short**: 5-15 second videos work best
2. **Optimize file size**: Use H.264 codec, compress for web
3. **Test on mobile**: Videos should load quickly on slow connections
4. **Use high contrast**: Make text readable over video
5. **No sound needed**: Videos play muted automatically

---

## 🛠️ File Formats Supported

- **MP4** (Recommended) - Best compatibility
- **WebM** - Good for modern browsers
- **OGG** - Alternative format

The system automatically detects the format based on file extension.

---

## 🔍 Troubleshooting

### Video not showing?
1. Check file exists in `assets/img/banners/`
2. Verify filename in database matches actual file
3. Run `check_banners.php` to see banner status
4. Hard refresh browser: `Ctrl + Shift + R`

### Video not playing?
1. Check file is valid MP4
2. Ensure video has no DRM protection
3. Try different browser
4. Check browser console for errors

### Video too large?
Use a video compression tool:
- HandBrake (free)
- Adobe Media Encoder
- Online tools: cloudconvert.com

---

## 📊 Recommended Video Settings

**For optimal performance:**
- Resolution: 1920x800 (or 1920x600)
- Codec: H.264
- Frame Rate: 24-30 fps
- Bitrate: 2-5 Mbps
- File Size: Under 10MB
- Duration: 5-15 seconds

---

## 🎯 Example Use Cases

- **Product Demos**: Show your products in action
- **Event Promotions**: Animated sale announcements
- **Brand Stories**: Short brand intro videos
- **Seasonal Campaigns**: Holiday-themed animations
- **Feature Highlights**: Showcase new features

---

## 📞 Need Help?

Run these helper scripts:
- `check_banners.php` - View all your banners
- `add_video_banner.php` - Add a new video banner

---

**Enjoy your dynamic video banners! 🎉**
