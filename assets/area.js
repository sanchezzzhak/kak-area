(function ($) {

  /**
   * @typedef {Object} kakAreaOption
   * @property {string} insertButton
   * @property {string} deleteButton
   * @property {string} insertPosition
   * @property {number} maxItems
   * @property {number} minItems
   */

  var EVENTS = {
	beforeInsert: 'beforeInsert',
	afterInsert: 'afterInsert',
	beforeDelete: 'beforeDelete',
	afterDelete: 'afterDelete',
	limitReached: 'limitReached'
  };

  var AREA_ITEM_CLASS = '.area-item';

  // **********************************
  // Constructor
  // **********************************
  /**
   * @param {HTMLElement|jQuery} element
   * @param {kakAreaOption} options
   */
  var kakArea = function (element, options) {
	this.element = $(element);
	this.container = $('#' + options.areaId);
	this.options = options;
	this.init();
  };

  kakArea.prototype = {
	constructor: kakArea,
	init: function () {
	  this.destroy();
	  this.create();
	},

	create: function () {
	  var isInit = this.element.data('kak-area-init');
	  if (isInit) {
		return;
	  }

	  this.element.on('click', this.options.deleteButton, this.onDeleteItem.bind
		? this.onDeleteItem.bind(this)
		: $.proxy(this.onDeleteItem, this)
	  );

	  this.element.on('click', this.options.insertButton, this.onInsertItem.bind
		? this.onInsertItem.bind(this)
		: $.proxy(this.onInsertItem, this)
	  );

	  this.element.data('kak-area-init', true);
	},

	destroy: function () {
	  this.element.find(this.options.insertButton).off('click');
	  this.element.find(this.options.deleteButton).each(function () {
		$(this).off('click')
	  })
	},


	getCountTotalItems: function () {
	  return this.container.find(AREA_ITEM_CLASS).length;
	},

	updateItem: function (item) {
	  var self = this;
	  var index = item.index();
	  item.find('*').each(function () {
		var el = $(this);
		self.updateAttr(el, index);
		self.updateName(el, index);
		self.updateTmpl(el, index);
	  });
	},

	updateAttr: function (el, index) {
	},

	updateTmpl: function (el, index) {
	  var dataTmpl = el.data('tmpl');
	  var dataRole = el.data('tmpl-role');
	  var template = tmpl(dataTmpl, {index: index})
	  switch (dataRole) {
		case 'attr':
		  el.attr(el.data('tmpl-attr'), template);
		case 'content':
		  el.html(template);
	  }
	},

	updateName: function (el, index) {
	},


	onInsertItem: function (event) {
	  var max = parseInt(this.options.maxItems);
	  if (this.getCountTotalItems() > max) {
		this.element.trigger(EVENTS.limitReached);
		return;
	  }
	  var target = $(event.currentTarget);
	  var item = $(tmpl(this.options.tmplId, {}));
	  this.element.trigger(EVENTS.beforeInsert, [item]);

	  if (this.options.insertPosition === 'top') {
		this.container.prepend(item);
	  } else {
		this.container.append(item);
	  }

	  this.element.trigger(EVENTS.afterInsert, [item]);
	},

	onDeleteItem: function (event) {
	  var min = parseInt(this.options.minItems);
	  if (this.getCountTotalItems() > min) {
		var target = $(event.currentTarget);
		var item = target.closest(AREA_ITEM_CLASS);
		this.element.trigger(EVENTS.beforeDelete, [item]);
		item.remove();
		this.element.trigger(EVENTS.beforeDelete);
		return;
	  }
	  this.element.trigger(EVENTS.limitReached);
	}
  };

  $.fn.kakArea = function (option) {
	var options = typeof option === 'object' && option;
	new kakArea(this, options);
	return this;
  };
  $.fn.kakArea.Constructor = kakArea;


})(jQuery)