# DMS Flowchart Scripts

Berikut adalah file-file Mermaid diagram untuk sistem DMS DAPEN:

## 1. Arsitektur Sistem

File: `01-arsitektur-sistem.mmd`

Menampilkan arsitektur lengkap sistem dengan 4 layer utama:

- User Layer (Login, Dashboard, Profile)
- RBAC Layer (Roles, Privileges, Functions)
- Document Modules (8 modul: Akuntansi, Anggaran, Hukum, Investasi, Keuangan, SDM, Sekretariat, Logistik)
- Core Features & Admin

## 2. Alur Utama Sistem

File: `02-alur-utama-sistem.mmd`

Flowchart lengkap dari login hingga aksi dokumen:

- Login & Authentication
- Dashboard navigation
- Document CRUD operations
- File access request flow
- Download permission checks

## 3. RBAC Flow

File: `03-rbac-flow.mmd`

Diagram Role-Based Access Control:

- User authentication & role loading
- Access scope (Global vs Division)
- Function permissions (View, Create, Edit, Delete, Download, Upload, Approval)
- Access check mechanism

## 4. File Access Request

File: `04-file-access-request.mmd`

Workflow permintaan akses file rahasia:

- Request submission
- Admin approval/rejection
- Permission assignment
- Download limit & expiry checks

## 5. Document Versioning

File: `05-document-versioning.mmd`

Alur version control dokumen:

- Version creation logic
- File storage mechanism
- Version history tracking

## 6. Super Admin Flow

File: `06-super-admin-flow.mmd`

Flowchart lengkap untuk Super Admin:

- User Management (CRUD, Role Assignment, Jabatan History)
- Role & Privilege Management (Access Scope, Function Assignment)
- Menu Management (Create, Edit, Reorder)
- Master Data Management (Divisi, Department, Jabatan)
- Document Assignment (Statistics, Bulk Reassign)
- Access Request Management (Approve/Reject, Manual Assignment)

## 7. Super Admin Capabilities

File: `07-super-admin-capabilities.mmd`

Breakdown detail kemampuan Super Admin:

- Functional areas breakdown
- Database tables yang dikelola
- Relasi antar komponen
- 58 document tables management

## 8. User Creation Flow

File: `08-user-creation-flow.mmd`

Flowchart lengkap pembuatan user oleh Super Admin:

- **Persiapan Master Data** (Urutan wajib):
    1. Department
    2. Divisi (FK: Department)
    3. Role (FK: Divisi optional)
    4. Privileges (FK: Role, Menu, Function)
    5. Jabatan (FK: Divisi, Default Role)
- **Form Input User** dengan auto-load/auto-suggest
- **Backend Processing** (Validation, Hash, Insert)
- **Database Relations** yang terbentuk

## 9. User Creation Overview

File: `09-user-creation-overview.mmd`

Diagram overview sederhana untuk user creation:

- Sequential steps preparation
- Data flow dari master data ke user
- Database tables yang terlibat

---

## Cara Menggunakan

### Online (Mermaid Live Editor)

1. Buka https://mermaid.live/
2. Copy isi file .mmd
3. Paste di editor
4. Diagram akan otomatis ter-render

### VS Code

1. Install extension "Markdown Preview Mermaid Support"
2. Buka file .mmd
3. Tekan `Ctrl+Shift+V` untuk preview

### Markdown

Embed dalam markdown dengan:
\`\`\`mermaid
[isi diagram]
\`\`\`
