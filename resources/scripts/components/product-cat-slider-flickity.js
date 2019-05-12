import Flickity from 'flickity'



const onLoad = () => {
	const sliders = document.querySelectorAll('.product-category-slider-flickity')

	sliders.length
		? sliders.forEach(slider => {
			slider.classList.remove('is-hidden')
			slider.offsetHeight
		})
		: false
}


export const flickity = el => {
	return new Flickity(el, {
		accessibility: true,
		arrowShape: { x0: 30, x1: 50, y1: 25, x2: 60, y2: 25, x3: 40 },
		draggable: true,
		freeScroll: true,
		freeScrollFriction: 1.0,
		friction: 0.15,
		fullscreen: false,
		groupCells: true,
		imagesLoaded: true,
//		initialIndex: '.flickity-initial-slide',
		lazyLoad: true,
		pageDots: true,
		percentPosition: false,
		prevNextButtons: true,
		resize: true,
		selectedAttraction: 0.01,
		wrapAround: true
	})
}


export const initFlickity = (sliders = []) =>
	sliders.length
	? sliders.forEach(slider => {
		onLoad()
		flickity(slider)
	})
	: false
