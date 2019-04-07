define([
		'jquery',
		'jquery/ui',
		'mage/redirect-url'
	],function($){
	'use strict';
										 // return from mage/redirect-url.js	
	$.widget('magelicious.redirecturl', $.mage.redirectUrl, {
		_onEvent: function () {
			console.log('raw extend');
			return this._super; 
		}
	});

	return $.mage.redirectUrl;
});