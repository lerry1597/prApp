<x-filament-panels::page>
    <style>
        .pr-history-container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* ===== ELDERLY FRIENDLY TYPOGRAPHY ===== */
        .pr-list-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
            transition: background-color 0.2s, transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: stretch;
        }

        .dark .pr-list-card {
            background: #1e293b;
            border-color: #334155;
            box-shadow: none;
        }

        .pr-list-card:hover {
            background: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.09);
        }

        .dark .pr-list-card:hover {
            background: #334155;
            box-shadow: 0 8px 20px rgba(2, 6, 23, 0.55);
        }

        /* Status Stripe */
        .pr-status-stripe {
            width: 12px;
            flex-shrink: 0;
        }

        .status-warning {
            background: #f59e0b;
        }

        .status-danger {
            background: #ef4444;
        }

        .status-info {
            background: #3b82f6;
        }

        .status-success {
            background: #10b981;
        }

        .status-gray {
            background: #94a3b8;
        }

        .pr-card-content {
            flex: 1;
            padding: 1.5rem 2rem;
            display: grid;
            grid-template-columns: 1fr auto auto;
            align-items: center;
            gap: 2rem;
        }

        /* Main Info Section */
        .pr-main-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .pr-number-label {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dark .pr-number-label {
            color: #60a5fa;
        }

        .pr-title-label {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .dark .pr-title-label {
            color: #f1f5f9;
        }

        .pr-document-no {
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.02em;
        }

        .dark .pr-document-no {
            color: #94a3b8;
        }

        .pr-meta-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-top: 0.5rem;
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        .dark .pr-meta-info {
            color: #94a3b8;
        }

        .pr-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Status Badge - Adjusted for visibility but smaller */
        .pr-status-badge {
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            font-size: 0.95rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
            min-width: 140px;
        }

        .badge-warning {
            background: #fffbeb;
            color: #92400e;
            border: 2px solid #fde68a;
        }

        .badge-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 2px solid #fecaca;
        }

        .badge-info {
            background: #eff6ff;
            color: #1e40af;
            border: 2px solid #bfdbfe;
        }

        .badge-success {
            background: #ecfdf5;
            color: #065f46;
            border: 2px solid #a7f3d0;
        }

        .badge-gray {
            background: #f9fafb;
            color: #4b5563;
            border: 2px solid #e5e7eb;
        }

        .dark .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .dark .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .dark .badge-info {
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .dark .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .dark .badge-gray {
            background: rgba(107, 114, 128, 0.1);
            color: #d1d5db;
            border-color: rgba(107, 114, 128, 0.3);
        }

        /* Action Button - Updated with Text */
        .pr-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.75rem 1.75rem;
            border-radius: 1rem;
            background: #f1f5f9;
            color: #1e40af;
            font-size: 1.1rem;
            font-weight: 800;
            transition: all 0.2s;
            cursor: pointer;
            border: 2px solid transparent;
            white-space: nowrap;
        }

        .dark .pr-action-btn {
            background: #334155;
            color: #60a5fa;
        }

        .pr-action-btn:hover {
            background: #1e40af;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }

        .dark .pr-action-btn:hover {
            background: #60a5fa;
            color: #0f172a;
            box-shadow: 0 4px 12px rgba(96, 165, 250, 0.3);
        }

        /* Empty State */
        .pr-empty-state {
            padding: 8rem 2rem;
            text-align: center;
            background: transparent;
            border: 1px dashed #cbd5e1;
            border-radius: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .dark .pr-empty-state {
            border-color: #475569;
        }

        .pr-empty-icon {
            width: 5rem;
            height: 5rem;
            margin: 0 auto 1.5rem;
            color: #94a3b8;
        }

        .pr-empty-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #475569;
        }

        .dark .pr-empty-text {
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .pr-card-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                text-align: center;
            }

            .pr-main-info {
                align-items: center;
            }

            .pr-meta-info {
                justify-content: center;
                flex-wrap: wrap;
            }

            .pr-action-btn {
                width: 100%;
                height: auto;
                padding: 1rem;
            }

            .pr-list-surface {
                padding: 0.45rem;
                gap: 0.45rem;
            }
        }

        .pr-toolbar {
            margin-bottom: 1.35rem;
            width: 100%;
        }

        .pr-list-surface {
            margin-top: 0.2rem;
            padding: 0.5rem;
            border-radius: 1.2rem;
            border: 1px solid #e2e8f0;
            background: linear-gradient(180deg, rgba(248, 250, 252, 0.9) 0%, rgba(241, 245, 249, 0.9) 100%);
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .dark .pr-list-surface {
            border-color: #334155;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.8) 0%, rgba(17, 24, 39, 0.8) 100%);
        }

        .pr-toolbar-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(0, 1.35fr);
            gap: 1rem;
            align-items: stretch;
        }

        .pr-toolbar-panel {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #dbe4f0;
            border-radius: 1.25rem;
            padding: 1rem 1.1rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .dark .pr-toolbar-panel {
            background: linear-gradient(180deg, #0f172a 0%, #111c31 100%);
            border-color: #334155;
            box-shadow: none;
        }

        .pr-toolbar-panel-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.85rem;
        }

        .pr-toolbar-icon-shell {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            color: #1d4ed8;
            flex-shrink: 0;
        }

        .dark .pr-toolbar-icon-shell {
            background: rgba(59, 130, 246, 0.16);
            color: #93c5fd;
        }

        .pr-toolbar-icon {
            width: 1.2rem;
            height: 1.2rem;
        }

        .pr-toolbar-heading {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .dark .pr-toolbar-heading {
            color: #f8fafc;
        }

        .pr-toolbar-subheading {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.15rem;
        }

        .dark .pr-toolbar-subheading {
            color: #94a3b8;
        }

        .pr-search-input-wrapper {
            position: relative;
        }

        .pr-search-input {
            width: 100%;
            min-height: 3.35rem;
            padding: 0.95rem 1.1rem 0.95rem 3rem;
            border-radius: 1rem;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            font-size: 0.98rem;
            font-weight: 700;
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
        }

        .pr-search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .dark .pr-search-input {
            background: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }

        .dark .pr-search-input:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.14);
        }

        .pr-search-input::placeholder,
        .pr-toolbar-input::placeholder {
            color: #94a3b8;
        }

        .pr-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.15rem;
            height: 1.15rem;
            color: #64748b;
            pointer-events: none;
        }

        .dark .pr-search-icon {
            color: #94a3b8;
        }

        .pr-filter-layout {
            display: flex;
            align-items: flex-end;
            gap: 0.9rem;
            flex-wrap: wrap;
        }

        .pr-filter-actions {
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .pr-date-field {
            display: flex;
            flex-direction: column;
            flex: 1 1 210px;
            min-width: 0;
        }

        .pr-filter-caption {
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 0.35rem;
        }

        .dark .pr-filter-caption {
            color: #94a3b8;
        }

        .pr-toolbar-input {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            box-shadow: none !important;
            outline: none !important;
            width: 100%;
        }

        .dark .pr-toolbar-input {
            color: #f8fafc !important;
        }

        .pr-date-input-unified {
            width: 100%;
            min-height: 2.85rem;
            cursor: pointer;
            background: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
            border-radius: 0.8rem;
            padding: 0.62rem 0.85rem !important;
            font-weight: 800 !important;
            color: #1e40af !important;
            accent-color: #1e40af;
            transition: all 0.2s ease;
        }

        .pr-date-input-unified:hover,
        .pr-date-input-unified:focus {
            background: #dbeafe !important;
            border-color: #3b82f6 !important;
        }

        .dark .pr-date-input-unified {
            background: rgba(30, 64, 175, 0.18) !important;
            border-color: rgba(96, 165, 250, 0.3) !important;
            color: #93c5fd !important;
            accent-color: #60a5fa;
        }

        .pr-filter-clear-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.85rem;
            min-height: 2.85rem;
            padding: 0 0.9rem;
            border-radius: 0.8rem;
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #be123c;
            font-size: 0.82rem;
            font-weight: 800;
            transition: all 0.2s ease;
        }

        .pr-filter-clear-btn:hover {
            background: #ffe4e6;
            border-color: #fda4af;
        }

        .dark .pr-filter-clear-btn {
            background: rgba(190, 24, 93, 0.12);
            border-color: rgba(251, 113, 133, 0.35);
            color: #fda4af;
        }

        .dark .pr-filter-clear-btn:hover {
            background: rgba(190, 24, 93, 0.18);
        }

        @media (max-width: 900px) {
            .pr-toolbar-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .pr-toolbar-panel {
                padding: 0.95rem;
            }

            .pr-filter-layout {
                flex-direction: column;
                align-items: stretch;
            }

            .pr-date-field,
            .pr-filter-actions,
            .pr-filter-clear-btn {
                width: 100%;
            }

            .pr-filter-actions {
                gap: 0.6rem;
            }

            .pr-filter-clear-btn {
                justify-content: center;
            }
        }

        /* Flatpickr Theme - Compact and Readable */
        .flatpickr-calendar {
            background: #ffffff !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.16) !important;
            border: 1px solid #cbd5e1 !important;
            font-family: inherit !important;
            width: 272px !important;
            font-size: 0.82rem !important;
        }

        .flatpickr-innerContainer {
            padding: 0.1rem !important;
        }

        .flatpickr-rContainer,
        .flatpickr-days {
            width: 100% !important;
            min-width: 0 !important;
        }

        .dark .flatpickr-calendar {
            background: #0f172a !important;
            border-color: #334155 !important;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.52) !important;
        }

        .flatpickr-day {
            color: #0f172a !important;
            font-weight: 700 !important;
            border-radius: 0.45rem !important;
            max-width: 34px !important;
            height: 31px !important;
            line-height: 31px !important;
        }

        .flatpickr-day.selected {
            background: #1e40af !important;
            border-color: #1e40af !important;
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(30, 64, 175, 0.35) !important;
        }

        .flatpickr-day:hover {
            background: #eff6ff !important;
            border-color: #bfdbfe !important;
        }

        .flatpickr-day.today {
            border-color: #3b82f6 !important;
            color: #1d4ed8 !important;
        }

        .dark .flatpickr-day {
            color: #f1f5f9 !important;
        }

        .dark .flatpickr-day.selected {
            background: #60a5fa !important;
            border-color: #60a5fa !important;
            color: #0f172a !important;
        }

        .dark .flatpickr-day:hover {
            background: rgba(59, 130, 246, 0.18) !important;
            border-color: rgba(96, 165, 250, 0.36) !important;
        }

        .dark .flatpickr-day.today {
            border-color: #60a5fa !important;
            color: #93c5fd !important;
        }

        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #94a3b8 !important;
        }

        .dark .flatpickr-day.flatpickr-disabled,
        .dark .flatpickr-day.prevMonthDay,
        .dark .flatpickr-day.nextMonthDay {
            color: #64748b !important;
        }

        .flatpickr-months {
            padding: 0.25rem 0.3rem 0 !important;
            align-items: center;
        }

        .flatpickr-months .flatpickr-month {
            color: #0f172a !important;
            fill: #0f172a !important;
            height: 40px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dark .flatpickr-months .flatpickr-month {
            color: #f1f5f9 !important;
            fill: #f1f5f9 !important;
        }

        .flatpickr-current-month {
            left: 1.95rem !important;
            width: calc(100% - 3.9rem) !important;
            height: 32px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            font-size: 0.82rem !important;
            line-height: 1 !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            height: 1.7rem !important;
            border: 1px solid #bfdbfe !important;
            border-radius: 0.45rem !important;
            padding: 0 0.45rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            background: #eff6ff !important;
            line-height: 1.7rem !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #ffffff !important;
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

        .flatpickr-prev-month svg,
        .flatpickr-next-month svg {
            fill: currentColor !important;
            stroke: currentColor !important;
            stroke-width: 1.4px !important;
            width: 12px !important;
            height: 12px !important;
            opacity: 1 !important;
            display: block !important;
        }

        .flatpickr-prev-month svg *,
        .flatpickr-next-month svg * {
            fill: currentColor !important;
            stroke: currentColor !important;
        }

        .dark .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #f1f5f9 !important;
            background: rgba(59, 130, 246, 0.18) !important;
            border-color: rgba(96, 165, 250, 0.42) !important;
        }

        .dark .flatpickr-current-month input.cur-year {
            color: #f8fafc !important;
        }

        .dark .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #1e293b !important;
            color: #f1f5f9 !important;
        }

        .dark .flatpickr-current-month .numInputWrapper {
            border-color: rgba(96, 165, 250, 0.42);
            background: rgba(59, 130, 246, 0.18);
        }

        .dark .flatpickr-current-month .numInputWrapper span {
            background: rgba(96, 165, 250, 0.22) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowUp,
        .dark .flatpickr-current-month .numInputWrapper span.arrowDown {
            border-left-color: rgba(96, 165, 250, 0.5) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowUp {
            border-bottom-color: rgba(96, 165, 250, 0.5) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span:hover {
            background: rgba(96, 165, 250, 0.34) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: #bfdbfe !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: #bfdbfe !important;
        }

        .dark .flatpickr-prev-month,
        .dark .flatpickr-next-month {
            border: 0 !important;
            background: transparent !important;
            color: #bfdbfe !important;
        }

        .dark .flatpickr-prev-month:hover,
        .dark .flatpickr-next-month:hover {
            background: transparent !important;
            color: #dbeafe !important;
        }

        .dark .flatpickr-prev-month svg,
        .dark .flatpickr-next-month svg {
            fill: currentColor !important;
            stroke: currentColor !important;
        }

        .flatpickr-weekday {
            color: #475569 !important;
            font-weight: 800 !important;
            font-size: 0.67rem !important;
        }

        .dark .flatpickr-weekday {
            color: #94a3b8 !important;
        }

        .flatpickr-time input,
        .flatpickr-time .flatpickr-am-pm {
            color: #0f172a !important;
            font-weight: 700 !important;
            font-size: 0.82rem !important;
        }

        .flatpickr-time .numInputWrapper {
            height: 34px !important;
        }

        .pr-flatpickr-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.45rem;
            padding: 0.35rem 0.45rem 0.45rem;
            border-top: 1px solid #e2e8f0;
            margin-top: 0.2rem;
        }

        .dark .pr-flatpickr-actions {
            border-top-color: #334155;
        }

        .pr-flatpickr-btn {
            height: 1.9rem;
            min-width: 4.25rem;
            border-radius: 0.55rem;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #334155;
            font-size: 0.74rem;
            font-weight: 800;
            padding: 0 0.65rem;
            cursor: pointer;
        }

        .pr-flatpickr-btn:hover {
            background: #f1f5f9;
        }

        .pr-flatpickr-btn-apply {
            border-color: #93c5fd;
            background: #1d4ed8;
            color: #ffffff;
        }

        .pr-flatpickr-btn-apply:hover {
            background: #1e40af;
        }

        .dark .pr-flatpickr-btn {
            border-color: #475569;
            background: #1e293b;
            color: #e2e8f0;
        }

        .dark .pr-flatpickr-btn:hover {
            background: #334155;
        }

        .dark .pr-flatpickr-btn-apply {
            border-color: #60a5fa;
            background: #3b82f6;
            color: #0f172a;
        }

        .dark .pr-flatpickr-btn-apply:hover {
            background: #60a5fa;
        }

        .dark .flatpickr-time input,
        .dark .flatpickr-time .flatpickr-am-pm {
            color: #f8fafc !important;
            background: #0f172a !important;
        }

        @media (max-width: 480px) {
            .flatpickr-calendar {
                width: 258px !important;
            }
        }

        /* ===== DETAIL MODAL STYLES (Reused from Form Preview) ===== */
        .pr-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 9000;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 1.5rem 1rem;
            overflow-y: auto;
            backdrop-filter: blur(2px);
            animation: fadeInOverlay 0.2s ease;
        }

        @keyframes fadeInOverlay {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .pr-modal-container {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 960px;
            display: flex;
            flex-direction: column;
            max-height: 88vh;
            animation: slideUpModal 0.25s ease;
            overflow: hidden;
            margin-top: 2rem;
        }

        .dark .pr-modal-container {
            background: #1e293b;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
        }

        @keyframes slideUpModal {
            from {
                transform: translateY(24px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .pr-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.75rem;
            border-bottom: 2px solid #e2e8f0;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border-radius: 1.25rem 1.25rem 0 0;
            flex-shrink: 0;
        }

        .pr-modal-header-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pr-modal-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 0.625rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pr-modal-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.02em;
        }

        .pr-modal-subtitle {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.75);
            margin-top: 0.1rem;
        }

        .pr-modal-close {
            width: 2.25rem;
            height: 2.25rem;
            background: rgba(255, 255, 255, 0.15);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.5rem;
            color: #ffffff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .pr-modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .pr-modal-body {
            overflow-y: auto;
            flex: 1;
            padding: 1.5rem 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .pr-preview-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.875rem;
        }

        @media (max-width: 640px) {
            .pr-preview-info-grid {
                grid-template-columns: 1fr;
            }
        }

        .pr-preview-info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
        }

        .dark .pr-preview-info-card {
            background: #0f172a;
            border-color: #334155;
        }

        .pr-preview-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .dark .pr-preview-info-item {
            border-bottom-color: #1e293b;
        }

        .pr-preview-info-item:last-child {
            border-bottom: none;
        }

        .pr-preview-info-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            min-width: 110px;
        }

        .dark .pr-preview-info-label {
            color: #94a3b8;
        }

        .pr-preview-info-value {
            font-size: 0.875rem;
            font-weight: 700;
            color: #0f172a;
        }

        .dark .pr-preview-info-value {
            color: #f1f5f9;
        }

        .pr-preview-badge {
            padding: 0.2rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            background: #dbeafe;
            color: #1d4ed8;
        }

        .dark .pr-preview-badge {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
        }

        .pr-preview-section-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
            display: block;
        }

        .dark .pr-preview-section-title {
            color: #94a3b8;
        }

        .pr-preview-table-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .dark .pr-preview-table-wrap {
            border-color: #334155;
        }

        .pr-preview-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .pr-preview-table thead th {
            background: #f1f5f9;
            color: #374151;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 2px solid #cbd5e1;
            text-transform: uppercase;
        }

        .dark .pr-preview-table thead th {
            background: #0f172a;
            color: #94a3b8;
            border-bottom-color: #475569;
        }

        .pr-preview-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: #0f172a;
        }

        .dark .pr-preview-table td {
            border-bottom-color: #1e293b;
            color: #e2e8f0;
        }

        .pr-preview-table tr:last-child td {
            border-bottom: none;
        }

        .pr-cat-divider td {
            background: linear-gradient(90deg, #eff6ff, #f8fafc) !important;
            border-top: 2px solid #bfdbfe !important;
            padding: 0.5rem 1rem !important;
        }

        .dark .pr-cat-divider td {
            background: linear-gradient(90deg, rgba(30, 64, 175, 0.12), rgba(15, 23, 42, 0.5)) !important;
            border-top-color: rgba(59, 130, 246, 0.3) !important;
        }

        .pr-cat-divider-label {
            font-size: 0.75rem;
            font-weight: 800;
            color: #1d4ed8;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .pr-modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 1.125rem 1.75rem;
            border-top: 2px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 0 0 1.25rem 1.25rem;
        }

        .dark .pr-modal-footer {
            border-top-color: #334155;
            background: #0f172a;
        }

        /* ===== HISTORY MODAL ===== */
        .pr-history-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            z-index: 9500;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 1.5rem 1rem;
            overflow-y: auto;
            backdrop-filter: blur(3px);
            animation: fadeInOverlay 0.2s ease;
        }

        .pr-history-modal-container {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.35);
            width: 100%;
            max-width: 1100px;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
            animation: slideUpModal 0.25s ease;
            overflow: hidden;
            margin-top: 2rem;
        }

        .dark .pr-history-modal-container {
            background: #1e293b;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.7);
        }

        .pr-history-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.75rem;
            background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%);
            border-radius: 1.25rem 1.25rem 0 0;
            flex-shrink: 0;
        }

        .pr-history-modal-body {
            overflow-x: auto;
            overflow-y: auto;
            flex: 1;
            padding: 1.5rem 1.75rem;
        }

        /* Timeline snapshot labels */
        .pr-snapshot-timeline {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 1.5rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .pr-snapshot-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 140px;
        }

        .pr-snapshot-dot {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background: #7c3aed;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .pr-snapshot-dot.first {
            background: #1e40af;
        }

        .pr-snapshot-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: #7c3aed;
            text-align: center;
            margin-top: 0.25rem;
            white-space: nowrap;
        }

        .pr-snapshot-label.first {
            color: #1e40af;
        }

        .pr-snapshot-line {
            flex: 1;
            height: 2px;
            background: #e2e8f0;
            min-width: 30px;
            margin-bottom: 1.5rem;
        }

        .dark .pr-snapshot-line {
            background: #334155;
        }

        /* Diff table */
        .pr-diff-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .pr-diff-table th {
            background: #f1f5f9;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 700;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .dark .pr-diff-table th {
            background: #0f172a;
            color: #94a3b8;
            border-bottom-color: #334155;
        }

        .pr-diff-table th.snapshot-header {
            background: #ede9fe;
            color: #5b21b6;
            text-align: center;
            border-left: 1px solid #ddd6fe;
        }

        .dark .pr-diff-table th.snapshot-header {
            background: rgba(124, 58, 237, 0.15);
            color: #a78bfa;
            border-left-color: rgba(124, 58, 237, 0.3);
        }

        .pr-diff-table th.snapshot-header.first {
            background: #dbeafe;
            color: #1e40af;
            border-left-color: #bfdbfe;
        }

        .dark .pr-diff-table th.snapshot-header.first {
            background: rgba(30, 64, 175, 0.15);
            color: #60a5fa;
        }

        .pr-diff-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .dark .pr-diff-table td {
            border-bottom-color: #1e293b;
        }

        .pr-diff-table tr:hover td {
            background: #f8fafc;
        }

        .dark .pr-diff-table tr:hover td {
            background: #334155;
        }

        .pr-diff-table td.snapshot-cell {
            text-align: center;
            border-left: 1px solid #f1f5f9;
            font-weight: 700;
            min-width: 110px;
        }

        .dark .pr-diff-table td.snapshot-cell {
            border-left-color: #1e293b;
        }

        /* Changed cell highlight */
        .pr-diff-changed {
            background: #fefce8 !important;
            color: #78350f;
        }

        .dark .pr-diff-changed {
            background: rgba(251, 191, 36, 0.15) !important;
            color: #fbbf24;
        }

        /* Badge naik (hijau) */
        .pr-diff-badge-up {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            font-size: 0.65rem;
            padding: 0.1rem 0.4rem;
            border-radius: 9999px;
            margin-left: 0.25rem;
            font-weight: 800;
            vertical-align: middle;
        }

        .dark .pr-diff-badge-up {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
        }

        /* Badge turun (merah) */
        .pr-diff-badge-down {
            display: inline-block;
            background: #fee2e2;
            color: #991b1b;
            font-size: 0.65rem;
            padding: 0.1rem 0.4rem;
            border-radius: 9999px;
            margin-left: 0.25rem;
            font-weight: 800;
            vertical-align: middle;
        }

        .dark .pr-diff-badge-down {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        /* Badge tidak berubah (abu) */
        .pr-diff-badge-same {
            display: inline-block;
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 0.65rem;
            padding: 0.1rem 0.4rem;
            border-radius: 9999px;
            margin-left: 0.25rem;
            font-weight: 800;
            vertical-align: middle;
        }

        .dark .pr-diff-badge-same {
            background: rgba(148, 163, 184, 0.15);
            color: #64748b;
        }

        .pr-diff-no-change {
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .pr-history-modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 1rem 1.75rem;
            border-top: 2px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 0 0 1.25rem 1.25rem;
            flex-shrink: 0;
        }

        .dark .pr-history-modal-footer {
            border-top-color: #334155;
            background: #0f172a;
        }

        /* Color classes for table cells */
        .pr-col-category {
            color: #1d4ed8;
            font-weight: 600;
        }

        .dark .pr-col-category {
            color: #93c5fd;
        }

        /* Brighter blue for dark mode */
    </style>

    {{-- Flatpickr Assets - Moved to @assets for SPA persistence --}}
    @assets
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endassets

    <div class="pr-history-container">
        <x-filament::section>
            <div class="pr-toolbar">
                <div class="pr-toolbar-grid">
                    <section class="pr-toolbar-panel">
                        <div class="pr-toolbar-panel-header">
                            <span class="pr-toolbar-icon-shell">
                                <svg class="pr-toolbar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                                </svg>
                            </span>
                            <div>
                                <div class="pr-toolbar-heading">Pencarian PR</div>
                                <div class="pr-toolbar-subheading">Cari berdasarkan nomor PR, judul, kebutuhan, atau nomor dokumen.</div>
                            </div>
                        </div>

                        <div class="pr-search-input-wrapper">
                            <svg class="pr-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                            </svg>
                            <input
                                type="text"
                                wire:model.live.debounce.500ms="search"
                                placeholder="Cari PR..."
                                class="pr-search-input">
                        </div>
                    </section>

                    <section class="pr-toolbar-panel">
                        <div class="pr-toolbar-panel-header">
                            <span class="pr-toolbar-icon-shell">
                                <svg class="pr-toolbar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M6 21h12a2.25 2.25 0 002.25-2.25V7.5A2.25 2.25 0 0018 5.25H6A2.25 2.25 0 003.75 7.5v11.25A2.25 2.25 0 006 21z" />
                                </svg>
                            </span>
                            <div>
                                <div class="pr-toolbar-heading">Filter Waktu Pengajuan</div>
                                <div class="pr-toolbar-subheading">Pisahkan rentang tanggal dan waktu agar hasil daftar lebih presisi.</div>
                            </div>
                        </div>

                        <div class="pr-filter-layout">
                            <div class="pr-date-field" wire:ignore>
                                <label for="startDate" class="pr-filter-caption">Tanggal Mulai</label>
                                <input
                                    type="text"
                                    id="startDate"
                                    class="pr-toolbar-input pr-date-input-unified"
                                    placeholder="Pilih tanggal dan waktu awal"
                                    readonly
                                    value="{{ $startDate }}"
                                    x-data="{
                                        init() {
                                            if ($el._flatpickr) {
                                                $el._flatpickr.destroy();
                                            }

                                            const picker = flatpickr($el, {
                                                dateFormat: 'Y-m-d H:i',
                                                altInput: true,
                                                altFormat: 'd M Y, H:i',
                                                enableTime: true,
                                                time_24hr: true,
                                                minuteIncrement: 5,
                                                disableMobile: true,
                                                defaultDate: @js($startDate),
                                                onReady: (selectedDates, dateStr, instance) => {
                                                    if (instance.calendarContainer.querySelector('.pr-flatpickr-actions')) {
                                                        return;
                                                    }

                                                    const actions = document.createElement('div');
                                                    actions.className = 'pr-flatpickr-actions';

                                                    const clearBtn = document.createElement('button');
                                                    clearBtn.type = 'button';
                                                    clearBtn.className = 'pr-flatpickr-btn';
                                                    clearBtn.textContent = 'Bersihkan';
                                                    clearBtn.addEventListener('click', () => {
                                                        instance.clear();
                                                        $wire.set('startDate', null);
                                                    });

                                                    const applyBtn = document.createElement('button');
                                                    applyBtn.type = 'button';
                                                    applyBtn.className = 'pr-flatpickr-btn pr-flatpickr-btn-apply';
                                                    applyBtn.textContent = 'Pilih';
                                                    applyBtn.addEventListener('click', () => {
                                                        instance.close();
                                                    });

                                                    actions.appendChild(clearBtn);
                                                    actions.appendChild(applyBtn);
                                                    instance.calendarContainer.appendChild(actions);
                                                },
                                                onChange: (selectedDates, dateStr) => {
                                                    $wire.set('startDate', dateStr);
                                                },
                                                onClose: (selectedDates, dateStr) => {
                                                    if (!dateStr) {
                                                        $wire.set('startDate', null);
                                                    }
                                                },
                                            });

                                            window.addEventListener('pr-date-filters-reset', () => {
                                                picker.clear();
                                            });
                                        }
                                    }">
                            </div>

                            <div class="pr-date-field" wire:ignore>
                                <label for="endDate" class="pr-filter-caption">Tanggal Akhir</label>
                                <input
                                    type="text"
                                    id="endDate"
                                    class="pr-toolbar-input pr-date-input-unified"
                                    placeholder="Pilih tanggal dan waktu akhir"
                                    readonly
                                    value="{{ $endDate }}"
                                    x-data="{
                                        init() {
                                            if ($el._flatpickr) {
                                                $el._flatpickr.destroy();
                                            }

                                            const picker = flatpickr($el, {
                                                dateFormat: 'Y-m-d H:i',
                                                altInput: true,
                                                altFormat: 'd M Y, H:i',
                                                enableTime: true,
                                                time_24hr: true,
                                                minuteIncrement: 5,
                                                disableMobile: true,
                                                defaultDate: @js($endDate),
                                                onReady: (selectedDates, dateStr, instance) => {
                                                    if (instance.calendarContainer.querySelector('.pr-flatpickr-actions')) {
                                                        return;
                                                    }

                                                    const actions = document.createElement('div');
                                                    actions.className = 'pr-flatpickr-actions';

                                                    const clearBtn = document.createElement('button');
                                                    clearBtn.type = 'button';
                                                    clearBtn.className = 'pr-flatpickr-btn';
                                                    clearBtn.textContent = 'Bersihkan';
                                                    clearBtn.addEventListener('click', () => {
                                                        instance.clear();
                                                        $wire.set('endDate', null);
                                                    });

                                                    const applyBtn = document.createElement('button');
                                                    applyBtn.type = 'button';
                                                    applyBtn.className = 'pr-flatpickr-btn pr-flatpickr-btn-apply';
                                                    applyBtn.textContent = 'Pilih';
                                                    applyBtn.addEventListener('click', () => {
                                                        instance.close();
                                                    });

                                                    actions.appendChild(clearBtn);
                                                    actions.appendChild(applyBtn);
                                                    instance.calendarContainer.appendChild(actions);
                                                },
                                                onChange: (selectedDates, dateStr) => {
                                                    $wire.set('endDate', dateStr);
                                                },
                                                onClose: (selectedDates, dateStr) => {
                                                    if (!dateStr) {
                                                        $wire.set('endDate', null);
                                                    }
                                                },
                                            });

                                            window.addEventListener('pr-date-filters-reset', () => {
                                                picker.clear();
                                            });
                                        }
                                    }">
                            </div>

                            <div class="pr-filter-actions">
                                <button
                                    type="button"
                                    class="pr-filter-clear-btn"
                                    wire:click="resetDateFilters"
                                    title="Reset rentang tanggal"
                                    aria-label="Reset rentang tanggal">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <div class="pr-list-surface">
                @forelse($prList as $pr)
                @php
                $statusColor = \App\Constants\PrStatusConstant::getColor($pr->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
                @endphp
                <div class="pr-list-card">
                    <div class="pr-status-stripe status-{{ $statusColor }}"></div>

                    <div class="pr-card-content">
                        <div class="pr-main-info">
                            <span class="pr-number-label">{{ $pr->detail->title ?? 'Tanpa Judul' }}</span>
                            <h2 class="pr-title-label">
                                {{ $pr->pr_number }}
                                @if($pr->detail->document_no)
                                <span class="pr-document-no">{{ $pr->detail->document_no }}</span>
                                @endif
                            </h2>

                            <div class="pr-meta-info">
                                <div class="pr-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    <span>{{ $pr->created_at?->format('d F Y') ?? '-' }}</span>
                                </div>

                                <div class="pr-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 14h2l-2 5H4l-2-5h2v-3l4-2h8l4 2v3z M8 11V7h3v4M13 11V5h3v6" />
                                    </svg>
                                    <span>{{ $pr->detail->vessel->name ?? 'Semua Kapal' }}</span>
                                </div>

                                <div class="pr-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                    </svg>
                                    <span>{{ $pr->detail->needs ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="pr-status-container">
                            @php
                            $statusLabel = \App\Constants\PrStatusConstant::getStatuses()[$pr->pr_status] ?? $pr->pr_status ?? 'Menunggu';
                            @endphp
                            <div class="pr-status-badge badge-{{ $statusColor }}">
                                {{ $statusLabel }}
                            </div>
                        </div>

                        <button class="pr-action-btn" wire:click="showDetail({{ $pr->id }})">
                            <span>Selengkapnya</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="pr-empty-state">
                    <div class="pr-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <p class="pr-empty-text">Belum ada daftar pengajuan barang.</p>
                    <p style="color: #94a3b8; margin-top: 0.5rem; font-size: 1.1rem;">Semua pengajuan yang Anda buat akan muncul di sini.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-10 pr-pagination-wrapper">
                @if ($prList->hasPages())
                <nav role="navigation" aria-label="Pagination Navigation" class="pr-pagination-nav">
                    {{-- Previous Page Link --}}
                    @if ($prList->onFirstPage())
                    <span class="pr-page-btn pr-page-disabled">
                        Sebelumnya
                    </span>
                    @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="pr-page-btn pr-page-active-btn">
                        Sebelumnya
                    </button>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($prList->getUrlRange(1, $prList->lastPage()) as $page => $url)
                    @if ($page == $prList->currentPage())
                    <span class="pr-page-num pr-page-num-current">{{ $page }}</span>
                    @else
                    <button wire:click="gotoPage({{ $page }})" class="pr-page-num pr-page-num-btn">
                        {{ $page }}
                    </button>
                    @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($prList->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="pr-page-btn pr-page-active-btn">
                        Berikutnya
                    </button>
                    @else
                    <span class="pr-page-btn pr-page-disabled">
                        Berikutnya
                    </span>
                    @endif
                </nav>
                @endif
            </div>

            <style>
                .pr-pagination-wrapper {
                    display: flex;
                    justify-content: center;
                    width: 100%;
                    padding-top: 0.65rem;
                    margin-top: 1.35rem;
                }

                .dark .pr-pagination-wrapper {
                    border-color: transparent;
                }

                .pr-pagination-nav {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.75rem;
                    width: auto;
                    max-width: 100%;
                    flex-wrap: nowrap;
                    white-space: nowrap;
                    padding: 0.55rem 0.7rem;
                    border-radius: 1rem;
                    border: 1px solid #dbe4f0;
                    background: #f8fafc;
                    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
                }

                .dark .pr-pagination-nav {
                    border-color: #334155;
                    background: #0f172a;
                    box-shadow: none;
                }

                .pr-page-btn {
                    padding: 0.75rem 1.5rem;
                    border-radius: 0.75rem;
                    font-size: 1.1rem;
                    font-weight: 700;
                    transition: all 0.2s;
                    border: 2px solid transparent;
                }

                .pr-page-active-btn {
                    background: #f1f5f9;
                    color: #1e40af;
                    cursor: pointer;
                }

                .dark .pr-page-active-btn {
                    background: #334155;
                    color: #60a5fa;
                }

                .pr-page-active-btn:hover {
                    background: #1e40af;
                    color: #ffffff;
                }

                .dark .pr-page-active-btn:hover {
                    background: #60a5fa;
                    color: #0f172a;
                }

                .pr-page-disabled {
                    color: #94a3b8;
                    cursor: not-allowed;
                    opacity: 0.5;
                }

                .pr-page-num {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 3.5rem;
                    height: 3.5rem;
                    border-radius: 50%;
                    font-size: 1.2rem;
                    font-weight: 800;
                    transition: all 0.2s;
                }

                .pr-page-num-btn {
                    color: #475569;
                    background: transparent;
                    cursor: pointer;
                }

                .dark .pr-page-num-btn {
                    color: #94a3b8;
                }

                .pr-page-num-btn:hover {
                    background: #f1f5f9;
                    color: #1e40af;
                }

                .dark .pr-page-num-btn:hover {
                    background: #334155;
                    color: #60a5fa;
                }

                .pr-page-num-current {
                    background: #1e40af;
                    color: #ffffff;
                    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
                }

                .dark .pr-page-num-current {
                    background: #60a5fa;
                    color: #0f172a;
                }
            </style>
        </x-filament::section>
    </div>

    {{-- ===== DETAIL MODAL ===== --}}
    @if($showDetailModal && $selectedPr)
    <div class="pr-modal-overlay" wire:click.self="closeDetail">
        <div class="pr-modal-container">
            {{-- MODAL HEADER --}}
            <div class="pr-modal-header">
                <div class="pr-modal-header-title">
                    <div class="pr-modal-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width:1.25rem;height:1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <div class="pr-modal-title">Detail Pengajuan PR</div>
                        <div class="pr-modal-subtitle">Nomor PR: {{ $selectedPr->pr_number }}</div>
                    </div>
                </div>
                <button type="button" class="pr-modal-close" wire:click="closeDetail" title="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- MODAL BODY --}}
            <div class="pr-modal-body">
                {{-- INFO SUMMARY --}}
                <div class="pr-preview-info-grid">
                    <div class="pr-preview-info-card">
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">No. Dokumen</span>
                            <span class="pr-preview-info-value">{{ $selectedPr->detail->document_no }}</span>
                        </div>
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Nama Kapal</span>
                            <span class="pr-preview-info-value">{{ $selectedPr->detail->vessel->name ?? '-' }}</span>
                        </div>
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Tanggal Pengajuan</span>
                            <span class="pr-preview-info-value">{{ $selectedPr->created_at->format('d F Y') }}</span>
                        </div>
                    </div>
                    <div class="pr-preview-info-card">
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Status</span>
                            @php
                            $selectedStatusColor = \App\Constants\PrStatusConstant::getColor($selectedPr->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
                            @endphp
                            <span class="pr-status-badge badge-{{ $selectedStatusColor }}" style="padding: 0.2rem 1rem; font-size: 0.75rem; min-width: auto;">
                                {{ \App\Constants\PrStatusConstant::getStatuses()[$selectedPr->pr_status] ?? $selectedPr->pr_status }}
                            </span>
                        </div>
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Kebutuhan</span>
                            <span class="pr-preview-info-value">{{ $selectedPr->detail->needs }}</span>
                        </div>
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Total Item</span>
                            <span class="pr-preview-info-value">{{ $selectedPr->detail->items->count() }} item</span>
                        </div>
                    </div>
                </div>

                {{-- ITEMS TABLE --}}
                <div>
                    <span class="pr-preview-section-title">Daftar Barang</span>
                    <div class="pr-preview-table-wrap">
                        <div class="pr-preview-table-scroll">
                            <table class="pr-preview-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px; text-align: center;">#</th>
                                        <th>Kategori Item</th>
                                        <th>Jenis / Nama Barang</th>
                                        <th>Ukuran / Spesifikasi</th>
                                        <th style="text-align: right;">Jumlah</th>
                                        <th>Satuan</th>
                                        <th style="text-align: right;">Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedPr->detail->items as $index => $item)
                                    <tr>
                                        <td style="text-align: center; color: #94a3b8; font-weight: 700;">{{ $index + 1 }}</td>
                                        <td class="pr-col-category">{{ $item->itemCategory->name ?? '—' }}</td>
                                        <td style="font-weight: 700;">{{ $item->type }}</td>
                                        <td>{{ $item->size }}</td>
                                        <td style="text-align: right; font-weight: 700;">{{ $item->quantity }}</td>
                                        <td style="color: #64748b;">{{ $item->unit }}</td>
                                        <td style="text-align: right; font-weight: 700;">{{ $item->remaining }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL FOOTER --}}
            <div class="pr-modal-footer">
                <x-filament::button color="gray" wire:click="closeDetail">
                    Tutup
                </x-filament::button>
                <x-filament::button
                    color="secondary"
                    wire:click="showItemHistory"
                    style="margin-left: 0.75rem; background: #ede9fe; color: #5b21b6; border: 2px solid #ddd6fe;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1rem;height:1rem;display:inline;margin-right:0.3rem;vertical-align:-2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Perubahan Item
                </x-filament::button>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== HISTORY MODAL ===== --}}
    @if($showHistoryModal && $selectedPr)
    <div class="pr-history-modal-overlay" wire:click.self="closeItemHistory">
        <div class="pr-history-modal-container">

            {{-- HEADER --}}
            <div class="pr-history-modal-header">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <div style="width:2.5rem;height:2.5rem;background:rgba(255,255,255,0.2);border-radius:0.625rem;display:flex;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width:1.25rem;height:1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:1.125rem;font-weight:700;color:#ffffff;">Riwayat Perubahan Item</div>
                        <div style="font-size:0.75rem;color:rgba(255,255,255,0.75);">PR {{ $selectedPr->pr_number }} — sebelum &amp; sesudah approval</div>
                    </div>
                </div>
                <button type="button" class="pr-modal-close" wire:click="closeItemHistory" title="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- BODY --}}
            <div class="pr-history-modal-body">

                @if(empty($itemSnapshots))
                <div style="text-align:center; padding:4rem 2rem; color:#94a3b8;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:3rem;height:3rem;margin:0 auto 1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p style="font-size:1.1rem;font-weight:700;">Belum ada riwayat perubahan item.</p>
                </div>
                @else
                {{-- TIMELINE --}}
                <div class="pr-snapshot-timeline">
                    @foreach($itemSnapshots as $sIdx => $snapshot)
                    <div class="pr-snapshot-node">
                        <div class="pr-snapshot-dot {{ $sIdx === 0 ? 'first' : '' }}">{{ $sIdx + 1 }}</div>
                        <span class="pr-snapshot-label {{ $sIdx === 0 ? 'first' : '' }}">{{ $snapshot['label'] }}</span>
                        <span style="font-size:0.6rem;color:#94a3b8;text-align:center;">
                            {{ \Carbon\Carbon::parse($snapshot['created_at'])->format('d/m/y H:i') }}
                        </span>
                    </div>
                    @if(! $loop->last)
                    <div class="pr-snapshot-line"></div>
                    @endif
                    @endforeach
                </div>

                {{-- DIFF TABLE --}}
                @php
                // Collect all unique item keys across all snapshots
                $allKeys = collect($itemSnapshots)->flatMap(fn($s) => array_keys($s['items']))->unique()->values();
                @endphp
                <div style="overflow-x:auto;">
                    <table class="pr-diff-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>Kategori</th>
                                <th>Nama Barang</th>
                                <th>Ukuran</th>
                                <th>Satuan</th>
                                @foreach($itemSnapshots as $sIdx => $snapshot)
                                <th class="snapshot-header {{ $sIdx === 0 ? 'first' : '' }}">
                                    {{ $snapshot['label'] }}<br>
                                    <span style="font-weight:500;font-size:0.65rem;">Jumlah</span>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allKeys as $rowIdx => $key)
                            @php
                            // Ambil referensi item dari snapshot pertama yang memiliki key ini
                            $baseItem = null;
                            foreach ($itemSnapshots as $s) {
                            if (isset($s['items'][$key])) { $baseItem = $s['items'][$key]; break; }
                            }
                            @endphp
                            <tr>
                                <td style="text-align:center;color:#94a3b8;font-weight:700;">{{ $rowIdx + 1 }}</td>
                                <td class="pr-col-category">{{ $baseItem['category'] ?? '—' }}</td>
                                <td style="font-weight:700;">{{ $baseItem['type'] ?? '—' }}</td>
                                <td>{{ $baseItem['size'] ?? '—' }}</td>
                                <td style="color:#64748b;">{{ $baseItem['unit'] ?? '—' }}</td>
                                @foreach($itemSnapshots as $sIdx => $snapshot)
                                @php
                                $cell = $snapshot['items'][$key] ?? null;
                                $prevQty = null;
                                $dir = null; // 'up' | 'down' | 'same' | null (first)
                                if ($sIdx > 0) {
                                $prevSnap = $itemSnapshots[$sIdx - 1];
                                $prevQty = isset($prevSnap['items'][$key]) ? (float)$prevSnap['items'][$key]['quantity'] : null;
                                if ($cell !== null && $prevQty !== null) {
                                $curQty = (float)$cell['quantity'];
                                $dir = $curQty > $prevQty ? 'up' : ($curQty < $prevQty ? 'down' : 'same' );
                                    }
                                    }
                                    $changed=$dir==='up' || $dir==='down' ;
                                    @endphp
                                    <td class="snapshot-cell {{ $changed ? 'pr-diff-changed' : '' }}">
                                    @if($cell)
                                    {{ number_format((float)$cell['quantity'], 0, ',', '.') }}
                                    @if($dir === 'up')
                                    <span class="pr-diff-badge-up">▲</span>
                                    @elseif($dir === 'down')
                                    <span class="pr-diff-badge-down">▼</span>
                                    @elseif($dir === 'same')
                                    <span class="pr-diff-badge-same">＝</span>
                                    @endif
                                    @else
                                    <span class="pr-diff-no-change">—</span>
                                    @endif
                                    </td>
                                    @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- LEGEND --}}
                <div style="margin-top:1rem;display:flex;align-items:center;gap:1.25rem;font-size:0.78rem;color:#64748b;flex-wrap:wrap;">
                    <span style="display:flex;align-items:center;gap:0.35rem;">
                        <span class="pr-diff-badge-up">▲</span> Jumlah bertambah
                    </span>
                    <span style="display:flex;align-items:center;gap:0.35rem;">
                        <span class="pr-diff-badge-down">▼</span> Jumlah berkurang
                    </span>
                    <span style="display:flex;align-items:center;gap:0.35rem;">
                        <span class="pr-diff-badge-same">＝</span> Tidak ada perubahan
                    </span>
                </div>
                @endif

            </div>

            {{-- FOOTER --}}
            <div class="pr-history-modal-footer">
                <x-filament::button color="gray" wire:click="closeItemHistory">
                    Tutup
                </x-filament::button>
            </div>

        </div>
    </div>
    @endif

</x-filament-panels::page>