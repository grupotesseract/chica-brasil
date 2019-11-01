/**
 * Atum Data Export
 *
 * @copyright Stock Management Labs ©2019
 *
 * @since 1.2.5
 */

window['$'] = window['jQuery'];


/**
 * Components
 */

import Settings from './config/_settings';
import DataExport from './components/export/_export';


// Modules that need to execute when the DOM is ready should go here.
jQuery( ($) => {
	
	// Get the options from the localized var.
	let settings = new Settings('atumExport');
	new DataExport(settings);
	
});