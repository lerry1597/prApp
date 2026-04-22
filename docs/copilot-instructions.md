# Copilot Instructions

## Context

Project ini menggunakan:

- Laravel 13
- Filament 5
- PHP 8.4+
- Arsitektur: Ikuti arsitektur filament (service layer, resource, widget, dll)

## Rules

- Gunakan naming yang konsisten (camelCase untuk method)
- Semua endpoint harus pakai validation
- Utamakan fitur bawaan Filament (Resource, Form, Table, Relation Manager)
- Hindari membuat CRUD manual jika bisa menggunakan Filament Resource
- Gunakan Form Builder dan Table Builder dari Filament
- Gunakan Action, Bulk Action, dan Widget dari Filament
- Gunakan Policy untuk authorization (terintegrasi dengan Filament)
- Gunakan Fiture Filament tanpa customisasi berlebihan

## Coding Style

- Gunakan clean architecture
- Hindari logic di blade
- Gunakan plugin Filament untuk UI
