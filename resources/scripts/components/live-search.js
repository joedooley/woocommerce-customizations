import debounce from 'lodash-es/debounce'
import Isotope from 'isotope-layout'



let qsRegex
let isotope
let filters = {}


const removePrefix = string => {
	return string.startsWith('.product_')
	       ? string.slice(13).replace('-', ' ')
	       : string
}


const addTag = (elementId, html) => {
	const id = elementId.replace('.', '')
	const tag = document.createElement('a')
	const closeButton = document.createElement('span')
	const container = document.querySelector('.wc-isotope-active-filters')

	if (!container) {
		return false
	}

	tag.href = '#'
	tag.innerHTML = removePrefix(html)
	tag.setAttribute('id', id)

	closeButton.innerText = 'x'
	closeButton.setAttribute('data-filter', elementId)

	tag.appendChild(closeButton)
	container.appendChild(tag)
}


const removeTag = id => {
	id = id.replace('.', '')

	const el = document.querySelector(`.wc-isotope-active-filters #${id}`)
	if (el) {
		el.parentNode.removeChild(el)
	}
}


const filterFns = {
	upTo50 (arg1, el) {
		const priceEl = el.querySelector('.amount')
		const salePriceEl = el.querySelector('.price ins .amount')

		if (!priceEl && !salePriceEl) {
			return false
		}

		const price     = parseInt(priceEl.innerText, 10)
		const salePrice = salePriceEl ? parseInt(salePriceEl.innerText, 10) : false
		const amount    = salePrice ? salePrice : price

		return amount > 0 && amount <= 50
	},

	between50and100 (arg1, el) {
		const priceEl = el.querySelector('.amount')
		const salePriceEl = el.querySelector('.price ins .amount')

		if (!priceEl && !salePriceEl) {
			return false
		}

		const price = parseInt(priceEl.innerText, 10)
		const salePrice = salePriceEl ? parseInt(salePriceEl.innerText, 10) : false
		const amount    = salePrice ? salePrice : price

		return amount >= 50 && amount <= 100
	},

	between100and250 (arg1, el) {
		const priceEl = el.querySelector('.amount')
		const salePriceEl = el.querySelector('.price ins .amount')

		if (!priceEl && !salePriceEl) {
			return false
		}

		const price = parseInt(priceEl.innerText, 10)
		const salePrice = salePriceEl ? parseInt(salePriceEl.innerText, 10) : false
		const amount    = salePrice ? salePrice : price

		return amount >= 100 && amount <= 250
	},

	between250and500 (arg1, el) {
		const priceEl = el.querySelector('.amount')
		const salePriceEl = el.querySelector('.price ins .amount')

		if (!priceEl && !salePriceEl) {
			return false
		}

		const price = parseInt(priceEl.innerText, 10)
		const salePrice = salePriceEl ? parseInt(salePriceEl.innerText, 10) : false
		const amount    = salePrice ? salePrice : price

		return amount >= 250 && amount <= 500
	},

	greaterThan500 (arg1, el) {
		const priceEl = el.querySelector('.amount')
		const salePriceEl = el.querySelector('.price ins .amount')

		if (!priceEl && !salePriceEl) {
			return false
		}

		const price = parseInt(priceEl.innerText, 10)
		const salePrice = salePriceEl ? parseInt(salePriceEl.innerText, 10) : false
		const amount    = salePrice ? salePrice : price

		return amount >= 500
	}
}


let initSearch = function () {
	const quicksearch = document.querySelector('#wc-isotope-search')

	if (!quicksearch) {
		return
	}

	quicksearch.addEventListener('keyup', debounce(() => {
		qsRegex = new RegExp(quicksearch.value, 'gi')

		isotope.arrange({
			filter: function (arg1, el) {
				const title = el.querySelector('.woocommerce-loop-product__title')

				return qsRegex ? title.innerText.match(qsRegex) : true
			}
		})
	}, 200))
}


function radioButtonGroup (filterLinkGroup) {
	filterLinkGroup.addEventListener('click', function (event) {
		console.log(event)
		console.log(event.target)
		console.log(filterLinkGroup)

		if (!matchesSelector(event.target, 'a')) {
			return
		}

		event.preventDefault()

//		if (filterLinkGroup.querySelector('.is-checked').classList.contains('is-checked')) {
//			filterLinkGroup.querySelector('.is-checked').classList.remove('is-checked')
//		}

		event.target.classList.add('is-checked')
	})
}


const toggleIsCheckedClass = () => {
	const filterLinkGroups = document.querySelectorAll('.filter-link-group')

	if (!filterLinkGroups || !filterLinkGroups.length) {
		return
	}

	filterLinkGroups.forEach(group => {
		radioButtonGroup(group)
	})
}


const toggleIsHiddenClassForFilters = () => {
	const toggle = document.querySelector('#filter-toggle')

	if (!toggle) {
		return
	}

	toggle.addEventListener('click', function (event) {
		const icon = toggle.querySelector('#arrow-icon')
		const target = document.querySelector('.wc-isotope-filters')

		event.preventDefault()

		this.__toggle = !this.__toggle

		if (!target) {
			return
		}

		if (this.__toggle) {
			target.classList.remove('is-hidden')
			this.classList.add('active')
			icon.classList.remove('fa-caret-down')
			icon.classList.add('fa-caret-up')
		} else {
			target.classList.add('is-hidden')
			this.classList.remove('active')
			icon.classList.remove('fa-caret-up')
			icon.classList.add('fa-caret-down')
		}
	})
}


const setup = () => {
	const inclusives = []
	const el = document.querySelector('ul.products')
	const filterLinkGroups = document.querySelectorAll('.filter-link-group')

	if (!filterLinkGroups || !filterLinkGroups.length || !el) {
		return
	}

	isotope = new Isotope(el, { itemSelector: '.product' })

	filterLinkGroups.forEach(group => {
		group.addEventListener('click', function (event) {
			console.log(event)
			console.log(event.target)

			if (!matchesSelector(event.target, 'a')) {
				return
			}

			let filterValue = event.target.getAttribute('data-filter')
			console.log(filterValue)
			console.log(inclusives)

			if (!inclusives.includes(filterValue)) {
				inclusives.push(filterValue)
				addTag(filterValue, filterValue)
			} else {
				inclusives.splice(filterValue.indexOf(filterValue), 1)
				removeTag(filterValue)
			}

			console.log(inclusives)

			filterValue = inclusives.length ? inclusives.join(', ') : '*'
			filterValue = filterFns[filterValue] || filterValue

			console.log(filterValue)

			isotope.arrange({ filter: filterValue })
		})
	})
}



export const initIsotope = () => {
	setup()
	initSearch()
	toggleIsCheckedClass()
	toggleIsHiddenClassForFilters()
}
