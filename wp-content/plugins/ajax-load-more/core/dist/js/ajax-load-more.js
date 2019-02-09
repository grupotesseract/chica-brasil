'use strict';

if (!Array.from) {
  Array.from = function () {
    var toStr = Object.prototype.toString;
    var isCallable = function isCallable(fn) {
      return typeof fn === 'function' || toStr.call(fn) === '[object Function]';
    };
    var toInteger = function toInteger(value) {
      var number = Number(value);
      if (isNaN(number)) {
        return 0;
      }
      if (number === 0 || !isFinite(number)) {
        return number;
      }
      return (number > 0 ? 1 : -1) * Math.floor(Math.abs(number));
    };
    var maxSafeInteger = Math.pow(2, 53) - 1;
    var toLength = function toLength(value) {
      var len = toInteger(value);
      return Math.min(Math.max(len, 0), maxSafeInteger);
    };

    // The length property of the from method is 1.
    return function from(arrayLike /*, mapFn, thisArg */) {
      // 1. Let C be the this value.
      var C = this;

      // 2. Let items be ToObject(arrayLike).
      var items = Object(arrayLike);

      // 3. ReturnIfAbrupt(items).
      if (arrayLike == null) {
        throw new TypeError('Array.from requires an array-like object - not null or undefined');
      }

      // 4. If mapfn is undefined, then let mapping be false.
      var mapFn = arguments.length > 1 ? arguments[1] : void undefined;
      var T;
      if (typeof mapFn !== 'undefined') {
        // 5. else
        // 5. a If IsCallable(mapfn) is false, throw a TypeError exception.
        if (!isCallable(mapFn)) {
          throw new TypeError('Array.from: when provided, the second argument must be a function');
        }

        // 5. b. If thisArg was supplied, let T be thisArg; else let T be undefined.
        if (arguments.length > 2) {
          T = arguments[2];
        }
      }

      // 10. Let lenValue be Get(items, "length").
      // 11. Let len be ToLength(lenValue).
      var len = toLength(items.length);

      // 13. If IsConstructor(C) is true, then
      // 13. a. Let A be the result of calling the [[Construct]] internal method
      // of C with an argument list containing the single item len.
      // 14. a. Else, Let A be ArrayCreate(len).
      var A = isCallable(C) ? Object(new C(len)) : new Array(len);

      // 16. Let k be 0.
      var k = 0;
      // 17. Repeat, while k < len… (also steps a - h)
      var kValue;
      while (k < len) {
        kValue = items[k];
        if (mapFn) {
          A[k] = typeof T === 'undefined' ? mapFn(kValue, k) : mapFn.call(T, kValue, k);
        } else {
          A[k] = kValue;
        }
        k += 1;
      }
      // 18. Let putStatus be Put(A, "length", len, true).
      A.length = len;
      // 20. Return A.
      return A;
    };
  }();
}

var almGetParameterByName = function almGetParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
      results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
};
'use strict';

/*
	almGetAjaxParams
	Build the data object to send with the Ajax request

   @param alm            object
   @param action         string
   @param queryType      string  
   
   @since 3.6
*/

var almGetAjaxParams = function almGetAjaxParams(alm, action, queryType) {

   // Defaults
   var data = {
      action: action,
      nonce: alm_localize.alm_nonce,
      query_type: queryType,
      id: alm.id,
      post_id: alm.post_id,
      slug: alm.slug,
      canonical_url: alm.canonical_url,
      posts_per_page: alm.posts_per_page,
      page: alm.page,
      offset: alm.offset,
      post_type: alm.post_type,
      repeater: alm.repeater,
      seo_start_page: alm.start_page
   };

   // Addons
   if (alm.theme_repeater) {
      data.theme_repeater = alm.theme_repeater;
   }
   if (alm.paging) {
      data.paging = alm.paging;
   }
   if (alm.preloaded) {
      data.preloaded = alm.preloaded;
      data.preloaded_amount = alm.preloaded_amount;
   }
   if (alm.cache === 'true') {
      data.cache_id = alm.cache_id;
      data.cache_logged_in = alm.cache_logged_in;
   }
   if (alm.acf_array) {
      data.acf = alm.acf_array;
   }
   if (alm.cta_array) {
      data.cta = alm.cta_array;
   }
   if (alm.comments_array) {
      data.comments = alm.comments_array;
   }
   if (alm.nextpage_array) {
      data.nextpage = alm.nextpage_array;
   }
   if (alm.single_post_array) {
      data.single_post = alm.single_post_array;
   }
   if (alm.users_array) {
      data.users = alm.users_array;
   }

   // Query data   
   if (alm.content.attr('data-lang')) {
      data.lang = alm.content.attr('data-lang');
   }
   if (alm.content.attr('data-sticky-posts')) {
      data.sticky_posts = alm.content.attr('data-sticky-posts');
   }
   if (alm.content.attr('data-post-format')) {
      data.post_format = alm.content.attr('data-post-format');
   }
   if (alm.content.attr('data-category')) {
      data.category = alm.content.attr('data-category');
   }
   if (alm.content.attr('data-category-and')) {
      data.category__and = alm.content.attr('data-category-and');
   }
   if (alm.content.attr('data-category-not-in')) {
      data.category__not_in = alm.content.attr('data-category-not-in');
   }
   if (alm.content.attr('data-tag')) {
      data.tag = alm.content.attr('data-tag');
   }
   if (alm.content.attr('data-tag-and')) {
      data.tag__and = alm.content.attr('data-tag-and');
   }
   if (alm.content.attr('data-tag-not-in')) {
      data.tag__not_in = alm.content.attr('data-tag-not-in');
   }
   if (alm.content.attr('data-taxonomy')) {
      data.taxonomy = alm.content.attr('data-taxonomy');
   }
   if (alm.content.attr('data-taxonomy-terms')) {
      data.taxonomy_terms = alm.content.attr('data-taxonomy-terms');
   }
   if (alm.content.attr('data-taxonomy-operator')) {
      data.taxonomy_operator = alm.content.attr('data-taxonomy-operator');
   }
   if (alm.content.attr('data-taxonomy-relation')) {
      data.taxonomy_relation = alm.content.attr('data-taxonomy-relation');
   }
   if (alm.content.attr('data-meta-key')) {
      data.meta_key = alm.content.attr('data-meta-key');
   }
   if (alm.content.attr('data-meta-value')) {
      data.meta_value = alm.content.attr('data-meta-value');
   }
   if (alm.content.attr('data-meta-compare')) {
      data.meta_compare = alm.content.attr('data-meta-compare');
   }
   if (alm.content.attr('data-meta-relation')) {
      data.meta_relation = alm.content.attr('data-meta-relation');
   }
   if (alm.content.attr('data-meta-type')) {
      data.meta_type = alm.content.attr('data-meta-type');
   }
   if (alm.content.attr('data-author')) {
      data.author = alm.content.attr('data-author');
   }
   if (alm.content.attr('data-year')) {
      data.year = alm.content.attr('data-year');
   }
   if (alm.content.attr('data-month')) {
      data.month = alm.content.attr('data-month');
   }
   if (alm.content.attr('data-day')) {
      data.day = alm.content.attr('data-day');
   }
   if (alm.content.attr('data-order')) {
      data.order = alm.content.attr('data-order');
   }
   if (alm.content.attr('data-orderby')) {
      data.orderby = alm.content.attr('data-orderby');
   }
   if (alm.content.attr('data-post-status')) {
      data.post_status = alm.content.attr('data-post-status');
   }
   if (alm.content.attr('data-post-in')) {
      data.post__in = alm.content.attr('data-post-in');
   }
   if (alm.content.attr('data-post-not-in')) {
      data.post__not_in = alm.content.attr('data-post-not-in');
   }
   if (alm.content.attr('data-exclude')) {
      data.exclude = alm.content.attr('data-exclude');
   }
   if (alm.content.attr('data-search')) {
      data.search = alm.content.attr('data-search');
   }
   if (alm.content.attr('data-s')) {
      data.search = alm.content.attr('data-s');
   }
   if (alm.content.attr('data-custom-args')) {
      data.custom_args = alm.content.attr('data-custom-args');
   }

   return data;
};

/*
	almGetRestParams
	Build the REST API data object to send with REST API request

   @param alm            object
   
   @since 3.6
*/
var almGetRestParams = function almGetRestParams(alm) {
   var data = {
      id: alm.id,
      post_id: alm.post_id,
      posts_per_page: alm.posts_per_page,
      page: alm.page,
      offset: alm.offset,
      slug: alm.slug,
      canonical_url: alm.canonical_url,
      post_type: alm.post_type,
      post_format: alm.content.attr('data-post-format'),
      category: alm.content.attr('data-category'),
      category__not_in: alm.content.attr('data-category-not-in'),
      tag: alm.content.attr('data-tag'),
      tag__not_in: alm.content.attr('data-tag-not-in'),
      taxonomy: alm.content.attr('data-taxonomy'),
      taxonomy_terms: alm.content.attr('data-taxonomy-terms'),
      taxonomy_operator: alm.content.attr('data-taxonomy-operator'),
      taxonomy_relation: alm.content.attr('data-taxonomy-relation'),
      meta_key: alm.content.attr('data-meta-key'),
      meta_value: alm.content.attr('data-meta-value'),
      meta_compare: alm.content.attr('data-meta-compare'),
      meta_relation: alm.content.attr('data-meta-relation'),
      meta_type: alm.content.attr('data-meta-type'),
      author: alm.content.attr('data-author'),
      year: alm.content.attr('data-year'),
      month: alm.content.attr('data-month'),
      day: alm.content.attr('data-day'),
      post_status: alm.content.attr('data-post-status'),
      order: alm.content.attr('data-order'),
      orderby: alm.content.attr('data-orderby'),
      post__in: alm.content.attr('data-post-in'),
      post__not_in: alm.content.attr('data-post-not-in'),
      search: alm.content.attr('data-search'),
      custom_args: alm.content.attr('data-custom-args'),
      lang: alm.lang,
      preloaded: alm.preloaded,
      preloaded_amount: alm.preloaded_amount,
      seo_start_page: alm.start_page
   };
   return data;
};
"use strict";

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var alm_is_filtering = false; // Global Masonry/Filtering var

(function ($) {

	/* $.fn.almFilter(type, speed, data)
  * 
  *  Filter Ajax Load More
  *
  *  @param transition string;
  *  @param speed number;
  *  @param data obj;
  *  @since 2.6.1
  */
	$.fn.almFilter = function (transition, speed, data) {
		if (data.target) {
			// if a target has been specified
			$(".ajax-load-more-wrap[data-id='" + data.target + "']").each(function (e) {
				var el = $(this);
				$.fn.almFilterTransition(transition, speed, data, el);
			});
		} else {
			// Target not specified
			$(".ajax-load-more-wrap").each(function (e) {
				var el = $(this);
				$.fn.almFilterTransition(transition, speed, data, el);
			});
		}
	};

	/* $.fn.almFilterTransition(transition, speed, data, el)
  *
  *  Transition Ajax Load More
  *
  *  @param transition string;
  *  @param speed number;
  *  @param data obj;
  *  @param el element;
  *  @since 2.13.1
  */
	$.fn.almFilterTransition = function (transition, speed, data, el) {
		if (transition === 'slide') {
			// Slide transition
			el.slideUp(speed, function () {
				almCompleteFilterTransition(speed, data, el);
			});
		} else if (transition === 'fade' || transition === 'masonry') {
			// Fade, Masonry transition
			el.fadeOut(speed, function () {
				almCompleteFilterTransition(speed, data, el);
			});
		} else {
			// No transition
			almCompleteFilterTransition(speed, data, el);
		}
	};

	/*  almCompleteFilterTransition
  *  Complete the filter transition
  * 
  *  @param speed number;
  *  @param data obj;
  *  @param el element;
  *  @since 3.3
  */
	var almCompleteFilterTransition = function almCompleteFilterTransition(speed, data, el) {
		var container = el.get(0);
		var listing = container.querySelectorAll('.alm-listing');
		// Loop over all .alm-listing divs
		[].concat(_toConsumableArray(listing)).forEach(function (e) {
			e.innerHTML = ''; // Clear listings
		});
		var button = container.querySelector('.alm-load-more-btn');
		if (button) {
			button.classList.remove('done'); // Reset Button 
		}
		almSetFilters(speed, data, el);
	};

	/*  almSetFilters
  *  Set filter parameters on .alm-listing element
  *
  *  @param speed number;
  *  @param el element;
  *  @param data string;
  *  @updated 3.3
  *  @since 2.6.1
  */
	var almSetFilters = function almSetFilters(speed, data, el) {

		// Update data attributes
		$.each(data, function (key, value) {
			key = key.replace(/\W+/g, '-').replace(/([a-z\d])([A-Z])/g, '$1-$2'); // Convert camelCase data() object back to dash (-)
			$('.alm-listing', el).attr('data-' + key, value);
		});

		el.fadeIn(speed); // Fade ALM back in

		alm_is_filtering = true;

		// re-initiate Ajax Load More
		if (data.target) {
			// if a target has been specified
			$(".ajax-load-more-wrap[data-id=" + data.target + "]").ajaxloadmore();
		} else {
			// Target not specified
			$(".ajax-load-more-wrap").ajaxloadmore();
		}
	};
})(jQuery);
'use strict';

/*
	almMasonry
	Function to trigger built-in Ajax Load More Masonry

   @param container        object
   @param items            object
   @param selector         string
   @param columnWidth      string
   @param animation        string
   @param horizontalOrder  string
   @param speed            int
   @param masonry_init     boolean
   @param init             boolean
   @param filtering        boolean   
   
   @since 3.1
   @updated 3.3.2
*/

var almMasonry = function almMasonry(container, items, selector, columnWidth, animation, horizontalOrder, speed, masonry_init, init, filtering) {

  var duration = (speed + 100) / 1000 + 's'; // Add 100 for some delay
  var hidden = 'scale(0.5)';
  var visible = 'scale(1)';

  if (animation === 'zoom-out') {
    hidden = 'translateY(-20px) scale(1.25)';
    visible = 'translateY(0) scale(1)';
  }

  if (animation === 'slide-up') {
    hidden = 'translateY(50px)';
    visible = 'translateY(0)';
  }

  if (animation === 'slide-down') {
    hidden = 'translateY(-50px)';
    visible = 'translateY(0)';
  }

  if (animation === 'none') {
    hidden = 'translateY(0)';
    visible = 'translateY(0)';
  }

  // columnWidth
  if (columnWidth) {
    if (!isNaN(columnWidth)) {
      // Check if number
      columnWidth = parseInt(columnWidth);
    }
  } else {
    // No columnWidth, use the selector
    columnWidth = selector;
  }

  // horizontalOrder
  horizontalOrder = horizontalOrder === 'true' ? true : false;

  if (!filtering) {

    // First Run
    if (masonry_init && init) {
      container.imagesLoaded(function () {

        var defaults = {
          itemSelector: selector,
          transitionDuration: duration,
          columnWidth: columnWidth,
          horizontalOrder: horizontalOrder,
          hiddenStyle: {
            transform: hidden,
            opacity: 0
          },
          visibleStyle: {
            transform: visible,
            opacity: 1
          }

          // Get custom Masonry options (https://masonry.desandro.com/options.html)
        };var alm_masonry_vars = window.alm_masonry_vars;
        if (alm_masonry_vars) {
          Object.keys(alm_masonry_vars).forEach(function (key) {
            // Loop object	to create key:prop			
            defaults[key] = alm_masonry_vars[key];
          });
        }

        // Trigger Masonry()		
        container.masonry(defaults);

        // Fade in
        almMasonryFadeIn(container[0].parentNode, speed);
      });
    }

    // Standard
    else {
        items.imagesLoaded(function () {
          container.append(items).masonry('appended', items);
        });
      }
  } else {
    // Filtering Reset
    container.masonry('destroy'); // destroy masonry
    container[0].parentNode.style.opacity = 0;
    container.append(items);
    almMasonry(container, items, selector, columnWidth, animation, horizontalOrder, speed, true, true, false);
  }
};

// Fade in masonry on initial page load
var almMasonryFadeIn = function almMasonryFadeIn(element, speed) {
  speed = speed / 10;
  var op = parseInt(element.style.opacity); // initial opacity
  var timer = setInterval(function () {
    if (op > 0.9) {
      element.style.opacity = 1;
      clearInterval(timer);
    }
    element.style.opacity = op;
    op += 0.1;
  }, speed);
};
'use strict';

/**  
 *  Set the results text if required.
 * 
 *  @param {Object} alm
 *  @since 4.1
 */
var almResultsText = function almResultsText(alm) {
   if (!alm.resultsText) return false;

   var resultsType = 'standard';
   if (alm.nextpage && alm.resultsText) {
      resultsType = 'nextpage';
   } else if (alm.paging) {
      resultsType = 'paging';
   } else if (alm.preloaded === 'true') {
      resultsType = 'preloaded';
   } else {
      resultsType = 'standard';
   }
   almGetResultsText(alm, resultsType);
};

/**  
 *  Render `Showing {x} of {y} results` text.
 * 
 *  @param {Element} el
 *  @param {String} current
 *  @param {String} total
 *  @since 4.1
 */
var almRenderResultsText = function almRenderResultsText(el, current, total) {
   var text = alm_localize.display_results;
   text = text.replace('{num}', current);
   text = text.replace('{total}', total);
   el.innerHTML = text;
};

/**  
 *  Get values for showing results text.
 * 
 *  @param {Object} alm
 *  @param {String} type
 *  @since 4.1
 */
var almGetResultsText = function almGetResultsText(alm) {
   var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'standard';


   if (!alm.resultsText) return false;

   var current = 0;
   var total = 0;

   switch (type) {

      // Nextpage
      case 'nextpage':

         current = alm.page + 1 + parseInt(alm.nextpage_startpage);
         total = parseInt(alm.totalposts) + parseInt(alm.nextpage_startpage);
         almRenderResultsText(alm.resultsText, current, total);

         break;

      // Preloaded
      case 'preloaded':

         console.log(alm);

         current = parseInt(alm.posts) + parseInt(alm.preloaded_amount);
         total = parseInt(alm.totalposts) + parseInt(alm.preloaded_amount);
         almRenderResultsText(alm.resultsText, current, total);

         break;

      // Paging
      case 'paging':

         var start = parseInt(alm.page) * parseInt(alm.posts_per_page) + 1;
         current = start + ' - ' + (parseInt(start) - 1 + parseInt(alm.posts));
         total = parseInt(alm.totalposts) + parseInt(alm.preloaded_amount);
         almRenderResultsText(alm.resultsText, current, total);

         break;

      default:

         current = alm.posts;
         total = parseInt(alm.totalposts);
         almRenderResultsText(alm.resultsText, current, total);

   }
};

/**  
 *  Display `Showing {x} of {y} results` text.
 *
 *  @param {Object} alm
 *  @param {String} type
 *  @since 4.1
 */
var almInitResultsText = function almInitResultsText(alm) {
   var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'standard';


   if (!alm.resultsText) return false;

   var current = 0;
   var total = 0;
   var totalEl = '';

   switch (type) {

      // Nextpage
      case 'nextpage':

         current = alm.page + parseInt(alm.nextpage_startpage);
         total = alm.localize.total_posts;
         if (total) {
            almRenderResultsText(alm.resultsText, current, total);
         }

         break;

      // Preloaded
      case 'preloaded':

         current = parseInt(alm.posts) + parseInt(alm.preloaded_amount);
         total = alm.localize.total_posts;
         if (total) {
            almRenderResultsText(alm.resultsText, current, total);
         }

         break;

      // Paging
      case 'paging':

         var start = parseInt(alm.page) * parseInt(alm.posts_per_page) + 1;
         current = start + ' - ' + (parseInt(start) - 1 + parseInt(alm.posts_per_page));
         totalEl = alm.container.get(0).querySelector('.alm-preloaded');
         if (totalEl) {
            almRenderResultsText(alm.resultsText, current, totalEl.dataset.totalPosts);
         }

         break;

      default:

         console.log('nothing');

   }
};
'use strict';

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

/*
 * Ajax Load More
 * http://wordpress.org/plugins/ajax-load-more/
 * https://connekthq.com/plugins/ajax-load-more/
 *
 * Copyright 2019 Connekt Media - https://connekthq.com
 * Free to use under the GPLv2 license. 
 * http://www.gnu.org/licenses/gpl-2.0.html 
 *
 * Author: Darren Cooney
 * Twitter: @KaptonKaos, @ajaxloadmore, @connekthq 
 */

(function ($) {

   "use strict";

   var ajaxloadmore = function ajaxloadmore(el, e) {

      // Prevent loading of unnessasry posts - move user to top of page
      if (alm_localize.scrolltop === 'true') {
         window.scrollTo(0, 0);
      }

      //Set ALM Variables
      var alm = this;
      alm.AjaxLoadMore = {};
      alm.window = window;
      alm.timer = '';
      alm.page = 0;
      alm.posts = 0;
      alm.totalposts = 0;
      alm.proceed = false;
      alm.disable_ajax = false;
      alm.init = true;
      alm.loading = true;
      alm.finished = false;
      alm.el = $(el);
      alm.container = $(el);
      alm.master_id = el.id; // the div#id of the instance 
      el.classList.add('alm-' + e); // Add unique classname
      el.setAttribute('data-alm-id', e); // Add unique data id

      // Get localized <script/> variables
      alm.master_id = alm.master_id.replace(/-/g, '_'); // Convert dashes to underscores for the var name
      alm.localize = window[alm.master_id + '_vars']; // Get localize vars

      // Main ALM Containers
      var container = el; // Get DOM element
      alm.listing = container.querySelector('.alm-listing') || container.querySelector('.alm-comments');
      alm.content = $(container.querySelector('.alm-ajax')); // Get first `.alm-ajax` element as $ obj
      alm.content_preloaded = $(container.querySelector('.alm-preloaded')); // Get first `.alm-preloaded` element as $ obj

      // Instance Params
      alm.canonical_url = el.dataset.canonicalUrl;
      alm.nested = el.dataset.nested;
      alm.is_search = el.dataset.search;
      alm.slug = el.dataset.slug;
      alm.post_id = el.dataset.postId;
      alm.id = el.dataset.id ? el.dataset.id : '';

      // Shortcode Params
      alm.repeater = alm.listing.dataset.repeater; // Repeaters
      alm.theme_repeater = alm.listing.dataset.themeRepeater;

      alm.post_type = alm.listing.dataset.postType ? alm.listing.dataset.postType.split(",") : 'post';
      alm.sticky_posts = alm.listing.dataset.stickyPosts;

      alm.btnWrap = $('> .alm-btn-wrap', alm.container);
      alm.btnWrap.get(0).style.visibility = 'visible';
      alm.button_label = alm.listing.dataset.buttonLabel;
      alm.button_loading_label = alm.listing.dataset.buttonLoadingLabel;

      alm.scroll_distance = alm.listing.dataset.scrollDistance;
      alm.scroll_distance = alm.scroll_distance ? parseInt(alm.scroll_distance) : 100;
      alm.scroll_container = alm.listing.dataset.scrollContainer;
      alm.max_pages = alm.listing.dataset.maxPages;
      alm.max_pages = alm.max_pages ? parseInt(alm.max_pages) : 0;
      alm.pause_override = alm.listing.dataset.pauseOverride; // true | false
      alm.pause = alm.listing.dataset.pause; // true | false
      alm.transition = alm.listing.dataset.transition; // Transition
      alm.transition_container = alm.listing.dataset.transitionContainer; // Transition Container
      alm.tcc = alm.listing.dataset.transitionContainerClasses; // Transition Container Classes
      alm.speed = 250;
      alm.images_loaded = alm.listing.dataset.imagesLoaded;
      alm.destroy_after = alm.listing.dataset.destroyAfter;
      alm.orginal_posts_per_page = alm.listing.dataset.postsPerPage; // Used for paging add-on
      alm.posts_per_page = alm.listing.dataset.postsPerPage;
      alm.offset = alm.listing.dataset.offset;

      alm.cache = alm.listing.dataset.cache; // Cache add-on
      alm.cache_id = alm.listing.dataset.cacheId;
      alm.cache_path = alm.listing.dataset.cachePath;
      alm.cache_logged_in = alm.listing.dataset.cacheLoggedIn;

      alm.cta = alm.listing.dataset.cta; // CTA add-on
      alm.cta_position = alm.listing.dataset.ctaPosition;
      alm.cta_repeater = alm.listing.dataset.ctaRepeater;
      alm.cta_theme_repeater = alm.listing.dataset.ctaThemeRepeater;

      alm.acf = alm.listing.dataset.acf; // ACF add-on
      alm.acf_field_type = alm.listing.dataset.acfFieldType;
      alm.acf_field_name = alm.listing.dataset.acfFieldName;
      alm.acf_post_id = alm.listing.dataset.acfPostId;

      alm.nextpage = alm.listing.dataset.nextpage; // Nextpage add-on
      alm.nextpage_urls = alm.listing.dataset.nextpageUrls;
      alm.nextpage_scroll = alm.listing.dataset.nextpageScroll;
      alm.nextpage_pageviews = alm.listing.dataset.nextpagePageviews;
      alm.nextpage_post_id = alm.listing.dataset.nextpagePostId;
      alm.nextpage_startpage = alm.listing.dataset.nextpageStartpage;

      alm.single_post = alm.listing.dataset.singlePost; // Previous Post add-on
      alm.single_post_id = alm.listing.dataset.singlePostId;
      alm.single_post_order = alm.listing.dataset.singlePostOrder;
      alm.single_post_init_id = alm.listing.dataset.singlePostId;
      alm.single_post_taxonomy = alm.listing.dataset.singlePostTaxonomy;
      alm.single_post_excluded_terms = alm.listing.dataset.singlePostExcludedTerms;

      alm.comments = alm.listing.dataset.comments; // Comments add-on
      if (alm.comments === 'true') {
         // if comments, adjust alm.content wrapper
         alm.content = $('.alm-comments', alm.container);
      }
      alm.comments_post_id = alm.listing.dataset.comments_post_id; // current post id
      alm.comments_per_page = alm.listing.dataset.comments_per_page;
      alm.comments_type = alm.listing.dataset.comments_type;
      alm.comments_style = alm.listing.dataset.comments_style;
      alm.comments_template = alm.listing.dataset.comments_template;
      alm.comments_callback = alm.listing.dataset.comments_callback;

      alm.filters = alm.listing.dataset.filters;

      alm.restapi = alm.listing.dataset.restapi;
      alm.restapi_base_url = alm.listing.dataset.restapiBaseUrl;
      alm.restapi_namespace = alm.listing.dataset.restapiNamespace;
      alm.restapi_endpoint = alm.listing.dataset.restapiEndpoint;
      alm.restapi_template_id = alm.listing.dataset.restapiTemplateId;
      alm.restapi_debug = alm.listing.dataset.restapiDebug;

      alm.seo = alm.listing.dataset.seo; // SEO add-on

      alm.preloaded = alm.listing.dataset.preloaded; // Preloaded add-on
      alm.preloaded_amount = alm.listing.dataset.preloadedAmount;
      alm.is_preloaded = alm.listing.dataset.isPreloaded === 'true' ? true : false;

      alm.paging = alm.listing.dataset.paging; // Paging add-on      

      alm.users = alm.listing.dataset.users === 'true' ? true : false; // Users add-on
      if (alm.users) {
         // Override paging params for users
         alm.orginal_posts_per_page = alm.listing.dataset.usersPerPage;
         alm.posts_per_page = alm.listing.dataset.usersPerPage;
      }

      /* Filters */
      if (alm.filters === 'true') {
         alm.filters = true;

         alm.filters_analtyics = alm.listing.dataset.filtersAnaltyics;
         alm.filters_debug = alm.listing.dataset.filtersDebug;

         // Check for startpage param
         /*
         alm.filters_startpage = alm.listing.dataset.filtersStartpage;
         alm.filters_startpage = parseInt(alm.filters_startpage);
         alm.page = alm.filters_startpage;
         */

         // Get Paged Querystring Val
         alm.filters_startpage = 0;
         var page = almGetParameterByName('pg');
         if (page !== null) {
            alm.filters_startpage = parseInt(page);
            alm.page = alm.filters_startpage;
         }

         alm.isPaged = false;
         if (alm.filters_startpage > 0) {
            alm.isPaged = true;
            alm.page = alm.filters_startpage - 1;
         }
      } else {

         alm.filters = false;
      }
      /* End Filters  */

      /* REST API */
      if (alm.restapi === 'true') {
         alm.restapi = true;
         if (alm.restapi_debug === undefined) {
            alm.restapi_debug = false;
         }
         if (alm.restapi_template_id === '') {
            alm.restapi = false;
         }
      } else {
         alm.restapi = false;
      }
      /* End REST API  */

      /* Paging */
      if (alm.paging === 'true') {
         alm.paging = true;
         alm.paging_controls = alm.listing.dataset.pagingControls ? true : false;
         alm.paging_show_at_most = alm.listing.dataset.pagingShowAtMost;
         alm.paging_classes = alm.listing.dataset.pagingClasses;
         alm.paging_init = true;
         alm.paging_show_at_most = alm.paging_show_at_most === undefined ? 7 : alm.paging_show_at_most;

         // If preloaded, pause ALM	
         alm.pause = alm.preloaded === 'true' ? true : alm.pause;
      } else {
         alm.paging = false;
      }
      /* End Paging  */

      /* Cache */
      if (alm.cache === undefined) {
         alm.cache = false;
      }
      if (alm.cache_logged_in === undefined) {
         alm.cache_logged_in = false;
      }
      /* End Cache  */

      /* Comments */
      if (alm.comments_per_page === undefined) {
         alm.comments_per_page = '5';
      }
      /* End Comments  */

      /* Preloaded */
      /* If posts_per_page <= preloaded_total_posts disable ajax load more */
      if (alm.preloaded === 'true') {

         // Get Preloaded Amount
         alm.preloaded_amount = alm.preloaded_amount === undefined ? alm.posts_per_page : alm.preloaded_amount;

         // Disable ALM if total_posts is </= preloaded_amount
         if (alm.localize && alm.localize.total_posts) {
            if (parseInt(alm.localize.total_posts) <= parseInt(alm.preloaded_amount)) {
               alm.preloaded_total_posts = alm.localize.total_posts;
               alm.disable_ajax = true;
            }
         }
      } else {
         alm.preloaded = 'false';
      }
      /* End Preloaded  */

      /* SEO */
      if (alm.seo === undefined) {
         alm.seo = false;
      }
      if (alm.seo === 'true') {
         alm.seo = true; // Convert string to boolean
      }
      if (alm.is_search === undefined) {
         alm.is_search = false;
      }
      alm.search_value = alm.is_search === 'true' ? alm.slug : ''; // Convert to value of slug for appending to seo url

      alm.permalink = alm.listing.dataset.seoPermalink;
      alm.pageview = alm.listing.dataset.seoPageview;
      alm.start_page = alm.listing.dataset.seoStartPage;
      alm.seo_trailing_slash = alm.listing.dataset.seoTrailingSlash === 'false' ? '' : '/';
      alm.seo_leading_slash = alm.listing.dataset.seoLeadingSlash === 'true' ? '/' : '';

      if (alm.start_page) {
         alm.seo_scroll = alm.listing.dataset.seoScroll;
         alm.seo_scroll_speed = alm.listing.dataset.seoScrollSpeed;
         alm.seo_scrolltop = alm.listing.dataset.seoScrolltop;
         alm.seo_controls = alm.listing.dataset.seoControls;
         alm.isPaged = false;
         if (alm.start_page > 1) {
            alm.isPaged = true; // Is this a $paged page > 1 ?
            alm.posts_per_page = alm.start_page * alm.posts_per_page;
         }
         if (alm.paging) {
            // If paging, reset posts_per_page
            alm.posts_per_page = alm.orginal_posts_per_page;
         }
      } else {
         alm.start_page = 1;
      }
      /* End SEO  */

      /* Nextpage */
      if (alm.nextpage === 'true') {
         alm.nextpage = true;
         alm.posts_per_page = 1;
      } else {
         alm.nextpage = false;
      }
      if (alm.nextpage_urls === undefined) {
         alm.nextpage_urls = 'true';
      }
      if (alm.nextpage_scroll === undefined) {
         alm.nextpage_scroll = '250:30';
      }
      if (alm.nextpage_pageviews === undefined) {
         alm.nextpage_pageviews = 'true';
      }
      if (alm.nextpage_post_id === undefined) {
         alm.nextpage = false;
         alm.nextpage_post_id = null;
      }
      if (alm.nextpage_startpage === undefined) {
         alm.nextpage_startpage = 1;
      }
      if (alm.nextpage_startpage > 1) {
         alm.isPaged = true;
      }
      /* End Nextpage  */

      /* Advanced Custom Fields */
      alm.acf = alm.acf === 'true' ? true : false;
      // if field type, name or post ID is empty
      if (alm.acf_field_type === undefined || alm.acf_field_name === undefined || alm.acf_post_id === undefined) {
         alm.acf = false;
      }
      /* End Advanced Custom Fields  */

      /* Previous Post */
      if (alm.single_post === 'true') {
         alm.single_post = true;
         alm.single_post_permalink = '';
         alm.single_post_title = '';
         alm.single_post_slug = '';
      } else {
         alm.single_post = false;
      }
      if (alm.single_post_id === undefined) {
         alm.single_post_id = '';
         alm.single_post_init_id = '';
      }
      alm.single_post_order = alm.single_post_order === undefined ? 'previous' : alm.single_post_order;
      alm.single_post_taxonomy = alm.single_post_taxonomy === undefined ? '' : alm.single_post_taxonomy;
      alm.single_post_excluded_terms = alm.single_post_excluded_terms === undefined ? '' : alm.single_post_excluded_terms;

      alm.single_post_title_template = alm.listing.dataset.singlePostTitleTemplate;
      alm.siteTitle = alm.listing.dataset.singlePostSiteTitle;
      alm.siteTagline = alm.listing.dataset.singlePostSiteTagline;
      alm.single_post_pageview = alm.listing.dataset.singlePostPageview;
      alm.single_post_scroll = alm.listing.dataset.singlePostScroll;
      alm.single_post_scroll_speed = alm.listing.dataset.singlePostScrollSpeed;
      alm.single_post_scroll_top = alm.listing.dataset.singlePostScrolltop;
      alm.single_post_controls = alm.listing.dataset.singlePostControls;
      /* End Previous Post */

      /* Offset */
      alm.offset = alm.offset === undefined ? 0 : alm.offset;

      /* Pause */
      if (alm.pause === undefined || alm.seo && alm.start_page > 1) {
         // SEO only
         alm.pause = false;
      }
      if (alm.preloaded === 'true' && alm.seo && alm.start_page > 0) {
         // SEO + Preloaded
         alm.pause = false;
      }
      if (alm.preloaded === 'true' && alm.paging) {
         alm.pause = true;
      }

      /* Repeater and Theme Repeater */
      if (alm.repeater === undefined) {
         alm.repeater = 'default';
      }
      alm.theme_repeater = alm.theme_repeater === undefined ? false : alm.theme_repeater;

      /* Max Pages (while scrolling) */
      alm.max_pages = alm.max_pages === undefined || alm.max_pages === 0 ? 10000 : alm.max_pages;

      /* Scroll Distance */
      alm.scroll_distance = alm.scroll_distance === undefined ? 150 : alm.scroll_distance;

      /* Scroll Container */
      alm.scroll_container = alm.scroll_container === undefined ? '' : alm.scroll_container;

      /* Transition */
      alm.transition = alm.transition === undefined ? 'fade' : alm.transition;

      /* Transition Container Class */
      alm.tcc = alm.tcc === undefined ? '' : alm.tcc;

      /* Masonry */
      alm.is_masonry_preloaded = false;
      if (alm.transition === 'masonry') {
         alm.masonry_init = true;
         alm.masonry_selector = alm.listing.dataset.masonrySelector;
         alm.masonry_columnwidth = alm.listing.dataset.masonryColumnwidth;
         alm.masonry_animation = alm.listing.dataset.masonryAnimation;
         alm.masonry_animation = alm.masonry_animation === undefined ? 'standard' : alm.masonry_animation;
         alm.masonry_horizontalorder = alm.listing.dataset.masonryHorizontalorder;
         alm.masonry_horizontalorder = alm.masonry_horizontalorder === undefined ? 'true' : alm.masonry_horizontalorder;
         alm.masonry_wrap = alm.content;
         alm.transition_container = false;
         alm.is_masonry_preloaded = alm.preloaded === 'true' ? true : alm.is_masonry_preloaded;
      }

      /* Scroll */
      if (alm.listing.dataset.scroll === undefined) {
         alm.scroll = true;
      } else if (alm.listing.dataset.scroll === 'false') {
         alm.scroll = false;
      } else {
         alm.scroll = true;
      }

      /* Transition Container */
      alm.transition_container = alm.transition_container === undefined || alm.transition_container === 'true' ? true : false;

      /* Images Loaded */
      alm.images_loaded = alm.images_loaded === undefined ? 'false' : alm.images_loaded;

      /* Button Labels */
      alm.button_label = alm.button_label === undefined ? 'Older Posts' : alm.button_label;
      alm.button_loading_label = alm.button_loading_label === undefined ? false : alm.button_loading_label;

      /* Paging */
      if (alm.paging) {
         alm.content.parent().addClass('loading'); // add loading class to main container
      } else {
         var almChildren = container.childNodes; // Get child nodes of instance [nodeList]
         if (almChildren) {
            var almChildArray = Array.prototype.slice.call(almChildren); // Convert nodeList to array   			
            var btnWrap = almChildArray.filter(function (element) {
               // Loop array to find the `.alm-btn-wrap` div
               return element.classList.contains('alm-btn-wrap');
            });
            alm.button = btnWrap ? btnWrap[0].querySelector('.alm-load-more-btn') : container.querySelector('.alm-btn-wrap .alm-load-more-btn');
         } else {
            alm.button = container.querySelector('.alm-btn-wrap .alm-load-more-btn');
         }
      }

      // Render "Showing x of y results" text.
      alm.resultsText = document.querySelector('.alm-results-text');
      if (alm.resultsText) {
         alm.resultsText.innerHTML = alm_localize.display_results;
      } else {
         alm.resultsText = false;
      }

      /**  
       *  LoadPosts()
       *  The function to get posts via Ajax
       *  @since 2.0.0
       */

      alm.AjaxLoadMore.loadPosts = function () {
         if (!alm.disable_ajax) {
            // Check for ajax blocker
            if (!alm.paging) {
               alm.button.classList.add('loading');
               if (alm.button_loading_label !== false) {
                  alm.button.innerHTML = alm.button_loading_label;
               }
            }
            alm.container.addClass('alm-loading');
            alm.loading = true;

            // If cache = true && cache_logged_in setting is false
            if (alm.cache === 'true' && !alm.cache_logged_in) {

               var cache_page;

               if (alm.init && alm.seo && alm.isPaged) {
                  // SEO Add-on
                  // If the request a paged URL (/page/3/)
                  var firstpage = '1';
                  cache_page = alm.cache_path + alm.cache_id + '/page-' + firstpage + '-' + alm.start_page + '.html';
               } else if (alm.nextpage) {
                  // Nextpage add-on
                  var nextpage_cache_page;
                  if (alm.paging) {
                     nextpage_cache_page = parseInt(alm.page) + 1;
                  } else {
                     nextpage_cache_page = parseInt(alm.page) + 2;
                     if (alm.isPaged) {
                        // If the request a paged URL (/page/3/)
                        nextpage_cache_page = parseInt(alm.page) + parseInt(alm.nextpage_startpage) + 1;
                     }
                  }
                  cache_page = alm.cache_path + alm.cache_id + '/page' + '-' + nextpage_cache_page + '.html';
               } else if (alm.single_post) {
                  // Previous Post
                  cache_page = alm.cache_path + alm.cache_id + '/' + alm.single_post_id + '.html';
               } else {
                  // Standard ALM URL request
                  cache_page = alm.cache_path + alm.cache_id + '/page-' + (alm.page + 1) + '.html';
               }

               $.get(cache_page, function (data) {
                  alm.AjaxLoadMore.success(data, true); // data contains whatever the request has returned
               }).fail(function () {
                  alm.AjaxLoadMore.ajax('standard');
               });
            } else {
               // Standard ALM query

               alm.AjaxLoadMore.ajax('standard');
            }
         }
      };

      /*  ajax()
       *  Ajax Load Moe Ajax function
       *
       *  @param queryType The type of Ajax request (standard/totalposts)
       *  @since 2.6.0
       */

      alm.AjaxLoadMore.ajax = function (queryType) {

         // Default action
         var action = 'alm_query_posts';

         // ACF Params
         alm.acf_array = '';
         if (alm.acf) {
            // Custom query for the Repeater / Gallery / Flexible Content field types
            if (alm.acf_field_type !== 'relationship') {
               action = 'alm_acf_query';
            }
            alm.acf_array = {
               'acf': 'true',
               'post_id': alm.acf_post_id,
               'field_type': alm.acf_field_type,
               'field_name': alm.acf_field_name
            };
         }

         // Nextpage Params
         alm.nextpage_array = '';
         if (alm.nextpage) {
            action = 'alm_nextpage_query';
            alm.nextpage_array = {
               'nextpage': 'true',
               'urls': alm.nextpage_urls,
               'scroll': alm.nextpage_scroll,
               'pageviews': alm.nextpage_pageviews,
               'post_id': alm.nextpage_post_id,
               'startpage': alm.nextpage_startpage
            };
         }

         // Previous Post Params
         alm.single_post_array = '';
         if (alm.single_post) {
            alm.single_post_array = {
               'single_post': 'true',
               'id': alm.single_post_id,
               'slug': alm.single_post_slug
            };
         }

         // Comment Params
         alm.comments_array = '';
         if (alm.comments === 'true') {
            action = 'alm_comments_query';
            alm.posts_per_page = alm.comments_per_page;
            alm.comments_array = {
               'comments': 'true',
               'post_id': alm.comments_post_id,
               'per_page': alm.comments_per_page,
               'type': alm.comments_type,
               'style': alm.comments_style,
               'template': alm.comments_template,
               'callback': alm.comments_callback
            };
         }

         // Users Params
         alm.users_array = '';
         if (alm.users) {
            action = 'alm_users_query';
            alm.users_array = {
               'users': 'true',
               'role': alm.listing.dataset.usersRole,
               'include': alm.listing.dataset.usersInclude,
               'exclude': alm.listing.dataset.usersExclude,
               'per_page': alm.posts_per_page,
               'order': alm.listing.dataset.usersOrder,
               'orderby': alm.listing.dataset.usersOrderby
            };
         }

         // CTA Params
         alm.cta_array = '';
         if (alm.cta === 'true') {
            alm.cta_array = {
               'cta': 'true',
               'cta_position': alm.cta_position,
               'cta_repeater': alm.cta_repeater,
               'cta_theme_repeater': alm.cta_theme_repeater
            };
         }

         // REST API
         if (alm.restapi) {
            var alm_rest_template = wp.template(alm.restapi_template_id);
            var alm_rest_url = alm.restapi_base_url + '/' + alm.restapi_namespace + '/' + alm.restapi_endpoint;
            var alm_rest_data = almGetRestParams(alm); // [./helpers/queryParams.js]

            $.ajax({
               type: 'GET',
               url: alm_rest_url,
               data: alm_rest_data,
               dataType: 'JSON',
               beforeSend: function beforeSend() {
                  if (alm.page != 1 && !alm.paging) {
                     alm.button.classList.add('loading');
                  }
               },
               success: function success(results) {
                  var data = '',
                      html = results.html,
                      meta = results.meta,
                      postcount = meta.postcount,
                      totalposts = meta.totalposts;

                  // loop results to get data from each
                  $.each(html, function (e) {
                     var result = html[e];
                     if (alm.restapi_debug === 'true') {
                        // If debug
                        console.log(result);
                     }
                     data += alm_rest_template(result);
                  });

                  // Create object to pass to success()
                  var obj = {
                     'html': data,
                     'meta': {
                        'postcount': postcount,
                        'totalposts': totalposts
                     }
                  };
                  alm.AjaxLoadMore.success(obj, false); // Send data
               }
            });
         }

         // Standard ALM
         else {

               var alm_data_params = almGetAjaxParams(alm, action, queryType); // [./helpers/queryParams.js]

               $.ajax({
                  type: 'GET',
                  url: alm_localize.ajaxurl,
                  dataType: 'JSON',
                  data: alm_data_params,
                  beforeSend: function beforeSend() {
                     if (alm.page != 1 && !alm.paging) {
                        alm.button.classList.add('loading');
                     }
                  },
                  success: function success(data) {
                     // Standard Query
                     if (queryType === 'standard') {
                        alm.AjaxLoadMore.success(data, false);
                     } else if (queryType === 'totalpages' && alm.paging && alm.nextpage) {
                        // Next Page and Paging
                        if ($.isFunction($.fn.almBuildPagination)) {
                           $.fn.almBuildPagination(data, alm);
                        }
                     } else if (queryType === 'totalposts' && alm.paging) {
                        // Paging
                        if ($.isFunction($.fn.almBuildPagination)) {
                           $.fn.almBuildPagination(data, alm);
                        }
                     }
                  },
                  error: function error(jqXHR, textStatus, errorThrown) {
                     alm.AjaxLoadMore.error(jqXHR, textStatus, errorThrown);
                  }

               });
            }
      };

      // If pagination enabled, run totalposts query
      if (alm.paging) {
         if (alm.nextpage) {
            alm.AjaxLoadMore.ajax('totalpages'); // Create paging menu and query for total pages
         } else {
            alm.AjaxLoadMore.ajax('totalposts'); // Create paging menu and query for total posts
         }
      }

      /*  success()
       *
       *  Success function after loading data
       *
       *  @param data     The results of the Ajax request
       *  @param is_cache Are results of the Ajax request coming from cache
       *  @since 2.6.0
       */

      alm.AjaxLoadMore.success = function (data, is_cache) {

         if (alm.single_post) {
            // Get previous page data
            alm.AjaxLoadMore.getPreviousPost();
         }

         var loadingStyle = 'style="opacity: 0; height: 0;"';

         var html, meta, total;

         if (is_cache) {
            // If content is cached don't look for json data - we won't be querying the DB.
            html = data;
         } else {
            // Standard ALM query results
            html = data.html;
            meta = data.meta;
            alm.posts = alm.paging ? meta.postcount : alm.posts + meta.postcount;
            total = meta.postcount;
            alm.totalposts = meta.totalposts;

            if (alm.preloaded === 'true') {
               alm.totalposts = alm.totalposts - alm.preloaded_amount;
            }
         }

         // Set localized vars for totalposts
         alm.setlocalizedVars('viewing', alm.posts);
         alm.setlocalizedVars('total_posts', alm.totalposts);

         almResultsText(alm); // Set Results Text           


         // data converted to an object
         alm.data = $(html);

         // If cache, get the length of the data object
         total = is_cache ? alm.data.length : total;

         // First Run
         if (alm.init) {

            if (meta) {
               if (meta.totalposts) {
                  alm.el.attr('data-total-posts', meta.totalposts);
               }
            }

            if (!alm.paging) {

               alm.button.innerHTML = alm.button_label;
            } else {

               // Paging
               if (total > 0) {
                  alm.el = $('<div class="alm-reveal" ' + loadingStyle + '/>');
                  alm.el.append('<div class="alm-paging-content' + alm.tcc + '"></div><div class="alm-paging-loading"></div>');
                  $('.alm-paging-content', alm.el).append(alm.data);
                  alm.content.append(alm.el);
                  alm.AjaxLoadMore.fadeIn(alm.el.get(0), alm.speed);
                  alm.content.parent().removeClass('loading'); // Remove loading class from main container
                  alm.AjaxLoadMore.resetBtnText();

                  // Delay reveal until paging elements have been added
                  setTimeout(function () {
                     $('.alm-paging-content', alm.el).fadeIn(alm.speed, 'alm_easeInOutQuad', function () {
                        var paddingT = parseInt(alm.content.css('padding-top')),
                            paddingB = parseInt(alm.content.css('padding-bottom'));
                        alm.content.css('height', alm.el.height() + paddingT + paddingB + 'px');
                        if ($.isFunction($.fn.almFadePageControls)) {
                           $.fn.almFadePageControls(alm.btnWrap);
                        }
                     });
                  }, alm.speed);
               }
            }

            // ALM Empty
            if (total === 0) {
               if (alm.paging) {
                  if ($.isFunction($.fn.almPagingEmpty)) {
                     $.fn.almPagingEmpty(alm);
                  }
               }
               if ($.isFunction($.fn.almEmpty)) {
                  $.fn.almEmpty(alm);
               }
            }

            // isPaged
            if (alm.isPaged) {

               // Reset the posts_per_page parameter
               alm.posts_per_page = alm.users ? alm.listing.dataset.usersPerPage : alm.listing.dataset.postsPerPage;

               // SEO add-on
               alm.page = alm.start_page ? alm.start_page - 1 : alm.page; // Set new page #

               // Filters add-on               
               if (alm.filters) {

                  if (alm.filters_startpage) {
                     // Set new page #
                     alm.page = alm.filters_startpage - 1;

                     // Reset filters-startpage data after the first run
                     alm.posts_per_page = alm.listing.dataset.postsPerPage;
                  }
               }
            }
         }

         if (total > 0) {

            // We have results!            

            if (!alm.paging) {

               if (alm.single_post) {
                  // Previous Post, create container and append data
                  alm.el = $('<div class="alm-reveal alm-single-post post-' + alm.single_post_id + '" ' + loadingStyle + ' data-id="' + alm.single_post_id + '" data-title="' + alm.single_post_title + '" data-url="' + alm.single_post_permalink + '" data-page="' + alm.page + '"/>');
                  alm.el.append(alm.data);
               } else {

                  if (!alm.transition_container) {
                     // No transition container

                     alm.el = alm.data;
                  } else {
                     // Standard container

                     var pagenum = void 0;
                     var querystring = window.location.search;

                     // SEO, init and paged
                     if (alm.init && alm.start_page > 1) {
                        // loop through items and break into separate .alm-reveal divs for paging       

                        var seo_data = [];
                        var container_array = [];
                        var posts_per_page = parseInt(alm.posts_per_page);
                        var pages = Math.ceil(total / posts_per_page);

                        // Set alm.el to be .alm-listing div
                        alm.el = alm.content;

                        // Call to Actions
                        if (alm.cta === 'true') {
                           posts_per_page = posts_per_page + 1; // Add 1 to posts_per_page for CTAs
                           pages = Math.ceil(total / posts_per_page); // Update pages var with new posts_per_page
                           total = pages + total; // Get new total w/ CTAs added
                        }

                        // Slice seo_data array into induvidual pages
                        for (var i = 0; i < total; i += posts_per_page) {
                           seo_data.push(alm.data.slice(i, posts_per_page + i));
                        }

                        // Loop seo_data to build .alm-reveal data attributes 
                        for (var k = 0; k < seo_data.length; k++) {

                           var p = alm.preloaded === 'true' ? 1 : 0; // Add 1 page if items are preloaded.
                           var div_reveal = void 0;

                           if (k > 0 || alm.preloaded === 'true') {

                              // > Paged
                              pagenum = k + 1 + p;

                              if (alm.permalink === 'default') {
                                 div_reveal = $('<div class="alm-reveal alm-seo' + alm.tcc + '" data-url="' + alm.canonical_url + '' + alm.search_value + '&paged=' + pagenum + '" data-page="' + pagenum + '" />');
                              } else {
                                 div_reveal = $('<div class="alm-reveal alm-seo' + alm.tcc + '" data-url="' + alm.canonical_url + alm.seo_leading_slash + 'page/' + pagenum + alm.seo_trailing_slash + alm.search_value + '" data-page="' + pagenum + '" />');
                              }
                           } else {
                              // First Page 
                              var preloaded_class = alm.is_preloaded ? ' alm-preloaded' : '';
                              div_reveal = $('<div class="alm-reveal alm-seo' + preloaded_class + alm.tcc + '" data-url="' + alm.canonical_url + '' + alm.search_value + '" data-page="1" />');
                           }

                           // Append data to div_reveal and add to container_array
                           container_array.push(div_reveal.append(seo_data[k]));
                        }

                        // Reverse the container_array so we start at page 1
                        //container_array.reverse();
                        for (var x = 0; x < container_array.length; x++) {
                           //alm.el.prepend(container_array[x]);	 
                           alm.el.append(container_array[x]);
                        }

                        // Set opacity and height of .alm-listing div to allow for fadein.
                        alm.el.get(0).style.opacity = 0;
                        alm.el.get(0).style.height = 0;
                     }
                     // End SEO

                     else {
                           // If is SEO and paged OR Preloaded.
                           if (alm.seo && alm.page > 0 || alm.preloaded === 'true') {

                              var p2 = alm.preloaded === 'true' ? 1 : 0; // Add 1 page if items are preloaded.

                              // SEO [Paged]
                              pagenum = alm.page + 1 + p2;

                              if (alm.seo) {

                                 if (alm.permalink === 'default') {
                                    alm.el = $('<div class="alm-reveal alm-seo' + alm.tcc + '" ' + loadingStyle + ' data-url="' + alm.canonical_url + '' + alm.search_value + '&paged=' + pagenum + '" data-page="' + pagenum + '" />');
                                 } else {
                                    alm.el = $('<div class="alm-reveal alm-seo' + alm.tcc + '" ' + loadingStyle + ' data-url="' + alm.canonical_url + alm.seo_leading_slash + 'page/' + pagenum + alm.seo_trailing_slash + alm.search_value + '" data-page="' + pagenum + '" />');
                                 }
                              } else if (alm.filters) {
                                 // Filters
                                 alm.el = $('<div class="alm-reveal alm-filters' + alm.tcc + '" ' + loadingStyle + ' data-url="' + alm.canonical_url + '' + querystring + '" data-page="' + pagenum + '" />');
                              } else {
                                 // Basic ALM
                                 alm.el = $('<div class="alm-reveal' + alm.tcc + '" ' + loadingStyle + ' />');
                              }
                           } else if (alm.filters) {
                              // Filters
                              alm.el = $('<div class="alm-reveal alm-filters' + alm.tcc + '" ' + loadingStyle + ' data-url="' + alm.canonical_url + '' + querystring + '" data-page="' + (alm.page + 1) + '" />');
                           } else {

                              if (alm.seo) {
                                 // SEO [Page 1]
                                 alm.el = $('<div class="alm-reveal alm-seo' + alm.tcc + '" ' + loadingStyle + ' data-url="' + alm.canonical_url + '' + alm.search_value + '" data-page="1" />');
                              } else {
                                 // Basic ALM
                                 alm.el = $('<div class="alm-reveal' + alm.tcc + '" ' + loadingStyle + ' />');
                              }
                           }

                           alm.el.append(alm.data);
                        }
                  }
               }

               // Append alm.el to ALM container
               // Do not append when transtion == masonry OR init and !preloaded
               if (alm.transition !== 'masonry' || alm.init && !alm.is_masonry_preloaded) {
                  alm.content.append(alm.el);
               }

               // Transitions


               // Masonry
               if (alm.transition === 'masonry') {
                  almMasonry(alm.masonry_wrap, alm.el, alm.masonry_selector, alm.masonry_columnwidth, alm.masonry_animation, alm.masonry_horizontalorder, alm.speed, alm.masonry_init, alm.init, alm_is_filtering);
                  alm.masonry_init = false;
                  alm.AjaxLoadMore.transitionEnd();
               }
               // None
               else if (alm.transition === 'none') {
                     alm.AjaxLoadMore.fadeIn(alm.el.get(0), 0);
                     alm.AjaxLoadMore.transitionEnd();
                  }
                  // Fade transition
                  else {
                        if (alm.images_loaded === 'true') {
                           alm.el.almWaitForImages().done(function () {
                              if (alm.transition_container) {
                                 alm.AjaxLoadMore.fadeIn(alm.el.get(0), alm.speed);
                              }
                              alm.AjaxLoadMore.transitionEnd();
                           });
                        } else {
                           if (alm.transition_container) {
                              alm.AjaxLoadMore.fadeIn(alm.el.get(0), alm.speed);
                           }
                           alm.AjaxLoadMore.transitionEnd();
                        }
                     }
            } else {

               // Paging
               if (!alm.init) {
                  $('.alm-paging-content', alm.el).html('').append(alm.data).almWaitForImages().done(function () {
                     // Remove loading class, append data
                     $('.alm-paging-loading', alm.el).fadeOut(alm.speed); // Fade out loader
                     if ($.isFunction($.fn.almOnPagingComplete)) {
                        setTimeout(function () {
                           // Delay for effect		                     
                           $.fn.almOnPagingComplete(alm);
                        }, alm.speed);
                     }
                     alm.container.removeClass('alm-loading');
                     alm.AjaxLoadMore.triggerAddons(alm);
                  });
               } else {
                  alm.container.removeClass('alm-loading');
                  alm.AjaxLoadMore.triggerAddons(alm);
               }
               // End Paging
            }

            // ALM Complete
            if ($.isFunction($.fn.almComplete)) {
               if (alm.images_loaded === 'true') {
                  alm.el.almWaitForImages().done(function () {
                     $.fn.almComplete(alm);
                  });
               } else {
                  $.fn.almComplete(alm);
               }
            }
            // End ALM Complete


            // ALM Done
            if (!alm.cache) {
               // Not Cache & Previous Post
               if (alm.posts >= alm.totalposts && !alm.single_post) {
                  alm.AjaxLoadMore.triggerDone();
               }
            } else {
               // Cache 
               if (total < alm.posts_per_page) {
                  alm.AjaxLoadMore.triggerDone();
               }
            }
            // End ALM Done

         } else {
            // No Results!	         

            if (!alm.paging) {
               // Add .done class, reset btn text
               setTimeout(function () {
                  alm.button.classList.remove('loading');
                  alm.button.classList.add('done');
               }, alm.speed);
               alm.AjaxLoadMore.resetBtnText();
            }

            alm.AjaxLoadMore.triggerDone(); // ALM Done
         }

         // Filters Complete            
         if ($.isFunction($.fn.almFilterComplete)) {
            // Standard Filtering
            $.fn.almFilterComplete();
         }
         if (typeof almFiltersAddonComplete == "function") {
            // Filters Add-on
            almFiltersAddonComplete(el);
         }
         // End Filters Complete


         // Destroy After
         if (alm.destroy_after !== undefined && alm.destroy_after !== '') {
            var currentPage = alm.page + 1; // Add 1 because alm.page starts at 0
            if (alm.preloaded === 'true') {
               // Add 1 for preloaded
               currentPage++;
            }
            if (currentPage == alm.destroy_after) {
               // Disable ALM if page = alm.destroy_after val
               alm.AjaxLoadMore.destroyed();
            }
         }
         // End Destroy After

         alm_is_filtering = alm.init = false;
      };

      /*  pagingPreloadedInit()
       *
       *  First run for Paging + Preloaded add-ons
       *  Moves preloaded content into ajax container
       *
       *  @param data     The results of the Ajax request
       *  @since 2.11.3
       */
      alm.AjaxLoadMore.pagingPreloadedInit = function (data) {

         data = data == null ? '' : data; // Check for null data object

         alm.el = $('<div class="alm-reveal' + alm.tcc + '"/>');
         alm.el.append('<div class="alm-paging-content">' + data + '</div><div class="alm-paging-loading"></div>');
         alm.content.append(alm.el);
         alm.content.parent().removeClass('loading'); // Remove loading class from main container
         alm.AjaxLoadMore.resetBtnText();

         var paddingT = parseInt(alm.content.css('padding-top')),
             paddingB = parseInt(alm.content.css('padding-bottom'));
         alm.content.css('height', alm.el.height() + paddingT + paddingB + 'px');

         if (data === '') {
            if ($.isFunction($.fn.almPagingEmpty)) {
               $.fn.almPagingEmpty(alm);
            }
            if ($.isFunction($.fn.almEmpty)) {
               $.fn.almEmpty(alm);
            }
         }
         // Delay to avoid positioning issues
         setTimeout(function () {
            if ($.isFunction($.fn.almFadePageControls)) {
               $.fn.almFadePageControls(alm.btnWrap);
            }
         }, alm.speed);
      };

      /*  pagingNextpageInit()
       *
       *  First run for Paging + Next Page add-ons
       *  Moves .alm-nextpage content into ajax container
       *
       *  @param data     The results of the Ajax request
       *  @since 2.14.0
       */
      alm.AjaxLoadMore.pagingNextpageInit = function (data) {
         alm.el = $('<div class="alm-reveal alm-nextpage"/>');
         alm.el.append('<div class="alm-paging-content">' + data + '</div><div class="alm-paging-loading"></div>');
         alm.el.appendTo(alm.content);
         alm.content.parent().removeClass('loading'); // Remove loading class from main container
         alm.AjaxLoadMore.resetBtnText();

         var paddingT = parseInt(alm.content.css('padding-top')),
             paddingB = parseInt(alm.content.css('padding-bottom'));
         alm.content.css('height', alm.el.height() + paddingT + paddingB + 'px');

         if ($.isFunction($.fn.almSetNextPageVars)) {
            $.fn.almSetNextPageVars(alm); // Next Page Add-on
         }

         // Delay to avoid positioning issues
         setTimeout(function () {
            if ($.isFunction($.fn.almFadePageControls)) {
               $.fn.almFadePageControls(alm.btnWrap); // Paging Add-on
            }

            if ($.isFunction($.fn.almOnWindowResize)) {
               $.fn.almOnWindowResize(alm); // Paging Add-on
            }
         }, alm.speed);
      };

      /*  fetchingPreviousPost()
       *
       *  Get the previous post ID via ajax
       *  @since 2.7.4
       */

      if (alm.single_post_id) {
         alm.fetchingPreviousPost = false;
         alm.single_post_init = true;
      }

      alm.AjaxLoadMore.getPreviousPost = function () {
         alm.fetchingPreviousPost = true;

         var data = {
            action: 'alm_query_single_post',
            init: alm.single_post_init,
            id: alm.single_post_id,
            initial_id: alm.single_post_init_id,
            order: alm.single_post_order,
            taxonomy: alm.single_post_taxonomy,
            excluded_terms: alm.single_post_excluded_terms,
            post_type: alm.post_type
         };

         $.ajax({
            type: "GET",
            dataType: "JSON",
            url: alm_localize.ajaxurl,
            data: data,
            success: function success(data) {
               if (data.has_previous_post) {
                  alm.listing.setAttribute('data-single-post-id', data.prev_id); // Update single-post-id on instance
                  alm.single_post_id = data.prev_id;
                  alm.single_post_permalink = data.prev_permalink;
                  alm.single_post_title = data.prev_title;
                  alm.single_post_slug = data.prev_slug;
               } else {
                  if (!data.has_previous_post) {
                     alm.AjaxLoadMore.triggerDone();
                  }
               }
               if (typeof window.almSetSinglePost === 'function') {
                  window.almSetSinglePost(alm, data.current_id, data.permalink, data.title);
               }
               alm.fetchingPreviousPost = false;
               alm.single_post_init = false;
            },
            error: function error(jqXHR, textStatus, errorThrown) {
               alm.AjaxLoadMore.error(jqXHR, textStatus, errorThrown);
               alm.fetchingPreviousPost = false;
            }
         });
      };

      /*  triggerAddons()
       *
       *  Triggers various add-on functions (if available) after load complete.
       *  @since 2.14.0
       */
      alm.AjaxLoadMore.triggerAddons = function (alm) {
         if ($.isFunction($.fn.almSEO) && alm.seo) {
            $.fn.almSEO(alm, false);
         }
         if ($.isFunction($.fn.almSetNextPage)) {
            $.fn.almSetNextPage(alm);
         }
      };

      /*  triggerDone()
       *
       *  Fires the almDone() function (if available).
       *  @since 2.11.3
       */
      alm.AjaxLoadMore.triggerDone = function () {
         alm.loading = false;
         alm.finished = true;
         if (!alm.paging) {
            alm.button.classList.add('done');
         }
         if ($.isFunction($.fn.almDone)) {
            // Delay done until after animation
            setTimeout(function () {
               $.fn.almDone(alm);
            }, alm.speed + 10);
         }
      };

      /*  resetBtnText()
       *
       *  Resets the loading button text after loading has completed
       *  @since 2.8.4
       */
      alm.AjaxLoadMore.resetBtnText = function () {
         if (alm.button_loading_label !== false && !alm.paging) {
            // Reset button text
            alm.button.innerHTML = alm.button_label;
         }
      };

      /** 
      *  Ajax Error
       *
       *  Error function after failed data
       *  @since 2.6.0
       */

      alm.AjaxLoadMore.error = function (jqXHR, textStatus, errorThrown) {
         alm.loading = false;
         if (!alm.paging) {
            alm.button.classList.remove('loading');
            alm.AjaxLoadMore.resetBtnText();
         }
         console.log(errorThrown);
      };

      /** 
      *  Click Handler
       *
       *  Button click handler to load posts       
       *  @since 4.2.0
       */
      alm.AjaxLoadMore.click = function (e) {
         var button = e.target || e.currentTarget;
         if (alm.pause === 'true') {
            alm.pause = false;
            alm.pause_override = false;
            alm.AjaxLoadMore.loadPosts();
         }
         if (!alm.loading && !alm.finished && !button.classList.contains('done')) {
            alm.loading = true;
            alm.page++;
            alm.AjaxLoadMore.loadPosts();
         }
         // Filters Paged URLs
         if (alm.filters && typeof almFiltersPaged === 'function') {
            almFiltersPaged(alm);
         }
      };

      /*  Button Click Event
       *
       *  Load more button click event 
       *  @since 1.0.0
       */

      if (!alm.paging && !alm.fetchingPreviousPost) {
         alm.button.onclick = alm.AjaxLoadMore.click;
      }

      /*  Window Resize
       *
       *  Add resize function for Paging add-on only.
       *  @since 2.1.2
       *  @updated 4.2
       */
      if (alm.paging) {
         var pagingResize = void 0;
         alm.window.onresize = function () {
            clearTimeout(pagingResize);
            pagingResize = setTimeout(function (e) {
               if ($.isFunction($.fn.almOnWindowResize)) {
                  $.fn.almOnWindowResize(alm);
               }
            }, alm.speed);
         };
      }

      /*  alm.AjaxLoadMore.isVisible()
       *
       *  Check to see if element is visible before loading posts
       *  @since 2.1.2
       */

      alm.AjaxLoadMore.isVisible = function () {
         alm.visible = false;
         if (alm.el.is(":visible")) {
            alm.visible = true;
         }
         return alm.visible;
      };

      /**
       *  Load posts as user scrolls the page
       *  @since 1.0
       *  @updated 4.2.0
       */

      alm.AjaxLoadMore.scroll = function () {

         if (alm.timer) {
            clearTimeout(alm.timer);
         }

         alm.timer = setTimeout(function () {

            if (alm.AjaxLoadMore.isVisible() && !alm.fetchingPreviousPost) {

               var trigger = alm.button.getBoundingClientRect();
               var btnPos = Math.round(trigger.top - alm.window.innerHeight) + alm.scroll_distance;
               var scrollTrigger = btnPos <= 0 ? true : false;

               // Scroll Container
               if (alm.window !== window) {
                  var scrollInstance = alm.window.querySelector('.ajax-load-more-wrap'); // ALM inside the container
                  var scrollHeight = scrollInstance.offsetHeight; // ALM height
                  var scrollPosition = Math.round(alm.window.scrollTop + alm.window.offsetHeight - alm.scroll_distance); // How far user has scrolled	
                  scrollTrigger = scrollHeight <= scrollPosition ? true : false;
               }

               // If Pause && Pause Override
               if (!alm.loading && !alm.finished && scrollTrigger && alm.page < alm.max_pages - 1 && alm.proceed && alm.pause === 'true' && alm.pause_override === 'true') {
                  alm.button.click();
               }

               // Standard Scroll
               else {
                     //console.log(alm.window.innerHeight, alm.scroll_distance, alm.window.innerHeight - alm.scroll_distance);
                     if (!alm.loading && !alm.finished && scrollTrigger && alm.page < alm.max_pages - 1 && alm.proceed && alm.pause !== 'true') {
                        alm.button.click();
                     }
                  }
            }
         }, 25);
      };

      if (alm.scroll && !alm.paging) {
         // Scroll Container
         if (alm.scroll_container !== '') {
            alm.window = document.querySelector(alm.scroll_container) ? document.querySelector(alm.scroll_container) : alm.window;
         }
         alm.window.addEventListener('scroll', alm.AjaxLoadMore.scroll);
         alm.window.addEventListener('touchstart', alm.AjaxLoadMore.scroll);
      }

      /*  Destroy Ajax load More
       *
       *  Destroy Ajax Load More functionality
       *  @since 3.4.2
       */
      alm.AjaxLoadMore.destroyed = function () {
         alm.disable_ajax = true;
         if (!alm.paging) {
            setTimeout(function () {
               alm.button.fadeOut(alm.speed);
            }, alm.speed);
            if ($.isFunction($.fn.almDestroyed)) {
               $.fn.almDestroyed(alm);
            }
         }
      };

      /*  Fade in helper
       *
       *  Fade in elements after an Ajax call
       *  @since 3.5 
       */
      alm.AjaxLoadMore.fadeIn = function (element, speed) {
         if (speed == 0) {
            element.style.opacity = 1;
            element.style.height = 'auto';
         } else {
            speed = speed / 10;
            var op = 0; // initial opacity
            var timer = setInterval(function () {
               if (op > 0.9) {
                  element.style.opacity = 1;
                  clearInterval(timer);
               }
               element.style.opacity = op;
               op += 0.1;
            }, speed);
            element.style.height = 'auto';
         }
      };

      /**  
      *  Set variables after loading transiton completes
      *  @since 3.5 
      */
      alm.AjaxLoadMore.transitionEnd = function () {
         setTimeout(function () {
            alm.loading = false;
            alm.container.removeClass('alm-loading');
            alm.AjaxLoadMore.triggerAddons(alm);
            if (!alm.paging) {
               setTimeout(function () {
                  alm.button.classList.remove('loading');
               }, alm.speed);
               alm.AjaxLoadMore.resetBtnText();
            }
         }, alm.speed);
      };

      /**  
       *  Set localized variables 
       *  @since 4.1 
       */
      alm.setlocalizedVars = function (name, value) {
         if (alm.localize && name && value) {
            alm.localize[name] = value; // Set ALM localize var
            window[alm.master_id + '_vars'][name] = value; // Update global window obj vars
         }
      };

      /**  
      *  Init Ajax load More
       *  Load posts as user scrolls the page
       *  @since 2.0 
       */
      alm.AjaxLoadMore.init = function () {

         // Preloaded and destroy_after is 1  
         if (alm.preloaded === 'true' && alm.destroy_after == 1) {
            alm.AjaxLoadMore.destroyed();
         }

         if (!alm.paging && !alm.single_post) {
            if (alm.disable_ajax) {
               alm.finished = true;
               alm.button.classList.add('done');
            } else {
               if (alm.pause === 'true') {
                  alm.button.innerHTML = alm.button_label;
                  alm.loading = false;
               } else {
                  alm.AjaxLoadMore.loadPosts();
               }
            }
         }

         // Previous Post Add-on
         if (alm.single_post) {
            alm.AjaxLoadMore.getPreviousPost(); // Set next post on load
            alm.loading = false;
         }

         // Preloaded + SEO && !Paging
         if (alm.preloaded === 'true' && alm.seo && !alm.paging) {
            // Delay for scripts to load
            setTimeout(function () {
               if ($.isFunction($.fn.almSEO) && alm.start_page < 1) {
                  $.fn.almSEO(alm, true);
               }
            }, alm.speed);

            if (alm.resultsText) {
               almInitResultsText(alm, 'preloaded');
            }
         }

         // Preloaded
         if (alm.preloaded === 'true' && !alm.paging) {
            // Delay for scripts to load
            setTimeout(function () {
               // triggerDone
               if (alm.preloaded_total_posts <= parseInt(alm.preloaded_amount)) {
                  alm.AjaxLoadMore.triggerDone();
               }
               // almEmpty
               if (alm.preloaded_total_posts == 0) {
                  if ($.isFunction($.fn.almEmpty)) {
                     $.fn.almEmpty(alm);
                  }
               }
            }, alm.speed);

            if (alm.resultsText) {
               almInitResultsText(alm, 'preloaded');
            }
         }

         if (alm.paging) {
            if (alm.resultsText) {
               almInitResultsText(alm, 'paging');
            }
         }

         // Next Page Add-on
         if (alm.nextpage) {
            if ($('.alm-nextpage', alm.container).length) {
               // `.alm-nextpage` check that posts remain
               var alm_nextpage_pages = $('.alm-nextpage', alm.container).length,
                   alm_nextpage_total = $('.alm-nextpage', alm.container).eq(0).data('total-posts');
               if (alm_nextpage_pages == alm_nextpage_total) {
                  alm.AjaxLoadMore.triggerDone();
               }
            }
            if (alm.resultsText) {
               almInitResultsText(alm, 'nextpage');
            }
         }

         // Window Load (Masonry + Preloaded)
         alm.window.addEventListener('load', function () {
            if (alm.is_masonry_preloaded) {
               almMasonry(alm.masonry_wrap, alm.el, alm.masonry_selector, alm.masonry_columnwidth, alm.masonry_animation, alm.masonry_horizontalorder, alm.speed, alm.masonry_init, true, false);
               alm.masonry_init = false;
            }
         });
      };

      // Init Ajax Load More
      alm.AjaxLoadMore.init();

      // Init flag to prevent unnecessary loading of posts.
      setTimeout(function () {
         alm.proceed = true;
      }, 150);

      /*  $.fn.almUpdateCurrentPage()
       *
       *  Update current page - triggered from paging add-on
       *  @since 2.7.0
       */
      $.fn.almUpdateCurrentPage = function (current, obj, alm) {

         alm.page = current;

         // Next Page add-on
         alm.page = alm.nextpage && !alm.paging ? alm.page - 1 : alm.page;

         var data = '';

         /*
          Paging + Preloaded & Paging + Next Page
          If is paging init and preloaded, grab preloaded data, and append it .alm-reveal
         */

         if (alm.paging_init && alm.preloaded === 'true') {

            // Paging + Preloaded Firstrun
            data = $('.alm-reveal', alm.el).html(); // Content of preloaded page
            $('.alm-reveal', alm.el).remove();
            alm.preloaded_amount = 0; // Reset
            alm.AjaxLoadMore.pagingPreloadedInit(data);
            alm.paging_init = false;
            alm.init = false;
         } else if (alm.paging_init && alm.nextpage) {

            // Paging + Next Page Firstrun
            data = $('.alm-nextpage', alm.el).html();
            $('.alm-nextpage', alm.el).remove();
            alm.AjaxLoadMore.pagingNextpageInit(data);
            alm.paging_init = false;
            alm.init = false;
         } else {

            // Standard Paging
            alm.AjaxLoadMore.loadPosts();
         }
      };

      /*  $.fn.almGetParentContainer()
       *
       *  return the parent ALM container
       *
       *  @since 2.7.0
        * @return element
       */
      $.fn.almGetParentContainer = function () {
         return alm.el.closest('#ajax-load-more');
      };

      /*  $.fn.almGetObj()
       *
       *  return the current ALM obj
       *
       *  @since 2.7.0
       *  @return object
       */
      $.fn.almGetObj = function () {
         return alm; // Return the entire alm object
      };

      /*  $.fn.almTriggerClick()
       *
       *  Trigger ajaxloadmore from any element on page
       *
       *  @since 2.12.0
       *  @return null
       */
      $.fn.almTriggerClick = function () {
         alm.button.click();
      };

      //Custom easing function
      $.easing.alm_easeInOutQuad = function (x, t, b, c, d) {
         if ((t /= d / 2) < 1) {
            return c / 2 * t * t + b;
         }
         return -c / 2 * (--t * (t - 2) - 1) + b;
      };
   };

   // End $.ajaxloadmore


   /** 
      *  $.fn.ajaxloadmore()
      *  Initiate all instances of Ajax load More
      *  @since 2.1.2
      */
   $.fn.ajaxloadmore = function () {
      return this.each(function (e) {
         new ajaxloadmore(this, e);
      });
   };

   /*
    *  Initiate Ajax load More if div is present on screen
    *  @since 2.1.2
    */

   var alm_instances = document.querySelectorAll('.ajax-load-more-wrap');
   if (alm_instances.length) {
      [].concat(_toConsumableArray(alm_instances)).forEach(function (alm, e) {
         new ajaxloadmore(alm, e);
      });
   }
})(jQuery);
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

/*! almWaitForImages
    jQuery Plugin
    v2.0.2
    Based on https://github.com/alexanderdickson/waitForImages
*/
// Include almWaitForImages()
;(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if ((typeof exports === 'undefined' ? 'undefined' : _typeof(exports)) === 'object') {
        // CommonJS / nodejs module
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
})(function ($) {
    // Namespace all events.
    var eventNamespace = 'almWaitForImages';

    // CSS properties which contain references to images.
    $.almWaitForImages = {
        hasImageProperties: ['backgroundImage', 'listStyleImage', 'borderImage', 'borderCornerImage', 'cursor'],
        hasImageAttributes: ['srcset']
    };

    // Custom selector to find all `img` elements with a valid `src` attribute.
    $.expr[':']['has-src'] = function (obj) {
        // Ensure we are dealing with an `img` element with a valid
        // `src` attribute.
        return $(obj).is('img[src][src!=""]');
    };

    // Custom selector to find images which are not already cached by the
    // browser.
    $.expr[':'].uncached = function (obj) {
        // Ensure we are dealing with an `img` element with a valid
        // `src` attribute.
        if (!$(obj).is(':has-src')) {
            return false;
        }

        return !obj.complete;
    };

    $.fn.almWaitForImages = function () {

        var allImgsLength = 0;
        var allImgsLoaded = 0;
        var deferred = $.Deferred();

        var finishedCallback;
        var eachCallback;
        var waitForAll;

        // Handle options object (if passed).
        if ($.isPlainObject(arguments[0])) {

            waitForAll = arguments[0].waitForAll;
            eachCallback = arguments[0].each;
            finishedCallback = arguments[0].finished;
        } else {

            // Handle if using deferred object and only one param was passed in.
            if (arguments.length === 1 && $.type(arguments[0]) === 'boolean') {
                waitForAll = arguments[0];
            } else {
                finishedCallback = arguments[0];
                eachCallback = arguments[1];
                waitForAll = arguments[2];
            }
        }

        // Handle missing callbacks.
        finishedCallback = finishedCallback || $.noop;
        eachCallback = eachCallback || $.noop;

        // Convert waitForAll to Boolean
        waitForAll = !!waitForAll;

        // Ensure callbacks are functions.
        if (!$.isFunction(finishedCallback) || !$.isFunction(eachCallback)) {
            throw new TypeError('An invalid callback was supplied.');
        }

        this.each(function () {
            // Build a list of all imgs, dependent on what images will
            // be considered.
            var obj = $(this);
            var allImgs = [];
            // CSS properties which may contain an image.
            var hasImgProperties = $.almWaitForImages.hasImageProperties || [];
            // Element attributes which may contain an image.
            var hasImageAttributes = $.almWaitForImages.hasImageAttributes || [];
            // To match `url()` references.
            // Spec: http://www.w3.org/TR/CSS2/syndata.html#value-def-uri
            var matchUrl = /url\(\s*(['"]?)(.*?)\1\s*\)/g;

            if (waitForAll) {

                // Get all elements (including the original), as any one of
                // them could have a background image.
                obj.find('*').addBack().each(function () {
                    var element = $(this);

                    // If an `img` element, add it. But keep iterating in
                    // case it has a background image too.
                    if (element.is('img:has-src')) {
                        allImgs.push({
                            src: element.attr('src'),
                            element: element[0]
                        });
                    }

                    $.each(hasImgProperties, function (i, property) {
                        var propertyValue = element.css(property);
                        var match;

                        // If it doesn't contain this property, skip.
                        if (!propertyValue) {
                            return true;
                        }

                        // Get all url() of this element.
                        while (match = matchUrl.exec(propertyValue)) {
                            allImgs.push({
                                src: match[2],
                                element: element[0]
                            });
                        }
                    });

                    $.each(hasImageAttributes, function (i, attribute) {
                        var attributeValue = element.attr(attribute);
                        var attributeValues;

                        // If it doesn't contain this property, skip.
                        if (!attributeValue) {
                            return true;
                        }

                        // Check for multiple comma separated images
                        attributeValues = attributeValue.split(',');

                        $.each(attributeValues, function (i, value) {
                            // Trim value and get string before first
                            // whitespace (for use with srcset).
                            value = $.trim(value).split(' ')[0];
                            allImgs.push({
                                src: value,
                                element: element[0]
                            });
                        });
                    });
                });
            } else {
                // For images only, the task is simpler.
                obj.find('img:has-src').each(function () {
                    allImgs.push({
                        src: this.src,
                        element: this
                    });
                });
            }

            allImgsLength = allImgs.length;
            allImgsLoaded = 0;

            // If no images found, don't bother.
            if (allImgsLength === 0) {
                finishedCallback.call(obj[0]);
                deferred.resolveWith(obj[0]);
            }

            $.each(allImgs, function (i, img) {

                var image = new Image();
                var events = 'load.' + eventNamespace + ' error.' + eventNamespace;

                // Handle the image loading and error with the same callback.
                $(image).one(events, function me(event) {
                    // If an error occurred with loading the image, set the
                    // third argument accordingly.
                    var eachArguments = [allImgsLoaded, allImgsLength, event.type == 'load'];
                    allImgsLoaded++;

                    eachCallback.apply(img.element, eachArguments);
                    deferred.notifyWith(img.element, eachArguments);

                    // Unbind the event listeners. I use this in addition to
                    // `one` as one of those events won't be called (either
                    // 'load' or 'error' will be called).
                    $(this).off(events, me);

                    if (allImgsLoaded == allImgsLength) {
                        finishedCallback.call(obj[0]);
                        deferred.resolveWith(obj[0]);
                        return false;
                    }
                });

                image.src = img.src;
            });
        });

        return deferred.promise();
    };
});