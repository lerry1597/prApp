<div 
    x-data="locationGuard('{{ route('capture.location') }}')" 
    x-init="init()" 
    x-show="show" 
    x-cloak 
    class="location-guard-overlay"
>
    <div class="location-guard-card">
        <div class="location-guard-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
        </div>
        
        <h2>Akses Lokasi Diperlukan</h2>
        <p>
            Untuk alasan keamanan dan validitas data, sistem memerlukan akses lokasi Anda. Mohon aktifkan GPS atau izinkan akses lokasi pada browser Anda.
        </p>

        <div class="location-guard-actions">
            <button 
                type="button" 
                @click="requestLocation()" 
                class="location-guard-btn"
                :disabled="loading"
            >
                <span x-show="!loading">Izinkan & Lanjutkan</span>
                <span x-show="loading" class="location-guard-spinner"></span>
            </button>
            
            <button 
                x-show="error"
                type="button" 
                @click="window.location.reload()" 
                class="location-guard-btn-secondary"
            >
                Muat Ulang Halaman
            </button>
        </div>

        <template x-if="error">
            <div class="location-guard-error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span x-text="error"></span>
            </div>
        </template>
    </div>
</div>

<style>
    .location-guard-overlay {
        position: fixed;
        inset: 0;
        z-index: 100000;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 1.5rem;
    }

    .location-guard-card {
        background: #ffffff;
        width: 100%;
        max-width: 440px;
        padding: 2.5rem;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        text-align: center;
        animation: location-guard-pop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .dark .location-guard-card {
        background: #1e293b;
        color: #f1f5f9;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    @keyframes location-guard-pop {
        from { opacity: 0; transform: scale(0.92); }
        to { opacity: 1; transform: scale(1); }
    }

    .location-guard-icon {
        width: 4rem;
        height: 4rem;
        background: #fffbeb;
        color: #d97706;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .dark .location-guard-icon {
        background: rgba(217, 119, 6, 0.15);
        color: #fbbf24;
    }

    .location-guard-icon svg {
        width: 2.25rem;
        height: 2.25rem;
    }

    .location-guard-card h2 {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
        color: #111827;
    }

    .dark .location-guard-card h2 {
        color: #ffffff;
    }

    .location-guard-card p {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #64748b;
        margin-bottom: 2rem;
    }

    .dark .location-guard-card p {
        color: #94a3b8;
    }

    .location-guard-btn {
        width: 100%;
        background: #d97706;
        color: #ffffff;
        border: none;
        padding: 0.85rem 1.5rem;
        border-radius: 0.75rem;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 3.25rem;
    }

    .location-guard-btn:hover {
        background: #b45309;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);
    }

    .location-guard-btn-secondary {
        width: 100%;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 0.85rem 1.5rem;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 0.75rem;
    }

    .location-guard-btn-secondary:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .dark .location-guard-btn-secondary {
        background: #334155;
        color: #cbd5e1;
        border-color: #475569;
    }

    .dark .location-guard-btn-secondary:hover {
        background: #475569;
        color: #ffffff;
    }

    .location-guard-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }

    .location-guard-error {
        margin-top: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: #ef4444;
        font-size: 0.85rem;
        font-weight: 600;
        background: #fef2f2;
        padding: 0.75rem;
        border-radius: 0.5rem;
    }

    .dark .location-guard-error {
        background: rgba(239, 68, 68, 0.1);
    }

    .location-guard-error svg {
        width: 1rem;
        height: 1rem;
    }

    .location-guard-spinner {
        width: 1.25rem;
        height: 1.25rem;
        border: 2.5px solid rgba(255, 255, 255, 0.3);
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: location-spinner 0.6s linear infinite;
    }

    @keyframes location-spinner {
        to { transform: rotate(360deg); }
    }
</style>

<script>
    function locationGuard(endpoint) {
        return {
            show: false,
            loading: false,
            error: null,
            endpoint: endpoint,

            init() {
                // Pantau status izin secara berkala, saat navigasi, dan saat fokus kembali
                this.checkPermission();
                
                // Selalu munculkan jika belum sukses menangkap lokasi dalam session
                const captured = sessionStorage.getItem('location_captured');
                if (!captured) {
                    this.show = true;
                    this.toggleBodyScroll(true);
                    this.blockSubmitButtons(true);
                }

                // Heartbeat check: Cek setiap 3 detik untuk keamanan ekstra
                setInterval(() => {
                    this.checkPermission();
                }, 3000);

                // Cek saat pengguna kembali ke tab ini
                window.addEventListener('focus', () => {
                    this.checkPermission();
                });

                // Cek ulang setiap kali Livewire melakukan navigasi
                document.addEventListener('livewire:navigated', () => {
                    this.checkPermission();
                });
            },

            toggleBodyScroll(block) {
                if (block) document.body.style.overflow = 'hidden';
                else document.body.style.overflow = '';
            },

            blockSubmitButtons(block) {
                // Mencari semua tombol submit (termasuk tombol login Filament)
                const buttons = document.querySelectorAll('button[type="submit"], .fi-btn');
                buttons.forEach(btn => {
                    if (block) {
                        btn.setAttribute('data-original-disabled', btn.disabled);
                        btn.disabled = true;
                        btn.style.opacity = '0.5';
                        btn.style.pointerEvents = 'none';
                    } else {
                        const original = btn.getAttribute('data-original-disabled') === 'true';
                        btn.disabled = original;
                        btn.style.opacity = '';
                        btn.style.pointerEvents = '';
                    }
                });
            },

            async checkPermission() {
                if (!navigator.permissions) return;
                
                try {
                    const status = await navigator.permissions.query({ name: 'geolocation' });
                    console.log('Current Location Permission State:', status.state);
                    
                    // Jika izin dicabut (denied) atau di-reset (prompt), paksa munculkan modal kembali
                    // meskipun sebelumnya sudah pernah sukses (sessionStorage ada)
                    if (status.state === 'denied' || status.state === 'prompt') {
                        if (status.state === 'denied') {
                            this.error = "Akses lokasi diblokir. Mohon aktifkan kembali untuk melanjutkan.";
                        } else {
                            this.error = null; // Status prompt, biarkan user klik tombol lagi
                        }

                        this.show = true;
                        sessionStorage.removeItem('location_captured');
                        this.toggleBodyScroll(true);
                        this.blockSubmitButtons(true);
                    }

                    // Pantau perubahan status secara real-time
                    status.onchange = () => {
                        console.log('Location Permission Changed to:', status.state);
                        this.checkPermission();
                    };
                } catch (e) {
                    console.error("Error checking permissions:", e);
                }
            },

            async requestLocation() {
                this.loading = true;
                this.error = null;

                if (!navigator.geolocation) {
                    this.error = "Browser Anda tidak mendukung geolokasi.";
                    this.loading = false;
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                        try {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                                             || document.querySelector('input[name="_token"]')?.value;

                            const response = await fetch(this.endpoint, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({ lat, lng })
                            });

                            sessionStorage.setItem('location_captured', 'true');
                            
                            window.dispatchEvent(new CustomEvent('location-captured', { 
                                detail: { lat, lng } 
                            }));

                            this.show = false;
                            this.toggleBodyScroll(false);
                            this.blockSubmitButtons(false);
                        } catch (err) {
                            console.error(err);
                            this.error = "Terjadi kesalahan saat memproses lokasi.";
                        } finally {
                            this.loading = false;
                        }
                    },
                    (err) => {
                        this.loading = false;
                        switch(err.code) {
                            case err.PERMISSION_DENIED:
                                this.error = "Akses ditolak. Silakan aktifkan lokasi di pengaturan browser/sistem Anda.";
                                break;
                            case err.POSITION_UNAVAILABLE:
                                this.error = "Informasi lokasi tidak tersedia. Pastikan GPS aktif.";
                                break;
                            case err.TIMEOUT:
                                this.error = "Waktu permintaan habis. Silakan coba lagi.";
                                break;
                            default:
                                this.error = "Gagal mendapatkan lokasi.";
                        }
                    },
                    { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
                );
            }
        };
    }
</script>
