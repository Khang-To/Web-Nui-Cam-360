document.addEventListener('DOMContentLoaded', function () {
    const uploadModal = document.getElementById('uploadModal');
    if (!uploadModal) return;

    const uploadUrl = uploadModal.getAttribute('data-upload-url');
    const csrfToken = uploadModal.getAttribute('data-csrf');
    const indexUrl = uploadModal.getAttribute('data-index-url');

    // Đã xóa step1 và btnShowUpload
    const step2 = document.getElementById('step-2-upload');
    const step3 = document.getElementById('step-3-progress');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const fileInput = document.getElementById('fileInput');
    const dropzone = document.getElementById('dropzone');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const loadingSpinner = document.getElementById('loadingSpinner');

    // Các biến dùng cho Hàng đợi (Queue)
    let fileQueue = [];
    let currentIndex = 0;
    let totalFiles = 0;
    let successCount = 0;
    let errorLog = [];

    // Khi đóng modal -> Reset về thẳng bước 2 (Kéo thả file)
    uploadModal.addEventListener('hidden.bs.modal', function () {
        step2.style.display = 'block';
        step3.style.display = 'none';
        fileInput.value = '';
        progressBar.style.width = '0%';
        progressBar.innerText = '0%';
        progressBar.classList.remove('bg-success');
        progressBar.classList.add('bg-info');
        loadingSpinner.style.display = 'none';
        btnCloseModal.style.display = 'block';
    });

    // Xử lý kéo thả
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.style.backgroundColor = "#e9ecef"; });
    dropzone.addEventListener('dragleave', () => { dropzone.style.backgroundColor = "#fff"; });
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.style.backgroundColor = "#fff";
        if (e.dataTransfer.files.length) {
            prepareQueue(e.dataTransfer.files);
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            prepareQueue(fileInput.files);
        }
    });

    // 1. Chuẩn bị Hàng đợi
    function prepareQueue(files) {
        fileQueue = Array.from(files);
        totalFiles = fileQueue.length;
        currentIndex = 0;
        successCount = 0;
        errorLog = [];

        if (totalFiles === 0) return;

        // Đổi sang giao diện upload
        step2.style.display = 'none';
        step3.style.display = 'block';
        btnCloseModal.style.display = 'none';
        loadingSpinner.style.display = 'inline-block';

        // Bắt đầu upload file đầu tiên
        uploadNextFile();
    }

    // 2. Hàm đệ quy: Xử lý upload TỪNG FILE MỘT
    function uploadNextFile() {
        if (currentIndex >= totalFiles) {
            return finishUpload();
        }

        const file = fileQueue[currentIndex];

        const percent = Math.round((currentIndex / totalFiles) * 100);
        progressBar.style.width = percent + '%';
        progressBar.innerText = percent + '%';
        progressText.innerHTML = `Đang xử lý ảnh <b>${currentIndex + 1}/${totalFiles}</b> <br> <span class="small text-muted">(${file.name})</span>`;

        const formData = new FormData();
        formData.append('images[]', file);
        formData.append('_token', csrfToken);

        fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json().then(data => ({status: response.status, body: data})))
        .then(result => {
            if (result.status === 200 || result.status === 201) {
                if (result.body.success) {
                    successCount++;
                }
                if (result.body.errors && result.body.errors.length > 0) {
                    errorLog.push(...result.body.errors);
                }
            } else if (result.status === 422) {
                let errMsg = result.body.message || "Định dạng không hợp lệ.";
                errorLog.push(`Lỗi file '${file.name}': ${errMsg}`);
            } else {
                errorLog.push(`Máy chủ từ chối file '${file.name}'.`);
            }
        })
        .catch(err => {
            errorLog.push(`Mất mạng khi đang tải file '${file.name}'.`);
        })
        .finally(() => {
            currentIndex++;
            uploadNextFile();
        });
    }

    // 3. Xử lý khi Hàng đợi chạy xong toàn bộ
    function finishUpload() {
        progressBar.style.width = '100%';
        progressBar.innerText = '100%';
        progressBar.classList.remove('bg-info');
        progressBar.classList.add('bg-success');
        loadingSpinner.style.display = 'none';

        progressText.innerHTML = "<b>Đã hoàn tất tải lên!</b> Đang chuẩn bị tải lại trang...";

        // Đợi một chút để thanh tiến trình hiện 100% rồi mới bắn Toast
        setTimeout(() => {
            if (errorLog.length > 0) {
                // Nếu hàm showToast của bạn hỗ trợ thẻ HTML, dùng <br> để xuống dòng cho đẹp
                let errorHtml = `Tải lên thành công ${successCount}/${totalFiles} ảnh.<br>Lỗi:<br>- ` + errorLog.join('<br>- ');
                showToast(errorHtml, "error");
            } else {
                showToast(`Tuyệt vời! Đã tải lên và nén thành công toàn bộ ${successCount} ảnh 360°.`, "success");
            }

            // Đợi Toast hiện 2 giây rồi mới chuyển trang (reload)
            setTimeout(() => {
                window.location.href = indexUrl;
            }, 2000);
        }, 500);
    }
});
