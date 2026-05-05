<x-filament-panels::page>
    <style>
        .prh-container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .prh-container .fi-section,
        .prh-container .fi-section-content,
        .prh-container .fi-section-content-ctn {
            width: 100%;
        }

        .prh-toolbar {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .prh-search-shell {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border: 1px solid #dbe4f0;
            border-radius: 1rem;
            background: #ffffff;
            padding: 0.42rem 0.58rem;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            flex: 1 1 340px;
            min-width: 260px;
        }

        .dark .prh-search-shell {
            background: #0f172a;
            border-color: #334155;
            box-shadow: none;
        }

        .prh-search-icon {
            width: 1.15rem;
            height: 1.15rem;
            color: #64748b;
            flex-shrink: 0;
        }

        .dark .prh-search-icon {
            color: #94a3b8;
        }

        .prh-search {
            width: 100%;
            padding: 0.46rem 0.25rem;
            border-radius: 0;
            border: none;
            background: transparent;
            color: #0f172a;
            font-weight: 600;
            font-size: 0.88rem;
        }

        .prh-search:focus {
            outline: none;
            box-shadow: none;
        }

        .dark .prh-search {
            background: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }

        .prh-search-clear {
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #be123c;
            border-radius: 0.65rem;
            height: 1.85rem;
            min-width: 1.85rem;
            padding: 0 0.55rem;
            font-weight: 800;
            font-size: 0.74rem;
            cursor: pointer;
        }

        .prh-search-clear:hover {
            background: #ffe4e6;
        }

        .dark .prh-search-clear {
            border-color: rgba(251, 113, 133, 0.35);
            background: rgba(190, 24, 93, 0.14);
            color: #fda4af;
        }

        .dark .prh-search-clear:hover {
            background: rgba(190, 24, 93, 0.2);
        }

        .prh-date-shell {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border: 1px solid #dbe4f0;
            border-radius: 0.9rem;
            background: #ffffff;
            padding: 0.35rem 0.45rem;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            flex: 0 1 auto;
        }

        .dark .prh-date-shell {
            background: #0f172a;
            border-color: #334155;
            box-shadow: none;
        }

        .prh-date-input {
            height: 1.9rem;
            padding: 0.2rem 0.55rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.55rem;
            background: #f8fafc;
            color: #0f172a;
            font-size: 0.78rem;
            font-weight: 700;
            min-width: 145px;
            cursor: pointer;
        }

        .dark .prh-date-input {
            background: #111827;
            border-color: #475569;
            color: #f8fafc;
        }

        .prh-date-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.14);
        }

        .prh-date-reset {
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #be123c;
            border-radius: 0.55rem;
            height: 1.9rem;
            padding: 0 0.55rem;
            font-size: 0.72rem;
            font-weight: 800;
            cursor: pointer;
        }

        .prh-date-reset:hover {
            background: #ffe4e6;
        }

        .dark .prh-date-reset {
            border-color: rgba(251, 113, 133, 0.35);
            background: rgba(190, 24, 93, 0.14);
            color: #fda4af;
        }

        .dark .prh-date-reset:hover {
            background: rgba(190, 24, 93, 0.2);
        }

        .prh-date-input::placeholder {
            color: #94a3b8;
        }

        .flatpickr-calendar {
            background: #ffffff !important;
            border: 1px solid #dbe4f0 !important;
            border-radius: 0.9rem !important;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.14) !important;
            width: 280px !important;
            font-size: 0.82rem !important;
        }

        .dark .flatpickr-calendar {
            background: #0f172a !important;
            border-color: #334155 !important;
            box-shadow: 0 16px 36px rgba(2, 6, 23, 0.5) !important;
        }

        .flatpickr-months {
            padding: 0.35rem 0.35rem 0 !important;
        }

        .flatpickr-months .flatpickr-month {
            height: 38px !important;
        }

        .flatpickr-current-month {
            font-size: 0.83rem !important;
            padding-top: 4px !important;
            display: inline-flex !important;
            align-items: center;
            gap: 0.35rem;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            appearance: none !important;
            -webkit-appearance: none !important;
            height: 1.7rem !important;
            border: 1px solid #bfdbfe !important;
            border-radius: 0.45rem !important;
            padding: 0 1.5rem 0 0.55rem !important;
            font-weight: 800 !important;
            font-size: 0.82rem !important;
            color: #0f172a !important;
            background: #eff6ff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%231e3a8a' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 0.4rem center !important;
            cursor: pointer;
            outline: none !important;
            line-height: 1.7rem !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.18) !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #ffffff !important;
            color: #0f172a !important;
            font-weight: 600 !important;
        }

        .flatpickr-current-month input.cur-year {
            font-weight: 800 !important;
            color: #0f172a !important;
        }

        .flatpickr-current-month .numInputWrapper {
            position: relative;
            width: 4.4rem;
            height: 1.7rem;
            border: 1px solid #bfdbfe;
            border-radius: 0.45rem;
            background: #eff6ff;
            overflow: hidden;
        }

        .flatpickr-current-month input.cur-year {
            height: 100% !important;
            color: #0f172a !important;
            font-weight: 800 !important;
            font-size: 0.78rem !important;
            padding-right: 1.05rem !important;
            padding-left: 0.35rem !important;
        }

        .flatpickr-current-month .numInputWrapper span {
            opacity: 1 !important;
            border: 0 !important;
            width: 1.05rem !important;
            right: 0 !important;
            cursor: pointer;
            border-radius: 0 !important;
            transition: background-color 0.18s ease;
            background: rgba(191, 219, 254, 0.45) !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowUp {
            top: 0 !important;
            border-left: 1px solid #93c5fd !important;
            border-bottom: 1px solid #93c5fd !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowDown {
            top: 50% !important;
            border-left: 1px solid #93c5fd !important;
        }

        .flatpickr-current-month .numInputWrapper span:hover {
            background: #dbeafe !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-left-width: 3.5px !important;
            border-right-width: 3.5px !important;
            border-bottom-width: 5.5px !important;
            border-bottom-color: #1e3a8a !important;
            margin-top: -1px !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-left-width: 3.5px !important;
            border-right-width: 3.5px !important;
            border-top-width: 5.5px !important;
            border-top-color: #1e3a8a !important;
            margin-top: 1px !important;
        }

        /* Dark mode — month dropdown */
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #f1f5f9 !important;
            background: rgba(30, 41, 59, 0.95) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%2393c5fd' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 0.4rem center !important;
            border-color: rgba(96, 165, 250, 0.42) !important;
        }

        .dark .flatpickr-current-month .flatpickr-monthDropdown-months:focus {
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.18) !important;
        }

        .dark .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #1e293b !important;
            color: #f1f5f9 !important;
            font-weight: 600 !important;
        }

        .dark .flatpickr-current-month input.cur-year {
            color: #f8fafc !important;
        }

        .dark .flatpickr-current-month .numInputWrapper {
            border-color: rgba(96, 165, 250, 0.42);
            background: rgba(59, 130, 246, 0.18);
        }

        .dark .flatpickr-current-month .numInputWrapper span {
            background: rgba(96, 165, 250, 0.22) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowUp {
            border-left-color: rgba(96, 165, 250, 0.4) !important;
            border-bottom-color: rgba(96, 165, 250, 0.4) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowDown {
            border-left-color: rgba(96, 165, 250, 0.4) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span:hover {
            background: rgba(96, 165, 250, 0.35) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: #93c5fd !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: #93c5fd !important;
        }

        .flatpickr-prev-month,
        .flatpickr-next-month {
            width: 1.25rem !important;
            height: 1.25rem !important;
            border-radius: 0 !important;
            border: 0 !important;
            background: transparent !important;
            color: #1e3a8a !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            top: 5px !important;
            transition: color 0.18s ease;
        }

        .flatpickr-prev-month:hover,
        .flatpickr-next-month:hover {
            background: transparent !important;
            color: #1d4ed8 !important;
        }

        .dark .flatpickr-prev-month,
        .dark .flatpickr-next-month {
            color: #93c5fd !important;
        }

        .dark .flatpickr-prev-month:hover,
        .dark .flatpickr-next-month:hover {
            color: #bfdbfe !important;
        }

        .flatpickr-prev-month svg,
        .flatpickr-next-month svg,
        .flatpickr-prev-month svg *,
        .flatpickr-next-month svg * {
            fill: currentColor !important;
            stroke: currentColor !important;
            stroke-width: 1.4px !important;
        }

        .flatpickr-weekday {
            color: #64748b !important;
            font-size: 0.67rem !important;
            font-weight: 800 !important;
        }

        .dark .flatpickr-weekday {
            color: #94a3b8 !important;
        }

        .flatpickr-day {
            border-radius: 0.55rem !important;
            color: #0f172a !important;
            font-weight: 700 !important;
        }

        .dark .flatpickr-day {
            color: #f8fafc !important;
        }

        .flatpickr-day:hover {
            background: #eff6ff !important;
            border-color: #bfdbfe !important;
        }

        .dark .flatpickr-day:hover {
            background: rgba(59, 130, 246, 0.18) !important;
            border-color: rgba(96, 165, 250, 0.3) !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
        }

        .dark .flatpickr-day.selected,
        .dark .flatpickr-day.startRange,
        .dark .flatpickr-day.endRange {
            background: #60a5fa !important;
            border-color: #60a5fa !important;
            color: #0f172a !important;
        }

        .prh-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            width: 100%;
        }

        /* ── PR Row card ── */
        .prh-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #6366f1;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1rem;
            align-items: center;
            box-shadow: 0 1px 4px rgba(15,23,42,.04);
            transition: box-shadow .18s, transform .18s;
        }
        .prh-row:hover {
            box-shadow: 0 6px 18px rgba(99,102,241,.12);
            transform: translateY(-1px);
        }
        .dark .prh-row {
            background: #1e293b;
            border-color: #334155;
            border-left-color: #818cf8;
        }
        .prh-row-icon {
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 0.75rem;
            background: #ede9fe;
            color: #6d28d9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .dark .prh-row-icon {
            background: rgba(139,92,246,.2);
            color: #c4b5fd;
        }
        .prh-row-icon svg { width: 1.2rem; height: 1.2rem; }
        .prh-row-body { min-width: 0; }
        .prh-row-top {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-bottom: 0.35rem;
        }
        .prh-title {
            font-weight: 800;
            color: #4f46e5;
            font-size: 0.97rem;
            line-height: 1.3;
        }
        .dark .prh-title { color: #818cf8; }
        .prh-title-sub {
            font-size: 0.82rem;
            font-weight: 600;
            color: #64748b;
        }
        .dark .prh-title-sub { color: #94a3b8; }
        .prh-meta-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem 1.25rem;
            margin-top: 0.25rem;
        }
        .prh-meta-item {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 600;
        }
        .dark .prh-meta-item { color: #94a3b8; }
        .prh-meta-item svg { width: 0.85rem; height: 0.85rem; flex-shrink: 0; }
        .prh-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.28rem;
            padding: 0.22rem 0.65rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .prh-badge-dot {
            width: 0.4rem;
            height: 0.4rem;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .prh-badge-warning { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
        .prh-badge-warning .prh-badge-dot { background: #f59e0b; }
        .prh-badge-danger  { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
        .prh-badge-danger .prh-badge-dot  { background: #ef4444; }
        .prh-badge-success { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .prh-badge-success .prh-badge-dot { background: #10b981; }
        .prh-badge-info    { background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; }
        .prh-badge-info .prh-badge-dot    { background: #3b82f6; }
        .prh-badge-gray    { background:#f8fafc; color:#475569; border:1px solid #e2e8f0; }
        .prh-badge-gray .prh-badge-dot    { background: #9ca3af; }
        .dark .prh-badge-warning { background:rgba(245,158,11,.12); color:#fbbf24; border-color:rgba(245,158,11,.25); }
        .dark .prh-badge-danger  { background:rgba(239,68,68,.12); color:#f87171; border-color:rgba(239,68,68,.25); }
        .dark .prh-badge-success { background:rgba(16,185,129,.12); color:#34d399; border-color:rgba(16,185,129,.25); }
        .dark .prh-badge-info    { background:rgba(59,130,246,.12); color:#60a5fa; border-color:rgba(59,130,246,.25); }
        .dark .prh-badge-gray    { background:rgba(107,114,128,.12); color:#d1d5db; border-color:rgba(107,114,128,.25); }
        .prh-detail-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border: none;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #ffffff;
            border-radius: 0.75rem;
            padding: 0.55rem 1rem;
            font-weight: 700;
            font-size: 0.82rem;
            cursor: pointer;
            white-space: nowrap;
            transition: all .18s;
            flex-shrink: 0;
        }
        .prh-detail-btn svg { width: 0.8rem; height: 0.8rem; }
        .prh-detail-btn:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79,70,229,.35);
        }

        .prh-empty {
            border: 1px dashed #cbd5e1;
            border-radius: 1rem;
            padding: 2.4rem 1.2rem;
            min-height: clamp(360px, 58vh, 620px);
            width: 100%;
            text-align: center;
            color: #64748b;
            font-weight: 600;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .dark .prh-empty {
            border-color: #475569;
            color: #94a3b8;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
        }

        .prh-empty-icon-wrap {
            width: 5rem;
            height: 5rem;
            border-radius: 999px;
            margin: 0 auto 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #1e40af;
        }

        .dark .prh-empty-icon-wrap {
            background: rgba(59, 130, 246, 0.18);
            color: #93c5fd;
        }

        .prh-empty-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
        }

        .dark .prh-empty-title {
            color: #f8fafc;
        }

        .prh-empty-text {
            margin-top: 0.45rem;
            font-size: 0.95rem;
            max-width: 640px;
            line-height: 1.5;
            color: #64748b;
        }

        .dark .prh-empty-text {
            color: #94a3b8;
        }

        /* ── Modal overlay ── */
        .prh-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(2,6,23,0.6);
            backdrop-filter: blur(4px);
            z-index: 9000;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 1.5rem 1rem;
            overflow-y: auto;
        }
        .prh-modal {
            width: 100%;
            max-width: 860px;
            background: #ffffff;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(2,6,23,.28);
            display: flex;
            flex-direction: column;
            animation: prhSlideUp .25s cubic-bezier(.22,1,.36,1);
        }
        @keyframes prhSlideUp {
            from { transform: translateY(1.5rem); opacity:0; }
            to   { transform: translateY(0);      opacity:1; }
        }
        .dark .prh-modal {
            background: #1e293b;
            box-shadow: 0 25px 60px rgba(0,0,0,.55);
        }
        /* Modal header */
        .prh-modal-header {
            padding: 1.1rem 1.5rem;
            background: linear-gradient(135deg,#312e81 0%,#4f46e5 55%,#7c3aed 100%);
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }
        .prh-modal-header-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }
        .prh-modal-header-icon {
            width: 2.2rem;
            height: 2.2rem;
            border-radius: 0.6rem;
            background: rgba(255,255,255,.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .prh-modal-header-icon svg { width:1.1rem; height:1.1rem; }
        .prh-modal-title { font-size:1.05rem; font-weight:800; }
        .prh-modal-subtitle { font-size:0.78rem; opacity:0.8; margin-top:0.1rem; font-weight:600; }
        .prh-close {
            width: 2rem;
            height: 2rem;
            border: 1.5px solid rgba(255,255,255,.35);
            background: rgba(255,255,255,.12);
            color: #fff;
            border-radius: 0.55rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background .15s;
        }
        .prh-close:hover { background: rgba(255,255,255,.25); }
        .prh-close svg { width:1rem; height:1rem; }
        /* Modal body */
        .prh-modal-body {
            padding: 1.25rem 1.5rem 1.5rem;
            overflow-y: auto;
            max-height: calc(90vh - 70px);
            background: #f1f5f9;
        }
        .dark .prh-modal-body { background: #0f172a; }
        /* Steps timeline */
        .prh-steps-list {
            display: flex;
            flex-direction: column;
            gap: 0;
            position: relative;
        }
        .prh-steps-list::before {
            content: '';
            position: absolute;
            left: 1.35rem;
            top: 1.5rem;
            bottom: 1.5rem;
            width: 2px;
            background: linear-gradient(180deg,#6366f1,#a78bfa);
            border-radius: 2px;
        }
        .prh-step {
            display: flex;
            gap: 1rem;
            padding: 0.6rem 0;
            position: relative;
        }
        .prh-step-num {
            width: 2.7rem;
            height: 2.7rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
            z-index: 1;
            box-shadow: 0 0 0 3px #f1f5f9;
        }
        .dark .prh-step-num { box-shadow: 0 0 0 3px #0f172a; }
        .prh-step-num-success { background: linear-gradient(135deg,#059669,#0d9488); }
        .prh-step-num-danger  { background: linear-gradient(135deg,#dc2626,#e11d48); }
        .prh-step-num-warning { background: linear-gradient(135deg,#d97706,#b45309); }
        .prh-step-num-info    { background: linear-gradient(135deg,#2563eb,#3b82f6); }
        .prh-step-num-gray    { background: linear-gradient(135deg,#64748b,#475569); }
        .prh-step-card {
            flex: 1;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.9rem;
            padding: 0.85rem 1rem;
            margin-bottom: 0.5rem;
            box-shadow: 0 1px 3px rgba(15,23,42,.05);
        }
        .dark .prh-step-card {
            background: #1e293b;
            border-color: #334155;
        }
        .prh-step-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.5rem;
        }
        .prh-step-label {
            font-size: 0.88rem;
            font-weight: 800;
            color: #0f172a;
        }
        .dark .prh-step-label { color: #f1f5f9; }
        .prh-step-meta-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem 1rem;
            margin-top: 0.35rem;
        }
        .prh-step-meta-item {
            display: flex;
            align-items: center;
            gap: 0.28rem;
            font-size: 0.76rem;
            color: #64748b;
            font-weight: 600;
        }
        .dark .prh-step-meta-item { color: #94a3b8; }
        .prh-step-meta-item svg { width:0.8rem; height:0.8rem; flex-shrink:0; }
        /* Changes section inside step */
        .prh-changes-block {
            margin-top: 0.65rem;
            padding: 0.65rem 0.75rem;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 0.65rem;
        }
        .dark .prh-changes-block { background:#0f172a; border-color:#1e293b; }
        .prh-changes-title {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #94a3b8;
            margin-bottom: 0.45rem;
        }
        .prh-change-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.78rem;
            padding: 0.2rem 0;
            color: #475569;
            font-weight: 600;
        }
        .dark .prh-change-row { color: #cbd5e1; }
        .prh-change-field { color: #1e40af; font-weight: 700; }
        .dark .prh-change-field { color: #60a5fa; }
        .prh-change-arrow { color: #94a3b8; font-size: 0.7rem; }
        .prh-change-from { color: #ef4444; text-decoration: line-through; }
        .prh-change-to   { color: #10b981; font-weight: 700; }
        /* Item change pills */
        .prh-item-changes {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 0.5rem;
        }
        .prh-item-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.18rem 0.55rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .prh-item-pill-added   { background:#d1fae5; color:#065f46; }
        .prh-item-pill-removed { background:#fee2e2; color:#991b1b; }
        .prh-item-pill-updated { background:#fef3c7; color:#92400e; }
        .dark .prh-item-pill-added   { background:rgba(16,185,129,.15); color:#34d399; }
        .dark .prh-item-pill-removed { background:rgba(239,68,68,.15);  color:#f87171; }
        .dark .prh-item-pill-updated { background:rgba(245,158,11,.15); color:#fbbf24; }
        .prh-pagination-wrap {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .prh-row {
                grid-template-columns: 1fr;
            }

            .prh-detail-btn {
                width: 100%;
            }

            .prh-date-shell {
                width: 100%;
                flex-wrap: wrap;
            }

            .prh-date-input,
            .prh-date-reset {
                width: 100%;
            }
        }
    </style>

    @assets
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endassets

    <div class="prh-container">
        <x-filament::section>
            <div class="prh-toolbar">
                <div class="prh-search-shell">
                    <svg class="prh-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                    </svg>
                    <input
                        type="text"
                        class="prh-search"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Cari riwayat berdasarkan nomor PR, judul, kebutuhan, atau nomor dokumen...">

                    @if($search !== '')
                    <button type="button" class="prh-search-clear" wire:click="$set('search', '')" aria-label="Reset pencarian" title="Reset pencarian">
                        Reset
                    </button>
                    @endif
                </div>

                <div class="prh-date-shell">
                    <input
                        type="text"
                        class="prh-date-input"
                        placeholder="Tanggal awal"
                        readonly
                        value="{{ $startDate }}"
                        wire:ignore
                        x-data="{
                            init() {
                                if ($el._flatpickr) {
                                    $el._flatpickr.destroy();
                                }

                                const picker = flatpickr($el, {
                                    dateFormat: 'Y-m-d',
                                    altInput: true,
                                    altFormat: 'd M Y',
                                    defaultDate: @js($startDate),
                                    disableMobile: true,
                                    clickOpens: true,
                                    onChange: (selectedDates, dateStr) => {
                                        $wire.set('startDate', dateStr || null);
                                    },
                                });

                                window.addEventListener('pr-history-date-filters-reset', () => {
                                    picker.clear();
                                });
                            }
                        }"
                        aria-label="Filter tanggal awal">
                    <input
                        type="text"
                        class="prh-date-input"
                        placeholder="Tanggal akhir"
                        readonly
                        value="{{ $endDate }}"
                        wire:ignore
                        x-data="{
                            init() {
                                if ($el._flatpickr) {
                                    $el._flatpickr.destroy();
                                }

                                const picker = flatpickr($el, {
                                    dateFormat: 'Y-m-d',
                                    altInput: true,
                                    altFormat: 'd M Y',
                                    defaultDate: @js($endDate),
                                    disableMobile: true,
                                    clickOpens: true,
                                    onChange: (selectedDates, dateStr) => {
                                        $wire.set('endDate', dateStr || null);
                                    },
                                });

                                window.addEventListener('pr-history-date-filters-reset', () => {
                                    picker.clear();
                                });
                            }
                        }"
                        aria-label="Filter tanggal akhir">
                    <button type="button" class="prh-date-reset" wire:click="resetDateFilters" title="Reset filter tanggal">Reset</button>
                </div>
            </div>

            <div class="prh-list">
                @forelse($historyList as $row)
                @php
                $statusColor = \App\Constants\PrStatusConstant::getColor($row->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
                $statusLabel = $statusLabels[$row->pr_status] ?? ($row->pr_status ?? 'Menunggu');
                @endphp
                <div class="prh-row">
                    {{-- Icon --}}
                    <div class="prh-row-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                    </div>

                    {{-- Body --}}
                    <div class="prh-row-body">
                        <div class="prh-row-top">
                            <span class="prh-title">{{ $row->pr_number ?? '-' }}</span>
                            @if($row->title)
                            <span class="prh-title-sub">&mdash; {{ $row->title }}</span>
                            @endif
                            <span class="prh-badge prh-badge-{{ $statusColor }}">
                                <span class="prh-badge-dot"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div class="prh-meta-grid">
                            @if($row->document_no)
                            <span class="prh-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                {{ $row->document_no }}
                            </span>
                            @endif
                            @if($row->needs)
                            <span class="prh-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z" /></svg>
                                {{ $row->needs }}
                            </span>
                            @endif
                            <span class="prh-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $row->updated_at?->format('d M Y, H:i') ?? '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Action button --}}
                    <button class="prh-detail-btn" wire:click="showFlowDetails('{{ $row->batch_id }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Selengkapnya
                    </button>
                </div>
                @empty
                <div class="prh-empty">
                    <div class="prh-empty-icon-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor" style="width:2.1rem;height:2.1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="prh-empty-title">Riwayat Pengajuan Belum Tersedia</div>
                    <div class="prh-empty-text">Belum ada flow PR selesai (approved, rejected, atau closed) untuk akun ini.</div>
                </div>
                @endforelse
            </div>

            <div class="prh-pagination-wrap">
                {{ $historyList->links() }}
            </div>
        </x-filament::section>
    </div>

    @if($showFlowModal && $selectedFlowHeader)
    <div class="prh-modal-overlay" wire:click.self="closeFlowDetails">
        <div class="prh-modal" role="dialog" aria-modal="true" aria-labelledby="prh-modal-title">

            {{-- Header --}}
            <div class="prh-modal-header">
                <div class="prh-modal-header-left">
                    <div class="prh-modal-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color:#c7d2fe;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div>
                        <div class="prh-modal-title" id="prh-modal-title">Alur Persetujuan &mdash; {{ $selectedFlowHeader['pr_number'] ?? '-' }}</div>
                        <div class="prh-modal-subtitle">Nomor Dokumen {{ $selectedFlowHeader['document_no'] ?? '-' }} &nbsp;&bull;&nbsp; Status akhir: {{ $selectedFlowHeader['status_label'] ?? '-' }}</div>
                    </div>
                </div>
                <button class="prh-close" type="button" wire:click="closeFlowDetails" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="prh-modal-body">
                <div class="prh-steps-list">
                    @foreach($flowSteps as $index => $step)
                    @php
                    $color = \App\Constants\PrStatusConstant::getColor($step['status'] ?? \App\Constants\PrStatusConstant::PENDING);
                    $fieldChanges = $flowFieldChanges[$index] ?? [];
                    $itemChanges = $flowItemChanges[$index] ?? ['added' => [], 'updated' => [], 'removed' => []];
                    $hasChanges = !empty($fieldChanges) || !empty($itemChanges['added']) || !empty($itemChanges['updated']) || !empty($itemChanges['removed']);
                    @endphp
                    <div class="prh-step">
                        <div class="prh-step-num prh-step-num-{{ $color }}">{{ $index + 1 }}</div>
                        <div class="prh-step-card">
                            <div class="prh-step-card-top">
                                <span class="prh-step-label">Tahap {{ $index + 1 }}: {{ $step['role'] ?? '-' }}</span>
                                <span class="prh-badge prh-badge-{{ $color }}">
                                    <span class="prh-badge-dot"></span>
                                    {{ $step['status_label'] ?? '-' }}
                                </span>
                            </div>
                            <div class="prh-step-meta-grid">
                                <span class="prh-step-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    {{ $step['approver'] ?? '-' }}
                                </span>
                                <span class="prh-step-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $step['created_at'] ?? '-' }}
                                </span>
                                @if($step['step'] ?? null)
                                <span class="prh-step-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" /></svg>
                                    Step {{ $step['step'] }}
                                </span>
                                @endif
                            </div>

                            @if($hasChanges)
                            <div class="prh-changes-block">
                                @if(!empty($fieldChanges))
                                <div class="prh-changes-title">Perubahan Field</div>
                                @foreach($fieldChanges as $change)
                                <div class="prh-change-row">
                                    <span class="prh-change-field">{{ $change['field'] ?? '-' }}</span>
                                    <span class="prh-change-from">{{ $change['from'] ?? '-' }}</span>
                                    <span class="prh-change-arrow">&rarr;</span>
                                    <span class="prh-change-to">{{ $change['to'] ?? '-' }}</span>
                                </div>
                                @endforeach
                                @endif

                                @if(!empty($itemChanges['added']) || !empty($itemChanges['updated']) || !empty($itemChanges['removed']))
                                <div class="prh-changes-title" style="margin-top:{{ !empty($fieldChanges) ? '0.6rem' : '0' }};">Perubahan Item</div>
                                <div class="prh-item-changes">
                                    @if(!empty($itemChanges['added']))
                                    <span class="prh-item-pill prh-item-pill-added">+ {{ count($itemChanges['added']) }} ditambahkan</span>
                                    @endif
                                    @if(!empty($itemChanges['removed']))
                                    <span class="prh-item-pill prh-item-pill-removed">&minus; {{ count($itemChanges['removed']) }} dihapus</span>
                                    @endif
                                    @if(!empty($itemChanges['updated']))
                                    <span class="prh-item-pill prh-item-pill-updated">&#9998; {{ count($itemChanges['updated']) }} diubah</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    @endif
</x-filament-panels::page>
