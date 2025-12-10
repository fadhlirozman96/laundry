#!/usr/bin/env python
# -*- coding: utf-8 -*-

with open('resources/views/product-list.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

print(f"Total lines before: {len(lines)}")
print(f"Line 195 (index 194): {lines[194].strip()}")
print(f"Line 196 (index 195): {lines[195].strip()[:50]}")
print(f"Line 513 (index 512): {lines[512].strip()}")

# Keep lines 0-195 (index 0-194, includes @endforelse)
# Then add line 513 onwards (index 512 onwards, starts with </tbody>)
new_lines = lines[:195] + lines[512:]

print(f"Total lines after: {len(new_lines)}")
print(f"Lines removed: {len(lines) - len(new_lines)}")

with open('resources/views/product-list.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(new_lines)

print("File fixed successfully!")
