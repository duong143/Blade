const sortTrigger = document.querySelector(".sort-trigger");
const sortDropdown = document.querySelector(".sort-dropdown");
const sortLabel = document.querySelector(".sort-label");

let departureFlightsData = Array.isArray(window.departureFlightCards) ? [...window.departureFlightCards] : [];
let returnFlightsData = Array.isArray(window.returnFlightCards) ? [...window.returnFlightCards] : [];

const defaultConditionLabels = [
    "Hành lý xách tay",
    "Hành lý ký gửi",
    "Suất ăn",
    "Thay đổi chuyến bay",
    "Đổi tên",
    "Hoàn vé",
    "Chọn ghế ngồi",
    "Phòng chờ thương gia",
    "Quầy thủ tục ưu tiên"
];

function parsePriceText(priceText) {
    if (!priceText) return 0;
    return parseInt(String(priceText).replace(/[^\d]/g, ""), 10) || 0;
}

function timeToMinutes(timeText) {
    if (!timeText || !String(timeText).includes(":")) return 0;
    const [hours, minutes] = String(timeText).split(":").map(Number);
    return (hours * 60) + minutes;
}

function formatPointText(pointValue) {
    const raw = String(pointValue ?? "").trim();

    if (!raw) return "";

    if (raw.includes("điểm")) {
        return raw.startsWith("+") ? raw : `+${raw}`;
    }

    const numeric = raw.replace(/[^\d]/g, "");
    if (!numeric) return "";

    return `+${numeric} điểm`;
}

function buildFullConditions(conditions) {
    const source = Array.isArray(conditions) ? conditions : [];

    return defaultConditionLabels.map(label => {
        const found = source.find(item => String(item.label || "").trim() === label);

        if (found) {
            return {
                label: label,
                value: found.value || "",
                enabled: !!found.enabled,
            };
        }

        return {
            label: label,
            value: "",
            enabled: false,
        };
    });
}

if (sortTrigger && sortDropdown) {
    sortTrigger.addEventListener("click", (e) => {
        e.stopPropagation();
        sortDropdown.toggleAttribute("hidden");
    });

    sortDropdown.querySelectorAll("li").forEach(item => {
        item.addEventListener("click", () => {
            sortLabel.textContent = item.textContent;
            sortTrigger.dataset.sort = item.dataset.sort;
            sortTrigger.dataset.order = item.dataset.order;
            sortDropdown.setAttribute("hidden", true);
            sortDepartureFlights(item.dataset.sort, item.dataset.order);
        });
    });

    document.addEventListener("click", () => {
        sortDropdown.setAttribute("hidden", true);
    });
}

const filterTrigger = document.querySelector(".filter-trigger");
if (filterTrigger) {
    filterTrigger.addEventListener("click", () => {
        console.log("Toggle filter panel");
    });
}

function sortDepartureFlights(sortBy, order) {
    departureFlightsData.sort((a, b) => {
        let valueA = 0;
        let valueB = 0;

        if (sortBy === "price") {
            valueA = parsePriceText(a.price);
            valueB = parsePriceText(b.price);
        }

        if (sortBy === "time") {
            valueA = timeToMinutes(a.departTime);
            valueB = timeToMinutes(b.departTime);
        }

        return order === "desc" ? valueB - valueA : valueA - valueB;
    });

    renderFlights(departureFlightsData);
}

function renderFlights(flights) {
    const container = document.getElementById("flight-cards-container");
    const template = document.getElementById("flight-card-template");

    if (!container || !template) {
        console.error("Không tìm thấy flight-cards-container hoặc flight-card-template");
        return;
    }

    container.innerHTML = "";

    if (!flights.length) {
        container.innerHTML = `
            <div class="flight-card">
                <div class="flight-card-body">
                    <div style="padding: 12px 0; color: #666;">
                        Hiện chưa có chuyến bay phù hợp cho ngày bạn chọn.
                    </div>
                </div>
            </div>
        `;
        return;
    }

    flights.forEach(flight => {
        const clone = template.content.cloneNode(true);
        const priceBox = clone.querySelector(".ticket-price");
        const conditionBox = clone.querySelector(".ticket-condition");

        if (!priceBox || !conditionBox) {
            console.error("Template bị thiếu .ticket-price hoặc .ticket-condition");
            return;
        }

        priceBox.innerHTML = "";
        conditionBox.innerHTML = "";

        (flight.priceDetail || []).forEach(item => {
            const row = document.createElement("div");
            row.className = "price-row";
            row.innerHTML = `
                <span>${item.label}</span>
                <span>${Number(item.value || 0).toLocaleString("vi-VN")} VND</span>
            `;
            priceBox.appendChild(row);
        });

        const totalRow = document.createElement("div");
        totalRow.className = "price-total";
        totalRow.innerHTML = `
            <span>Tổng giá vé chiều đi</span>
            <strong>${flight.totalPriceText || flight.price || ""}</strong>
        `;
        priceBox.appendChild(totalRow);

        const fullConditions = buildFullConditions(flight.conditions);
        fullConditions.forEach(cond => {
            const item = document.createElement("div");
            item.className = "condition-item" + (cond.enabled ? "" : " disabled");
            item.innerHTML = `
                <span class="condition-icon"></span>
                <span>${cond.label}:</span>
                <strong>${cond.value || ""}</strong>
            `;
            conditionBox.appendChild(item);
        });

        const airlineImg = clone.querySelector(".flight-airline img");
        if (airlineImg) {
            airlineImg.src = flight.logo || "";
            airlineImg.alt = flight.airline || "";
        }

        const flightCode = clone.querySelector(".flight-code");
        if (flightCode) {
            flightCode.textContent = `${flight.airline || ""} ${flight.code || ""}`.trim();
        }

        const flightClass = clone.querySelector(".flight-class");
        if (flightClass) {
            flightClass.textContent = flight.class || "";
        }

        const timeDepart = clone.querySelector(".time-depart");
        const placeDepart = clone.querySelector(".place-depart");
        const timeArrive = clone.querySelector(".time-arrive");
        const placeArrive = clone.querySelector(".place-arrive");
        const duration = clone.querySelector(".duration");
        const direct = clone.querySelector(".direct");
        const price = clone.querySelector(".price");
        const point = clone.querySelector(".point");
        const badge = clone.querySelector(".badge");
        const flyTimeSpan = clone.querySelector(".fly-time span");
        const infoDepart = clone.querySelector(".info-depart");
        const infoDepartAirport = clone.querySelector(".info-depart-airport");
        const infoArrive = clone.querySelector(".info-arrive");
        const infoArriveAirport = clone.querySelector(".info-arrive-airport");
        const aircraft = clone.querySelector(".aircraft");
        const seatClassInfo = clone.querySelector(".seat-class");
        const carryOn = clone.querySelector(".carry-on");
        const checkedBag = clone.querySelector(".checked-bag");
        const convinient = clone.querySelector(".convinient");

        if (timeDepart) timeDepart.textContent = flight.departTime || "";
        if (placeDepart) placeDepart.textContent = flight.departCity || "";
        if (timeArrive) timeArrive.textContent = flight.arriveTime || "";
        if (placeArrive) placeArrive.textContent = flight.arriveCity || "";
        if (duration) duration.textContent = flight.duration || "";
        if (direct) direct.textContent = flight.direct || "";
        if (price) price.textContent = flight.price || "";
        if (point) point.textContent = formatPointText(flight.point);
        if (badge) badge.textContent = flight.code || "";
        if (flyTimeSpan) flyTimeSpan.textContent = flight.duration || "";
        if (infoDepart) infoDepart.textContent = `${flight.departTime || ""} · ${flight.departCity || ""}`;
        if (infoDepartAirport) infoDepartAirport.textContent = flight.departAirport || "";
        if (infoArrive) infoArrive.textContent = `${flight.arriveTime || ""} · ${flight.arriveCity || ""}`;
        if (infoArriveAirport) infoArriveAirport.textContent = flight.arriveAirport || "";

        const aircraftParts = [];
        if (flight.aircraft) aircraftParts.push(flight.aircraft);
        if (flight.seatLayout) aircraftParts.push(flight.seatLayout);
        if (flight.seatPitch) aircraftParts.push(flight.seatPitch);

        if (aircraft) {
            aircraft.innerHTML = aircraftParts.length ? aircraftParts.join("<br>") : "";
        }

        if (seatClassInfo) seatClassInfo.textContent = flight.class || "";
        if (carryOn) carryOn.textContent = flight.carryOn || "";
        if (checkedBag) checkedBag.textContent = flight.checkedBag || "";
        if (convinient) convinient.innerHTML = (flight.convinient || "").split(", ").join("<br>");

        const card = clone.querySelector(".flight-card");
        initFlightCardTabs(card);

        const bookBtn = clone.querySelector(".btn-book");
        if (bookBtn && flight.id) {
            bookBtn.addEventListener("click", function (e) {
                e.stopPropagation();
                window.location.href = `/flight/booking?flight_id=${flight.id}&adult=1&child=0&infant=0`;
            });
        }

        container.appendChild(clone);
    });
}

function renderReturnFlights(flights) {
    const container = document.getElementById("return-flight-cards-container");
    const template = document.getElementById("return-card-template");

    if (!container || !template) return;

    container.innerHTML = "";

    if (!flights.length) {
        container.innerHTML = `
            <div class="return-item-wrapper">
                <div class="return-item">
                    <div style="padding: 12px; color: #666; font-size: 13px;">
                        Chưa có chuyến chiều về phù hợp.
                    </div>
                </div>
            </div>
        `;
        return;
    }

    flights.forEach(flight => {
        const clone = template.content.cloneNode(true);

        const returnLogo = clone.querySelector(".return-logo");
        const returnName = clone.querySelector(".return-name");
        const returnTime = clone.querySelector(".return-time");
        const returnArrive = clone.querySelector(".return-arrive");
        const returnFrom = clone.querySelector(".return-from");
        const returnTo = clone.querySelector(".return-to");
        const returnDuration = clone.querySelector(".return-duration");
        const returnDirect = clone.querySelector(".return-direct");

        if (returnLogo) {
            returnLogo.src = flight.logo || "";
            returnLogo.alt = flight.airline || "";
        }

        if (returnName) returnName.textContent = flight.airline || "";
        if (returnTime) returnTime.textContent = flight.departTime || "";
        if (returnArrive) returnArrive.textContent = flight.arriveTime || "";
        if (returnFrom) returnFrom.textContent = flight.from || "";
        if (returnTo) returnTo.textContent = flight.to || "";
        if (returnDuration) returnDuration.textContent = flight.duration || "";
        if (returnDirect) returnDirect.textContent = flight.direct || "Bay thẳng";

        container.appendChild(clone);
    });
}

function initDateBarScroll() {
    const dateBar = document.querySelector(".departure-dates");
    if (!dateBar) return;

    const track = dateBar.querySelector(".date-track");
    const prevBtn = dateBar.querySelector(".nav-arrow:first-child");
    const nextBtn = dateBar.querySelector(".nav-arrow:last-child");

    if (!track) return;

    if (prevBtn) {
        prevBtn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            track.scrollBy({ left: -240, behavior: "smooth" });
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            track.scrollBy({ left: 240, behavior: "smooth" });
        });
    }
}

function initFlightCardTabs(card) {
    if (!card) return;

    const header = card.querySelector(".flight-card-header");
    const tabLinks = card.querySelectorAll(".tab-link");
    const tabContents = card.querySelectorAll(".tab-content");

    card.classList.add("collapsed");

    if (header) {
        header.addEventListener("click", (e) => {
            if (e.target.closest(".btn-book")) return;
            card.classList.toggle("collapsed");
        });
    }

    tabLinks.forEach(tab => {
        tab.addEventListener("click", () => {
            const target = tab.dataset.tab;

            card.classList.remove("collapsed");

            tabLinks.forEach(t => t.classList.remove("active"));
            tabContents.forEach(c => c.classList.remove("active"));

            tab.classList.add("active");

            const targetContent = card.querySelector(`.tab-content[data-content="${target}"]`);
            if (targetContent) {
                targetContent.classList.add("active");
            }
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    console.log("departureFlightsData:", departureFlightsData);
    renderFlights(departureFlightsData);
    renderReturnFlights(returnFlightsData);
    initDateBarScroll();
});