@props([
    'wireModel'   => null,
    'placeholder' => 'Pilih tanggal & waktu',
    'resetEvent'  => null,
    'value'       => null,
    'withTime'    => true,
    'showIcon'    => true,
    'submitWithTime' => false,
])

@once
    <link rel="stylesheet" href="{{ asset('css/components/date-picker.css') }}">
@endonce

<div
    class="app-dp-wrap"
    wire:ignore
    x-data="appDatePicker({
        wireModel:   '{{ $wireModel }}',
        resetEvent:  '{{ $resetEvent }}',
        initValue:   '{{ $value }}',
        withTime:    {{ $withTime ? 'true' : 'false' }},
        submitWithTime: {{ $submitWithTime ? 'true' : 'false' }},
        placeholder: '{{ $placeholder }}',
    })"
    x-init="init()"
>
    {{-- ── Input trigger ── --}}
    <div
        x-ref="trigger"
        @class([
            'app-dp-input',
            'app-dp-input--no-icon' => !$showIcon,
        ])
        :class="{ 'app-dp-input--active': open }"
        @click="toggle()"
        role="button"
        tabindex="0"
        @keydown.enter.prevent="toggle()"
        @keydown.space.prevent="toggle()"
        :aria-label="placeholder"
    >
        @if($showIcon)
        <span class="app-dp-input-icon-box" aria-hidden="true">
            <svg class="app-dp-input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </span>
        @endif
        <span class="app-dp-input-text-wrap">
            <span class="app-dp-input-text" x-text="displayValue || placeholder" :class="{ 'app-dp-placeholder': !displayValue }"></span>
        </span>
        <span class="app-dp-input-caret-box" aria-hidden="true">
            <svg class="app-dp-input-caret" :class="{ 'app-dp-caret-open': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
    </div>

    {{-- ── Modal dialog ── --}}
    <div
        class="app-dp-modal"
        x-show="open"
        x-transition:enter="app-dp-enter"
        x-transition:enter-start="app-dp-enter-start"
        x-transition:enter-end="app-dp-enter-end"
        x-transition:leave="app-dp-leave"
        x-transition:leave-start="app-dp-leave-start"
        x-transition:leave-end="app-dp-leave-end"
        @click.self="close()"
        @keydown.escape.window="close()"
        x-cloak
    >
        <div class="app-dp-dialog" role="dialog" aria-modal="true" @click.stop>
            <div class="app-dp-panes">

            {{-- ── LEFT: Calendar ── --}}
            <div class="app-dp-cal">
                {{-- Month navigation --}}
                <div class="app-dp-cal-nav">
                    <button type="button" class="app-dp-nav-btn" @click="prevMonth()" aria-label="Bulan sebelumnya">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <div class="app-dp-cal-title-group">
                        <select x-model.number="viewMonth" @change="buildCal()" class="app-dp-nav-select">
                            <template x-for="(m, i) in ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']" :key="i">
                                <option :value="i" x-text="m"></option>
                            </template>
                        </select>
                        <select x-model.number="viewYear" @change="buildCal()" class="app-dp-nav-select">
                            <template x-for="y in Array.from({length: 11}, (_, i) => (new Date().getFullYear() - 5) + i)" :key="y">
                                <option :value="y" x-text="y"></option>
                            </template>
                        </select>
                    </div>
                    <button type="button" class="app-dp-nav-btn" @click="nextMonth()" aria-label="Bulan berikutnya">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>

                {{-- Weekday headers --}}
                <div class="app-dp-weekdays">
                    <template x-for="d in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']">
                        <span x-text="d"></span>
                    </template>
                </div>

                {{-- Day grid --}}
                <div class="app-dp-days">
                    <template x-for="cell in calCells" :key="cell.key">
                        <button
                            type="button"
                            class="app-dp-day"
                            :class="{
                                'app-dp-day--other':    cell.other,
                                'app-dp-day--today':    cell.today,
                                'app-dp-day--selected': cell.selected,
                            }"
                            @click="selectDay(cell)"
                            :tabindex="cell.other ? -1 : 0"
                            :aria-label="cell.label"
                            :aria-pressed="cell.selected"
                        >
                            <span x-text="cell.d"></span>
                            <span class="app-dp-today-dot" x-show="cell.today && !cell.selected"></span>
                        </button>
                    </template>
                </div>
            </div>

                {{-- ── RIGHT: Time picker (only when withTime) ── --}}
                <template x-if="withTime">
                    <div class="app-dp-time">
                        <div class="app-dp-time-body">
                            <div class="app-dp-time-display">
                                <div class="app-dp-time-box">
                                    <button type="button" class="app-dp-spin-btn" @click="changeHour(1)" aria-label="Tambah jam">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                    </button>
                                    <input
                                        type="text"
                                        inputmode="numeric"
                                        class="app-dp-time-value"
                                        x-ref="hourInput"
                                        x-init="$el.value = String(hour).padStart(2,'0')"
                                        @focus="$event.target.select()"
                                        @input="let hv=parseInt($event.target.value)||0; if($event.target.value.length>=2){ hour=Math.min(12,Math.max(1,hv)); $event.target.value=String(hour).padStart(2,'0'); buildDisplay(); }"
                                        @blur="hour=Math.min(12,Math.max(1,parseInt($event.target.value)||1)); $event.target.value=String(hour).padStart(2,'0'); buildDisplay()"
                                        @keydown.up.prevent="changeHour(1)"
                                        @keydown.down.prevent="changeHour(-1)"
                                        maxlength="2"
                                        aria-label="Jam"
                                    />
                                    <button type="button" class="app-dp-spin-btn" @click="changeHour(-1)" aria-label="Kurangi jam">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <span class="app-dp-time-sub">Jam</span>
                                </div>
                                <div class="app-dp-time-sep">:</div>
                                <div class="app-dp-time-box">
                                    <button type="button" class="app-dp-spin-btn" @click="changeMin(5)" aria-label="Tambah menit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                    </button>
                                    <input
                                        type="text"
                                        inputmode="numeric"
                                        class="app-dp-time-value"
                                        x-ref="minInput"
                                        x-init="$el.value = String(minute).padStart(2,'0')"
                                        @focus="$event.target.select()"
                                        @input="let mv=parseInt($event.target.value)||0; if($event.target.value.length>=2){ minute=Math.min(59,Math.max(0,mv)); $event.target.value=String(minute).padStart(2,'0'); buildDisplay(); }"
                                        @blur="minute=Math.min(59,Math.max(0,parseInt($event.target.value)||0)); $event.target.value=String(minute).padStart(2,'0'); buildDisplay()"
                                        @keydown.up.prevent="changeMin(1)"
                                        @keydown.down.prevent="changeMin(-1)"
                                        maxlength="2"
                                        aria-label="Menit"
                                    />
                                    <button type="button" class="app-dp-spin-btn" @click="changeMin(-5)" aria-label="Kurangi menit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <span class="app-dp-time-sub">Menit</span>
                                </div>
                            </div>

                            <div class="app-dp-ampm-toggle">
                                <button type="button" 
                                    @click="setAmpm('AM')" 
                                    class="app-dp-ampm-btn" 
                                    :class="{ 'app-dp-ampm-btn--active': ampm === 'AM' }">AM</button>
                                <button type="button" 
                                    @click="setAmpm('PM')" 
                                    class="app-dp-ampm-btn" 
                                    :class="{ 'app-dp-ampm-btn--active': ampm === 'PM' }">PM</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="app-dp-action-row">
                <button type="button" class="app-dp-action-btn app-dp-action-cancel" @click="cancelSelection()">Batal</button>
                <button type="button" class="app-dp-action-btn app-dp-action-apply" @click="applySelection()">Terapkan</button>
            </div>
        </div>
    </div>
</div>

@once
<script>
function appDatePicker({ wireModel, resetEvent, initValue, withTime, submitWithTime, placeholder }) {
    return {
        open: false,
        withTime,
        submitWithTime,
        wireModel,
        resetEvent,
        placeholder,

        // Calendar state
        viewYear: 0,
        viewMonth: 0,
        selYear: null,
        selMonth: null,
        selDay: null,

        // Time state
        hour: 12,   // 1–12
        minute: 0,
        ampm: 'AM',

        // Computed display
        displayValue: '',
        calCells: [],
        snapshot: null,

        // ── Lifecycle ──────────────────────────────
        init() {
            const today = new Date();
            if (initValue) {
                const normalizedValue = String(initValue).trim().replace(' ', 'T');
                const candidate = normalizedValue.includes('T')
                    ? normalizedValue
                    : `${normalizedValue}T00:00:00`;
                const d = new Date(candidate);
                if (!isNaN(d)) {
                    this.selYear  = d.getFullYear();
                    this.selMonth = d.getMonth();
                    this.selDay   = d.getDate();
                    this.viewYear  = this.selYear;
                    this.viewMonth = this.selMonth;
                    if (withTime) {
                        const h = d.getHours();
                        this.ampm   = h >= 12 ? 'PM' : 'AM';
                        this.hour   = h % 12 || 12;
                        this.minute = d.getMinutes();
                    }
                    this.buildDisplay();
                } else {
                    this.viewYear  = today.getFullYear();
                    this.viewMonth = today.getMonth();
                }
            } else {
                this.viewYear  = today.getFullYear();
                this.viewMonth = today.getMonth();
            }
            this.buildCal();
            this.captureState();

            if (resetEvent) {
                window.addEventListener(resetEvent, () => this.clearValue());
            }
        },

        // ── Calendar builder ───────────────────────
        get monthYearLabel() {
            return new Date(this.viewYear, this.viewMonth, 1)
                .toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        },

        buildCal() {
            const cells = [];
            const first = new Date(this.viewYear, this.viewMonth, 1).getDay();
            const days  = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
            const today = new Date();
            const ty = today.getFullYear(), tm = today.getMonth(), td = today.getDate();

            // Prev month padding
            const prevDays = new Date(this.viewYear, this.viewMonth, 0).getDate();
            for (let i = first - 1; i >= 0; i--) {
                const d = prevDays - i;
                cells.push({ key: `p${d}`, d, other: true, today: false, selected: false, label: '' });
            }

            for (let d = 1; d <= days; d++) {
                const isToday    = this.viewYear === ty && this.viewMonth === tm && d === td;
                const isSelected = this.selYear === this.viewYear && this.selMonth === this.viewMonth && this.selDay === d;
                cells.push({
                    key: `c${d}`,
                    d,
                    other:    false,
                    today:    isToday,
                    selected: isSelected,
                    label:    `${d} ${this.monthYearLabel}`,
                });
            }

            // Next month padding (fill to 42 cells)
            let n = 1;
            while (cells.length < 42) {
                cells.push({ key: `n${n}`, d: n++, other: true, today: false, selected: false, label: '' });
            }

            this.calCells = cells;
        },

        prevMonth() {
            if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; }
            else this.viewMonth--;
            this.buildCal();
        },

        nextMonth() {
            if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; }
            else this.viewMonth++;
            this.buildCal();
        },

        selectDay(cell) {
            if (cell.other) return;
            this.selYear  = this.viewYear;
            this.selMonth = this.viewMonth;
            this.selDay   = cell.d;
            this.buildCal();
            this.buildDisplay();
        },

        clearValue() {
            this.selYear = this.selMonth = this.selDay = null;
            this.displayValue = '';
            this.buildCal();
            if (wireModel && this.$wire) this.$wire.set(wireModel, null);
        },

        // ── Time helpers ───────────────────────────
        get hourItems() {
            return Array.from({ length: 12 }, (_, i) => {
                const v = i + 1;
                return { val: v, label: String(v).padStart(2, '0'), active: v === this.hour };
            });
        },

        get minItems() {
            return Array.from({ length: 12 }, (_, i) => {
                const v = i * 5;
                return { val: v, label: String(v).padStart(2, '0'), active: v === this.minute };
            });
        },

        changeHour(delta) { this.hour = ((this.hour - 1 + delta + 12) % 12) + 1; if (this.$refs.hourInput) this.$refs.hourInput.value = String(this.hour).padStart(2,'0'); this.buildDisplay(); },
        setHour(v)        { this.hour = v; this.buildDisplay(); },
        changeMin(delta)  { this.minute = ((this.minute + delta) + 60) % 60; if (this.$refs.minInput) this.$refs.minInput.value = String(this.minute).padStart(2,'0'); this.buildDisplay(); },
        setMin(v)         { this.minute = v; this.buildDisplay(); },
        setAmpm(v)        { this.ampm = v; this.buildDisplay(); },

        // ── Display & value ───────────────────────
        buildDisplay() {
            if (this.selDay === null) { this.displayValue = ''; return; }
            const date = new Date(this.selYear, this.selMonth, this.selDay);
            let str = date.toLocaleDateString('en-US', {
                month: 'short',
                day: '2-digit',
                year: 'numeric',
            });
            if (withTime) {
                str += ` - ${String(this.hour).padStart(2,'0')}:${String(this.minute).padStart(2,'0')} ${this.ampm}`;
            }
            this.displayValue = str;
        },

        emitValue() {
            if (!wireModel || this.selDay === null) return;
            let h24 = this.hour % 12 + (this.ampm === 'PM' ? 12 : 0);
            const pad = n => String(n).padStart(2, '0');
            const dateStr = `${this.selYear}-${pad(this.selMonth + 1)}-${pad(this.selDay)}`;
            const val = (withTime && submitWithTime)
                ? `${dateStr} ${pad(h24)}:${pad(this.minute)}:00`
                : dateStr;
            if (this.$wire) this.$wire.set(wireModel, val);
        },

        captureState() {
            this.snapshot = {
                selYear: this.selYear,
                selMonth: this.selMonth,
                selDay: this.selDay,
                viewYear: this.viewYear,
                viewMonth: this.viewMonth,
                hour: this.hour,
                minute: this.minute,
                ampm: this.ampm,
            };
        },

        restoreState() {
            if (!this.snapshot) return;
            this.selYear = this.snapshot.selYear;
            this.selMonth = this.snapshot.selMonth;
            this.selDay = this.snapshot.selDay;
            this.viewYear = this.snapshot.viewYear;
            this.viewMonth = this.snapshot.viewMonth;
            this.hour = this.snapshot.hour;
            this.minute = this.snapshot.minute;
            this.ampm = this.snapshot.ampm;
            this.buildCal();
            this.buildDisplay();
        },

        applySelection() {
            // Sync raw DOM values before applying (in case blur hasn't fired)
            if (this.$refs.hourInput) {
                this.hour = Math.min(12, Math.max(1, parseInt(this.$refs.hourInput.value) || 1));
                this.$refs.hourInput.value = String(this.hour).padStart(2, '0');
            }
            if (this.$refs.minInput) {
                this.minute = Math.min(59, Math.max(0, parseInt(this.$refs.minInput.value) || 0));
                this.$refs.minInput.value = String(this.minute).padStart(2, '0');
            }
            this.buildDisplay();
            if (this.selDay === null) {
                if (wireModel && this.$wire) this.$wire.set(wireModel, null);
            } else {
                this.emitValue();
            }
            this.captureState();
            this.close();
        },

        cancelSelection() {
            this.restoreState();
            this.close();
        },

        // ── Dialog open/close ──────────────────────
        toggle() {
            if (!this.open) {
                this.captureState();
                this.open = true;
                return;
            }
            this.close();
        },

        close() {
            this.open = false;
        },
    };
}
</script>
@endonce
