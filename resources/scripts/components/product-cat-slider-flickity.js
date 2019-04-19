import Flickity from 'flickity'



const flickitySlider = el => {
	return new Flickity(el, {
		arrowShape: { x0: 30, x1: 50, y1: 25, x2: 60, y2: 25, x3: 40 },
		freeScroll: true,
		groupCells: true,
		imagesLoaded: true,
		lazyLoad: true,
		pageDots: false,
		prevNextButtons: true,
		wrapAround: true,
	})
}


export const initFlickity = (sliders = []) =>
	sliders.length
	? sliders.forEach(slider => flickitySlider(slider))
	: false
