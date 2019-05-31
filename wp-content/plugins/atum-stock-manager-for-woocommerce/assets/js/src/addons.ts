/**
 * Atum Addons
 *
 * @copyright Stock Management Labs ©2019
 *
 * @since 1.2.0
 */

window['$'] = window['jQuery'];

/**
 * Components
 */

import Settings from './config/_settings';
import AddonsPage from './components/addons-page/_addons-page';


// Modules that need to execute when the DOM is ready should go here.
jQuery( ($) => {
	
	// Get the options from the localized var.
	let settings = new Settings('atumAddons');
	new AddonsPage(settings);
	
});