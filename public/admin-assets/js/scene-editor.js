// Các biến toàn cục dùng chung cho Marzipano
let viewer;
let scene;
let targetViewer = null; // Dùng cho ảnh preview thu nhỏ của Scene đích
let targetView = null;   // Dùng để lấy góc nhìn hiện tại của Scene đích

/**
 * 1. KHỞI TẠO BAN ĐẦU (INIT)
 * Được gọi khi trang web vừa load xong
 */
function initSceneViewer() {
    const pano = document.getElementById("pano");
    viewer = new Marzipano.Viewer(pano);

    loadScene(sceneData);

    // Kích hoạt các sự kiện tương tác
    initDoubleClick();
    initModalEvents();
    initLocationFilter();
    initSave();
    initDelete();
    initSetInitialView();
}

/**
 * 2. TẢI CẢNH 360 & HOTSPOTS
 * Cấu hình giới hạn zoom và nạp ảnh vào khung Marzipano
 */
function loadScene(data) {
    const source = Marzipano.ImageUrlSource.fromString(data.image);
    const geometry = new Marzipano.EquirectGeometry([{ width: 4000 }]);

    // Giới hạn zoom out (FOV tối đa 120 độ) để không bị méo ảnh
    const limiter = Marzipano.RectilinearView.limit.traditional(4000, 120 * Math.PI / 180);

    const view = new Marzipano.RectilinearView({
        yaw: data.yaw ?? 0,
        pitch: data.pitch ?? 0,
        fov: data.fov ?? Math.PI / 2
    }, limiter);

    scene = viewer.createScene({ source, geometry, view });
    scene.switchTo();

    // Fix lỗi tràn viền của hotspot container
    setTimeout(() => {
        scene.hotspotContainer()._container.style.overflow = "hidden";
    }, 100);

    loadHotspots(); // Tải danh sách hotspot có sẵn
}

/**
 * 3. HÀM PHỤ: XÓA HOTSPOT KHỎI MARZIPANO
 * Dùng khi update hoặc xóa hotspot để không bị trùng lặp icon
 */
function removeHotspotById(id) {
    let existingWrapper = document.querySelector(`.hotspot-wrapper[data-id="${id}"]`);
    if (existingWrapper) {
        let hotspotsList = scene.hotspotContainer().listHotspots();
        let hsObj = hotspotsList.find(h => h.domElement() === existingWrapper);
        if (hsObj) {
            scene.hotspotContainer().destroyHotspot(hsObj);
        } else {
            existingWrapper.remove();
        }
    }
}

/**
 * 4. VẼ ICON HOTSPOT LÊN ẢNH 360
 * Gắn sự kiện click mở Modal Sửa cho từng icon
 */
function createHotspotElement(h) {
    let wrapper = document.createElement("div");
    wrapper.className = "hotspot-wrapper";
    wrapper.setAttribute('data-id', h.id);

    // Tạo tooltip (nhãn tên hiện khi rê chuột)
    let label = document.createElement("span");
    label.className = "hotspot-label";
    if (h.type === 'link') {
        label.innerText = (h.target_scene && h.target_scene.name) ? h.target_scene.name : "Chuyển cảnh";
    } else {
        label.innerText = (h.tourist_object && h.tourist_object.name) ? h.tourist_object.name : "Thông tin";
    }
    wrapper.appendChild(label);

    // Tạo icon hình ảnh (link chuyển cảnh hoặc info)
    let el = document.createElement("img");
    el.src = h.type === "link" ? "/images/icons/link.png" : "/images/icons/info.png";
    el.className = "hotspot-icon";
    el.style.transform = `rotate(${h.rotation || 0}deg)`; // Xoay mũi tên (nếu có)
    wrapper.appendChild(el);

    // Sự kiện Click vào Hotspot -> Mở form cập nhật
    wrapper.onclick = function() {
        document.getElementById('hotspot_id').value = h.id;
        document.getElementById('yaw').value = h.yaw;
        document.getElementById('pitch').value = h.pitch;
        document.getElementById('type').value = h.type;
        document.getElementById('target_scene_id').value = h.target_scene_id || "";
        document.getElementById('tourist_object_id').value = h.tourist_object_id || "";
        document.getElementById('rotation').value = h.rotation || 0;

        document.getElementById('target_yaw').value = (h.target_yaw !== null && h.target_yaw !== undefined) ? h.target_yaw : "";
        document.getElementById('target_pitch').value = (h.target_pitch !== null && h.target_pitch !== undefined) ? h.target_pitch : "";
        document.getElementById('target_fov').value = (h.target_fov !== null && h.target_fov !== undefined) ? h.target_fov : "";

        // Hiện nút Xóa vì đây là form cập nhật
        document.getElementById('deleteHotspot').style.display = "inline-block";

        // Kích hoạt thay đổi giao diện form
        document.getElementById('type').dispatchEvent(new Event('change'));
        if (h.type === 'link') {
            document.getElementById('icon-preview').style.transform = `rotate(${h.rotation}deg)`;
            document.getElementById('target_scene_id').dispatchEvent(new Event('change'));
        }

        new bootstrap.Modal(document.getElementById('hotspotModal')).show();
    };

    // Đưa hotspot vào Scene
    scene.hotspotContainer().createHotspot(wrapper, {
        yaw: parseFloat(h.yaw),
        pitch: parseFloat(h.pitch)
    });
}

function loadHotspots() {
    if (!window.hotspots) return;
    window.hotspots.forEach(h => createHotspotElement(h));
}

/**
 * 5. SỰ KIỆN DOUBLE CLICK TẠO HOTSPOT MỚI
 */
function initDoubleClick() {
    const pano = document.getElementById("pano");

    pano.addEventListener("dblclick", function(e) {
        // Lấy tọa độ click chuột trên ảnh 360
        const rect = pano.getBoundingClientRect();
        const coords = viewer.view().screenToCoordinates({
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        });

        // Reset toàn bộ form về trạng thái trống để thêm mới
        document.getElementById('hotspot_id').value = "";
        document.getElementById('yaw').value = coords.yaw;
        document.getElementById('pitch').value = coords.pitch;
        document.getElementById('rotation').value = 0;
        document.getElementById('target_scene_id').value = "";
        document.getElementById('tourist_object_id').value = "";

        document.getElementById('target_yaw').value = "";
        document.getElementById('target_pitch').value = "";
        document.getElementById('target_fov').value = "";

        // Ẩn nút xóa vì đang tạo mới
        document.getElementById('deleteHotspot').style.display = "none";

        // Kích hoạt thay đổi giao diện theo loại (Link/Info)
        document.getElementById('type').dispatchEvent(new Event('change'));

        // ĐÃ FIX: Bắt buộc kích hoạt sự kiện change của target_scene_id để xóa sạch khung ảnh mini cũ
        document.getElementById('target_scene_id').dispatchEvent(new Event('change'));

        document.getElementById('icon-preview').style.transform = `rotate(0deg)`;

        new bootstrap.Modal(document.getElementById('hotspotModal')).show();
    });
}

/**
 * 6. XỬ LÝ GIAO DIỆN MODAL VÀ ẢNH PREVIEW MINI
 */
function initModalEvents() {
    const typeSelect = document.getElementById('type');
    const linkGroup = document.getElementById('link-group');
    const infoGroup = document.getElementById('info-group');
    const rotationInput = document.getElementById('rotation');
    const iconPreview = document.getElementById('icon-preview');
    const targetSceneSelect = document.getElementById('target_scene_id');
    const btnSetTargetView = document.getElementById('btnSetTargetView');
    const modalElement = document.getElementById('hotspotModal');

    // Sửa lỗi kích thước khung Marzipano mini khi modal vừa hiện lên
    modalElement.addEventListener('shown.bs.modal', function () {
        if (targetViewer) {
            targetViewer.updateSize();
        }
    });

    // Chuyển đổi giao diện giữa "Link chuyển cảnh" và "Info thông tin"
    typeSelect.addEventListener('change', function() {
        if (this.value === 'link') {
            linkGroup.style.display = 'block';
            infoGroup.style.display = 'none';
            if (iconPreview) iconPreview.src = "/images/icons/link.png";
        } else {
            linkGroup.style.display = 'none';
            infoGroup.style.display = 'block';
            if (iconPreview) iconPreview.src = "/images/icons/info.png";
        }
    });

    // Xoay icon xem trước
    rotationInput.addEventListener('input', function() {
        if (iconPreview) iconPreview.style.transform = `rotate(${this.value}deg)`;
    });

    // Xử lý load ảnh preview mini khi chọn Scene đích
    targetSceneSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const imageUrl = selectedOption ? selectedOption.getAttribute('data-image') : null;
        const container = document.getElementById('target-pano');

        if (imageUrl) {
            // Có chọn Scene -> Khởi tạo viewer thu nhỏ
            if (targetViewer) targetViewer.destroy();

            targetViewer = new Marzipano.Viewer(container);
            const source = Marzipano.ImageUrlSource.fromString(imageUrl);
            const geometry = new Marzipano.EquirectGeometry([{ width: 2000 }]);
            const limiter = Marzipano.RectilinearView.limit.traditional(2000, 100 * Math.PI / 180);

            let tYaw = document.getElementById('target_yaw').value;
            let tPitch = document.getElementById('target_pitch').value;
            let tFov = document.getElementById('target_fov').value;

            targetView = new Marzipano.RectilinearView({
                yaw: tYaw !== "" ? parseFloat(tYaw) : 0,
                pitch: tPitch !== "" ? parseFloat(tPitch) : 0,
                fov: tFov !== "" ? parseFloat(tFov) : Math.PI / 2
            }, limiter);

            const miniScene = targetViewer.createScene({ source, geometry, view: targetView });
            miniScene.switchTo();
        } else {
            // ĐÃ FIX: Không chọn Scene (hoặc tạo mới) -> Xóa sạch viewer cũ và rác thẻ canvas
            if (targetViewer) {
                targetViewer.destroy();
                targetViewer = null;
            }
            if (container) {
                container.innerHTML = ""; // Quét sạch tàn dư HTML
            }
        }
    });

    // Chốt góc nhìn Scene đích vào các ô Input
    if (btnSetTargetView) {
        btnSetTargetView.addEventListener('click', function() {
            if (!targetView) return;
            document.getElementById('target_yaw').value = targetView.yaw();
            document.getElementById('target_pitch').value = targetView.pitch();
            document.getElementById('target_fov').value = targetView.fov();
            showToast("Đã chốt góc nhìn Scene đích thành công!", "success");
        });
    }
}

/**
 * 7. BỘ LỌC ĐỐI TƯỢNG (INFO HOTSPOT)
 */
function initLocationFilter() {
    const filterLocation = document.getElementById('filter_location_id');
    const objectSelect = document.getElementById('tourist_object_id');

    if (filterLocation && objectSelect) {
        const allOptions = Array.from(objectSelect.options); // Lưu danh sách gốc

        filterLocation.addEventListener('change', function () {
            const selectedLocation = this.value;
            objectSelect.innerHTML = ''; // Xóa sạch

            // Lọc và render lại
            allOptions.forEach(option => {
                if (option.value === "") {
                    objectSelect.appendChild(option);
                    return;
                }
                if (selectedLocation === 'all' || option.getAttribute('data-location') === selectedLocation) {
                    objectSelect.appendChild(option);
                }
            });

            objectSelect.value = ""; // Đặt về mặc định
        });
    }
}

/**
 * 8. LƯU HOTSPOT (THÊM/SỬA QUA AJAX)
 */
function initSave() {
    document.getElementById('saveHotspot').onclick = function() {
        const id = document.getElementById('hotspot_id').value;
        const typeVal = document.getElementById('type').value;
        const targetSceneVal = document.getElementById('target_scene_id').value;
        const touristObjVal = document.getElementById('tourist_object_id').value;
        const yawVal = document.getElementById('yaw').value;
        const pitchVal = document.getElementById('pitch').value;
        const rotationVal = document.getElementById('rotation').value;

        const tYaw = document.getElementById('target_yaw').value;
        const tPitch = document.getElementById('target_pitch').value;
        const tFov = document.getElementById('target_fov').value;

        // Validate cơ bản trước khi gửi
        if (typeVal === "link" && !targetSceneVal) { showToast("Vui lòng chọn cảnh đích", "warning"); return; }
        if (typeVal === "info" && !touristObjVal) { showToast("Vui lòng chọn thông tin đối tượng", "warning"); return; }

        const url = id ? `/admin/hotspots/${id}` : "/admin/hotspots";
        const btnSave = this;

        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang lưu...';

        // Gói dữ liệu gửi lên Laravel
        const payloadData = {
            _method: id ? 'PUT' : 'POST',
            scene_id: sceneData.id,
            type: typeVal,
            yaw: parseFloat(yawVal),
            pitch: parseFloat(pitchVal),
            rotation: rotationVal ? parseFloat(rotationVal) : 0,
            target_scene_id: targetSceneVal !== "" ? targetSceneVal : null,
            tourist_object_id: touristObjVal !== "" ? touristObjVal : null,
            target_yaw: tYaw !== "" ? parseFloat(tYaw) : null,
            target_pitch: tPitch !== "" ? parseFloat(tPitch) : null,
            target_fov: tFov !== "" ? parseFloat(tFov) : null
        };

        fetch(url, {
            method: 'POST', // Dùng POST kèm _method: PUT cho Laravel Update
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payloadData)
        })
        .then(async res => {
            const text = await res.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error("LỖI LARAVEL:", text);
                throw new Error("Lỗi Server: Vui lòng F12 -> Console để xem chi tiết!");
            }

            if (!res.ok) {
                let errorMsg = "Có lỗi xảy ra từ máy chủ!";
                if (data.errors) errorMsg = Object.values(data.errors)[0][0]; // Lấy lỗi validate đầu tiên
                else if (data.message) errorMsg = data.message;
                throw new Error(errorMsg);
            }
            return data;
        })
        .then(data => {
            if(data.success) {
                showToast(id ? "Cập nhật thành công!" : "Thêm mới thành công!", "success");
                bootstrap.Modal.getInstance(document.getElementById('hotspotModal')).hide();

                // Lấy tên cảnh/đối tượng từ Dropdown để gán vào Tooltip tức thì
                let tsSelect = document.getElementById('target_scene_id');
                let toSelect = document.getElementById('tourist_object_id');
                let tsName = targetSceneVal ? tsSelect.options[tsSelect.selectedIndex].text.trim() : null;
                let toName = touristObjVal ? toSelect.options[toSelect.selectedIndex].text.trim() : null;

                let hData = {
                    id: id ? id : data.hotspot.id,
                    type: payloadData.type,
                    yaw: payloadData.yaw,
                    pitch: payloadData.pitch,
                    rotation: payloadData.rotation,
                    target_scene_id: payloadData.target_scene_id,
                    tourist_object_id: payloadData.tourist_object_id,
                    target_yaw: payloadData.target_yaw,
                    target_pitch: payloadData.target_pitch,
                    target_fov: payloadData.target_fov,
                    target_scene: tsName ? { name: tsName } : null,
                    tourist_object: toName ? { name: toName } : null
                };

                // Xóa icon cũ (nếu là update) và vẽ lại icon mới ngay lập tức
                if (id) removeHotspotById(id);
                createHotspotElement(hData);
            } else {
                showToast("Lỗi xử lý logic từ máy chủ!", "error");
            }
        })
        .catch(err => {
            showToast(err.message, "error");
        })
        .finally(() => {
            btnSave.disabled = false;
            btnSave.innerHTML = "Lưu Hotspot";
        });
    };
}

/**
 * 9. XÓA HOTSPOT QUA AJAX
 */
function initDelete() {
    const deleteBtn = document.getElementById('deleteHotspot');
    if (!deleteBtn) return;

    deleteBtn.onclick = function() {
        const id = document.getElementById('hotspot_id').value;
        if (!id) return;
        if (!confirm("Bạn có chắc muốn xóa Hotspot này?")) return;

        this.disabled = true;
        this.innerHTML = "Đang xóa...";

        fetch(`/admin/hotspots/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showToast("Đã xóa Hotspot!", "success");
                bootstrap.Modal.getInstance(document.getElementById('hotspotModal')).hide();
                removeHotspotById(id); // Xóa icon khỏi màn hình ngay lập tức
            } else {
                showToast("Không thể xóa!", "error");
            }
        })
        .catch(err => showToast("Lỗi mạng!", "error"))
        .finally(() => {
            this.disabled = false;
            this.innerHTML = "Xóa";
        });
    };
}

/**
 * 10. CHỐT GÓC NHÌN MẶC ĐỊNH CHO SCENE HIỆN TẠI
 */
function initSetInitialView() {
    document.getElementById('btnSetView').onclick = function() {
        const v = viewer.view();
        const btnSetView = this;
        const originalText = btnSetView.innerHTML;

        btnSetView.disabled = true;
        btnSetView.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang lưu...';

        fetch(`/admin/scenes/${sceneData.id}/set-initial-view`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ yaw: v.yaw(), pitch: v.pitch(), fov: v.fov() })
        })
        .then(res => res.json())
        .then(data => showToast("Đã lưu góc nhìn mặc định thành công", "success"))
        .catch(err => showToast("Không thể lưu góc nhìn", "error"))
        .finally(() => {
            btnSetView.disabled = false;
            btnSetView.innerHTML = originalText;
        });
    };
}

// Bắt đầu chạy script khi web load xong
document.addEventListener("DOMContentLoaded", initSceneViewer);
