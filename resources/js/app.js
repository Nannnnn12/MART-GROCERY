import './bootstrap';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

document.addEventListener('DOMContentLoaded', function () {
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: {
            delay: 3000,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });

    const categoriesSwiper = new Swiper('.categoriesSwiper', {
        slidesPerView: 1,
        spaceBetween: 12,
        autoHeight: false,
        navigation: {
            nextEl: '.categories-next',
            prevEl: '.categories-prev',
        },
        breakpoints: {
            480: {
                slidesPerView: 3,
                spaceBetween: 12,
            },
            640: {
                slidesPerView: 3, // bisa 3 untuk tablet kecil
                spaceBetween: 16,
            },
            768: { // tablet (md)
                slidesPerView: 4,
                spaceBetween: 20,
            },
            1024: { // lg / laptop
                slidesPerView: 5,
                spaceBetween: 24,
            },
            1280: { // xl
                slidesPerView: 6,
                spaceBetween: 18,
            },
        },
        loop: true,
        grabCursor: true,
    });

    const reviewsSwiper = new Swiper('.reviewsSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: '.reviews-next',
            prevEl: '.reviews-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
        },
        loop: true,
        grabCursor: true,
    });

    // Account Dropdown Hover Functionality
    const accountDropdownToggle = document.querySelector('.account-dropdown-toggle');
    const accountDropdownMenu = document.querySelector('.account-dropdown-menu');

    if (accountDropdownToggle && accountDropdownMenu) {
        let hoverTimeout;

        accountDropdownToggle.addEventListener('mouseenter', function () {
            clearTimeout(hoverTimeout);
            accountDropdownToggle.classList.add('active');
            accountDropdownMenu.classList.add('show');
        });

        accountDropdownToggle.addEventListener('mouseleave', function () {
            hoverTimeout = setTimeout(() => {
                accountDropdownToggle.classList.remove('active');
                accountDropdownMenu.classList.remove('show');
            }, 150); // Small delay to prevent flickering
        });

        accountDropdownMenu.addEventListener('mouseenter', function () {
            clearTimeout(hoverTimeout);
        });

        accountDropdownMenu.addEventListener('mouseleave', function () {
            hoverTimeout = setTimeout(() => {
                accountDropdownToggle.classList.remove('active');
                accountDropdownMenu.classList.remove('show');
            }, 150);
        });
    }
});
