<!-- SKM Floating Button -->
<div id="skm-floating-button" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    <button type="button" class="btn btn-lg btn-primary shadow-lg" data-toggle="modal" data-target="#modalSkm" style="border-radius: 50px; padding: 12px 25px; transition: all 0.3s ease;">
        <i class="fa fa-pencil-square-o"></i> Survei SKM
    </button>
</div>

<!-- SKM Modal -->
<div class="modal fade" id="modalSkm" tabindex="-1" role="dialog" aria-labelledby="modalSkmLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; border: none; padding: 20px;">
                <h5 class="modal-title" id="modalSkmLabel" style="font-weight: 700; font-size: 1.5rem;">
                    <i class="fa fa-heartbeat"></i> Survei Kepuasan Masyarakat (SKM)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formSkm" action="<?= base_url('skm/submit'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="modal-body" style="padding: 30px; background-color: #f8fafc;">
                    <p class="text-muted" style="margin-bottom: 25px;">Mohon luangkan waktu Anda sejenak untuk menilai kualitas pelayanan kami. Penilaian Anda sangat berharga bagi peningkatan kualitas pelayanan kami.</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight: 600;">Nama Lengkap (Opsional)</label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight: 600;">Jenis Layanan yang Diterima</label>
                                <select name="service_type" class="form-control" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    <option value="SPKT">SPKT (Laporan/Pengaduan)</option>
                                    <option value="SKCK">SKCK (Surat Keterangan Catatan Kepolisian)</option>
                                    <option value="SIM">SIM (Surat Izin Mengemudi)</option>
                                    <option value="SIDIK JARI">Perumusan Sidik Jari</option>
                                    <option value="IZIN KERAMAIAN">Perizinan Keramaian</option>
                                    <option value="LAINNYA">Layanan Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 style="font-weight: 700; color: #1e3a8a; margin-bottom: 20px;">Indikator Penilaian</h5>
                    
                    <?php 
                    $indicators = [
                        'r1' => 'Bagaimana pemahaman Anda terhadap <b>Persyaratan</b> pelayanan di unit ini?',
                        'r2' => 'Bagaimana pendapat Anda tentang kemudahan <b>Prosedur</b> pelayanan?',
                        'r3' => 'Bagaimana pendapat Anda tentang <b>Waktu</b> penyelesaian pelayanan?',
                        'r4' => 'Bagaimana pendapat Anda tentang kesesuaian <b>Biaya/Tarif</b>?',
                        'r5' => 'Bagaimana pendapat Anda tentang kesesuaian <b>Produk Layanan</b> dengan yang dijanjikan?',
                        'r6' => 'Bagaimana pendapat Anda tentang <b>Kompetensi/Kemampuan</b> petugas?',
                        'r7' => 'Bagaimana pendapat Anda tentang <b>Perilaku/Kesopanan</b> petugas?',
                        'r8' => 'Bagaimana pendapat Anda tentang kualitas <b>Sarana & Prasarana</b>?',
                        'r9' => 'Bagaimana pendapat Anda tentang penanganan <b>Pengaduan</b>?'
                    ];
                    
                    foreach($indicators as $key => $label): ?>
                        <div class="indicator-row" style="margin-bottom: 15px; padding: 15px; background: white; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <label style="display: block; margin-bottom: 10px; font-weight: 500;"><?= $label; ?></label>
                            <div class="rating-options" style="display: flex; justify-content: space-between; gap: 10px;">
                                <label class="rating-label" style="flex: 1; text-align: center; cursor: pointer; padding: 8px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem;">
                                    <input type="radio" name="<?= $key; ?>" value="1" required style="display: none;">
                                    <span>Tidak Baik</span>
                                </label>
                                <label class="rating-label" style="flex: 1; text-align: center; cursor: pointer; padding: 8px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem;">
                                    <input type="radio" name="<?= $key; ?>" value="2" required style="display: none;">
                                    <span>Kurang Baik</span>
                                </label>
                                <label class="rating-label" style="flex: 1; text-align: center; cursor: pointer; padding: 8px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem;">
                                    <input type="radio" name="<?= $key; ?>" value="3" required style="display: none;">
                                    <span>Baik</span>
                                </label>
                                <label class="rating-label" style="flex: 1; text-align: center; cursor: pointer; padding: 8px; background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem;">
                                    <input type="radio" name="<?= $key; ?>" value="4" required style="display: none;">
                                    <span>Sangat Baik</span>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="form-group">
                        <label style="font-weight: 600;">Saran atau Masukan</label>
                        <textarea name="suggestion" class="form-control" rows="3" placeholder="Tuliskan saran Anda agar kami bisa melayani lebih baik lagi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px; border: none; background: #f8fafc;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 30px; border-radius: 8px; font-weight: 600; background: #1e3a8a; border: none;">Kirim Survei</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .rating-options label:hover {
        background-color: #e2e8f0;
    }
    .rating-options input:checked + span {
        font-weight: 700;
        color: #1e3a8a;
    }
    .rating-options label.active {
        background-color: #1e3a8a !important;
        color: white !important;
        border-color: #1e3a8a !important;
    }
    #skm-floating-button button:hover {
        transform: scale(1.05);
        background-color: #1e3a8a;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ratingLabels = document.querySelectorAll('.rating-label');
        ratingLabels.forEach(label => {
            label.addEventListener('click', function() {
                const radios = this.closest('.rating-options').querySelectorAll('input');
                const labels = this.closest('.rating-options').querySelectorAll('.rating-label');
                
                labels.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Ajax Submit
        const formSkm = document.getElementById('formSkm');
        formSkm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status ==='success') {
                    Swal.fire({
                        title: 'Terima Kasih!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Tutup'
                    }).then(() => {
                        $('#modalSkm').modal('hide');
                        formSkm.reset();
                        ratingLabels.forEach(l => l.classList.remove('active'));
                    });
                } else {
                    Swal.fire({
                        title: 'Oops!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            });
        });
    });
</script>
