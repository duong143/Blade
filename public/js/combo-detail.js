document.addEventListener('DOMContentLoaded', function () {
    function initSlider(config) {
        const gallery = document.querySelector(config.gallerySelector);
        if (!gallery) return;

        const track = gallery.querySelector(config.trackSelector);
        const slides = gallery.querySelectorAll(config.slideSelector);
        const prevBtn = gallery.querySelector(config.prevSelector);
        const nextBtn = gallery.querySelector(config.nextSelector);

        if (!track || !slides.length || !prevBtn || !nextBtn) return;

        let currentIndex = 0;

        function getStep() {
            if (typeof config.getStep === 'function') {
                return config.getStep();
            }
            return 1;
        }

        function getSlideWidth() {
            const firstSlide = slides[0];
            if (!firstSlide) return 0;

            const slideWidth = firstSlide.getBoundingClientRect().width;
            const trackStyle = window.getComputedStyle(track);
            const gap = parseFloat(trackStyle.columnGap || trackStyle.gap || 0);

            return slideWidth + gap;
        }

        function updateSlider() {
            const stepWidth = getSlideWidth();
            track.style.transform = `translateX(-${currentIndex * stepWidth}px)`;

            prevBtn.disabled = currentIndex <= 0;

            const maxIndex = Math.max(0, slides.length - getStep());
            nextBtn.disabled = currentIndex >= maxIndex;
        }

        prevBtn.addEventListener('click', function () {
            currentIndex = Math.max(0, currentIndex - 1);
            updateSlider();
        });

        nextBtn.addEventListener('click', function () {
            const maxIndex = Math.max(0, slides.length - getStep());
            currentIndex = Math.min(maxIndex, currentIndex + 1);
            updateSlider();
        });

        window.addEventListener('resize', updateSlider);
        updateSlider();
    }

    initSlider({
        gallerySelector: '.combo-detail-gallery',
        trackSelector: '.combo-detail-track',
        slideSelector: '.combo-detail-slide',
        prevSelector: '.combo-detail-prev',
        nextSelector: '.combo-detail-next',
        getStep: function () {
            return window.innerWidth <= 767.98 ? 1 : 3;
        }
    });

    initSlider({
        gallerySelector: '.combo-content-gallery',
        trackSelector: '.combo-content-track',
        slideSelector: '.combo-content-slide',
        prevSelector: '.combo-content-prev',
        nextSelector: '.combo-content-next',
        getStep: function () {
            return 1;
        }
    });

    const saleCountdown = document.getElementById('comboSaleCountdown');

    if (saleCountdown) {
        const saleEndValue = saleCountdown.dataset.saleEnd;

        function updateSaleCountdown() {
            const endTime = new Date(saleEndValue.replace(' ', 'T'));
            const now = new Date();

            const diff = endTime - now;

            if (diff <= 0) {
                saleCountdown.textContent = 'Hết sale hôm nay';
                return;
            }

            const totalSeconds = Math.floor(diff / 1000);
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            if (days > 0) {
                saleCountdown.textContent = `${days} ngày ${hours}h:${minutes}m:${seconds}s`;
            } else {
                saleCountdown.textContent = `${hours}h:${minutes}m:${seconds}s`;
            }
        }

        updateSaleCountdown();
        setInterval(updateSaleCountdown, 1000);
    }

    const qtyCards = document.querySelectorAll('.combo-qty-card');
    const totalValueEl = document.getElementById('comboTotalValue');

    function formatVND(value) {
        return new Intl.NumberFormat('vi-VN').format(value) + ' VND';
    }

    function updateMinusState(card, count) {
        const minusBtn = card.querySelector('.combo-step-btn-minus');
        if (!minusBtn) return;

        if (count <= 0) {
            minusBtn.classList.add('combo-step-btn-disabled');
        } else {
            minusBtn.classList.remove('combo-step-btn-disabled');
        }
    }

    function updateTotal() {
        let total = 0;

        qtyCards.forEach(function (card) {
            const price = parseInt(card.dataset.price || '0', 10);
            const countEl = card.querySelector('.combo-step-count');
            const count = parseInt(countEl?.textContent || '0', 10);

            total += price * count;
        });

        if (totalValueEl) {
            totalValueEl.textContent = total > 0 ? formatVND(total) : '--';
        }

        updateBookNowUrl();
    }

    qtyCards.forEach(function (card) {
        const minusBtn = card.querySelector('.combo-step-btn-minus');
        const plusBtn = card.querySelector('.combo-step-btn-plus');
        const countEl = card.querySelector('.combo-step-count');

        if (!minusBtn || !plusBtn || !countEl) return;

        let count = parseInt(countEl.textContent || '0', 10);

        updateMinusState(card, count);

        minusBtn.addEventListener('click', function () {
            if (count <= 0) return;

            count -= 1;
            countEl.textContent = String(count).padStart(2, '0');
            updateMinusState(card, count);
            updateTotal();
        });

        plusBtn.addEventListener('click', function () {
            count += 1;
            countEl.textContent = String(count).padStart(2, '0');
            updateMinusState(card, count);
            updateTotal();
        });
    });

    const departureTrigger = document.getElementById('comboDepartureTrigger');
    const departureTriggerText = document.getElementById('comboDepartureTriggerText');
    const departureMenu = document.getElementById('comboDepartureMenu');
    const departureItems = document.querySelectorAll('.combo-departure-item');
    const remainingSlotsEl = document.getElementById('comboRemainingSlots');
    const soldCountEl = document.getElementById('comboSoldCount');
    const priceOldEl = document.getElementById('comboPriceOld');
    const mainPriceEl = document.getElementById('comboMainPrice');
    const saleBadgeEl = document.getElementById('comboSaleBadge');

    function formatK(value) {
        if (!value || value <= 0) return '--';
        return new Intl.NumberFormat('vi-VN').format(Math.round(value / 1000)) + 'K';
    }

    function resetQuantities() {
        qtyCards.forEach(function (card) {
            const type = card.dataset.type || '';
            const countEl = card.querySelector('.combo-step-count');
            if (!countEl) return;

            const nextCount = type === 'adult' ? 1 : 0;
            countEl.textContent = String(nextCount).padStart(2, '0');
            updateMinusState(card, nextCount);
        });
    }

    function closeDepartureMenu() {
        if (departureMenu) {
            departureMenu.classList.remove('show');
        }
    }

    function updateDepartureUI(item) {
        const triggerDateText = item.dataset.date || 'Chưa có ngày khởi hành';
        const remainingSlots = item.dataset.remainingSlots || '--';
        const sold = item.dataset.sold || '--';
        const salePercent = parseInt(item.dataset.salePercent || '0', 10);

        const adultBasePrice = parseInt(item.dataset.adultBasePrice || '0', 10);
        const adultFinalPrice = parseInt(item.dataset.adultFinalPrice || '0', 10);
        const childFinalPrice = parseInt(item.dataset.childFinalPrice || '0', 10);
        const infantFinalPrice = parseInt(item.dataset.infantFinalPrice || '0', 10);

        if (departureTriggerText) {
            departureTriggerText.textContent = triggerDateText;
        }

        if (remainingSlotsEl) {
            remainingSlotsEl.textContent = `Số lượng: ${remainingSlots}`;
        }

        if (soldCountEl) {
            soldCountEl.textContent = `Đã mua: ${sold}`;
        }

        if (priceOldEl) {
            if (adultBasePrice > 0 && salePercent > 0) {
                priceOldEl.textContent = formatVND(adultBasePrice);
            } else {
                priceOldEl.innerHTML = '&nbsp;';
            }
        }

        if (mainPriceEl) {
            mainPriceEl.textContent = adultFinalPrice > 0 ? formatVND(adultFinalPrice) : '--';
        }

        if (saleBadgeEl) {
            if (salePercent > 0) {
                saleBadgeEl.textContent = `GIẢM ${salePercent}% HÔM NAY`;
                saleBadgeEl.classList.remove('d-none');
            } else {
                saleBadgeEl.classList.add('d-none');
            }
        }

        const adultCard = document.querySelector('.combo-qty-card[data-type="adult"]');
        const childCard = document.querySelector('.combo-qty-card[data-type="child"]');
        const infantCard = document.querySelector('.combo-qty-card[data-type="infant"]');

        const adultPill = document.getElementById('comboAdultPricePill');
        const childPill = document.getElementById('comboChildPricePill');
        const infantPill = document.getElementById('comboInfantPricePill');

        if (adultCard) adultCard.dataset.price = adultFinalPrice;
        if (childCard) childCard.dataset.price = childFinalPrice;
        if (infantCard) infantCard.dataset.price = infantFinalPrice;

        if (adultPill) adultPill.textContent = formatK(adultFinalPrice);
        if (childPill) childPill.textContent = formatK(childFinalPrice);
        if (infantPill) infantPill.textContent = formatK(infantFinalPrice);

        departureItems.forEach(function (depItem) {
            depItem.classList.remove('active');
        });
        item.classList.add('active');

        resetQuantities();
        updateTotal();
        closeDepartureMenu();
    }

    if (departureTrigger && departureMenu) {
        departureTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            departureMenu.classList.toggle('show');
        });

        departureItems.forEach(function (item) {
            item.addEventListener('click', function () {
                updateDepartureUI(item);
            });
        });

        document.addEventListener('click', function (e) {
            if (!departureMenu.contains(e.target) && !departureTrigger.contains(e.target)) {
                closeDepartureMenu();
            }
        });
    }

    const bookNowBtn = document.getElementById('comboBookNowBtn');

    function updateBookNowUrl() {
        if (!bookNowBtn) return;

        const url = new URL(bookNowBtn.href, window.location.origin);

        const adultCount = parseInt(document.querySelector('.combo-qty-card[data-type="adult"] .combo-step-count')?.textContent || '0', 10);
        const childCount = parseInt(document.querySelector('.combo-qty-card[data-type="child"] .combo-step-count')?.textContent || '0', 10);
        const infantCount = parseInt(document.querySelector('.combo-qty-card[data-type="infant"] .combo-step-count')?.textContent || '0', 10);

        const activeDeparture = document.querySelector('.combo-departure-item.active');

        if (activeDeparture) {
            url.searchParams.set('departure_id', activeDeparture.dataset.departureId || '');
            url.searchParams.set('selected_start_date', activeDeparture.dataset.startDateValue || '');
        }

        url.searchParams.set('adult', adultCount);
        url.searchParams.set('child', childCount);
        url.searchParams.set('infant', infantCount);

        bookNowBtn.href = url.pathname + '?' + url.searchParams.toString();
    }

    updateTotal();

    document.querySelectorAll('.combo-qty-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('.combo-qty-card')
                .forEach(c => c.classList.remove('combo-qty-card-active'));

            this.classList.add('combo-qty-card-active');
        });
    });
});