<div id="cmdPalette" class="cmd-palette-backdrop">
    <div class="glass w-100 mx-3 rounded-4 shadow-lg overflow-hidden animate__animated animate__fadeInDown animate__faster"
        style="max-width: 600px; border: 1px solid var(--color-border); margin-top: 10vh;">
        <!-- Header / Search -->
        <div class="p-3 border-bottom d-flex align-items-center gap-3">
            <i class="bi bi-search text-secondary fs-5"></i>
            <input type="text" id="cmdSearchInput" class="form-control border-0 shadow-none bg-transparent px-0 fs-5"
                placeholder="Search commands, pages, or actions..." autocomplete="off">
            <kbd class="bg-light border rounded px-2 text-muted" style="cursor: pointer;"
                onclick="closeCmdPalette()">ESC</kbd>
        </div>

        <!-- Results -->
        <div class="p-2 overflow-auto" style="max-height: 400px;" id="cmdResults">

            <div class="text-uppercase text-secondary fw-semibold mb-2 px-3 pt-2" style="font-size: 0.7rem;">Quick
                Navigation</div>

            <a href="{{ route('admin.dashboard') }}"
                class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none text-body hover-lift cmd-item active-cmd">
                <div class="bg-light rounded p-2 text-secondary"><i class="bi bi-grid-1x2-fill"></i></div>
                <div class="flex-grow-1">
                    <div class="fw-medium">Dashboard</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">Go to main dashboard</div>
                </div>
                <i class="bi bi-arrow-return-left text-muted"></i>
            </a>

            <a href="{{ route('admin.erp-requests.index') }}"
                class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none text-body hover-lift cmd-item mt-1">
                <div class="bg-light rounded p-2 text-secondary"><i class="bi bi-hdd-network"></i></div>
                <div class="flex-grow-1">
                    <div class="fw-medium">ERP Requests</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">Manage ERP setup requests</div>
                </div>
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none text-body hover-lift cmd-item mt-1">
                <div class="bg-light rounded p-2 text-secondary"><i class="bi bi-box-seam"></i></div>
                <div class="flex-grow-1">
                    <div class="fw-medium">Products</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">Manage products and pricing plans</div>
                </div>
            </a>

            <div class="text-uppercase text-secondary fw-semibold mb-2 px-3 pt-3 mt-2 border-top"
                style="font-size: 0.7rem;">Actions</div>

            <a href="#"
                class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none text-body hover-lift cmd-item">
                <div class="bg-primary-subtle text-primary rounded p-2"><i class="bi bi-plus-lg"></i></div>
                <div class="flex-grow-1">
                    <div class="fw-medium text-primary">Create New Product</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">Add a new product offering</div>
                </div>
            </a>
        </div>

        <!-- Footer -->
        <div class="p-2 border-top bg-light d-flex justify-content-between align-items-center text-secondary"
            style="font-size: 0.75rem;">
            <div class="d-flex gap-3">
                <span><kbd class="bg-white border rounded px-1">↑</kbd> <kbd
                        class="bg-white border rounded px-1">↓</kbd> to navigate</span>
                <span><kbd class="bg-white border rounded px-1">↵</kbd> to select</span>
            </div>
            <span><kbd class="bg-white border rounded px-1">esc</kbd> to close</span>
        </div>
    </div>
</div>

<style>
    .cmd-item.active-cmd {
        background-color: rgba(var(--color-primary-rgb), 0.05);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cmdInput = document.getElementById('cmdSearchInput');
        const cmdResults = document.getElementById('cmdResults');
        const cmdItems = cmdResults.querySelectorAll('.cmd-item');
        let activeIndex = 0;

        const updateActiveItem = () => {
            cmdItems.forEach((item, index) => {
                if (index === activeIndex) {
                    item.classList.add('active-cmd');
                    item.scrollIntoView({
                        block: 'nearest'
                    });
                } else {
                    item.classList.remove('active-cmd');
                }
            });
        };

        cmdInput.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = (activeIndex + 1) % cmdItems.length;
                updateActiveItem();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = (activeIndex - 1 + cmdItems.length) % cmdItems.length;
                updateActiveItem();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const activeEl = cmdItems[activeIndex];
                if (activeEl) {
                    window.location.href = activeEl.getAttribute('href');
                }
            }
        });

        // Basic frontend filtering for command palette
        cmdInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            let visibleIndex = 0;
            let firstVisibleFound = false;

            cmdItems.forEach((item) => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = '';
                    if (!firstVisibleFound) {
                        activeIndex = Array.from(cmdItems).indexOf(item);
                        firstVisibleFound = true;
                    }
                } else {
                    item.style.display = 'none';
                }
            });
            updateActiveItem();
        });

        // Prevent click inside palette from closing it
        document.querySelector('#cmdPalette > div').addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Click outside closes
        document.getElementById('cmdPalette').addEventListener('click', closeCmdPalette);
    });
</script>
