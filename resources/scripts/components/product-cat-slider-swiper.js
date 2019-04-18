import Swiper from 'swiper'



const swiperSlider = el => {
	return new Swiper(el, {
		slidesPerView:  'auto',
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
}


export const initSwiperSliders = sliders => {
	if (!sliders || !sliders.length) {
		return
	}

	sliders.forEach(slider => swiperSlider(slider))
}
