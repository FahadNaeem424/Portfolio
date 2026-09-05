# Personal gallery images

This folder contains optimized WebP copies used by the **My Space** page. The original photographs remain untouched outside the website workspace.

Portraits and workspace photographs have responsive AVIF and WebP variants. Regenerate the original portrait set with `php scripts/optimize-portrait-images.php "C:/path/to/source/photos" original`, the August 2026 additions with `php scripts/optimize-portrait-images.php "C:/path/to/source/photos" 2026-08`, the original workspace set with `php scripts/optimize-workspace-images.php "C:/path/to/source/photos" original`, and the March 2026 workspace additions with `php scripts/optimize-workspace-images.php "C:/path/to/source/photos" 2026-03` from the project root.

To add or reorder photos in the Personal Gallery or Development Workspace collection, edit `data/gallery.php` and keep personal photos before workspace photos. Every entry should use an accurate title, caption, descriptive alternative text, and the image’s actual width and height. Keep filenames meaningful, lowercase, and hyphenated.
