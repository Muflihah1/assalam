@extends('layouts.customer')

@section('content')
<div class="container-xl py-4">

    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="{{ route('customer.beranda') }}" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">Workshop & Lokasi</li>
        </ol>
    </nav>

    <!-- PAGE HEADER: MINIMAL & CLEAR -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 pb-2 border-bottom">
        <div>
            <h3 class="fw-bold text-dark mb-1">Workshop Assalam Mebel</h3>
            <p class="text-muted small mb-0">
                <i class="fa-solid fa-location-dot text-danger me-1"></i> Dusun Somangkaan, Desa Karduluk, Kec. Pragaan, Kab. Sumenep, Madura (Plus Code: <strong>VPR6+PH7</strong>)
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="https://maps.google.com/?q=-7.1082125,113.7114219" target="_blank" rel="noopener" class="btn btn-outline-dark btn-sm rounded-pill px-3 py-2 fw-semibold">
                <i class="fa-solid fa-location-arrow me-1 text-danger"></i> Buka Google Maps
            </a>
            <a href="https://wa.me/6285234567890?text=Halo%20Assalam%20Mebel,%20saya%20ingin%20konsultasi%20workshop%20Karduluk" target="_blank" rel="noopener" class="btn btn-success btn-sm rounded-pill px-3 py-2 fw-semibold">
                <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
            </a>
        </div>
    </div>

    <!-- PETA INTERAKTIF: BATAS DARATAN PULAU MADURA & TITIK WORKSHOP -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="border: 1px solid var(--border-color) !important;">
        <div class="p-3 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <span class="small fw-bold text-dark">
                    <i class="fa-solid fa-map-location-dot text-warning me-1.5"></i> Peta Wilayah Jangkauan Pengiriman
                </span>
                <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill small">
                    Khusus Daratan Pulau Madura
                </span>
            </div>
            <div class="d-flex align-items-center gap-1.5">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" onclick="window.resetMapMadura()">
                    <i class="fa-solid fa-expand me-1"></i> Seluruh Madura
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-bold" onclick="window.focusWorkshopKarduluk()">
                    <i class="fa-solid fa-store me-1"></i> Workshop Karduluk
                </button>
            </div>
        </div>

        <!-- Canvas Peta Leaflet (Tinggi 500px, responsif dan lapang) -->
        <div id="workshopCoverageMap" style="height: 500px; width: 100%; position: relative; z-index: 1;"></div>

        <!-- Legend Minimalis & Ringkas -->
        <div class="p-3 bg-light d-flex align-items-center justify-content-start flex-wrap gap-4 small text-muted border-top">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 14px; height: 14px; background: #DC2626; border-radius: 50%; border: 2px solid #FFF; box-shadow: 0 0 0 2px rgba(220,38,38,0.3);"></div>
                <span class="text-dark fw-semibold">Workshop Karduluk (Pusat Produksi)</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div style="width: 16px; height: 10px; background: rgba(245, 158, 11, 0.2); border: 2px dashed #B45309; border-radius: 2px;"></div>
                <span class="text-dark fw-semibold">Batas Daratan Pulau Madura (Bangkalan, Sampang, Pamekasan, Sumenep)</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div style="width: 12px; height: 12px; background: #3B2314; border-radius: 50%; border: 1.5px solid #FFF;"></div>
                <span>Titik Kabupaten di Madura</span>
            </div>
        </div>
    </div>

    <!-- 3 KARTU INFORMASI UTAMA WORKSHOP (TERSTILISASI ELEGAN & SESUAI DENGAN PETA) -->
    <div class="mb-4">
        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-info text-warning"></i>
            <span>Informasi Detail Workshop & Layanan Pengiriman</span>
        </h5>

        <div class="row g-3 g-lg-4">
            
            <!-- KARTU 1: PUSAT WORKSHOP & GALERI KARDULUK -->
            <div class="col-lg-4 col-md-6">
                <div class="workshop-card workshop-card-accent-red">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="workshop-card-icon red">
                            <i class="fa-solid fa-store"></i>
                        </div>
                        <div>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-0.5 small fw-bold mb-1">
                                Pusat Produksi
                            </span>
                            <h6 class="workshop-card-title">Workshop & Galeri Karduluk</h6>
                        </div>
                    </div>

                    <ul class="workshop-info-list mb-3">
                        <li>
                            <i class="fa-solid fa-location-dot text-danger"></i>
                            <div>
                                <strong>Dusun Somangkaan</strong>, Desa Karduluk, Kec. Pragaan, Kab. Sumenep, Madura 69465.
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-hashtag text-secondary"></i>
                            <div>
                                Google Plus Code: <span class="badge bg-light text-dark border font-monospace fw-bold">VPR6+PH7</span>
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-couch text-warning"></i>
                            <div class="text-muted">
                                Titik bengkel kerja oven kayu (*kiln-dry*), pemahatan seni ukir asli Karduluk, serta *finishing* mebel kayu jati solid.
                            </div>
                        </li>
                    </ul>

                    <div class="mt-auto pt-3 border-top">
                        <a href="https://maps.google.com/?q=-7.1082125,113.7114219" target="_blank" rel="noopener" class="btn btn-outline-danger btn-sm rounded-pill w-100 py-2 fw-semibold">
                            <i class="fa-solid fa-location-arrow me-1"></i> Buka Rute di Google Maps
                        </a>
                    </div>
                </div>
            </div>

            <!-- KARTU 2: CAKUPAN PENGIRIMAN SE-MADURA -->
            <div class="col-lg-4 col-md-6">
                <div class="workshop-card workshop-card-accent-amber">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="workshop-card-icon amber">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div>
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2.5 py-0.5 small fw-bold mb-1">
                                Khusus Se-Madura
                            </span>
                            <h6 class="workshop-card-title">Cakupan Armada Truk</h6>
                        </div>
                    </div>

                    <ul class="workshop-info-list mb-3">
                        <li>
                            <i class="fa-solid fa-map-location-dot text-warning"></i>
                            <div>
                                <strong>Menjangkau 4 Kabupaten:</strong> Kab. Sumenep, Kab. Pamekasan, Kab. Sampang, dan Kab. Bangkalan.
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-truck-ramp-box text-primary"></i>
                            <div class="text-muted">
                                Pengiriman menggunakan <strong>armada truk tertutup internal</strong> Assalam Mebel untuk menjamin keamanan kayu dan cat ukiran.
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-screwdriver-wrench text-success"></i>
                            <div class="text-muted">
                                Termasuk layanan perakitan dan penempatan mebel langsung di rumah atau ruangan pemesan.
                            </div>
                        </li>
                    </ul>

                    <div class="mt-auto pt-3 border-top">
                        <a href="https://wa.me/6285234567890?text=Halo%20Assalam%20Mebel,%20saya%20ingin%20konsultasi%20jadwal%20dan%20ongkir%20pengiriman%20truk%20ke%20alamat%20saya" target="_blank" rel="noopener" class="btn btn-outline-dark btn-sm rounded-pill w-100 py-2 fw-semibold">
                            <i class="fa-brands fa-whatsapp text-success me-1"></i> Cek Jadwal & Ongkir Truk
                        </a>
                    </div>
                </div>
            </div>

            <!-- KARTU 3: JAM OPERASIONAL & KUNJUNGAN TAMU -->
            <div class="col-lg-4 col-md-12">
                <div class="workshop-card workshop-card-accent-green">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="workshop-card-icon green">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5 small fw-bold mb-1">
                                <i class="fa-solid fa-door-open me-1"></i> Buka untuk Tamu
                            </span>
                            <h6 class="workshop-card-title">Jam Kerja & Kunjungan</h6>
                        </div>
                    </div>

                    <ul class="workshop-info-list mb-3">
                        <li>
                            <i class="fa-solid fa-calendar-check text-success"></i>
                            <div>
                                <strong>Senin – Sabtu:</strong> 08:00 – 17:00 WIB<br>
                                <small class="text-muted">Bengkel produksi aktif, terbuka langsung untuk calon pembeli.</small>
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-calendar-day text-secondary"></i>
                            <div>
                                <strong>Minggu & Hari Libur:</strong> Buka dengan konfirmasi janji temu melalui WhatsApp terlebih dahulu.
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-handshake text-primary"></i>
                            <div class="text-muted">
                                Anda dapat melihat langsung kualitas kayu jati solid, memilih motif ukiran, atau membawa denah ruangan.
                            </div>
                        </li>
                    </ul>

                    <div class="mt-auto pt-3 border-top">
                        <a href="https://wa.me/6285234567890?text=Halo%20Assalam%20Mebel,%20saya%20ingin%20jadwalkan%20kunjungan%20ke%20workshop%20Karduluk" target="_blank" rel="noopener" class="btn btn-success btn-sm rounded-pill w-100 py-2 fw-semibold">
                            <i class="fa-solid fa-calendar-plus me-1"></i> Buat Janji Kunjungan via WA
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    (function() {
        let workshopMapInstance = null;
        let maduraLayerInstance = null;
        let kardulukMarker = null;

        const WORKSHOP_LAT = -7.1082125;
        const WORKSHOP_LNG = 113.7114219;

        const REGENCY_COORDS = {
            sumenep: { lat: -7.0051, lng: 113.8604, name: 'Kabupaten Sumenep' },
            pamekasan: { lat: -7.1580, lng: 113.4750, name: 'Kabupaten Pamekasan' },
            sampang: { lat: -7.1873, lng: 113.2394, name: 'Kabupaten Sampang' },
            bangkalan: { lat: -7.0315, lng: 112.7480, name: 'Kabupaten Bangkalan' }
        };

        function initWorkshopMap() {
            const mapContainer = document.getElementById('workshopCoverageMap');
            if (!mapContainer || workshopMapInstance) return;

            workshopMapInstance = L.map('workshopCoverageMap', {
                scrollWheelZoom: false,
                zoomControl: true,
                minZoom: 8,
                maxZoom: 18
            }).setView([WORKSHOP_LAT, WORKSHOP_LNG], 9);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(workshopMapInstance);

            // Batas Garis Geografis Pulau Madura
            if (window.MADURA_GEOJSON) {
                maduraLayerInstance = L.geoJSON(window.MADURA_GEOJSON, {
                    style: {
                        color: '#B45309',
                        weight: 2.5,
                        opacity: 0.9,
                        dashArray: '5, 5',
                        fillColor: '#F59E0B',
                        fillOpacity: 0.15
                    }
                }).addTo(workshopMapInstance);

                workshopMapInstance.fitBounds(maduraLayerInstance.getBounds(), {
                    padding: [25, 25]
                });
            }

            // Pin Karduluk
            const kardulukIcon = L.divIcon({
                className: 'custom-workshop-pin',
                html: `<div style="position: relative; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                         <div style="position: absolute; width: 100%; height: 100%; border-radius: 50%; background: rgba(220, 38, 38, 0.35); box-shadow: 0 0 12px rgba(220,38,38,0.5);"></div>
                         <div style="position: relative; background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2.5px solid #FFFFFF; box-shadow: 0 3px 10px rgba(0,0,0,0.3); font-size: 14px;">
                             <i class="fa-solid fa-store"></i>
                         </div>
                       </div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20],
                popupAnchor: [0, -22]
            });

            kardulukMarker = L.marker([WORKSHOP_LAT, WORKSHOP_LNG], { icon: kardulukIcon }).addTo(workshopMapInstance);
            
            kardulukMarker.bindPopup(`
                <div style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif; min-width: 220px; padding: 2px;">
                    <strong style="color: #3B2314; font-size: 13px; display: block; margin-bottom: 2px;">Workshop Assalam Mebel</strong>
                    <div style="font-size: 11px; color: #555; margin-bottom: 6px; line-height: 1.4;">
                        Dusun Somangkaan, Desa Karduluk, Kec. Pragaan, Kab. Sumenep, Madura<br>
                        <span style="font-family: monospace; font-size: 10.5px; background: #f3f4f6; padding: 1px 4px; border-radius: 3px; display: inline-block; margin-top: 3px;">Plus Code: VPR6+PH7</span>
                    </div>
                    <a href="https://maps.google.com/?q=-7.1082125,113.7114219" target="_blank" rel="noopener" class="btn btn-sm btn-dark text-white w-100 py-1" style="font-size: 11px; border-radius: 6px;">
                        <i class="fa-solid fa-location-arrow me-1"></i> Buka Google Maps
                    </a>
                </div>
            `).openPopup();

            // Marker 4 Kabupaten di Madura (Minimalis)
            for (const [key, reg] of Object.entries(REGENCY_COORDS)) {
                const regIcon = L.divIcon({
                    className: 'custom-regency-pin',
                    html: `<div style="background-color: #3B2314; color: #F59E0B; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #FFFFFF; box-shadow: 0 2px 6px rgba(0,0,0,0.25); font-size: 10px;">
                            <i class="fa-solid fa-location-dot"></i>
                           </div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                    popupAnchor: [0, -14]
                });

                const m = L.marker([reg.lat, reg.lng], { icon: regIcon }).addTo(workshopMapInstance);
                m.bindPopup(`<strong style="font-size: 12px; font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">${reg.name}</strong><br><span style="font-size: 11px; color: #666;">Wilayah jangkauan armada Assalam</span>`);
            }
        }

        window.resetMapMadura = function() {
            if (!workshopMapInstance) initWorkshopMap();
            if (workshopMapInstance && maduraLayerInstance) {
                workshopMapInstance.fitBounds(maduraLayerInstance.getBounds(), { padding: [25, 25] });
            }
        };

        window.focusWorkshopKarduluk = function() {
            if (!workshopMapInstance) initWorkshopMap();
            if (workshopMapInstance) {
                workshopMapInstance.flyTo([WORKSHOP_LAT, WORKSHOP_LNG], 14, { duration: 1.1 });
                if (kardulukMarker) kardulukMarker.openPopup();
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initWorkshopMap);
        } else {
            initWorkshopMap();
        }
    })();
</script>
@endpush
