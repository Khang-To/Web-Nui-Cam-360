let viewer = null;
let currentScene = null;
let currentSceneId = null;
let currentRequest = 0;

document.addEventListener("DOMContentLoaded", function () {
    // 1. Modal Welcome
    const welcomeModalEl = document.getElementById("welcomeModal");
    if (welcomeModalEl) {
        // Hiển thị modal
        const modalInstance = new bootstrap.Modal(welcomeModalEl);
        modalInstance.show();

        // Khi bấm nút "Bắt đầu khám phá" thì khởi tạo Tour
        const startTourBtn = document.getElementById("startTour");
        if (startTourBtn) {
            startTourBtn.addEventListener("click", () => {
                initViewer(); // Khởi tạo Marzipano
            }, { once: true }); // Sự kiện chỉ chạy 1 lần
        }
    } else {
        // Dự phòng nếu không tìm thấy modal trong HTML thì vẫn chạy tour
        initViewer();
    }

    // 2. Logic Menu
    const menu = document.getElementById("menu");
    const toggleMenuBtn = document.getElementById("toggleMenuBtn");
    const toggleMenuIcon = toggleMenuBtn ? toggleMenuBtn.querySelector("img") : null;

    function updateMenuIcon() {
        if (!toggleMenuIcon || !menu) return;
        if (menu.classList.contains("closed")) {
            toggleMenuIcon.src = "/images/icons/expand.png";
        } else {
            toggleMenuIcon.src = "/images/icons/collapse.png";
        }
    }

    updateMenuIcon();

    if (toggleMenuBtn) {
        toggleMenuBtn.onclick = () => {
            menu.classList.toggle("closed");
            updateMenuIcon();
        };
    }

    // 3. Click Menu Item
    document.querySelectorAll('.menu-item').forEach(el => {
        el.addEventListener('click', (e) => {
            document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
            e.currentTarget.classList.add('active');

            loadScene(el.dataset.id);

            // Tự đóng menu trên Mobile
            if (window.innerWidth <= 768 && menu) {
                menu.classList.add('closed');
                updateMenuIcon();
            }
        });
    });

    // 4. Fullscreen Logic
    const fullscreenBtn = document.getElementById("btnFullscreen");
    if (fullscreenBtn) {
        const fsIcon = fullscreenBtn.querySelector("img");

        function updateFullscreenIcon() {
            if (!fsIcon) return;
            if (document.fullscreenElement || document.webkitFullscreenElement) {
                fsIcon.src = "/images/icons/windowed.png";
            } else {
                fsIcon.src = "/images/icons/fullscreen.png";
            }
        }

        fullscreenBtn.addEventListener("click", function () {
            if (!document.fullscreenElement && !document.webkitFullscreenElement) {
                document.documentElement.requestFullscreen?.() || document.documentElement.webkitRequestFullscreen?.();
            } else {
                document.exitFullscreen?.() || document.webkitExitFullscreen?.();
            }
        });

        document.addEventListener("fullscreenchange", updateFullscreenIcon);
        document.addEventListener("webkitfullscreenchange", updateFullscreenIcon);
        updateFullscreenIcon();
    }
});

function initViewer() {
    if (!window.defaultSceneId) return;

    const panoElement = document.getElementById("pano");
    if (!panoElement || viewer) return;

    viewer = new Marzipano.Viewer(panoElement, {
        controls: { mouseViewMode: 'drag' }
    });

    loadScene(window.defaultSceneId);
}

function loadScene(sceneId, customView = null) {
    if (!sceneId || !viewer) return;

    // Tránh tải lại ảnh hiện tại, chỉ quay view
    if (sceneId == currentSceneId) {
        if (currentScene && customView) {
            currentScene.view().setParameters({
                yaw: customView.yaw ?? currentScene.view().yaw(),
                pitch: customView.pitch ?? currentScene.view().pitch(),
                fov: customView.fov ?? currentScene.view().fov()
            });
        }
        return;
    }

    const requestId = ++currentRequest;
    const url = window.sceneUrl.replace(':id', sceneId);

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (requestId !== currentRequest) return;

            currentSceneId = sceneId;
            const sceneNameEl = document.getElementById("sceneName");
            if (sceneNameEl) sceneNameEl.innerText = data.name || "Cảnh quan";

            const img = new Image();

            img.onload = function () {
                const panoWidth = 4000;
                const geometry = new Marzipano.EquirectGeometry([{ width: panoWidth }]);
                const source = Marzipano.ImageUrlSource.fromString(data.image);

                const view = new Marzipano.RectilinearView({
                    yaw: customView?.yaw ?? data.initial_yaw ?? 0,
                    pitch: customView?.pitch ?? data.initial_pitch ?? 0,
                    fov: customView?.fov ?? Math.PI / 2.2
                });

                const limiter = Marzipano.RectilinearView.limit.traditional(panoWidth, 110 * Math.PI / 180);
                view.setLimiter(limiter);

                const newScene = viewer.createScene({
                    source: source,
                    geometry: geometry,
                    view: view
                });

                // Chuyển cảnh mượt mà
                newScene.switchTo({ transitionDuration: 500 });

                const oldScene = currentScene;
                currentScene = newScene;

                if (oldScene) {
                    setTimeout(() => {
                        try {
                            const container = oldScene.hotspotContainer();
                            if (container) {
                                const hotspots = container.listHotspots();
                                hotspots.forEach(hs => {
                                    try { container.destroyHotspot(hs); } catch(e) {}
                                });
                            }
                            oldScene.destroy();
                        } catch(e) {
                            console.warn("Lỗi khi dọn dẹp scene cũ:", e);
                        }
                    }, 600);
                }

                setTimeout(() => window.dispatchEvent(new Event('resize')), 150);

                if (data.hotspots && Array.isArray(data.hotspots)) {
                    data.hotspots.forEach(addHotspot);
                }
            };

            img.onerror = () => {
                console.error("Không tải được ảnh 360:", data.image);
            };

            img.src = data.image;
        })
        .catch(err => console.error("Lỗi load scene:", err));
}

function addHotspot(h) {
    if (!currentScene) return;

    let wrapper = document.createElement("div");
    wrapper.className = "hotspot-wrapper";

    let icon = document.createElement("img");
    icon.className = "hotspot-icon";

    if (h.rotation !== undefined && h.rotation !== null) {
        icon.style.transform = `rotate(${h.rotation}deg)`;
    }

    let tooltip = document.createElement("div");
    tooltip.className = "hotspot-tooltip";

    if (h.type === "link") {
        icon.src = "/images/icons/link.png";
        tooltip.innerText = h.target_scene_name || "Đường đang được bảo trì";

        wrapper.onclick = () => {
            if (h.target_scene_id) {
                loadScene(h.target_scene_id, {
                    yaw: h.target_yaw || 0,
                    pitch: h.target_pitch || 0,
                    fov: h.target_fov || Math.PI / 2.2
                });
            }
        };
    } else {
        icon.src = "/images/icons/info.png";
        tooltip.innerText = h.tourist_object ? h.tourist_object.name : "Điểm tham quan đang bảo trì";
        icon.classList.add("info-icon-bg");

        wrapper.onclick = () => {
            if (!h.tourist_object) return;

            const infoTitle = document.getElementById("infoTitle");
            if (infoTitle) infoTitle.innerText = h.tourist_object.name;

            const infoImg = document.getElementById("infoImage");
            if (infoImg) {
                if (h.tourist_object.image) {
                    infoImg.src = h.tourist_object.image;
                    infoImg.style.display = 'block';
                    infoImg.style.border = 'none';
                } else {
                    infoImg.style.display = 'none';
                }
            }

            const infoContent = document.getElementById("infoContent");
            if (infoContent) infoContent.innerHTML = h.tourist_object.description || '';

            const infoModalEl = document.getElementById("infoModal");
            if (infoModalEl) new bootstrap.Modal(infoModalEl).show();
        };
    }

    wrapper.appendChild(icon);
    wrapper.appendChild(tooltip);

    currentScene.hotspotContainer().createHotspot(wrapper, {
        yaw: parseFloat(h.yaw) || 0,
        pitch: parseFloat(h.pitch) || 0
    });
}
