/* =======================================
   FILTERS FOR LIST TABLES
   ======================================= */

import Settings from '../../config/_settings';
import Globals from './_globals';
import ListTable from './_list-table';
import Router from './_router';
import Tooltip from '../_tooltip';
import DateTimePicker from '../_date-time-picker';
import { Utils } from '../../utils/_utils';

export default class Filters {
	
	constructor(
		private settings: Settings,
		private globals: Globals,
		private listTable: ListTable,
		private router: Router,
		private tooltip: Tooltip,
		private dateTimePicker: DateTimePicker
	) {
		
		//
		// Ajax filters.
		// -------------
		if (this.settings.get('ajaxFilter') === 'yes') {
			
			this.globals.$atumList
				
				// Dropdown filters.
				.on('change', '.dropdown_product_cat, .dropdown_product_type, .dropdown_supplier, .dropdown_extra_filter', (evt: JQueryEventObject) => {
					this.keyUp(evt);
				})
				
				// Search filter.
				.on('keyup paste search input', '.atum-post-search', (evt: JQueryEventObject) => {
					
					let searchColumnBtnVal: string = this.globals.$searchColumnBtn.data('value'),
					    $searchInputVal: any       = $(evt.currentTarget).val();
					
					Utils.delay( () => {
						this.pseudoKeyUpAjax(searchColumnBtnVal, $searchInputVal);
					}, 500);
					
				})
				
				// Pagination input changes.
				.on('keyup paste', '.current-page', (evt: JQueryEventObject) => {
					this.keyUp(evt);
				});
			
			
			if (this.settings.get('searchDropdown') === 'yes') {
				
				this.globals.$searchColumnBtn.on('atum-search-column-data-changed', (evt: JQueryEventObject) => {
					this.pseudoKeyUpAjax($(evt.currentTarget).data('value'), this.globals.$searchInput.val());
				});
				
			}
			
		}
		
		//
		// Non-ajax filters.
		// -----------------
		else {
			
			let $searchSubmitBtn: JQuery = this.globals.$searchInput.siblings('.search-submit');
			
			if (!this.globals.$searchInput.val()) {
				$searchSubmitBtn.prop('disabled', true);
			}
			
			// If s is empty, search-submit must be disabled and ?s removed.
			// If s and searchColumnBtnVal have values, then we can push over search.
			this.globals.$searchInput.on('input', (evt: JQueryEventObject) => {
				
				let searchColumnBtnVal: string = this.globals.$searchColumnBtn.data('value'),
				    inputVal: any              = $(evt.currentTarget).val();
				
				if (!inputVal) {
					
					$searchSubmitBtn.prop('disabled', true);
					
					if (inputVal != $.address.parameter('s')) {
						$.address.parameter('s', '');
						$.address.parameter('search_column', '');
						this.router.updateHash(); // Force clean search.
					}
					
				}
				// Uncaught TypeError: Cannot read property 'length' of undefined (redundant check fails).
				else if ( typeof searchColumnBtnVal !== 'undefined' && searchColumnBtnVal.length > 0) {
					$searchSubmitBtn.prop('disabled', false);
				}
				else if (inputVal) {
					$searchSubmitBtn.prop('disabled', false);
				}
				
			});
			
			// TODO on init address, check s i search_column values, and disable or not
			// When a search_column changes, set ?s and ?search_column if s has value. If s is empty, clean this two parameters.
			if (this.settings.get('searchDropdown') === 'yes') {
				
				// TODO: IS THIS WORKING? IS NOT ONLY FOR AJAX FILTERS?
				this.globals.$searchColumnBtn.on('atum-search-column-data-changed', (evt: JQueryEventObject) => {
					
					let searchInputVal: any        = this.globals.$searchInput.val(),
					    searchColumnBtnVal: string = $(evt.currentTarget).data('value');
					
					if (searchInputVal.length > 0) {
						$.address.parameter('s', searchInputVal);
						$.address.parameter('search_column', searchColumnBtnVal);
						this.keyUp(evt);
					}
					// Force clean s when required.
					else {
						$.address.parameter('s', '');
						$.address.parameter('search_column', '');
					}
					
				});
				
			}
			
			this.globals.$atumList.on('click', '.search-category, .search-submit', () => {
				
				let searchInputVal: any        = this.globals.$searchInput.val(),
				    searchColumnBtnVal: string = this.globals.$searchColumnBtn.data('value');
				
				$searchSubmitBtn.prop('disabled', typeof searchColumnBtnVal !== 'undefined' && searchColumnBtnVal.length === 0 ? true : false);
				
				if (searchInputVal.length > 0) {
					$.address.parameter('s', this.globals.$searchInput.val());
					$.address.parameter('search_column', this.globals.$searchColumnBtn.data('value'));
					
					this.router.updateHash();
				}
				// Force clean s when required.
				else {
					$.address.parameter('s', '');
					$.address.parameter('search_column', '');
					this.router.updateHash();
				}
				
			});
			
		}
		
		//
		// Events common to all filters.
		// -----------------------------
		this.globals.$atumList
		
			//
			// Reset Filters button.
			// ---------------------
			.on('click', '.reset-filters', (evt: JQueryEventObject) => {
				
				this.tooltip.destroyTooltips();
				
				// TODO reset s and column search
				$.address.queryString('');
				this.globals.$searchInput.val('');
				
				if (this.settings.get('searchDropdown') === 'yes' && this.globals.$searchColumnBtn.data('value') !== 'title') {
					this.globals.$searchColumnBtn.trigger('atum-search-column-set-data', ['title', $('#search_column_dropdown').data('product-title') + ' <span class="caret"></span>']);
				}
				
				this.listTable.updateTable();
				$(evt.currentTarget).addClass('hidden');
				
			})
			
			.on('atum-table-updated', () => this.addDateSelectorFilter());
		
		
		//
		// Add date selector filter.
		// -------------------------
		this.addDateSelectorFilter();
		
	}
	
	/**
	 * Search box keyUp event callback
	 *
	 * @param Object  evt       The event data object.
	 * @param Boolean noTimer   Optional. Whether to delay before triggering the update (used for autosearch).
	 */
	keyUp(evt: JQueryEventObject, noTimer?: boolean) {
		
		let delay: number       = 500,
		    searchInputVal: any = this.globals.$searchInput.val();
		
		noTimer = noTimer || false;
		
		/*
		 * If user hits enter, we don't want to submit the form.
		 * We don't preventDefault() for all keys because it would also prevent to get the page number!
		 *
		 * Also, if the 's' param is empty, we don't want to search anything.
		 */
		if (evt.type !== 'keyup' || searchInputVal.length > 0) {
			
			if (13 === evt.which) {
				evt.preventDefault();
			}
			
			if (noTimer) {
				this.router.updateHash();
			}
			else {
				/*
				 * Now the timer comes to use: we wait half a second after
				 * the user stopped typing to actually send the call. If
				 * we don't, the keyup event will trigger instantly and
				 * thus may cause duplicate calls before sending the intended value.
				 */
				Utils.delay( () => {
					this.router.updateHash();
				}, delay);
				
			}
			
		}
		else {
			evt.preventDefault();
		}
		
	}
	
	pseudoKeyUpAjax(searchColumnBtnVal: string, searchInputVal: any) {
		
		if (searchInputVal.length === 0) {
			
			if (searchInputVal != $.address.parameter('s')) {
				$.address.parameter('s', '');
				$.address.parameter('search_column', '');
				this.router.updateHash(); // Force clean search.
			}
			
		}
		else if (typeof searchColumnBtnVal != 'undefined' && searchColumnBtnVal.length > 0) {
			$.address.parameter('s', searchInputVal);
			$.address.parameter('search_column', searchColumnBtnVal);
			this.router.updateHash();
		}
		else if (searchInputVal.length > 0) {
			$.address.parameter('s', searchInputVal);
			this.router.updateHash();
		}
		
	}
	
	/**
	 * Add the date selector filter
	 */
	addDateSelectorFilter() {
		
		let linkedFilters: string[] = this.settings.get('dateSelectorFilters'),
		    $dateSelector: JQuery   = this.globals.$atumList.find('.date-selector'),
		    dateFromVal: string     = $.address.parameter('date_from') ? $.address.parameter('date_from') : $('.date_from').val(),
		    dateToVal: string       = $.address.parameter('date_to') ? $.address.parameter('date_to') : $('.date_to').val();
		
		if ( ! dateToVal ) {
			
			let today: Date = new Date(),
			    dd: any     = today.getDate(),
			    mm: any     = today.getMonth() + 1, // January is 0.
			    yyyy: any   = today.getFullYear();
			
			if (dd < 10) {
				dd = '0' + dd.toString();
			}
			
			if (mm < 10) {
				mm = '0' + mm.toString();
			}
			
			dateToVal = yyyy + '-' + mm + '-' + dd;
			
		}
		
		$dateSelector.on('select2:select', (evt: any) => {
			
			const $select: JQuery = $(evt.currentTarget);
		
			if ( $.inArray($select.val(), linkedFilters) > -1 ) {
				
				$select.val('');
				
				const popupClass: string = 'filter-range-dates-modal',
				      swal: any          = window['swal'];
				
				swal({
					customClass    : popupClass,
					width          : 440,
					showCloseButton: true,
					title          : `<h1 class="title">${ this.settings.get('setTimeWindow') }</h1><span class="sub-title">${ this.settings.get('selectDateRange') }</span>`,
					html           : `
						<div class="input-date">
							<label for="date_from">${ this.settings.get('from') }</label><br/>
							<input type="text" placeholder="Beginning" class="date-picker date_from" name="date_from" id="date_from" maxlength="10" value="${ dateFromVal }">
						</div>
						<div class="input-date">
							<label for="date_to">${ this.settings.get('to') }</label><br/>
							<input type="text" class="date-picker date_to" name="date_to" id="date_to" maxlength="10" value="${ dateToVal }">
						</div>
						<button class="btn btn-warning apply">${ this.settings.get('apply') }</button>
					`,
					showConfirmButton: false,
					onOpen           : () => {
						
						// Init date time pickers.
						this.dateTimePicker.addDateTimePickers($('.date-picker'), {minDate: false});
						
						$('.' + popupClass).find('.swal2-content .apply').on('click', () => {
							this.keyUp(evt);
							swal.close();
						});
						
						$('.' + popupClass).find('.swal2-close').on('click', () => {
							$('.' + popupClass).find('.date_to, .date_from').val('');
						});
					
					},
					onClose: () => {
						
						if ( this.settings.get('ajaxFilter') === 'yes' ) {
							this.keyUp(evt);
						}
						
					},
					
				})
				.catch(swal.noop);
				
			}
			
		});
		
	}
	
}
