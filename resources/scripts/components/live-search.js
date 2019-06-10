import Isotope  from 'isotope-layout'
import MatchHeight from 'matchheight'
import max from 'lodash-es/max'
import debounce from 'lodash-es/debounce'



let isotope
let items = false
let filters = []
let inclusives = []


const removePrefix = string => {
	return string.startsWith('.product_')
	       ? string.slice(13).replace(/-/g, ' ')
	       : string
}


const formatTag = string => {
	let value = ''

	if (string.startsWith('.product_')) {
		value = removePrefix(string)
	} else if (string === '.upTo50') {
		value = '$0 - $50'
	} else if (string === '.between50and100') {
		value = '$50 - $100'
	} else if (string === '.between100and250') {
		value = '$100 - $250'
	} else if (string === '.between250and500') {
		value = '$250 - $500'
	} else if (string === '.greaterThan500') {
		value = '$500 and up'
	}

	return value
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
	tag.innerHTML = formatTag(html)
	tag.setAttribute('id', id)

	closeButton.innerText = 'x'
	closeButton.setAttribute('data-filter', elementId)

	tag.appendChild(closeButton)

	tag.addEventListener('click', function (event) {
		event.preventDefault()

		inclusives = inclusives.filter(value => value !== elementId)
		removeTag(tag.id)

		filters['links'] = inclusives.length ? inclusives.join('') : ''

		runFilter()
	})

	container.appendChild(tag)
}


const removeTag = id => {
	id = id.replace('.', '')
	const el = document.querySelector(`.wc-isotope-active-filters #${id}`)
	const matchingFilterEl = document.querySelector(`.filter-link-group #${id}`)

	if (el) {
		el.parentNode.removeChild(el)
		matchingFilterEl.classList.remove('is-checked')
	}
}


const initSearch = () => {
	const clearButtons = document.querySelectorAll('.wc-isotope-search .clear')
	const quicksearches = document.querySelectorAll('#wc-isotope-search')

	if (!quicksearches || !quicksearches.length || !clearButtons || !clearButtons.length) {
		return
	}

	quicksearches.forEach(quicksearch => {
		quicksearch.addEventListener('keyup', debounce(() => {
			filters['search'] = quicksearch.value.toLowerCase()

			runFilter()
		}, 200))
	})

	clearButtons.forEach(clearButton => {
		clearButton.addEventListener('click', () => {
			quicksearches.forEach(quicksearch => {
				quicksearch.value = ''
			})

			filters['search'] = ''

			runFilter()
		})
	})
}


const initSort = () => {
	const selects = document.querySelectorAll('#wc-isotope-sort')

	if (!selects || !selects.length) {
		return
	}

	selects.forEach(select => {
		select.addEventListener('change', event => {
			const sortValue = event.target.value

			isotope.arrange({ sortBy: sortValue })
		})
	})

	MatchHeight.update()
}


const runFilter = () => {
	isotope.arrange({
		filter: function (el) {
			const rawFilterValue = filters['links']
			const filterValues = rawFilterValue ? rawFilterValue.replace('.', '') : false
			const searchValue = filters['search'] && filters['search'] !== '' ? filters['search'] : false

			if (searchValue) {
				const titleEl = el.querySelector('.woocommerce-loop-product__title')
				const title = titleEl ? titleEl.innerText.toLowerCase() : false

				if (!title) {
					return false
				}

				if (!title.includes(searchValue)) {
					return false
				}
			}

			if (filterValues) {
				let isMatch = true

				inclusives.forEach(value => {
					const cssClass = value.replace('.', '')

					if (!el.classList.contains(cssClass)) {
						isMatch = false
					}
				})

				return isMatch
			}

			return true
		}
	})

	MatchHeight.update()
}


const updateProductTitleHeights = () => {
	const heights = []
	const productTitleEls = document.querySelectorAll('.woocommerce-loop-product__title')

	if (!productTitleEls || !productTitleEls.length) {
		return
	}

	productTitleEls.forEach(function (el) {
		const currentHeight = el.clientHeight

		if (!heights.includes(currentHeight)) {
			heights.push(currentHeight)
		}
	})

	const maxHeight = max(heights)
	document.documentElement.style.setProperty('--product-title-min-height', `${maxHeight}px`)
}


const toggleIsCheckedClass = () => {
	const filterLinks = document.querySelectorAll('.filter-link')

	if (!filterLinks || !filterLinks.length) {
		return
	}

	filterLinks.forEach(link => {
		link.addEventListener('click', function (event) {
			event.preventDefault()

			if (link.classList.contains('is-checked')) {
				link.classList.remove('is-checked')
			} else {
				link.classList.add('is-checked')
			}
		})
	})
}


const toggleIsHiddenClassForFilters = () => {
	const toggles = document.querySelectorAll('#filter-toggle')

	if (!toggles || !toggles.length) {
		return
	}

	toggles.forEach(toggle => {
		toggle.addEventListener('click', function (event) {
			const target = document.querySelector('.wc-isotope-filters')

			event.preventDefault()

			this.__toggle = !this.__toggle

			if (!target) {
				return
			}

			if (this.__toggle) {
				target.classList.remove('is-hidden')
				this.classList.add('active')
			} else {
				target.classList.add('is-hidden')
				this.classList.remove('active')
			}
		})
	})
}


const updateEachCounts = () => {
	const filterLinks = document.querySelectorAll('.filter-link')

	if (!filterLinks || !filterLinks.length) {
		return
	}

	filterLinks.forEach(link => {
		const count = link.querySelector('.count')
		const filterValue = link.getAttribute('data-filter')
		const formattedFilterValue = filterValue.replace('.', '')
		const total = items.filter(({ element }) => element.classList.contains(formattedFilterValue)).length

		if (total === 0) {
			link.classList.add('disabled')
		}

		if (total !== 0 && link.classList.contains('disabled')) {
			link.classList.remove('disabled')
		}

		count.innerText = total
	})
}


const updateTotalCount = () => {
	const el = document.querySelector('.woocommerce-result-count')

	if (!el) {
		return
	}

	isotope.on('arrangeComplete', filteredItems => {
		items       = filteredItems
		const total = items.length

		el.innerText = `Showing all ${ total } results`
		updateEachCounts()
	})
}


const setup = () => {
	const el = document.querySelector('ul.products')
	const filterLinkGroups = document.querySelectorAll('.filter-link-group')

	if (!filterLinkGroups || !filterLinkGroups.length || !el) {
		return
	}

	isotope = new Isotope(el, {
		itemSelector: '.product',
		percentPosition: true,
		masonry: { columnWidth: '.product' },
		isJQueryFiltering: false,
		layoutMode: 'fitRows',
		horizontalOrder: true,
		stagger: 30,
		getSortData: {
			name:     '.woocommerce-loop-product__title',
			nameDesc: '.woocommerce-loop-product__title',
			price (el) {
				const priceEl     = el.querySelector('.woocommerce-Price-amount')
				const salePriceEl = el.querySelector('ins .woocommerce-Price-amount')

				 if (!priceEl && !salePriceEl) {
				    return 0
				 }

				const price     = parseInt(priceEl.innerText.replace(',', '.'), 10)
				const salePrice = salePriceEl ? parseInt(salePriceEl.innerText.replace(',', '.'), 10) : false
				const amount = salePrice ? salePrice : price

				return amount
			},
			priceDesc (el) {
				const priceEl = el.querySelector('.woocommerce-Price-amount')
				const salePriceEl = el.querySelector('ins .woocommerce-Price-amount')

				if (!priceEl && !salePriceEl) {
					return 0
				}

				const price     = parseInt(priceEl.innerText.replace(',', '.'), 10)
				const salePrice = salePriceEl ? parseInt(salePriceEl.innerText.replace(',', '.'), 10) : false
				const amount = salePrice ? salePrice : price

				return amount
			},
		},
		sortAscending: {
			name:      true,
			nameDesc:  false,
			price:     true,
			priceDesc: false,
		},
	})

	if (!items) {
		items = isotope.filteredItems
	}

	filterLinkGroups.forEach(group => {
		group.addEventListener('click', function (event) {
			if (!matchesSelector(event.target, 'a')) {
				return
			}

			let filterValue = event.target.getAttribute('data-filter')

			if (!inclusives.includes(filterValue)) {
				inclusives.push(filterValue)
				addTag(filterValue, filterValue)
			} else {
				inclusives = inclusives.filter(value => value !== filterValue)
				removeTag(filterValue)
			}

			filterValue = inclusives.length ? inclusives.join('') : ''
			filters['links'] = filterValue

			runFilter()
		})
	})

	updateTotalCount()
}



export const initIsotope = () => {
	updateProductTitleHeights()
	MatchHeight.init()
	setup()
	initSearch()
	initSort()
	toggleIsCheckedClass()
	toggleIsHiddenClassForFilters()
	updateEachCounts()
}
