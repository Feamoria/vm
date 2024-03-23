/* Russian initialisation for the jQuery UI multiselect plugin. */
/* Written by Artem Packhomov (gorblnu4@gmail.com). */

(function ( $ ) {

$.extend($.ech.multiselect.prototype.options, {
	linkInfo: {
		checkAll: {text: 'Всё', title: 'Всё'},
		uncheckAll: {text: 'Ничего', title: 'Ничего'}
	},
	noneSelectedText: 'Выберите из списка',
	selectedText: 'Выбрано #'
});
	
})( jQuery );
