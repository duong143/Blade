// BANNER
const slider = document.querySelector('.banner-slider');
const track = document.querySelector('.banner-track');
const slides = document.querySelectorAll('.banner-track img');
const prev = document.querySelector('.banner-btn.prev');
const next = document.querySelector('.banner-btn.next');

let index = 0;
let slideWidth = slider.clientWidth;

function updateSlide() {
    track.style.transform = `translateX(-${index * slideWidth}px)`;
}

next.addEventListener('click', () => {
    index++;
    if (index >= slides.length) index = 0;
    updateSlide();
});

prev.addEventListener('click', () => {
    index--;
    if (index < 0) index = slides.length - 1;
    updateSlide();
});

window.addEventListener('resize', () => {
    slideWidth = slider.clientWidth;
    updateSlide();
});


// đổi chỗ từ - đến
const swapBtn = document.getElementById('swapBtn');
const fromInput = document.getElementById('from');
const toInput = document.getElementById('to');

swapBtn.addEventListener('click', () => {
    const temp = fromInput.value;
    fromInput.value = toInput.value;
    toInput.value = temp;
});

// đổi sang tab
const tabs = document.querySelectorAll('.search-tabs .tab');

const flightForm = document.querySelector('.flight-form');
const hotelForm = document.querySelector('.hotel-form');
const airportForm = document.querySelector('.airport-form');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {

        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        flightForm.style.display = 'none';
        hotelForm.style.display = 'none';
        airportForm.style.display = 'none';
        // tab tương ứng
        if (tab.classList.contains('flight')) {
            flightForm.style.display = 'block';
        }
        if (tab.classList.contains('hotel')) {
            hotelForm.style.display = 'block';
        }
        if (tab.classList.contains('airport')) {
            airportForm.style.display = 'block';
        }
    });
});

document.querySelectorAll('.star-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        if (btn.classList.contains('active')) {
            btn.classList.remove('active');
        } else {
            document.querySelectorAll('.star-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }
    });
});

// khứ hồi bật tắt ngày về
const roundTrip = document.getElementById("roundTrip");
const returnDate = document.querySelector(".return-date");

roundTrip.addEventListener("change", () => {
    if (roundTrip.checked) {
        returnDate.classList.remove("hidden");
    } else {
        returnDate.classList.add("hidden");
    }
});
// ngày đi không được sau ngày về
const departInput = document.querySelector('.col-2 .date-row input[type="date"]');
const returnInput = document.querySelector('.return-date input[type="date"]');
const dateError = document.getElementById('dateError');
const searchBtn = document.querySelector('.btn-search');

function validateDate() {
    if (!roundTrip.checked) {
        dateError.classList.remove('show');
        returnInput.style.borderColor = '#ddd';
        searchBtn.disabled = false;
        return;
    }

    if (!departInput.value || !returnInput.value) {
        searchBtn.disabled = false;
        return;
    }

    const departDate = new Date(departInput.value);
    const returnDateValue = new Date(returnInput.value);

    if (returnDateValue < departDate) {
        dateError.classList.add('show');
        returnInput.style.borderColor = '#e53935';
        searchBtn.disabled = true;
    } else {
        dateError.classList.remove('show');
        returnInput.style.borderColor = '#ddd';
        searchBtn.disabled = false;
    }
}

departInput.addEventListener('change', () => {
    returnInput.min = departInput.value;
    validateDate();
});

returnInput.addEventListener('change', validateDate);
// giữ active màu khi ấn menu
const menuLinks = document.querySelectorAll('.menu a');

menuLinks.forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        menuLinks.forEach(item => item.classList.remove('active'));
        this.classList.add('active');
    });
});


const airportSwapBtn = document.getElementById('swapAirport');
const airportInputs = airportSwapBtn
    ? airportSwapBtn.closest('.airport-form').querySelectorAll('input[type="text"]')
    : [];

if (airportSwapBtn && airportInputs.length === 2) {
    airportSwapBtn.addEventListener('click', () => {

        const temp = airportInputs[0].value;
        airportInputs[0].value = airportInputs[1].value;
        airportInputs[1].value = temp;
        setTimeout(() => {
            airportSwapBtn.classList.remove('swapping');
        }, 350);
    });
}


// chuyến sang flight khi ấn tìm kiếm
const flightSearchBtn = document.querySelector(
    '.flight-form .btn-search'
);

if (flightSearchBtn) {
    flightSearchBtn.addEventListener('click', () => {
        window.location.href = '/flightsearch';
    });
}
// 


// 
document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const form = params.get("form");

    if (form === "flight") {
        document.querySelector('.flight-form').style.display = 'block';
        document.querySelector('.hotel-form').style.display = 'none';
        document.querySelector('.airport-form').style.display = 'none';

        document.querySelector('.search-tabs .tab.flight')?.classList.add('active');
        document.querySelector('.search-tabs .tab.hotel')?.classList.remove('active');
        document.querySelector('.search-tabs .tab.airport')?.classList.remove('active');
    }
});

document.addEventListener("DOMContentLoaded", function () {

    const tabs = document.querySelectorAll('.search-tabs .tab');

    const flightForm = document.querySelector('.flight-form');
    const hotelForm = document.querySelector('.hotel-form');
    const airportForm = document.querySelector('.airport-form');

    const menuLinks = document.querySelectorAll('.menu-link');

    function showForm(type) {
        if (!flightForm || !hotelForm || !airportForm) return;

        // ẨN HẾT
        flightForm.style.display = 'none';
        hotelForm.style.display = 'none';
        airportForm.style.display = 'none';

        tabs.forEach(tab => tab.classList.remove('active'));

        // HIỆN FORM ĐÚNG
        if (type === 'flight') {
            flightForm.style.display = 'block';
            document.querySelector('.search-tabs .tab.flight')?.classList.add('active');
        }

        if (type === 'hotel') {
            hotelForm.style.display = 'block';
            document.querySelector('.search-tabs .tab.hotel')?.classList.add('active');
        }

        if (type === 'airport') {
            airportForm.style.display = 'block';
            document.querySelector('.search-tabs .tab.airport')?.classList.add('active');
        }
    }
    // CLICK TAB
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            if (tab.classList.contains('flight')) showForm('flight');
            if (tab.classList.contains('hotel')) showForm('hotel');
            if (tab.classList.contains('airport')) showForm('airport');
        });
    });
    menuLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const type = link.dataset.type;
            if (type) showForm(type);
        });
    });
    const params = new URLSearchParams(window.location.search);
    const form = params.get("form");
    if (form) showForm(form);

    
});
