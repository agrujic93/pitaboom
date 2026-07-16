/**
 * File main.js.
 *
 * @package ci-uikit
 */

jQuery( document ).ready(
	function ($) {

		$('body').addClass('loaded');

		//hamburger-menu
		$('.open-menu').click(function(e){
			e.preventDefault();
			if($(this).hasClass('open')) {
				$(this).removeClass('open');
				$('.navigation-wrp').removeClass('open');
				$('body').removeClass('uk-overflow-hidden');
			} else {
				$(this).addClass('open');
				$('.navigation-wrp').addClass('open');
				$('body').addClass('uk-overflow-hidden');
			}
		});

		// Stop many submits.
		$( '.wpcf7-submit' ).on(
			'click',
			function () {
				$( this ).css( 'pointer-events','none' );
			}
		);
		document.addEventListener(
			'wpcf7submit',
			function ( event ) {
				$( '.wpcf7-submit' ).css( 'pointer-events','' );
			},
			false
		);

	}
);


document.addEventListener("DOMContentLoaded", function() {
	const lazyImages = document.querySelectorAll('.lazy-blur');

  	if ('IntersectionObserver' in window) {
		const imageObserver = new IntersectionObserver((entries, observer) => {
			entries.forEach(entry => {
        		if (entry.isIntersecting) {
					const img = entry.target;

					const nextSrc = img.dataset.src || '';
					const nextSrcset = img.dataset.srcset || '';
					const nextSizes = img.dataset.sizes || '';

					if (!nextSrc && !nextSrcset) {
						img.classList.add('loaded');
						observer.unobserve(img);
						return;
					}

					const applySource = () => {
						if (nextSrcset) img.srcset = nextSrcset;
						if (nextSizes) img.sizes = nextSizes;
						if (nextSrc) img.src = nextSrc;
						img.classList.add('loaded');
					};

					const preloader = new Image();
					if (nextSrcset) preloader.srcset = nextSrcset;
					if (nextSizes) preloader.sizes = nextSizes;
					if (nextSrc) preloader.src = nextSrc;

					let hasSwapped = false;
					const finalizeSwap = () => {
						if (hasSwapped) return;
						hasSwapped = true;
						applySource();
					};

					preloader.onload = finalizeSwap;
					preloader.onerror = finalizeSwap;

					if (typeof preloader.decode === 'function') {
						preloader.decode().then(finalizeSwap).catch(() => {
							if (preloader.complete) {
								finalizeSwap();
							}
						});
					} else {
						if (preloader.complete) {
							finalizeSwap();
						}
					}
					observer.unobserve(img);
        		}
      		}
		);
    }, {
      rootMargin: "-150px 0px" 
    });

    	lazyImages.forEach(img => imageObserver.observe(img));
  	}
});
