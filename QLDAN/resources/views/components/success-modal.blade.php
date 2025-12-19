{{-- Success Modal Component --}}
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-body text-center p-5">
                {{-- Success Icon --}}
                <div class="mb-4">
                    <div class="success-checkmark mx-auto" style="width: 80px; height: 80px;">
                        <div class="check-icon" style="width: 80px; height: 80px; position: relative;">
                            <span class="icon-line line-tip" style="position: absolute; width: 25px; height: 5px; background-color: #4CAF50; display: block; border-radius: 2px; left: 14px; top: 46px; transform: rotate(45deg);"></span>
                            <span class="icon-line line-long" style="position: absolute; width: 47px; height: 5px; background-color: #4CAF50; display: block; border-radius: 2px; right: 8px; top: 38px; transform: rotate(-45deg);"></span>
                            <div class="icon-circle" style="width: 80px; height: 80px; border-radius: 50%; border: 4px solid #4CAF50; background-color: #E8F5E9; position: absolute; top: 0; left: 0;"></div>
                        </div>
                    </div>
                </div>

                {{-- Success Message --}}
                <h4 class="fw-bold mb-3" id="successTitle">Thêm thành công!</h4>
                <p class="text-muted mb-4" id="successMessage">Đã thêm cán bộ/sinh viên thành công vào hệ thống.</p>

                {{-- Action Buttons --}}
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-lg" onclick="window.location.reload()" style="border-radius: 8px; padding: 12px;">
                        Quay lại danh sách
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="addMoreBtn" style="border-radius: 8px; padding: 10px;">
                        + Thêm mới
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .success-checkmark .check-icon .icon-line {
        animation: checkmark 0.8s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    
    .success-checkmark .check-icon .icon-circle {
        animation: circle 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    
    @keyframes checkmark {
        0% {
            width: 0;
        }
        100% {
            width: 47px;
        }
    }
    
    @keyframes circle {
        0% {
            transform: scale(0);
        }
        100% {
            transform: scale(1);
        }
    }
</style>

<script>
    // Function to show success modal
    function showSuccessModal(title, message, hideAddMore = false) {
        document.getElementById('successTitle').textContent = title;
        document.getElementById('successMessage').textContent = message;
        
        // Hide "Thêm mới" button if needed (for update/delete actions)
        const addMoreBtn = document.getElementById('addMoreBtn');
        if (hideAddMore) {
            addMoreBtn.style.display = 'none';
        } else {
            addMoreBtn.style.display = 'block';
        }
        
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
        
        // Auto close after 2 seconds and reload
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    }
</script>
