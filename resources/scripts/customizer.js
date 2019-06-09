const init = (function (api) {
//	const api = wp.customize

	console.log(wp)
	console.log(api)

	api.bind('ready', function () {
		//	const productCatsHeading = document.querySelector('.product-cats-heading')
		const productSubcatsHeading = document.querySelector('.product-subcats-heading')
		const productTagsHeading    = document.querySelector('.product-tags-heading')
		const productPricesHeading  = document.querySelector('.product-prices-heading')

		console.log(api)

		api('wc_product_cats_heading', function (value) {
			console.log(value)

			value.bind(function (newValue) {
				const productCatsHeading = document.querySelector('.product-cats-heading')

				console.log(newValue)

				if (productCatsHeading) {
					console.log(productCatsHeading)
					productCatsHeading.textContent = newValue
				}
			})
		})
	})
})(wp.customize)





//window.addEventListener(
//	'load',
//	() => init(),
//)
