let viewer;
let scene;
let targetViewer = null;
let targetView = null;

/**
 * INIT
 */
function initSceneViewer() {
    const pano = document.getElementById("pano");
    viewer = new Marzipano.Viewer(pano);

    loadScene(sceneData);

    initDoubleClick();
    initModalEvents();
    initLocationFilter();
    initSave();
    initDelete();
    initSetInitialView();
}

/**
 * LOAD SCENE (Giới hạn Zoom)
 */
function loadScene(data) {
    const source = Marzipano.ImageUrlSource.fromString(data.image);
    const geometry = new Marzipano.EquirectGeometry([{ width: 4000 }]);

    const limiter = Marzipano.RectilinearView.limit.traditional(4000, 120 * Math.PI / 180);

    const view = new Marzipano.RectilinearView({
        yaw: data.yaw ?? 0,
        pitch: data.pitch ?? 0,
        fov: data.fov ?? Math.PI / 2
    }, limiter);

    scene = viewer.createScene({ source, geometry, view });
    scene.switchTo();

    setTimeout(() => {
        scene.hotspotContainer()._container.style.overflow = "hidden";
    }, 100);

    loadHotspots();
}

/**
 * HÀM PHỤ: GỠ HOTSPOT KHỎI MARZIPANO
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
 * TẠO ICON HOTSPOT & SỰ KIỆN SỬA
 */
function createHotspotElement(h) {
    let wrapper = document.createElement("div");
    wrapper.className = "hotspot-wrapper";
    wrapper.setAttribute('data-id', h.id);

    // 1. TẠO TOOLTIP (NHÃN TÊN)
    let label = document.createElement("span");
    label.className = "hotspot-label";
    if (h.type === 'link') {
        label.innerText = (h.target_scene && h.target_scene.name) ? h.target_scene.name : "Chuyển cảnh";
    } else {
        label.innerText = (h.tourist_object && h.tourist_object.name) ? h.tourist_object.name : "Thông tin";
    }
    wrapper.appendChild(label);

    // 2. TẠO ICON
    let el = document.createElement("img");
    el.src = h.type === "link" ? "/images/icons/link.png" : "/images/icons/info.png";
    el.className = "hotspot-icon";
    el.style.transform = `rotate(${h.rotation || 0}deg)`;

    wrapper.appendChild(el);

    // 3. Sự kiện Click mở Modal Sửa
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

        document.getElementById('deleteHotspot').style.display = "inline-block";
        document.getElementById('type').dispatchEvent(new Event('change'));
        if (h.type === 'link') {
            document.getElementById('icon-preview').style.transform = `rotate(${h.rotation}deg)`;
            document.getElementById('target_scene_id').dispatchEvent(new Event('change'));
        }

        new bootstrap.Modal(document.getElementById('hotspotModal')).show();
    };

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
 * DOUBLE CLICK (THÊM MỚI)
 */
function initDoubleClick() {
    const pano = document.getElementById("pano");

    pano.addEventListener("dblclick", function(e) {
        const rect = pano.getBoundingClientRect();
        const coords = viewer.view().screenToCoordinates({
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        });

        document.getElementById('hotspot_id').value = "";
        document.getElementById('yaw').value = coords.yaw;
        document.getElementById('pitch').value = coords.pitch;
        document.getElementById('rotation').value = 0;
        document.getElementById('target_scene_id').value = "";
        document.getElementById('tourist_object_id').value = "";

        document.getElementById('target_yaw').value = "";
        document.getElementById('target_pitch').value = "";
        document.getElementById('target_fov').value = "";

        document.getElementById('deleteHotspot').style.display = "none";
        document.getElementById('type').dispatchEvent(new Event('change'));
        document.getElementById('icon-preview').style.transform = `rotate(0deg)`;

        new bootstrap.Modal(document.getElementById('hotspotModal')).show();
    });
}

/**
 * GIAO DIỆN MODAL
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

    modalElement.addEventListener('shown.bs.modal', function () {
        if (targetViewer) {
            targetViewer.updateSize();
        }
    });

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

    rotationInput.addEventListener('input', function() {
        if (iconPreview) iconPreview.style.transform = `rotate(${this.value}deg)`;
    });

    targetSceneSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const imageUrl = selectedOption.getAttribute('data-image');

        if (imageUrl) {
            const container = document.getElementById('target-pano');
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
            if (targetViewer) targetViewer.destroy();
        }
    });

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
 * XỬ LÝ LỌC ĐỐI TƯỢNG
 */
function initLocationFilter() {
    const filterLocation = document.getElementById('filter_location_id');
    const objectSelect = document.getElementById('tourist_object_id');

    if (filterLocation && objectSelect) {
        // Lưu toàn bộ thẻ option ban đầu
        const allOptions = Array.from(objectSelect.options);

        filterLocation.addEventListener('change', function () {
            const selectedLocation = this.value;

            // Xóa sạch danh sách đối tượng
            objectSelect.innerHTML = '';

            // Lọc và thêm lại
            allOptions.forEach(option => {
                if (option.value === "") {
                    objectSelect.appendChild(option);
                    return;
                }

                if (selectedLocation === 'all' || option.getAttribute('data-location') === selectedLocation) {
                    objectSelect.appendChild(option);
                }
            });

            // Nếu người dùng đang tương tác, reset giá trị về rỗng
            objectSelect.value = "";
        });
    }
}

/**
 * LƯU (AJAX 100% KHÔNG RELOAD)
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

        if (typeVal === "link" && !targetSceneVal) { showToast("Vui lòng chọn scene đích", "warning"); return; }
        if (typeVal === "info" && !touristObjVal) { showToast("Vui lòng chọn đối tượng", "warning"); return; }

        const url = id ? `/admin/hotspots/${id}` : "/admin/hotspots";

        const btnSave = this;
        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang lưu...';

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
            method: 'POST',
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
                console.error("LỖI LARAVEL TRẢ VỀ:", text);
                throw new Error("Lỗi Server: Hãy bấm F12 -> tab Console để xem nguyên nhân gốc!");
            }

            if (!res.ok) {
                let errorMsg = "Có lỗi xảy ra từ máy chủ!";
                if (data.errors) {
                    errorMsg = Object.values(data.errors)[0][0];
                } else if (data.message) {
                    errorMsg = data.message;
                }
                throw new Error(errorMsg);
            }
            return data;
        })
        .then(data => {
            if(data.success) {
                showToast(id ? "Cập nhật Hotspot thành công!" : "Thêm mới Hotspot thành công!", "success");
                bootstrap.Modal.getInstance(document.getElementById('hotspotModal')).hide();

                // LẤY TÊN TỪ THẺ SELECT DROPDOWN NGAY TRÊN MODAL
                let tsSelect = document.getElementById('target_scene_id');
                let toSelect = document.getElementById('tourist_object_id');
                let tsName = targetSceneVal ? tsSelect.options[tsSelect.selectedIndex].text.trim() : null;
                let toName = touristObjVal ? toSelect.options[toSelect.selectedIndex].text.trim() : null;

                // TẠO OBJECT DỮ LIỆU ĐỂ HÀM CREATE VẼ RA
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
                    // Ép dữ liệu ảo để hàm createHotspotElement đọc được ngay tên của Tooltip
                    target_scene: tsName ? { name: tsName } : null,
                    tourist_object: toName ? { name: toName } : null
                };

                if (id) {
                    removeHotspotById(id);
                }

                // VẼ ICON LÊN CÙNG TOOLTIP MỚI
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
 * XÓA
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
            headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showToast("Đã xóa Hotspot!", "success");
                bootstrap.Modal.getInstance(document.getElementById('hotspotModal')).hide();
                removeHotspotById(id);
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
 * SET VIEW SCENE BAN ĐẦU
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
        .then(data => showToast("Đã lưu góc nhìn mặc định", "success"))
        .catch(err => showToast("Không thể lưu góc nhìn", "error"))
        .finally(() => {
            btnSetView.disabled = false;
            btnSetView.innerHTML = originalText;
        });
    };
}

document.addEventListener("DOMContentLoaded", initSceneViewer);
