/**
 * Atum Marketing Popup
 *
 * @copyright Stock Management Labs ©2019
 *
 * @since 1.5.2
 */

window['$'] = window['jQuery'];

/**
 * Components
 */

import MarketingPopup from './components/_marketing-popup';
import Settings from './config/_settings';


// Modules that need to execute when the DOM is ready should go here.
jQuery( ($) => {
	
	// Get the options from the localized var.
	let settings = new Settings('atumMarketingPopupVars');
	new MarketingPopup(settings);
	
});