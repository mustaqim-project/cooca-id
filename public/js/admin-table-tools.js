/**
 * admin-table-tools.js
 * Auto-wires client-side search, filtering, and CSV export for admin pages.
 *
 * - Search: <input placeholder="Search ..."> inside a .card that contains a table
 * - Filter: <select data-filter-key="type"> inside a .card-header (matches row data-<key> attribute)
 * - Export (table): <button title="Export ..."> inside a .card-header with a table
 * - Export (standalone): <button> with <i class="bi bi-download"> not inside a table card
 *   exports the page's stat cards (label + value) as CSV.
 */
(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        initTableTools();
        initStandaloneExports();
    });

    function initTableTools() {
        document.querySelectorAll(".card").forEach(function (card) {
            const table = card.querySelector("table");
            if (!table) return;
            const tbody = table.querySelector("tbody");
            if (!tbody) return;

            const searchInputs = card.querySelectorAll(
                'input[placeholder^="Search"]',
            );
            const filterSelects = card.querySelectorAll(
                "select[data-filter-key]",
            );
            const exportBtn = card.querySelector('button[title^="Export"]');
            const footer = card.querySelector(".card-footer");

            if (searchInputs.length === 0 && filterSelects.length === 0) return;

            function applyFilter() {
                const term = searchInputs.length
                    ? searchInputs[0].value.toLowerCase().trim()
                    : "";

                let visible = 0;
                const rows = Array.from(tbody.querySelectorAll("tr"));

                rows.forEach(function (row) {
                    if (row.classList.contains("admin-no-results")) return;

                    // Skip the server-rendered empty-state row (single full-width cell)
                    if (
                        row.querySelectorAll("td").length === 1 &&
                        row.querySelector("td[colspan]")
                    ) {
                        return;
                    }

                    const text = row.textContent.toLowerCase();
                    let show = true;

                    if (term && text.indexOf(term) === -1) show = false;

                    filterSelects.forEach(function (sel) {
                        if (!sel.value) return;
                        const key = sel.getAttribute("data-filter-key");
                        const attr = row.getAttribute("data-" + key);
                        if (
                            attr === null ||
                            attr
                                .toLowerCase()
                                .indexOf(sel.value.toLowerCase()) === -1
                        ) {
                            show = false;
                        }
                    });

                    row.style.display = show ? "" : "none";
                    if (show) visible++;
                });

                toggleNoResults(tbody, table, visible);
                if (footer)
                    footer.style.display =
                        term || hasActiveFilter(filterSelects) ? "none" : "";
            }

            searchInputs.forEach(function (inp) {
                inp.addEventListener("input", applyFilter);
            });
            filterSelects.forEach(function (sel) {
                sel.addEventListener("change", applyFilter);
            });

            if (exportBtn) {
                exportBtn.addEventListener("click", function () {
                    exportTableToCsv(table, exportBtn);
                });
            }
        });
    }

    function initStandaloneExports() {
        // Buttons with a download icon that are NOT inside a table card.
        document.querySelectorAll("button").forEach(function (btn) {
            if (!btn.querySelector(".bi-download")) return;
            // Skip if already handled by table export
            const card = btn.closest(".card");
            if (card && card.querySelector("table")) return;

            btn.addEventListener("click", function () {
                exportStatsToCsv(btn);
            });
        });
    }

    function hasActiveFilter(selects) {
        return Array.from(selects).some(function (s) {
            return !!s.value;
        });
    }

    function toggleNoResults(tbody, table, visible) {
        let noResults = tbody.querySelector("tr.admin-no-results");
        if (visible === 0) {
            if (!noResults) {
                noResults = document.createElement("tr");
                noResults.className = "admin-no-results";
                const td = document.createElement("td");
                td.colSpan = table.querySelectorAll("thead th").length;
                td.className = "py-5 text-center text-secondary";
                td.innerHTML =
                    '<div class="mb-3"><i class="bi bi-search fs-1 text-secondary"></i></div>' +
                    '<h6 class="fw-medium">No matching results</h6>' +
                    '<p class="fs-7">Try adjusting your search or filters.</p>';
                tbody.appendChild(noResults);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }

    function exportTableToCsv(table, btn) {
        const headers = [];
        table.querySelectorAll("thead th").forEach(function (th) {
            const t = th.textContent.trim();
            if (t && !/^action$/i.test(t)) headers.push(t);
        });

        const rows = [];
        table.querySelectorAll("tbody tr").forEach(function (tr) {
            if (tr.classList.contains("admin-no-results")) return;
            if (tr.style.display === "none") return;

            const tds = tr.querySelectorAll("td");
            // Skip server-rendered empty-state row
            if (tds.length === 1 && tr.querySelector("td[colspan]")) return;

            const limit = tds.length - 1; // drop the Action column
            const cells = [];
            for (let i = 0; i < limit; i++) {
                cells.push(
                    tr.children[i].textContent.replace(/\s+/g, " ").trim(),
                );
            }
            if (cells.length) rows.push(cells);
        });

        if (!rows.length) {
            if (window.showToast)
                window.showToast(
                    "Export",
                    "No data available to export.",
                    "warning",
                );
            return;
        }

        const csv = [headers.map(csvEscape).join(",")];
        rows.forEach(function (r) {
            csv.push(r.map(csvEscape).join(","));
        });

        downloadCsv(csv.join("\r\n"), btn, "export");
    }

    function exportStatsToCsv(btn) {
        const rows = [];
        document
            .querySelectorAll(".card h3, .card .h3, .card h2, .card .h2")
            .forEach(function (el) {
                const card = el.closest(".card");
                if (!card) return;
                const labelEl = card.querySelector(
                    ".text-secondary, .text-muted",
                );
                const label = labelEl
                    ? labelEl.textContent.replace(/\s+/g, " ").trim()
                    : "";
                const value = el.textContent.replace(/\s+/g, " ").trim();
                if (value) rows.push([label || "Metric", value]);
            });

        if (!rows.length) {
            if (window.showToast)
                window.showToast(
                    "Export",
                    "No data available to export.",
                    "warning",
                );
            return;
        }

        const csv = [["Metric", "Value"]];
        rows.forEach(function (r) {
            csv.push(r.map(csvEscape).join(","));
        });

        downloadCsv(csv.join("\r\n"), btn, "report");
    }

    function downloadCsv(content, btn, fallback) {
        const blob = new Blob(["﻿" + content], {
            type: "text/csv;charset=utf-8;",
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        const titleText = (document.title.split(" - ")[0] || fallback)
            .trim()
            .replace(/[^a-z0-9]+/gi, "_")
            .toLowerCase();
        a.href = url;
        a.download =
            titleText + "_" + new Date().toISOString().slice(0, 10) + ".csv";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

        if (window.showToast)
            window.showToast(
                "Export",
                "CSV downloaded successfully.",
                "success",
            );
    }

    function csvEscape(val) {
        val = String(val == null ? "" : val);
        if (/[",\r\n]/.test(val)) {
            val = '"' + val.replace(/"/g, '""') + '"';
        }
        return val;
    }
})();
