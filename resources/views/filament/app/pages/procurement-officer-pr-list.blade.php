<x-filament-panels::page>
    <style>
        /* ===================================================
           PROCUREMENT OFFICER — PR LIST  (Modern Design)
           =================================================== */

        /* ── Root container ── */
        .poc-root {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
        }

        /* ── Stats Strip ── */
        .poc-stats-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.75rem;
        }

        .poc-stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.9rem;
            box-shadow: 0 1px 4px rgba(15, 23, 42, .05);
            transition: box-shadow .18s;
        }

        .poc-stat-card:hover {
            box-shadow: 0 4px 14px rgba(15, 23, 42, .09);
        }

        .dark .poc-stat-card {
            background: #1e293b;
            border-color: #334155;
            box-shadow: none;
        }

        .poc-stat-icon {
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .poc-stat-icon svg {
            width: 1.2rem;
            height: 1.2rem;
        }

        .poc-stat-icon.blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .poc-stat-icon.amber {
            background: #fef3c7;
            color: #b45309;
        }

        .poc-stat-icon.green {
            background: #d1fae5;
            color: #065f46;
        }

        .poc-stat-icon.rose {
            background: #fee2e2;
            color: #be123c;
        }

        .poc-stat-icon.violet {
            background: #ede9fe;
            color: #6d28d9;
        }

        .dark .poc-stat-icon.blue {
            background: rgba(59, 130, 246, .15);
            color: #93c5fd;
        }

        .dark .poc-stat-icon.amber {
            background: rgba(245, 158, 11, .15);
            color: #fbbf24;
        }

        .dark .poc-stat-icon.green {
            background: rgba(16, 185, 129, .15);
            color: #34d399;
        }

        .dark .poc-stat-icon.rose {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
        }

        .dark .poc-stat-icon.violet {
            background: rgba(139, 92, 246, .15);
            color: #a78bfa;
        }

        .poc-stat-body {}

        .poc-stat-value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .dark .poc-stat-value {
            color: #f8fafc;
        }

        .poc-stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-top: .15rem;
        }

        .dark .poc-stat-label {
            color: #94a3b8;
        }

        /* ── Toolbar ── */
        .poc-toolbar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 1px 4px rgba(15, 23, 42, .04);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .75rem;
        }

        .dark .poc-toolbar {
            background: #1e293b;
            border-color: #334155;
        }

        .poc-search-wrap {
            position: relative;
            flex: 1 1 220px;
            min-width: 0;
        }

        .poc-search-icon {
            position: absolute;
            left: .85rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1rem;
            height: 1rem;
            color: #94a3b8;
            pointer-events: none;
        }

        .poc-search-input {
            width: 100%;
            padding: .65rem .9rem .65rem 2.4rem;
            border-radius: .75rem;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: .875rem;
            font-weight: 600;
            transition: all .18s;
        }

        .poc-search-input:focus {
            outline: none;
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        }

        .dark .poc-search-input {
            background: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }

        .dark .poc-search-input:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, .16);
        }

        .poc-search-input::placeholder {
            color: #94a3b8;
        }

        .poc-filter-select {
            padding: .65rem .9rem;
            border-radius: .75rem;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            flex: 0 0 auto;
            transition: all .18s;
            min-width: 170px;
        }

        .poc-filter-select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        }

        .dark .poc-filter-select {
            background: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }

        .poc-reset-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .65rem 1rem;
            border-radius: .75rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            font-size: .8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .18s;
            flex-shrink: 0;
        }

        .poc-reset-btn:hover {
            background: #fee2e2;
            border-color: #fecaca;
            color: #be123c;
        }

        .dark .poc-reset-btn {
            background: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }

        .dark .poc-reset-btn:hover {
            background: rgba(239, 68, 68, .15);
            border-color: rgba(239, 68, 68, .3);
            color: #f87171;
        }

        .poc-reset-btn svg {
            width: .85rem;
            height: .85rem;
        }

        /* ── Table card ── */
        .poc-table-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.1rem;
            box-shadow: 0 1px 4px rgba(15, 23, 42, .04);
            overflow: hidden;
        }

        .dark .poc-table-card {
            background: #1e293b;
            border-color: #334155;
        }

        .poc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .875rem;
        }

        .poc-table thead th {
            background: #f8fafc;
            padding: .85rem 1rem;
            text-align: left;
            font-size: .7rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .07em;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .dark .poc-table thead th {
            background: #0f172a;
            color: #64748b;
            border-bottom-color: #334155;
        }

        .poc-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background .14s;
        }

        .poc-table tbody tr:last-child {
            border-bottom: none;
        }

        .poc-table tbody tr:hover {
            background: #f8fafc;
        }

        .dark .poc-table tbody tr {
            border-bottom-color: #1e293b;
        }

        .dark .poc-table tbody tr:hover {
            background: #0f172a;
        }

        .poc-table td {
            padding: .9rem 1rem;
            color: #0f172a;
            vertical-align: middle;
        }

        .dark .poc-table td {
            color: #e2e8f0;
        }

        .poc-pr-number {
            font-weight: 800;
            color: #4f46e5;
            font-size: .9rem;
        }

        .dark .poc-pr-number {
            color: #818cf8;
        }

        .poc-vessel-name {
            font-weight: 700;
            color: #0f172a;
        }

        .dark .poc-vessel-name {
            color: #f1f5f9;
        }

        .poc-needs-text {
            color: #475569;
            font-weight: 500;
        }

        .dark .poc-needs-text {
            color: #94a3b8;
        }

        .poc-date-text {
            color: #64748b;
            font-size: .82rem;
            white-space: nowrap;
        }

        .dark .poc-date-text {
            color: #64748b;
        }

        .poc-requester-text {
            color: #374151;
            font-weight: 600;
            font-size: .82rem;
        }

        .dark .poc-requester-text {
            color: #cbd5e1;
        }

        /* ── Status pill ── */
        .poc-status-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .28rem .75rem;
            border-radius: 9999px;
            font-size: .72rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .poc-status-pill .poc-dot {
            width: .45rem;
            height: .45rem;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .poc-pill-warning {
            background: #fffbeb;
            color: #92400e;
        }

        .poc-pill-danger {
            background: #fef2f2;
            color: #991b1b;
        }

        .poc-pill-info {
            background: #eff6ff;
            color: #1e40af;
        }

        .poc-pill-success {
            background: #ecfdf5;
            color: #065f46;
        }

        .poc-pill-gray {
            background: #f9fafb;
            color: #4b5563;
        }

        .poc-pill-violet {
            background: #ede9fe;
            color: #5b21b6;
        }

        .poc-dot-warning {
            background: #f59e0b;
        }

        .poc-dot-danger {
            background: #ef4444;
        }

        .poc-dot-info {
            background: #3b82f6;
        }

        .poc-dot-success {
            background: #10b981;
        }

        .poc-dot-gray {
            background: #9ca3af;
        }

        .poc-dot-violet {
            background: #8b5cf6;
        }

        .dark .poc-pill-warning {
            background: rgba(245, 158, 11, .12);
            color: #fbbf24;
        }

        .dark .poc-pill-danger {
            background: rgba(239, 68, 68, .12);
            color: #f87171;
        }

        .dark .poc-pill-info {
            background: rgba(59, 130, 246, .12);
            color: #60a5fa;
        }

        .dark .poc-pill-success {
            background: rgba(16, 185, 129, .12);
            color: #34d399;
        }

        .dark .poc-pill-gray {
            background: rgba(107, 114, 128, .1);
            color: #d1d5db;
        }

        .dark .poc-pill-violet {
            background: rgba(139, 92, 246, .12);
            color: #a78bfa;
        }

        /* ── Review button ── */
        .poc-review-btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .5rem 1.1rem;
            border-radius: .75rem;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #ffffff;
            font-size: .8rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all .18s;
            white-space: nowrap;
        }

        .poc-review-btn:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, .35);
        }

        .poc-review-btn svg {
            width: .8rem;
            height: .8rem;
        }

        /* ── Empty state ── */
        .poc-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 5rem 2rem;
            gap: 1rem;
        }

        .poc-empty-icon {
            width: 4rem;
            height: 4rem;
            color: #cbd5e1;
        }

        .poc-empty-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #475569;
        }

        .poc-empty-sub {
            font-size: .875rem;
            color: #94a3b8;
        }

        .dark .poc-empty-title {
            color: #94a3b8;
        }

        /* ── Pagination ── */
        .poc-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .85rem 1rem;
            border-top: 1px solid #f1f5f9;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .dark .poc-pagination {
            border-top-color: #1e293b;
        }

        .poc-pagination-info {
            font-size: .8rem;
            color: #64748b;
            font-weight: 600;
        }

        .poc-pagination-btns {
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        .poc-page-btn {
            min-width: 2rem;
            height: 2rem;
            padding: 0 .65rem;
            border-radius: .5rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #374151;
            font-size: .8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
        }

        .poc-page-btn:hover {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #ffffff;
        }

        .poc-page-btn.active {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #ffffff;
        }

        .poc-page-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .poc-page-btn:disabled:hover {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #374151;
        }

        .dark .poc-page-btn {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }

        .dark .poc-page-btn:hover,
        .dark .poc-page-btn.active {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #ffffff;
        }

        .dark .poc-page-btn:disabled:hover {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }

        /* ══════════════════════════════════════════
           DETAIL SIDE-PANEL  →  Full-screen Overlay
           ══════════════════════════════════════════ */
        .poc-panel-backdrop {
            position: fixed;
            inset: 0;
            z-index: 8000;
            background: rgba(2, 6, 23, .55);
            backdrop-filter: blur(4px);
            animation: pocFadeIn .2s ease;
        }

        @keyframes pocFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes pocSlideUp {
            from {
                transform: translateY(2rem);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .poc-panel {
            position: fixed;
            inset: 0;
            z-index: 8001;
            display: flex;
            flex-direction: column;
            background: #f1f5f9;
            animation: pocSlideUp .25s cubic-bezier(.22, 1, .36, 1);
            overflow: hidden;
        }

        .dark .poc-panel {
            background: #0f172a;
        }

        /* Panel header — top bar */
        .poc-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.75rem;
            flex-shrink: 0;
            background: linear-gradient(135deg, #312e81 0%, #4f46e5 50%, #7c3aed 100%);
            box-shadow: 0 2px 12px rgba(79, 70, 229, .35);
            gap: 1rem;
        }

        .poc-panel-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0;
        }

        .poc-panel-back-btn {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: .6rem;
            border: 1.5px solid rgba(255, 255, 255, .3);
            background: rgba(255, 255, 255, .12);
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background .15s;
        }

        .poc-panel-back-btn:hover {
            background: rgba(255, 255, 255, .25);
        }

        .poc-panel-back-btn svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        .poc-panel-header-info {
            min-width: 0;
        }

        .poc-panel-kicker {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .09em;
            color: rgba(255, 255, 255, .6);
            line-height: 1;
        }

        .poc-panel-pr-number {
            font-size: 1.35rem;
            font-weight: 900;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .poc-panel-header-actions {
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-shrink: 0;
        }

        .poc-panel-close-btn {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: .6rem;
            border: 1.5px solid rgba(255, 255, 255, .3);
            background: rgba(255, 255, 255, .12);
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
        }

        .poc-panel-close-btn:hover {
            background: rgba(255, 255, 255, .25);
        }

        .poc-panel-close-btn svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        /* Panel body — scrollable area */
        .poc-panel-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        /* Two-column layout: info left, items right */
        .poc-detail-layout {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 1.25rem;
            align-items: start;
            max-width: 1600px;
            margin: 0 auto;
        }

        @media (max-width: 960px) {
            .poc-detail-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Left column */
        .poc-detail-left {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: sticky;
            top: 0;
        }

        @media (max-width: 960px) {
            .poc-detail-left {
                position: static;
            }
        }

        /* Status banner */
        .poc-status-banner {
            background: #fff;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }

        .dark .poc-status-banner {
            background: #1e293b;
            border-color: #334155;
        }

        .poc-sb-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .poc-sb-label {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #94a3b8;
        }

        /* Info card */
        .poc-info-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .dark .poc-info-card {
            background: #1e293b;
            border-color: #334155;
        }

        .poc-info-card-title {
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #94a3b8;
            padding-bottom: .6rem;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: .1rem;
        }

        .dark .poc-info-card-title {
            border-bottom-color: #334155;
        }

        .poc-info-row {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            padding: .55rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        .dark .poc-info-row {
            border-bottom-color: #0f172a;
        }

        .poc-info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .poc-info-row:first-of-type {
            padding-top: .55rem;
        }

        .poc-info-row-icon {
            width: 1.6rem;
            height: 1.6rem;
            border-radius: .4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: .1rem;
        }

        .poc-info-row-icon svg {
            width: .8rem;
            height: .8rem;
        }

        .poc-row-icon-indigo {
            background: #e0e7ff;
            color: #4338ca;
        }

        .poc-row-icon-teal {
            background: #ccfbf1;
            color: #0f766e;
        }

        .poc-row-icon-amber {
            background: #fef9c3;
            color: #a16207;
        }

        .poc-row-icon-rose {
            background: #ffe4e6;
            color: #be123c;
        }

        .poc-row-icon-sky {
            background: #e0f2fe;
            color: #0369a1;
        }

        .poc-row-icon-violet {
            background: #ede9fe;
            color: #6d28d9;
        }

        .poc-row-icon-green {
            background: #d1fae5;
            color: #065f46;
        }

        .dark .poc-row-icon-indigo {
            background: rgba(67, 56, 202, .2);
            color: #a5b4fc;
        }

        .dark .poc-row-icon-teal {
            background: rgba(15, 118, 110, .2);
            color: #2dd4bf;
        }

        .dark .poc-row-icon-amber {
            background: rgba(161, 98, 7, .2);
            color: #fcd34d;
        }

        .dark .poc-row-icon-rose {
            background: rgba(190, 18, 60, .2);
            color: #fb7185;
        }

        .dark .poc-row-icon-sky {
            background: rgba(3, 105, 161, .2);
            color: #38bdf8;
        }

        .dark .poc-row-icon-violet {
            background: rgba(109, 40, 217, .2);
            color: #c4b5fd;
        }

        .dark .poc-row-icon-green {
            background: rgba(6, 95, 70, .2);
            color: #6ee7b7;
        }

        .poc-info-label {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #94a3b8;
            line-height: 1;
            margin-bottom: .18rem;
        }

        .poc-info-value {
            font-size: .9rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.4;
        }

        .dark .poc-info-value {
            color: #f1f5f9;
        }

        /* Convert button in header */
        .poc-convert-btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .6rem 1.25rem;
            border-radius: .75rem;
            border: none;
            background: linear-gradient(135deg, #059669, #0d9488);
            color: #fff;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .18s;
            white-space: nowrap;
        }

        .poc-convert-btn:hover {
            background: linear-gradient(135deg, #047857, #0f766e);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(5, 150, 105, .4);
        }

        .poc-convert-btn svg {
            width: .9rem;
            height: .9rem;
        }

        .poc-close-action-btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem 1.1rem;
            border-radius: .75rem;
            border: 1.5px solid rgba(255, 255, 255, .3);
            background: rgba(255, 255, 255, .12);
            color: #fff;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
        }

        .poc-close-action-btn:hover {
            background: rgba(255, 255, 255, .22);
        }

        /* Right column — items */
        .poc-items-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
        }

        .dark .poc-items-card {
            background: #1e293b;
            border-color: #334155;
        }

        .poc-items-toolbar {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .dark .poc-items-toolbar {
            border-bottom-color: #334155;
        }

        .poc-items-title {
            font-size: .85rem;
            font-weight: 800;
            color: #0f172a;
            flex: 1;
            white-space: nowrap;
        }

        .dark .poc-items-title {
            color: #f1f5f9;
        }

        .poc-item-count-badge {
            padding: .2rem .65rem;
            border-radius: 9999px;
            background: #ede9fe;
            color: #6d28d9;
            font-size: .72rem;
            font-weight: 800;
        }

        .dark .poc-item-count-badge {
            background: rgba(139, 92, 246, .2);
            color: #c4b5fd;
        }

        .poc-item-search-wrap {
            position: relative;
            flex: 1 1 180px;
            min-width: 0;
        }

        .poc-item-search-icon {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            width: .85rem;
            height: .85rem;
            color: #94a3b8;
            pointer-events: none;
        }

        .poc-item-search-input {
            width: 100%;
            padding: .55rem .8rem .55rem 2.2rem;
            border-radius: .65rem;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: .82rem;
            font-weight: 600;
        }

        .poc-item-search-input:focus {
            outline: none;
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .1);
        }

        .dark .poc-item-search-input {
            background: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }

        .poc-item-search-input::placeholder {
            color: #94a3b8;
        }

        .poc-item-cat-select {
            padding: .55rem .8rem;
            border-radius: .65rem;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            min-width: 160px;
        }

        .poc-item-cat-select:focus {
            outline: none;
            border-color: #6366f1;
        }

        .dark .poc-item-cat-select {
            background: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }

        /* Items table */
        .poc-items-wrap {
            overflow-x: auto;
        }

        .poc-items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .83rem;
        }

        .poc-items-table thead th {
            background: #f8fafc;
            padding: .7rem 1rem;
            text-align: left;
            font-size: .68rem;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: .07em;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .dark .poc-items-table thead th {
            background: #0f172a;
            color: #64748b;
            border-bottom-color: #1e293b;
        }

        .poc-items-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background .12s;
        }

        .poc-items-table tbody tr:last-child {
            border-bottom: none;
        }

        .poc-items-table tbody tr:hover {
            background: #f8fafc;
        }

        .dark .poc-items-table tbody tr {
            border-bottom-color: #0f172a;
        }

        .dark .poc-items-table tbody tr:hover {
            background: #0f172a;
        }

        .poc-items-table td {
            padding: .8rem 1rem;
            color: #374151;
            vertical-align: middle;
        }

        .dark .poc-items-table td {
            color: #cbd5e1;
        }

        .poc-item-no {
            width: 2rem;
            height: 2rem;
            border-radius: .5rem;
            background: #ede9fe;
            color: #6d28d9;
            font-size: .7rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dark .poc-item-no {
            background: rgba(139, 92, 246, .2);
            color: #c4b5fd;
        }

        .poc-item-cat-badge {
            display: inline-block;
            padding: .18rem .65rem;
            border-radius: 9999px;
            background: #e0f2fe;
            color: #0369a1;
            font-size: .68rem;
            font-weight: 700;
        }

        .dark .poc-item-cat-badge {
            background: rgba(3, 105, 161, .2);
            color: #38bdf8;
        }

        .poc-item-type {
            font-weight: 700;
            color: #0f172a;
        }

        .dark .poc-item-type {
            color: #f1f5f9;
        }

        .poc-item-qty {
            font-weight: 800;
            color: #4f46e5;
        }

        .dark .poc-item-qty {
            color: #818cf8;
        }

        .poc-item-remaining {
            font-weight: 600;
            color: #10b981;
            font-size: .8rem;
        }

        .dark .poc-item-remaining {
            color: #34d399;
        }

        /* No items */
        .poc-no-items {
            text-align: center;
            padding: 3rem 2rem;
            color: #94a3b8;
            font-size: .875rem;
        }

        /* Section heading */
        .poc-section-heading {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .6rem;
        }

        .poc-section-line {
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .dark .poc-section-line {
            background: #334155;
        }

        .poc-section-label {
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .09em;
            color: #94a3b8;
            white-space: nowrap;
        }
        }

        .poc-info-value {
            font-size: .9rem;
            font-weight: 700;
            color: #0f172a;
        }

        .dark .poc-info-value {
            color: #f1f5f9;
        }

        /* Section heading */
        .poc-section-heading {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .75rem;
        }

        .poc-section-line {
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .dark .poc-section-line {
            background: #334155;
        }

        .poc-section-label {
            font-size: .7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .09em;
            color: #94a3b8;
            white-space: nowrap;
        }

        /* Items table */
        .poc-items-wrap {
            border: 1px solid #e2e8f0;
            border-radius: .875rem;
            overflow: hidden;
        }

        .dark .poc-items-wrap {
            border-color: #334155;
        }

        .poc-items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }

        .poc-items-table thead th {
            background: #f1f5f9;
            padding: .65rem .85rem;
            text-align: left;
            font-size: .68rem;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: .07em;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .dark .poc-items-table thead th {
            background: #1e293b;
            color: #64748b;
            border-bottom-color: #334155;
        }

        .poc-items-table tbody tr {
            border-bottom: 1px solid #f8fafc;
        }

        .poc-items-table tbody tr:last-child {
            border-bottom: none;
        }

        .poc-items-table tbody tr:hover {
            background: #f8fafc;
        }

        .dark .poc-items-table tbody tr {
            border-bottom-color: #1e293b;
        }

        .dark .poc-items-table tbody tr:hover {
            background: #1e293b;
        }

        .poc-items-table td {
            padding: .7rem .85rem;
            color: #374151;
            vertical-align: middle;
        }

        .dark .poc-items-table td {
            color: #cbd5e1;
        }

        .poc-item-no {
            width: 2rem;
            height: 2rem;
            border-radius: .5rem;
            background: #ede9fe;
            color: #6d28d9;
            font-size: .7rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dark .poc-item-no {
            background: rgba(139, 92, 246, .2);
            color: #c4b5fd;
        }

        .poc-item-cat-badge {
            display: inline-block;
            padding: .15rem .6rem;
            border-radius: 9999px;
            background: #e0f2fe;
            color: #0369a1;
            font-size: .68rem;
            font-weight: 700;
        }

        .dark .poc-item-cat-badge {
            background: rgba(3, 105, 161, .2);
            color: #38bdf8;
        }

        .poc-item-type {
            font-weight: 700;
            color: #0f172a;
        }

        .dark .poc-item-type {
            color: #f1f5f9;
        }

        .poc-item-size {
            font-size: .85rem;
            font-weight: 600;
            color: #334155;
        }

        .dark .poc-item-size {
            color: #cbd5e1;
        }

        .poc-item-desc {
            font-size: .85rem;
            font-weight: 500;
            color: #475569;
            line-height: 1.4;
        }

        .dark .poc-item-desc {
            color: #94a3b8;
        }

        .poc-item-unit {
            font-size: .75rem;
            font-weight: 700;
            color: #64748b;
            background: #f1f5f9;
            padding: .2rem .5rem;
            border-radius: .4rem;
        }

        .dark .poc-item-unit {
            color: #94a3b8;
            background: #1e293b;
        }

        .poc-item-qty {
            font-weight: 800;
            color: #4f46e5;
        }

        .dark .poc-item-qty {
            color: #818cf8;
        }

        .poc-item-remaining {
            font-weight: 600;
            color: #10b981;
            font-size: .8rem;
        }

        .dark .poc-item-remaining {
            color: #34d399;
        }

        /* No items */
        .poc-no-items {
            text-align: center;
            padding: 2rem;
            color: #94a3b8;
            font-size: .875rem;
        }

        /* Panel footer */
        .poc-panel-footer {
            flex-shrink: 0;
            padding: 1rem 1.75rem;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .dark .poc-panel-footer {
            border-top-color: #1e293b;
            background: #0f172a;
        }

        .poc-close-action-btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .65rem 1.4rem;
            border-radius: .75rem;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #374151;
            font-size: .875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
        }

        .poc-close-action-btn:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .dark .poc-close-action-btn {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }

        .dark .poc-close-action-btn:hover {
            background: #334155;
        }

        /* ── Convert-to-PO action button ── */
        .poc-convert-btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .65rem 1.4rem;
            border-radius: .75rem;
            border: none;
            background: linear-gradient(135deg, #059669, #0d9488);
            color: #ffffff;
            font-size: .875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .18s;
        }

        .poc-convert-btn:hover {
            background: linear-gradient(135deg, #047857, #0f766e);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(5, 150, 105, .35);
        }

        .poc-convert-btn svg {
            width: .9rem;
            height: .9rem;
        }

        /* ── PO Number Modal ── */
        .poc-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 9000;
            background: rgba(2, 6, 23, .6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            animation: pocFadeIn .18s ease;
        }

        .poc-modal {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 25px 60px rgba(2, 6, 23, .25);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }

        .dark .poc-modal {
            background: #1e293b;
            box-shadow: 0 25px 60px rgba(0, 0, 0, .6);
        }

        .poc-modal-header {
            padding: 1.5rem 1.75rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dark .poc-modal-header {
            border-bottom-color: #334155;
        }

        .poc-modal-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .dark .poc-modal-title {
            color: #f1f5f9;
        }

        .poc-modal-title-icon {
            width: 2rem;
            height: 2rem;
            border-radius: .6rem;
            background: #d1fae5;
            color: #065f46;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dark .poc-modal-title-icon {
            background: rgba(16, 185, 129, .2);
            color: #34d399;
        }

        .poc-modal-title-icon svg {
            width: 1rem;
            height: 1rem;
        }

        .poc-modal-close-btn {
            width: 2rem;
            height: 2rem;
            border-radius: .5rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
        }

        .poc-modal-close-btn:hover {
            background: #fee2e2;
            border-color: #fecaca;
            color: #be123c;
        }

        .poc-modal-close-btn svg {
            width: .85rem;
            height: .85rem;
        }

        .dark .poc-modal-close-btn {
            background: #0f172a;
            border-color: #334155;
            color: #94a3b8;
        }

        .poc-modal-body {
            padding: 1.5rem 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .poc-modal-desc {
            font-size: .875rem;
            color: #64748b;
            line-height: 1.6;
        }

        .dark .poc-modal-desc {
            color: #94a3b8;
        }

        .poc-modal-field label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .45rem;
        }

        .dark .poc-modal-field label {
            color: #cbd5e1;
        }

        .poc-modal-input {
            width: 100%;
            padding: .75rem 1rem;
            border-radius: .75rem;
            border: 1.5px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: .95rem;
            font-weight: 600;
            transition: all .18s;
        }

        .poc-modal-input:focus {
            outline: none;
            border-color: #059669;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, .14);
        }

        .dark .poc-modal-input {
            background: #0f172a;
            border-color: #334155;
            color: #f1f5f9;
        }

        .dark .poc-modal-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .18);
        }

        .poc-modal-input::placeholder {
            color: #94a3b8;
            font-weight: 500;
        }

        .poc-modal-error {
            display: flex;
            align-items: flex-start;
            gap: .45rem;
            padding: .7rem .9rem;
            border-radius: .65rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            font-size: .8rem;
            font-weight: 600;
        }

        .dark .poc-modal-error {
            background: rgba(239, 68, 68, .12);
            border-color: rgba(239, 68, 68, .25);
            color: #f87171;
        }

        .poc-modal-error svg {
            width: .9rem;
            height: .9rem;
            flex-shrink: 0;
            margin-top: .05rem;
        }

        .poc-modal-footer {
            padding: 1rem 1.75rem;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .6rem;
        }

        .dark .poc-modal-footer {
            border-top-color: #334155;
            background: #0f172a;
        }

        .poc-modal-cancel-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .6rem 1.1rem;
            border-radius: .65rem;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #374151;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
        }

        .poc-modal-cancel-btn:hover {
            background: #f1f5f9;
        }

        .dark .poc-modal-cancel-btn {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }

        .poc-modal-confirm-btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem 1.3rem;
            border-radius: .65rem;
            border: none;
            background: linear-gradient(135deg, #059669, #0d9488);
            color: #ffffff;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .18s;
        }

        .poc-modal-confirm-btn:hover {
            background: linear-gradient(135deg, #047857, #0f766e);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, .3);
        }

        .poc-modal-confirm-btn svg {
            width: .85rem;
            height: .85rem;
        }

        /* ── Detail Review Modal ── */
        .poc-detail-modal {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 25px 60px rgba(2, 6, 23, .3);
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: pocSlideUp .25s cubic-bezier(.22, 1, .36, 1);
        }

        .dark .poc-detail-modal {
            background: #1e293b;
            box-shadow: 0 25px 60px rgba(0, 0, 0, .55);
        }

        .poc-detail-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.1rem 1.5rem;
            background: linear-gradient(135deg, #312e81 0%, #4f46e5 55%, #7c3aed 100%);
            flex-shrink: 0;
            gap: 1rem;
        }

        .poc-detail-modal-header-left {
            display: flex;
            align-items: center;
            gap: .75rem;
            min-width: 0;
            flex: 1;
            flex-wrap: wrap;
        }

        .poc-detail-modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background: #f1f5f9;
        }

        .dark .poc-detail-modal-body {
            background: #0f172a;
        }

        .poc-detail-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .85rem;
        }

        .poc-detail-modal-footer {
            flex-shrink: 0;
            padding: .9rem 1.5rem;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .6rem;
        }

        .dark .poc-detail-modal-footer {
            border-top-color: #334155;
            background: #1e293b;
        }

        @media (max-width: 640px) {
            .poc-detail-modal {
                max-width: 100%;
                max-height: 100dvh;
                border-radius: 0;
            }

            .poc-detail-info-grid {
                grid-template-columns: 1fr;
            }

            .poc-table thead {
                display: none;
            }

            .poc-table tbody tr {
                display: flex;
                flex-direction: column;
                padding: .75rem;
                gap: .25rem;
            }

            .poc-table td {
                padding: .15rem 0;
            }
        }
    </style>

    <div class="poc-root">

        {{-- ── Stats Strip ── --}}
        <div class="poc-stats-strip">
            <div class="poc-stat-card">
                <div class="poc-stat-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                </div>
                <div class="poc-stat-body">
                    <div class="poc-stat-value">{{ $stats['total'] }}</div>
                    <div class="poc-stat-label">Total PR</div>
                </div>
            </div>

            <div class="poc-stat-card">
                <div class="poc-stat-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="poc-stat-body">
                    <div class="poc-stat-value">{{ $stats['approved'] }}</div>
                    <div class="poc-stat-label">Disetujui</div>
                </div>
            </div>

            <div class="poc-stat-card">
                <div class="poc-stat-icon violet">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </div>
                <div class="poc-stat-body">
                    <div class="poc-stat-value">{{ $stats['converted'] }}</div>
                    <div class="poc-stat-label">Ke PO</div>
                </div>
            </div>
        </div>

        {{-- ── Toolbar ── --}}
        <div class="poc-toolbar">
            {{-- Search --}}
            <div class="poc-search-wrap">
                <svg class="poc-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Cari nomor PR, kapal, keperluan, pengaju..."
                    class="poc-search-input">
            </div>

            {{-- Status filter --}}
            {{-- select wire:model.live="statusFilter" class="poc-filter-select">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
            </select> --}}


            {{-- Reset --}}
            <button type="button" wire:click="resetFilters" class="poc-reset-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Reset
            </button>
        </div>

        {{-- ── Table ── --}}
        <div class="poc-table-card">
            @if($prList->isEmpty())
            <div class="poc-empty">
                <svg class="poc-empty-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <div class="poc-empty-title">Tidak ada pengajuan PR</div>
                <div class="poc-empty-sub">Belum ada data yang sesuai dengan filter yang dipilih.</div>
            </div>
            @else
            <table class="poc-table">
                <thead>
                    <tr>
                        <th>Nomor PR</th>
                        <th>Nama Kapal</th>
                        <th>Keperluan</th>
                        <th>Pengaju</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prList as $pr)
                    @php
                    $color = \App\Constants\PrStatusConstant::getColor($pr->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
                    $label = \App\Constants\PrStatusConstant::getStatuses()[$pr->pr_status] ?? $pr->pr_status ?? '—';
                    @endphp
                    <tr>
                        <td>
                            <span class="poc-pr-number">{{ $pr->pr_number }}</span>
                        </td>
                        <td>
                            <span class="poc-vessel-name">{{ $pr->detail?->vessel?->name ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="poc-needs-text">{{ $pr->detail?->needs ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="poc-requester-text">{{ $pr->requester?->name ?? '—' }}</span>
                        </td>
                        <td>
                            <div class="poc-date-text">
                                {{ $pr->created_at?->timezone('Asia/Jakarta')->format('d M Y') ?? '—' }}
                            </div>
                            <div class="poc-date-text" style="margin-top:.1rem;">
                                {{ $pr->created_at?->timezone('Asia/Jakarta')->format('H:i') ?? '' }}
                            </div>
                        </td>
                        <td>
                            <span class="poc-status-pill poc-pill-{{ $color }}">
                                <span class="poc-dot poc-dot-{{ $color }}"></span>
                                {{ $label }}
                            </span>
                        </td>
                        <td>
                            <button
                                type="button"
                                class="poc-review-btn"
                                wire:click="openDetail({{ $pr->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Tinjau
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($prList->hasPages())
            <div class="poc-pagination">
                <div class="poc-pagination-info">
                    Menampilkan {{ $prList->firstItem() }}–{{ $prList->lastItem() }} dari {{ $prList->total() }} data
                </div>
                <div class="poc-pagination-btns">
                    @if($prList->onFirstPage())
                    <button class="poc-page-btn" disabled>‹</button>
                    @else
                    <button class="poc-page-btn" wire:click="previousPage">‹</button>
                    @endif

                    @foreach($prList->getUrlRange(max(1, $prList->currentPage() - 2), min($prList->lastPage(), $prList->currentPage() + 2)) as $page => $url)
                    <button
                        class="poc-page-btn {{ $page == $prList->currentPage() ? 'active' : '' }}"
                        wire:click="gotoPage({{ $page }})">
                        {{ $page }}
                    </button>
                    @endforeach

                    @if($prList->hasMorePages())
                    <button class="poc-page-btn" wire:click="nextPage">›</button>
                    @else
                    <button class="poc-page-btn" disabled>›</button>
                    @endif
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         DETAIL REVIEW MODAL
         ══════════════════════════════════════════ --}}
    @if($showDetailPanel && $selectedPr)
    @php
    $pr = $selectedPr;
    $det = $pr->detail;
    $color = \App\Constants\PrStatusConstant::getColor($pr->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
    $label = \App\Constants\PrStatusConstant::getStatuses()[$pr->pr_status] ?? $pr->pr_status ?? '—';
    $items = $det?->items ?? collect();
    @endphp

    <div class="poc-modal-backdrop" wire:click.self="closeDetail">
        <div class="poc-detail-modal" role="dialog" aria-modal="true" aria-labelledby="poc-detail-title">

            {{-- Modal Header --}}
            <div class="poc-detail-modal-header">
                <div class="poc-detail-modal-header-left">
                    <div>
                        <div class="poc-panel-kicker">Detail Pengajuan PR</div>
                        <div class="poc-panel-pr-number" id="poc-detail-title">{{ $pr->pr_number }}</div>
                    </div>
                    <span class="poc-status-pill poc-pill-{{ $color }}" style="font-size:.73rem;">
                        <span class="poc-dot poc-dot-{{ $color }}"></span>
                        {{ $label }}
                    </span>
                    @if($pr->currentRole)
                    <span style="font-size:.75rem;font-weight:600;color:rgba(255,255,255,.7);">
                        &mdash; ditangani: <strong style="color:#fff;">{{ $pr->currentRole->name }}</strong>
                    </span>
                    @endif
                </div>
                <button type="button" class="poc-panel-close-btn" wire:click="closeDetail" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="poc-detail-modal-body">

                {{-- Info Grid --}}
                <div>
                    <div class="poc-section-heading" style="margin-bottom:.6rem;">
                        <span class="poc-section-label">Informasi Pengajuan</span>
                        <span class="poc-section-line"></span>
                    </div>
                    <div class="poc-detail-info-grid">

                        {{-- Card: Identitas PR --}}
                        <div class="poc-info-card">
                            <div class="poc-info-card-title">Identitas PR</div>
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-indigo">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Nomor PR</div>
                                    <div class="poc-info-value">{{ $pr->pr_number }}</div>
                                </div>
                            </div>
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-sky">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Pengaju</div>
                                    <div class="poc-info-value">{{ $pr->requester?->name ?? '—' }}</div>
                                </div>
                            </div>
                            @if($det?->document_no)
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-teal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Nomor Dokumen</div>
                                    <div class="poc-info-value">{{ $det->document_no }}</div>
                                </div>
                            </div>
                            @endif
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-sky">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Tgl Pengajuan</div>
                                    <div class="poc-info-value" style="font-size:.85rem;">
                                        {{ $pr->created_at ? $pr->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') : '—' }}
                                    </div>
                                </div>
                            </div>
                            @if($det?->required_date)
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-rose">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Tgl Dibutuhkan</div>
                                    <div class="poc-info-value" style="font-size:.85rem;">{{ $det->required_date->format('d M Y') }}</div>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Card: Kapal & Keperluan --}}
                        <div class="poc-info-card">
                            <div class="poc-info-card-title">Kapal &amp; Keperluan</div>
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-violet">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Nama Kapal</div>
                                    <div class="poc-info-value">{{ $det?->vessel?->name ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-amber">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Keperluan</div>
                                    <div class="poc-info-value">{{ $det?->needs ?? '—' }}</div>
                                </div>
                            </div>
                            @if($det?->title)
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-green">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Judul</div>
                                    <div class="poc-info-value" style="font-size:.85rem;">{{ $det->title }}</div>
                                </div>
                            </div>
                            @endif
                            @if($pr->currentRole)
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-rose">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Ditangani Oleh</div>
                                    <div class="poc-info-value">{{ $pr->currentRole->name }}</div>
                                </div>
                            </div>
                            @endif
                            @if($det?->request_date_client)
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-teal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Tgl Pengajuan (Klien)</div>
                                    <div class="poc-info-value" style="font-size:.85rem;">
                                        {{ $det->request_date_client->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- Items Section --}}
                <div>
                    <div class="poc-section-heading" style="margin-bottom:.6rem;">
                        <span class="poc-section-label">Daftar Barang</span>
                        <span class="poc-section-line"></span>
                        @if($items->count())
                        <span style="font-size:.72rem;font-weight:700;color:#6d28d9;background:#ede9fe;padding:.2rem .6rem;border-radius:9999px;flex-shrink:0;">
                            {{ $items->count() }} item
                        </span>
                        @endif
                    </div>

                    @if(isset($itemDiffs) && count($itemDiffs) > 0)
                    <div style="margin-bottom: 1.2rem;">
                        <button type="button" wire:click="openDiffModal" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 0.85rem; font-size:0.75rem; font-weight:600; color:#6366f1; background:#eef2ff; border:1px solid #c7d2fe; border-radius:0.5rem; cursor:pointer; transition:all 0.2s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1rem;height:1rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                            Lihat {{ count($itemDiffs) }} Perubahan oleh Approver
                        </button>
                    </div>
                    @endif

                    <div class="poc-items-wrap">
                        @if($items->isEmpty())
                        <div class="poc-no-items">Belum ada barang dalam pengajuan ini.</div>
                        @else
                        <table class="poc-items-table">
                            <thead>
                                <tr>
                                    <th style="width:2.5rem;">#</th>
                                    <th>Kategori</th>
                                    <th>Jenis</th>
                                    <th>Ukuran / Spesifikasi</th>
                                    <th>Keterangan</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td>
                                        <div class="poc-item-no">{{ $item->no ?? $loop->iteration }}</div>
                                    </td>
                                    <td>
                                        <span class="poc-item-cat-badge">{{ $item->itemCategory?->name ?? '—' }}</span>
                                    </td>
                                    <td>
                                        <div class="poc-item-type">{{ $item->type ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <div class="poc-item-size">{{ $item->size ?? '—' }}</div>
                                    </td>
                                    <td style="max-width:180px;">
                                        <div class="poc-item-desc">{{ $item->description ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <span class="poc-item-qty">{{ $item->quantity }}</span>
                                    </td>
                                    <td>
                                        <span class="poc-item-unit">{{ $item->unit ?? '—' }}</span>
                                    </td>
                                    <td>
                                        @if($item->remaining !== null)
                                        <span class="poc-item-remaining">{{ $item->remaining }}</span>
                                        @else
                                        <span style="color:#94a3b8;font-size:.8rem;">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>

            </div>{{-- /modal-body --}}

            {{-- Modal Footer --}}
            <div class="poc-detail-modal-footer">
                <button type="button" class="poc-close-action-btn" wire:click="closeDetail">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.9rem;height:.9rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tutup
                </button>
                <button type="button" class="poc-convert-btn" wire:click="openApproveModal({{ $pr->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Konversi ke PO
                </button>
            </div>

        </div>{{-- /detail-modal --}}
    </div>{{-- /backdrop --}}
    @endif

    {{-- ══════════════════════════════════════════
         PO NUMBER MODAL (Per-Item)
         ══════════════════════════════════════════ --}}
    @if($showApproveModal)
    <div class="poc-modal-backdrop">
        <div style="width:100%; max-width:56rem; margin:auto; border-radius:1rem; overflow:hidden; box-shadow:0 25px 50px rgba(0,0,0,0.35); display:flex; flex-direction:column; max-height:90vh; background:#fff;" role="dialog" aria-modal="true">
            <style>
                .pom-header {
                    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0ea5e9 100%);
                    padding: 1.15rem 1.5rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    flex-shrink: 0;
                }

                .pom-header-left {
                    display: flex;
                    align-items: center;
                    gap: .75rem;
                }

                .pom-header-icon {
                    width: 2.2rem;
                    height: 2.2rem;
                    border-radius: .6rem;
                    background: rgba(255, 255, 255, 0.15);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                .pom-header-icon svg {
                    width: 1.1rem;
                    height: 1.1rem;
                    color: #bae6fd;
                }

                .pom-header-title {
                    font-size: 1.05rem;
                    font-weight: 800;
                    color: #fff;
                }

                .pom-header-sub {
                    font-size: .78rem;
                    color: rgba(186, 230, 253, 0.75);
                    font-weight: 600;
                    margin-top: .1rem;
                }

                .pom-close {
                    width: 2rem;
                    height: 2rem;
                    border-radius: .5rem;
                    border: none;
                    background: rgba(255, 255, 255, 0.12);
                    color: #bae6fd;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: background .2s;
                }

                .pom-close:hover {
                    background: rgba(255, 255, 255, 0.25);
                }

                .pom-close svg {
                    width: 1rem;
                    height: 1rem;
                }

                .pom-body {
                    flex: 1;
                    overflow-y: auto;
                    padding: 1rem 1.25rem;
                    background: #f8fafc;
                }

                .dark .pom-body {
                    background: #0f172a;
                }

                .pom-bulk-bar {
                    display: flex;
                    align-items: flex-end;
                    gap: .5rem;
                    margin-bottom: 1rem;
                    padding: .75rem;
                    background: #eff6ff;
                    border: 1px solid #bfdbfe;
                    border-radius: .6rem;
                }

                .dark .pom-bulk-bar {
                    background: rgba(30, 64, 175, 0.12);
                    border-color: rgba(96, 165, 250, 0.3);
                }

                .pom-bulk-label {
                    font-size: .72rem;
                    font-weight: 700;
                    color: #1e40af;
                    margin-bottom: .25rem;
                }

                .dark .pom-bulk-label {
                    color: #93c5fd;
                }

                .pom-bulk-input {
                    flex: 1;
                    min-height: 2.35rem;
                    padding: .45rem .7rem;
                    border-radius: .5rem;
                    border: 1px solid #93c5fd;
                    font-size: .82rem;
                    font-weight: 700;
                    color: #0f172a;
                    background: #fff;
                }

                .dark .pom-bulk-input {
                    background: #1e293b;
                    border-color: #334155;
                    color: #f8fafc;
                }

                .pom-bulk-input:focus {
                    border-color: #2563eb;
                    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
                    outline: none;
                }

                .pom-bulk-btn {
                    min-height: 2.35rem;
                    padding: 0 .85rem;
                    border-radius: .5rem;
                    border: none;
                    background: #2563eb;
                    color: #fff;
                    font-size: .78rem;
                    font-weight: 800;
                    cursor: pointer;
                    white-space: nowrap;
                    transition: background .2s;
                }

                .pom-bulk-btn:hover {
                    background: #1d4ed8;
                }

                .pom-table-wrap {
                    border-radius: .6rem;
                    overflow: hidden;
                    border: 1px solid #e2e8f0;
                }

                .dark .pom-table-wrap {
                    border-color: #334155;
                }

                .pom-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: .78rem;
                }

                .pom-table thead th {
                    padding: .55rem .6rem;
                    font-weight: 800;
                    text-align: left;
                    background: #f1f5f9;
                    color: #475569;
                    border-bottom: 1px solid #e2e8f0;
                    white-space: nowrap;
                }

                .dark .pom-table thead th {
                    background: #1e293b;
                    color: #94a3b8;
                    border-bottom-color: #334155;
                }

                .pom-table tbody td {
                    padding: .5rem .6rem;
                    border-bottom: 1px solid #f1f5f9;
                    color: #0f172a;
                    font-weight: 600;
                    vertical-align: middle;
                }

                .dark .pom-table tbody td {
                    border-bottom-color: #1e293b;
                    color: #e2e8f0;
                }

                .pom-table tbody tr:last-child td {
                    border-bottom: none;
                }

                .pom-table tbody tr:hover td {
                    background: rgba(14, 165, 233, 0.04);
                }

                .dark .pom-table tbody tr:hover td {
                    background: rgba(14, 165, 233, 0.06);
                }

                .pom-po-input {
                    width: 100%;
                    min-height: 2.15rem;
                    padding: .35rem .55rem;
                    border-radius: .4rem;
                    border: 1px solid #cbd5e1;
                    font-size: .78rem;
                    font-weight: 700;
                    color: #0f172a;
                    background: #fff;
                    transition: border-color .2s;
                }

                .dark .pom-po-input {
                    background: #0f172a;
                    border-color: #334155;
                    color: #f8fafc;
                }

                .pom-po-input:focus {
                    border-color: #2563eb;
                    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
                    outline: none;
                }

                .pom-po-input.pom-has-po {
                    background: #ecfdf5;
                    border-color: #86efac;
                    color: #065f46;
                }

                .dark .pom-po-input.pom-has-po {
                    background: rgba(6, 95, 70, 0.15);
                    border-color: rgba(134, 239, 172, 0.4);
                    color: #6ee7b7;
                }

                .pom-po-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: .25rem;
                    padding: .15rem .4rem;
                    border-radius: .3rem;
                    font-size: .65rem;
                    font-weight: 700;
                    background: #d1fae5;
                    color: #065f46;
                }

                .dark .pom-po-badge {
                    background: rgba(6, 95, 70, 0.25);
                    color: #6ee7b7;
                }

                .pom-footer {
                    background: #fff;
                    border-top: 1px solid #e2e8f0;
                    padding: .85rem 1.25rem;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-shrink: 0;
                    gap: .75rem;
                }

                .dark .pom-footer {
                    background: #0f172a;
                    border-top-color: #1e293b;
                }

                .pom-footer-info {
                    font-size: .72rem;
                    color: #64748b;
                    font-weight: 600;
                }

                .dark .pom-footer-info {
                    color: #94a3b8;
                }

                .pom-footer-actions {
                    display: flex;
                    gap: .5rem;
                }

                .pom-cancel-btn {
                    padding: .5rem 1rem;
                    border-radius: .6rem;
                    border: 1px solid #e2e8f0;
                    background: #fff;
                    color: #475569;
                    font-size: .82rem;
                    font-weight: 700;
                    cursor: pointer;
                    transition: all .2s;
                }

                .pom-cancel-btn:hover {
                    background: #f1f5f9;
                }

                .dark .pom-cancel-btn {
                    background: #1e293b;
                    border-color: #334155;
                    color: #cbd5e1;
                }

                .dark .pom-cancel-btn:hover {
                    background: #334155;
                }

                .pom-confirm-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: .35rem;
                    padding: .5rem 1rem;
                    border-radius: .6rem;
                    border: none;
                    background: #059669;
                    color: #fff;
                    font-size: .82rem;
                    font-weight: 800;
                    cursor: pointer;
                    transition: background .2s;
                }

                .pom-confirm-btn:hover {
                    background: #047857;
                }

                .pom-confirm-btn svg {
                    width: .9rem;
                    height: .9rem;
                }

                .pom-error {
                    display: flex;
                    align-items: center;
                    gap: .4rem;
                    padding: .5rem .75rem;
                    border-radius: .5rem;
                    background: #fff1f2;
                    border: 1px solid #fecaca;
                    color: #be123c;
                    font-size: .78rem;
                    font-weight: 700;
                    margin-bottom: .75rem;
                }

                .dark .pom-error {
                    background: rgba(190, 18, 60, 0.12);
                    border-color: rgba(252, 165, 165, 0.3);
                    color: #fda4af;
                }

                .pom-error svg {
                    width: .9rem;
                    height: .9rem;
                    flex-shrink: 0;
                }
            </style>

            {{-- Header --}}
            <div class="pom-header">
                <div class="pom-header-left">
                    <div class="pom-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="pom-header-title">Konversi ke Purchase Order</div>
                        <div class="pom-header-sub">Isi nomor PO untuk setiap item. Bisa sebagian atau semua sekaligus.</div>
                    </div>
                </div>
                <button type="button" class="pom-close" wire:click="closeApproveModal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="pom-body">

                @if($approveError)
                <div class="pom-error">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ $approveError }}
                </div>
                @endif

                <!-- {{-- Bulk fill --}}
                <div class="pom-bulk-bar">
                    <div style="flex:1;">
                        <div class="pom-bulk-label">Isi semua item yang belum punya PO</div>
                        <input type="text" wire:model="bulkPoNumber" class="pom-bulk-input" placeholder="Contoh: PO-2026-0001" autocomplete="off">
                    </div>
                    <button type="button" class="pom-bulk-btn" wire:click="applyBulkPo">
                        Terapkan
                    </button>
                </div> -->

                {{-- Items table --}}
                <div class="pom-table-wrap">
                    <table class="pom-table">
                        <thead>
                            <tr>
                                <th style="width:2rem;">#</th>
                                <th>Kategori</th>
                                <th>Nama Barang</th>
                                <th>Ukuran</th>
                                <th style="width:3.5rem;">Qty</th>
                                <th style="width:3.5rem;">Satuan</th>
                                <th style="min-width:10rem;">Nomor PO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvalItems as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $item['category'] }}</td>
                                <td class="font-extrabold text-slate-900 dark:text-white">{{ $item['type'] }}</td>
                                <td class="text-slate-600 dark:text-slate-400">{{ $item['size'] }}</td>
                                <td style="text-align:center;">{{ $item['qty'] }}</td>
                                <td>{{ $item['unit'] }}</td>
                                <td>
                                    @if(!empty($item['po_number']))
                                    <span class="pom-po-badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.7rem;height:.7rem;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $item['po_number'] }}
                                    </span>
                                    @endif
                                    <input
                                        type="text"
                                        wire:model="poNumbers.{{ $item['id'] }}"
                                        class="pom-po-input {{ !empty($poNumbers[$item['id']] ?? '') ? 'pom-has-po' : '' }}"
                                        placeholder="{{ !empty($item['po_number']) ? 'Ubah PO...' : 'Isi nomor PO' }}"
                                        autocomplete="off">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="pom-footer">
                @php
                $filledCount = collect($poNumbers)->filter(fn($po) => is_string($po) && trim($po) !== '')->count();
                $totalCount = count($approvalItems);
                @endphp
                <div class="pom-footer-info">
                    {{ $filledCount }} / {{ $totalCount }} item sudah diisi PO
                </div>
                <div class="pom-footer-actions">
                    <button type="button" class="pom-cancel-btn" wire:click="closeApproveModal">
                        Batal
                    </button>
                    <button type="button" class="pom-confirm-btn" wire:click="confirmApprove">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Simpan & Konversi
                    </button>
                </div>
            </div>

        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════
         APPROVER DIFF MODAL (Comparison Table)
         ══════════════════════════════════════════ --}}
    @if($showDiffModal && !empty($diffRows))
    <div class="poc-modal-backdrop" style="z-index: 9999;">
        <div style="width:100%; max-width:52rem; margin:auto; border-radius:1rem; overflow:hidden; box-shadow:0 25px 50px rgba(0,0,0,0.4); display:flex; flex-direction:column; max-height:90vh;" role="dialog" aria-modal="true">
            <style>
                .rdm-header {
                    background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 50%, #7c3aed 100%);
                    padding: 1.25rem 1.5rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    flex-shrink: 0;
                }

                .rdm-header-left {
                    display: flex;
                    align-items: center;
                    gap: .75rem;
                }

                .rdm-header-icon {
                    width: 2.2rem;
                    height: 2.2rem;
                    border-radius: .6rem;
                    background: rgba(255, 255, 255, 0.15);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                .rdm-header-icon svg {
                    width: 1.1rem;
                    height: 1.1rem;
                    color: #e0e7ff;
                }

                .rdm-header-title {
                    font-size: 1.05rem;
                    font-weight: 800;
                    color: #fff;
                }

                .rdm-header-sub {
                    font-size: .78rem;
                    color: rgba(224, 231, 255, 0.75);
                    font-weight: 600;
                    margin-top: .1rem;
                }

                .rdm-close {
                    width: 2rem;
                    height: 2rem;
                    border-radius: .5rem;
                    border: none;
                    background: rgba(255, 255, 255, 0.12);
                    color: #e0e7ff;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: background .2s;
                }

                .rdm-close:hover {
                    background: rgba(255, 255, 255, 0.25);
                }

                .rdm-close svg {
                    width: 1rem;
                    height: 1rem;
                }

                .rdm-body {
                    background: #0f172a;
                    flex: 1;
                    overflow-y: auto;
                    padding: 1.25rem 1.5rem;
                }

                .rdm-timeline {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    margin-bottom: 1.25rem;
                    position: relative;
                    padding: 0 .5rem;
                }

                .rdm-timeline::before {
                    content: '';
                    position: absolute;
                    top: 1rem;
                    left: 2.5rem;
                    right: 2.5rem;
                    height: 3px;
                    background: linear-gradient(90deg, #6366f1, #a78bfa);
                    border-radius: 2px;
                }

                .rdm-step {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    position: relative;
                    z-index: 1;
                }

                .rdm-step-num {
                    width: 2rem;
                    height: 2rem;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: .75rem;
                    font-weight: 800;
                    color: #fff;
                    margin-bottom: .35rem;
                }

                .rdm-step-1 .rdm-step-num {
                    background: #2563eb;
                }

                .rdm-step-2 .rdm-step-num {
                    background: #059669;
                }

                .rdm-step-label {
                    font-size: .72rem;
                    font-weight: 700;
                    text-align: center;
                }

                .rdm-step-1 .rdm-step-label {
                    color: #60a5fa;
                }

                .rdm-step-2 .rdm-step-label {
                    color: #34d399;
                }

                .rdm-step-time {
                    font-size: .65rem;
                    color: #94a3b8;
                    font-weight: 600;
                    margin-top: .1rem;
                }

                .rdm-table-wrap {
                    border-radius: .6rem;
                    overflow: hidden;
                    border: 1px solid #334155;
                }

                .rdm-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: .78rem;
                }

                .rdm-table thead th {
                    padding: .6rem .65rem;
                    font-weight: 800;
                    text-align: left;
                    white-space: nowrap;
                }

                .rdm-table thead th.rdm-th-base {
                    background: #1e293b;
                    color: #cbd5e1;
                    border-bottom: 1px solid #334155;
                }

                .rdm-table thead th.rdm-th-init {
                    background: rgba(37, 99, 235, 0.2);
                    color: #93c5fd;
                    border-bottom: 1px solid #334155;
                    text-align: center;
                }

                .rdm-table thead th.rdm-th-latest {
                    background: rgba(124, 58, 237, 0.25);
                    color: #c4b5fd;
                    border-bottom: 1px solid #334155;
                    text-align: center;
                }

                .rdm-table thead th .rdm-th-sub {
                    display: block;
                    font-size: .65rem;
                    font-weight: 600;
                    opacity: .7;
                    margin-top: .1rem;
                }

                .rdm-table tbody td {
                    padding: .55rem .65rem;
                    color: #e2e8f0;
                    border-bottom: 1px solid #1e293b;
                    font-weight: 600;
                }

                .rdm-table tbody tr:last-child td {
                    border-bottom: none;
                }

                .rdm-table tbody tr:hover td {
                    background: rgba(99, 102, 241, 0.06);
                }

                .rdm-td-center {
                    text-align: center !important;
                }

                .rdm-td-init {
                    background: rgba(37, 99, 235, 0.06);
                }

                .rdm-td-latest {
                    background: rgba(124, 58, 237, 0.1);
                }

                .rdm-qty-up {
                    color: #fbbf24 !important;
                }

                .rdm-qty-down {
                    color: #fbbf24 !important;
                }

                .rdm-qty-same {
                    color: #64748b !important;
                }

                .rdm-qty-added {
                    color: #34d399 !important;
                }

                .rdm-qty-removed {
                    color: #f87171 !important;
                    text-decoration: line-through;
                }

                .rdm-arrow {
                    font-size: .65rem;
                    margin-left: .25rem;
                }

                .rdm-arrow-up {
                    color: #fbbf24;
                }

                .rdm-arrow-down {
                    color: #f87171;
                }

                .rdm-legend {
                    display: flex;
                    gap: 1.2rem;
                    flex-wrap: wrap;
                    margin-top: 1rem;
                    padding-top: .75rem;
                }

                .rdm-legend-item {
                    display: flex;
                    align-items: center;
                    gap: .35rem;
                    font-size: .7rem;
                    font-weight: 700;
                    color: #94a3b8;
                }

                .rdm-legend-icon {
                    width: .9rem;
                    height: .9rem;
                    border-radius: .2rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: .55rem;
                    font-weight: 900;
                }

                .rdm-legend-up {
                    background: #fef3c7;
                    color: #92400e;
                }

                .rdm-legend-down {
                    background: #ffe4e6;
                    color: #be123c;
                }

                .rdm-legend-same {
                    background: #334155;
                    color: #94a3b8;
                }

                .rdm-footer {
                    background: #0f172a;
                    border-top: 1px solid #1e293b;
                    padding: .85rem 1.5rem;
                    display: flex;
                    justify-content: flex-end;
                    flex-shrink: 0;
                }

                .rdm-close-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: .4rem;
                    padding: .5rem 1.2rem;
                    border-radius: .6rem;
                    border: 1px solid #334155;
                    background: #1e293b;
                    color: #cbd5e1;
                    font-size: .82rem;
                    font-weight: 700;
                    cursor: pointer;
                    transition: all .2s;
                }

                .rdm-close-btn:hover {
                    background: #334155;
                    color: #fff;
                }
            </style>

            {{-- Header --}}
            <div class="rdm-header">
                <div class="rdm-header-left">
                    <div class="rdm-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="rdm-header-title">Riwayat Perubahan Item</div>
                        <div class="rdm-header-sub">{{ $pr->pr_number ?? '' }} — sebelum & sesudah approval</div>
                    </div>
                </div>
                <button type="button" class="rdm-close" wire:click="closeDiffModal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="rdm-body">
                @if(!empty($diffSteps))
                <div class="rdm-timeline">
                    @foreach($diffSteps as $idx => $step)
                    <div class="rdm-step rdm-step-{{ $idx + 1 }}">
                        <div class="rdm-step-num">{{ $idx + 1 }}</div>
                        <div class="rdm-step-label">{{ $step['label'] }}</div>
                        <div class="rdm-step-time">{{ $step['time'] }}</div>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="rdm-table-wrap">
                    <table class="rdm-table">
                        <thead>
                            <tr>
                                <th class="rdm-th-base" style="width:2rem;">#</th>
                                <th class="rdm-th-base">Kategori</th>
                                <th class="rdm-th-base">Nama Barang</th>
                                <th class="rdm-th-base">Ukuran</th>
                                <th class="rdm-th-base">Satuan</th>
                                <th class="rdm-th-init">{{ $diffSteps[0]['label'] ?? 'Awal' }}<span class="rdm-th-sub">Jumlah</span></th>
                                <th class="rdm-th-latest">{{ $diffSteps[1]['label'] ?? 'Terbaru' }}<span class="rdm-th-sub">Jumlah</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($diffRows as $row)
                            <tr>
                                <td>{{ $row['no'] }}</td>
                                <td style="font-weight:700;">{{ $row['category'] }}</td>
                                <td style="font-weight:800;">{{ $row['type'] }}</td>
                                <td>{{ $row['size'] }}</td>
                                <td>{{ $row['unit'] }}</td>
                                <td class="rdm-td-center rdm-td-init">
                                    @if($row['init_qty'] !== null)
                                    <span style="font-weight:800;">{{ $row['init_qty'] }}</span>
                                    @else
                                    <span style="color:#475569;">—</span>
                                    @endif
                                </td>
                                <td class="rdm-td-center rdm-td-latest">
                                    @if($row['latest_qty'] !== null)
                                    <span class="rdm-qty-{{ $row['qty_status'] }}" style="font-weight:800;">
                                        {{ $row['latest_qty'] }}
                                        @if($row['qty_status'] === 'up')
                                        <span class="rdm-arrow rdm-arrow-up">▲</span>
                                        @elseif($row['qty_status'] === 'down')
                                        <span class="rdm-arrow rdm-arrow-down">▼</span>
                                        @endif
                                    </span>
                                    @else
                                    <span style="color:#475569;">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="rdm-legend">
                    <div class="rdm-legend-item">
                        <span class="rdm-legend-icon rdm-legend-up">▲</span>
                        Jumlah bertambah
                    </div>
                    <div class="rdm-legend-item">
                        <span class="rdm-legend-icon rdm-legend-down">▼</span>
                        Jumlah berkurang
                    </div>
                    <div class="rdm-legend-item">
                        <span class="rdm-legend-icon rdm-legend-same">—</span>
                        Tidak ada perubahan
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="rdm-footer">
                <button type="button" class="rdm-close-btn" wire:click="closeDiffModal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</x-filament-panels::page>