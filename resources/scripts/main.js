import Swiper from 'swiper'



(function (document, $, undefined) {
	const swiperSlider = new Swiper('.swiper-container', {
		slidesPerView:  'auto',
		spaceBetween:   30,
		loop:           true,
		loopedSlides:   37,
		freeMode:       true,
		freeModeSticky: true,
		mousewheel:     {
			sensitivity: 4,
		},
		navigation:     {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
	})
})(document, jQuery)
