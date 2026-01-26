
const sortTrigger = document.querySelector(".sort-trigger");
const sortDropdown = document.querySelector(".sort-dropdown");
const sortLabel = document.querySelector(".sort-label");

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

function renderFlights(flights) {
    const container = document.querySelector(".flight-main");
    const template = document.getElementById("flight-card-template");

    flights.forEach(flight => {
        const clone = template.content.cloneNode(true);
        const priceBox = clone.querySelector(".ticket-price");
        priceBox.innerHTML = "";
        flight.priceDetail.forEach(item => {
            const row = document.createElement("div");
            row.className = "price-row";

            row.innerHTML = `
        <span>${item.label}</span>
        <span>${item.value.toLocaleString("vi-VN")} VND</span>
    `;

            priceBox.appendChild(row);
        });
        const totalRow = document.createElement("div");
        totalRow.className = "price-total";

        totalRow.innerHTML = `
    <span>Tổng giá vé chiều đi</span>
    <strong>${flight.totalPrice.toLocaleString("vi-VN")} VND</strong>
`;
        const conditionBox = clone.querySelector(".ticket-condition");
        conditionBox.innerHTML = "";

        flight.conditions.forEach(cond => {
            const item = document.createElement("div");
            item.className =
                "condition-item" + (cond.enabled ? "" : " disabled");

            item.innerHTML = `
    <span class="condition-icon"></span>
    <span>${cond.label}:</span>
    <strong>${cond.value}</strong>
`;


            conditionBox.appendChild(item);
        });

        priceBox.appendChild(totalRow);



        clone.querySelector(".flight-airline img").src = flight.logo;
        clone.querySelector(".flight-code").textContent = `${flight.airline} ${flight.code}`;
        clone.querySelector(".flight-class").textContent = flight.class;

        clone.querySelector(".time-depart").textContent = flight.departTime;
        clone.querySelector(".place-depart").textContent = flight.departCity;
        clone.querySelector(".time-arrive").textContent = flight.arriveTime;
        clone.querySelector(".place-arrive").textContent = flight.arriveCity;

        clone.querySelector(".duration").textContent = flight.duration;
        clone.querySelector(".direct").textContent = flight.direct;

        clone.querySelector(".price").textContent = flight.price;
        clone.querySelector(".point").textContent = flight.point;

        clone.querySelector(".badge").textContent = flight.code;
        clone.querySelector(".fly-time span").textContent = flight.duration;

        clone.querySelector(".info-depart").textContent = `${flight.departTime} · ${flight.departCity}`;
        clone.querySelector(".info-depart-airport").textContent = flight.departAirport;
        clone.querySelector(".info-arrive").textContent = `${flight.arriveTime} · ${flight.arriveCity}`;
        clone.querySelector(".info-arrive-airport").textContent = flight.arriveAirport;

        clone.querySelector(".aircraft").innerHTML =
            flight.aircraft.split(", ").join("<br>");
        clone.querySelector(".seat-class").textContent = flight.class;
        clone.querySelector(".carry-on").textContent = flight.carryOn;
        clone.querySelector(".checked-bag").textContent = flight.checkedBag;
        clone.querySelector(".convinient").innerHTML =
            flight.convinient.split(", ").join("<br>");

        initFlightCardTabs(clone.querySelector(".flight-card"));

        container.appendChild(clone);
    });
}
document.addEventListener("DOMContentLoaded", () => {
    renderFlights(mockFlights);
});

// thông tin mẫu
const mockFlights = [
    {
        airline: "Vietjet Air",
        logo: "/images/vietjet.png",
        code: "VJ-121",
        class: "Economy Class",
        departTime: "08:00",
        departCity: "Hà Nội (HAN)",
        departAirport: "HAN-Sân bay Nội Bài",
        arriveTime: "10:10",
        arriveCity: "TP HCM (SGN)",
        arriveAirport: "SGN-Sân bay Tân Sơn Nhất",
        duration: "2h 25m",
        direct: "Bay thẳng",
        price: "10.369.000 VND",
        point: "+17 điểm",
        aircraft: "Airbus A320, Sơ đồ ghế 3-3, Khoảng cách ghế 32 inch ( Tiêu chuẩn)",
        carryOn: "7Kg",
        checkedBag: "Mua thêm khi đặt chỗ",
        convinient: "Suất ăn trên máy bay, Hệ thống giải trí, Ổ điện và cổng USB",
        priceDetail: [
            { label: "Người lớn (x1)", value: 599000 },
            { label: "Thuế VAT", value: 350000 },
            { label: "Phí dịch vụ", value: 1005000 },
            { label: "Phí soi chiếu an ninh", value: 1005000 }
        ],

        totalPrice: 2962000,

        conditions: [
            { label: "Hành lý xách tay", value: "7kg", enabled: true },
            { label: "Hành lý ký gửi", value: "Trả phí", enabled: true },
            { label: "Suất ăn", value: "Đã bao gồm", enabled: true },
            { label: "Thay đổi chuyến bay", value: "Không hỗ trợ", enabled: false },
            { label: "Đổi tên", value: "Không hỗ trợ", enabled: false },
            { label: "Hoàn vé", value: "Không hỗ trợ", enabled: false },
            { label: "Chọn ghế ngồi", value: "Trả phí", enabled: false },
            { label: "Phòng chờ thương gia", value: "Trả phí", enabled: false },
            { label: "Quầy thủ tục ưu tiên", value: "Trả phí", enabled: false },

        ]
    },
    {
        airline: "Vietnam Airlines",
        logo: "/images/vna.png",
        code: "VN-678",
        class: "Economy Class",
        departTime: "09:00",
        departCity: "Hà Nội (HAN)",
        departAirport: "HAN-Sân bay Nội Bài",
        arriveTime: "11:30",
        arriveCity: "TP HCM (SGN)",
        arriveAirport: "SGN-Sân bay Tân Sơn Nhất",
        duration: "2h 30m",
        direct: "Bay thẳng",
        price: "12.150.000 VND",
        point: "+22 điểm",
        aircraft: "Airbus A321, Sơ đồ ghế 3-3, Khoảng cách ghế 32 inch ( Tiêu chuẩn)",
        carryOn: "10Kg",
        checkedBag: "20Kg",
        convinient: "Suất ăn trên máy bay, Hệ thống giải trí, Ổ điện và cổng USB",
        priceDetail: [
            { label: "Người lớn (x1)", value: 599000 },
            { label: "Thuế VAT", value: 350000 },
            { label: "Phí dịch vụ", value: 1005000 },
            { label: "Phí soi chiếu an ninh", value: 1005000 }
        ],

        totalPrice: 2962000,

        conditions: [
            { label: "Hành lý xách tay", value: "7kg", enabled: true },
            { label: "Hành lý ký gửi", value: "Trả phí", enabled: true },
            { label: "Suất ăn", value: "Đã bao gồm", enabled: true },
            { label: "Thay đổi chuyến bay", value: "Không hỗ trợ", enabled: false },
            { label: "Đổi tên", value: "Không hỗ trợ", enabled: false },
            { label: "Hoàn vé", value: "Không hỗ trợ", enabled: false },
            { label: "Chọn ghế ngồi", value: "Trả phí", enabled: false },
            { label: "Phòng chờ thương gia", value: "Trả phí", enabled: false },
            { label: "Quầy thủ tục ưu tiên", value: "Trả phí", enabled: false },
        ]
    }
];


//mock data chiều về
const mockReturnFlights = [
    {
        airline: "Bamboo Airways",
        logo: "/images/bamboo.png",
        departTime: "08:00",
        arriveTime: "10:10",
        duration: "2h 25m",
        from: "Hà Nội (HAN)",
        to: "TP HCM (SGN)",
        duration: "2h 25m"
    },
    {
        airline: "Vietnam Airlines",
        logo: "/images/vna.png",
        departTime: "09:30",
        arriveTime: "11:55",
        duration: "2h 25m",
        from: "Hà Nội (HAN)",
        to: "TP HCM (SGN)",
        duration: "2h 25m"
    },
    {
        airline: "Vietjet Air",
        logo: "/images/vietjet.png",
        departTime: "13:20",
        arriveTime: "15:40",
        duration: "2h 20m",
        from: "Hà Nội (HAN)",
        to: "TP HCM (SGN)",
        duration: "2h 25m"
    },
    {
        airline: "Vietjet Air",
        logo: "/images/vietjet.png",
        departTime: "13:20",
        arriveTime: "15:40",
        duration: "2h 20m",
        from: "Hà Nội (HAN)",
        to: "TP HCM (SGN)",
        duration: "2h 25m"
    },
    {
        airline: "Vietjet Air",
        logo: "/images/vietjet.png",
        departTime: "13:20",
        arriveTime: "15:40",
        duration: "2h 20m",
        from: "Hà Nội (HAN)",
        to: "TP HCM (SGN)",
        duration: "2h 25m"
    }
];
function renderReturnFlights(flights) {
    const container = document.querySelector(".return-section");
    const template = document.getElementById("return-card-template");

    if (!container || !template) return;

    flights.forEach(flight => {
        const clone = template.content.cloneNode(true);

        clone.querySelector(".return-logo").src = flight.logo;
        clone.querySelector(".return-name").textContent = flight.airline;

        clone.querySelector(".return-time").textContent = flight.departTime;
        clone.querySelector(".return-arrive").textContent = flight.arriveTime;

        clone.querySelector(".return-from").textContent = flight.from;
        clone.querySelector(".return-to").textContent = flight.to;

        clone.querySelector(".return-duration").textContent = flight.duration;

        container.appendChild(clone);



    });
}
document.addEventListener("DOMContentLoaded", () => {
    renderFlights(mockFlights);
    renderReturnFlights(mockReturnFlights);
});
//thời gian và giá tiền
// mock data 
document.addEventListener("DOMContentLoaded", () => {
    const dateBar = document.querySelector(".departure-dates");
    if (!dateBar) return;

    const prevBtn = dateBar.querySelector(".nav-arrow:first-child");
    const nextBtn = dateBar.querySelector(".nav-arrow:last-child");

    // ===== MOCK DATA =====
    const mockDatePrices = [
        { date: "11 Th 10", price: 299000 },
        { date: "12 Th 10", price: 329000 },
        { date: "13 Th 10", price: 279000 },
        { date: "14 Th 10", price: 299000 },
        { date: "15 Th 10", price: 359000 },
        { date: "16 Th 10", price: 399000 },
        { date: "17 Th 10", price: 319000 },
        { date: "18 Th 10", price: 289000 },
    ];

    // ===== TẠO TRACK =====
    let track = document.createElement("div");
    track.className = "date-track";

    // clear date-item cũ
    dateBar.querySelectorAll(".date-item").forEach(el => el.remove());

    // render date
    mockDatePrices.forEach((item, index) => {
        const el = document.createElement("div");
        el.className = "date-item" + (index === 3 ? " active" : "");
        el.innerHTML = `
            <div class="date">${item.date}</div>
            <div class="price">${item.price.toLocaleString("vi-VN")} VND</div>
        `;
        track.appendChild(el);
    });
    dateBar.insertBefore(track, nextBtn);

    const dateItems = track.querySelectorAll(".date-item");
    let activeIndex = 3;

    // date
    dateItems.forEach((item, index) => {
        item.addEventListener("click", () => setActive(index));
    });

    const itemWidth = dateItems[0].offsetWidth;

    prevBtn.addEventListener("click", () => {
        track.scrollLeft -= itemWidth * 2;
    });

    nextBtn.addEventListener("click", () => {
        track.scrollLeft += itemWidth * 2;
    });

    function setActive(index) {
        dateItems[activeIndex].classList.remove("active");
        dateItems[index].classList.add("active");
        activeIndex = index;

        dateItems[index].scrollIntoView({
            behavior: "smooth",
            inline: "center",
            block: "nearest"
        });
    }
});


const date = dateItems[index].querySelector(".date")?.textContent;
const price = dateItems[index].querySelector(".price")?.textContent;

console.log("Selected date:", date, price);
// chuyển tab chi tiết vé
function initFlightCardTabs(card) {
    if (!card) return;

    const header = card.querySelector(".flight-card-header");
    const tabLinks = card.querySelectorAll(".tab-link");
    const tabContents = card.querySelectorAll(".tab-content");

    card.classList.add("collapsed");

    header.addEventListener("click", (e) => {
        if (e.target.closest(".btn-book")) return;

        card.classList.toggle("collapsed");
    });

    tabLinks.forEach(tab => {
        tab.addEventListener("click", () => {
            const target = tab.dataset.tab;
            const isCollapsed = card.classList.contains("collapsed");


            if (!isCollapsed) {
                card.classList.remove("collapsed");

                // reset
                tabLinks.forEach(t => t.classList.remove("active"));
                tabContents.forEach(c => c.classList.remove("active"));

                // active tab
                tab.classList.add("active");
                card.querySelector(`.tab-content[data-content="${target}"]`)
                    .classList.add("active");
            } else {
                card.classList.add("collapsed");
            }
            return;
        });
    });
}

// chuyến về form vé máy bay
document.addEventListener("click", function (e) {
    const link = e.target.closest(".menu-link");

    if (!link) return;

    if (link.textContent.trim() === "Vé máy bay") {
        e.preventDefault();
        window.location.href = "/";
    }
}); 