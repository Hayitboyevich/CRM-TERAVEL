(function($, undefined){
	var $window = $(window);
    var dpObject = {
        viewMode:'month',
        isLeapYear: function(year){
			return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0));
		},
		getDaysInMonth: function(year, month){
			return [31, (dpObject.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
		},
		validParts: /dd?|DD?|mm?|MM?|yy(?:yy)?/g,
		parseFormat: function(format){
			var separators = format.replace(this.validParts, '\0').split('\0'),
				parts = format.match(this.validParts);
			if (!separators || !separators.length || !parts || parts.length === 0){
				throw new Error("Invalid date format.");
			}
			return {separators: separators, parts: parts};
		},
		formatDate: function(date, format){
			if (!date)
				return '';
			if (typeof format === 'string')
				format = dpObject.parseFormat(format);
			
			var val = {
				d: date.getDate(),
				D: dates.daysShort[date.getDay()],
				DD: dates.days[date.getDay()],
				m: date.getMonth() + 1,
				M: dates.monthsShort[date.getMonth()],
				MM: dates.months[date.getMonth()],
				yy: date.getFullYear().toString().substring(2),
				yyyy: date.getFullYear()
			};
			val.dd = (val.d < 10 ? '0' : '') + val.d;
			val.mm = (val.m < 10 ? '0' : '') + val.m;
			date = [];
			var seps = $.extend([], format.separators);
			
			for (var i=0, cnt = format.parts.length; i <= cnt; i++){
				if (seps.length)
					date.push(seps.shift());
				date.push(val[format.parts[i]]);
			}
			return date.join('');
		},
		isValidDate:function(date){
			if(!date)
				return false;
			var dateStr= new Date(date);
			if( ! isNaN ( dateStr.getMonth() ) ) {
				return true;
			}
			return false;
		},
		isDMYFormat:function(dateStr,retrunDate){
			if(!dateStr)
				return false;
			var dateArray = dateStr.split('-');
			var monthInd = dates.monthsShort.indexOf(dateArray[1]); 
			if(dateArray.length == 3 && monthInd > -1)
				return retrunDate ? new Date(dateArray[2],monthInd,dateArray[0]) : true;
			return false;
		},
        monthsOfQuarters:{'Q1':1,'Q2':4,'Q3':7,'Q4':10},
        contTemplate:'<div id="custom-datepicker" class="custom-datepicker">',
		headerTemplate:'<div class="custom-dp-header">'+
							'<div class="month-dropdown-view">'+
								'<a id="prev-month" class="prev-month" data-attr="month"> prev </a>'+
								'<select class="datepicker-month-dropdown"></select>'+
								'<a id="next-month" class="next-month" data-attr="month"> next </a>'+
							'</div>'+
							'<div class="quarter-dropdown-view">'+
								'<a id="prev-quarter" class="prev-quarter" data-attr="quarter"> prev </a>'+
								'<select class="datepicker-quarter-dropdown">'+
									'<option value ="Q1">Q1</option>'+
									'<option value="Q2">Q2</option>'+
									'<option value="Q3">Q3</option>'+
									'<option value="Q4">Q4</option>'+
								'</select>'+
								'<a id="next-quarter" class="next-quarter" data-attr="quarter"> next </a>'+
							'</div>'+
							'<div class="year-dropdown-view">'+
								'<a id="prev-year" class="prev-year" data-attr="year"> prev </a>'+
								'<select class="datepicker-year-dropdown"></select>'+
								'<a id="next-year" class="next-year" data-attr="year"> next </a>'+
							'</div>'+
						'</div>',
		footerTemplate:'<div class="newLinks">'+
							'<a id="switch-quarter-view" class="switch-view quarter-view" href="javascript:void(0);"> Q </a>'+
							'<a id="switch-month-view" class="switch-view" href="javascript:void(0);">M</a>'+
						'</div>'
    };
    dpObject.template = '<div id="custom-dp-wrapper" class="'+dpObject.viewMode+'-view" style="display:none;">'+
                            '<div class="custom-dp">'+
                            dpObject.headerTemplate +
                            dpObject.contTemplate +
							'</div>' +
                            dpObject.footerTemplate +'</div>';

                            
    var jqueryDatepicker = function(element, options){
        this.element = $(element);
        this.isInline = false;
		this.focusDate = null;
		this.dates = dates;
        this.isInput = this.element.is('input');
		this._process_options(options);
		this.viewMode = dpObject.viewMode = this.o.defaultView;
        this.dpWrapper = $(dpObject.template);
        this.dpMDropdown = this.dpWrapper.find('.datepicker-month-dropdown');
        this.dpQDropdown = this.dpWrapper.find('.datepicker-quarter-dropdown');
        this.dpYDropdown = this.dpWrapper.find('.datepicker-year-dropdown');
        this._buildEvents();
		this._attachEvents();
    };
    jqueryDatepicker.prototype = {
        constructor: jqueryDatepicker,
        _process_options: function(opts){
            this._o = $.extend({}, this._o, opts);
			var o = this.o = $.extend({}, this._o);
			this.viewDate = new Date();
			if(this.o.defaultDate && (dpObject.isValidDate(this.o.defaultDate) || dpObject.isDMYFormat(this.o.defaultDate))){
				if(dpObject.isDMYFormat(this.o.defaultDate)){
					this.o.defaultDate = dpObject.isDMYFormat(this.o.defaultDate,true);
				}
				this.setValue(new Date(this.o.defaultDate));
				this.viewDate = new Date(this.o.defaultDate);
			}
			this.selectedYear = this.viewDate.getFullYear();	
			if(this.o.minDate && dpObject.isValidDate(this.o.minDate))
				this.o.minDate = new Date(this.o.minDate);
			if(this.o.maxDate && dpObject.isValidDate(this.o.maxDate))
				this.o.maxDate = new Date(this.o.maxDate);
        },
        _events: [],
		_secondaryEvents: [],
		_applyEvents: function(evs){
			for (var i=0, el, ch, ev; i < evs.length; i++){
				el = evs[i][0];
				if (evs[i].length === 2){
					ch = undefined;
					ev = evs[i][1];
				}
				else if (evs[i].length === 3){
					ch = evs[i][1];
					ev = evs[i][2];
				}
				el.on(ev, ch);
			}
		},
		_unapplyEvents: function(evs){
			for (var i=0, el, ev, ch; i < evs.length; i++){
				el = evs[i][0];
				if (evs[i].length === 2){
					ch = undefined;
					ev = evs[i][1];
				}
				else if (evs[i].length === 3){
					ch = evs[i][1];
					ev = evs[i][2];
				}
				el.off(ev, ch);
			}
		},
		_buildEvents: function(){
			if (this.isInput){ // single input
				this._events = [
					[this.element, {
						focus: $.proxy(this.show, this)
					}]
				];
			}
			else if (this.element.is('div')){  // inline datepicker
				this.isInline = true;
			}
			else {
				this._events = [
					[this.element, {
						click: $.proxy(this.show, this)
					}]
				];
			}
			this._events.push(
				// Component: listen for blur on element descendants
				[this.element, '*', {
					blur: $.proxy(function(e){
						this._focused_from = e.target;
					}, this)
				}],
				// Input: listen for blur on element
				[this.element, {
					blur: $.proxy(function(e){
						this._focused_from = e.target;
					}, this)
				}]
			);

			this._secondaryEvents = [
				[this.dpWrapper, {
					click: $.proxy(this.click, this)
				}],
				[this.dpMDropdown, {
					change: $.proxy(this.changeMonth, this)
				}],
				[this.dpYDropdown, {
					change: $.proxy(this.changeYear, this)
				}],
				[this.dpQDropdown, {
					change: $.proxy(this.changeQuarter, this)
				}],
				[this.dpWrapper.find('.prev-month,.next-month'), {
					click: $.proxy(this.nextPrevMonth, this)
				}],
				[this.dpWrapper.find('.prev-year,.next-year'), {
					click: $.proxy(this.nextPrevYear, this)
				}],
				[this.dpWrapper.find('.prev-quarter,.next-quarter'), {
					click: $.proxy(this.nextPrevQuarter, this)
				}],
				[this.dpWrapper.find('.switch-view'), {
					click: $.proxy(this.switchView, this)
				}],
				[$(window), {
					resize: $.proxy(this.place, this)
				}],
				[$(document), {
					'mousedown touchstart': $.proxy(function(e){
						// Clicked outside the datepicker, hide it
						if (!(
							this.element.is(e.target) ||
							this.element.find(e.target).length ||
							this.dpWrapper.is(e.target) ||
							this.dpWrapper.find(e.target).length
						)){
							this.hide();
						}
					}, this)
				}],
			];
		},
		_attachEvents: function(){
			this._detachEvents();
			this._applyEvents(this._events);
		},
		_detachEvents: function(){
			this._unapplyEvents(this._events);
		},
		_attachSecondaryEvents: function(){
			this._detachSecondaryEvents();
			this._applyEvents(this._secondaryEvents);
		},
		_detachSecondaryEvents: function(){
			this._unapplyEvents(this._secondaryEvents);
		},
		_trigger: function(event, altdate){
			this.element.trigger({
				type: event
			});
		},
		click: function(e){
			e.preventDefault();
			var target = $(e.target).closest('span, td.dp-day, th'),
				year, month, day;
			if (target.length === 1){
				if(target.hasClass('dp-day') && !target.hasClass('disabled') && !target.hasClass('non-day')){
					year = target.find('a').attr('data-year');
					month = target.find('a').attr('data-month');
					day = target.find('a').text();
					var date = new Date(year,(month >= 1?(month-1):month),day);
					this.setValue(date);
					this.o.onSelect(date);
					if(this.o.closeOnSelect){
						this.hide();
					}
				}
			}
		},
        show: function(){
			if(this.dpWrapper.is(':visible'))
				return;
			if (!this.isInline)
				this.dpWrapper.appendTo('body');
			this.o.beforeShow(this.element,this);
            this.dpWrapper.show();
			this.createCalendar();
			$this = this;
			this.place();
			this._attachSecondaryEvents();
			this._trigger('show');
		},
		hide: function(){
			if (this.isInline)
				return;
			if (!this.dpWrapper.is(':visible'))
				return;
			this.focusDate = null;
			this.dpWrapper.hide().detach();
			this._detachSecondaryEvents();
			//this.viewMode = this.o.defaultView;
			this._trigger('hide');
		},
		remove: function(){
			this.hide();
			this._detachEvents();
			this._detachSecondaryEvents();
			this.dpWrapper.remove();
			delete this.element.data().datepicker;
			if (!this.isInput){
				delete this.element.data().date;
			}
        },
        place: function(){
			if (this.isInline)
				return;
			var calendarHeight = this.dpWrapper.outerHeight(false),
				calendarWidth = this.dpWrapper.outerWidth(false),
				windowWidth = $window.width(),
				windowHeight = $window.height(),
				scrollTop = $window.scrollTop();
			var zIndex = parseInt(this.element.parents().filter(function(){
					return $(this).css('z-index') !== 'auto';
				}).first().css('z-index'))+10;
			var offset = this.element.offset();
			var height = this.element.outerHeight(false);
			var width = this.element.outerWidth(false);
			var left = offset.left,
				top = offset.top;
			console.log(offset);
			this.dpWrapper.removeClass('datepicker-orient-top datepicker-orient-bottom');

			var yorient = 'auto',
				top_overflow, bottom_overflow;
			if (yorient === 'auto'){
				top_overflow = -scrollTop + offset.top - calendarHeight;
				bottom_overflow = scrollTop + windowHeight - (offset.top + height + calendarHeight);
				if (Math.max(top_overflow, bottom_overflow) === bottom_overflow)
					yorient = 'top';
				else
					yorient = 'bottom';
			}
			this.dpWrapper.addClass('datepicker-orient-' + yorient);
			var dpPaddingTop = parseInt(this.dpWrapper.css('padding-top'));
			if (yorient === 'top')
				top += height;
			else
				top -= calendarHeight + (!isNaN(dpPaddingTop) ? dpPaddingTop : 0);

			var viewViseWidth = calendarWidth - 319;
			if(this.viewMode == 'quarter'){
				left = left-viewViseWidth > 0 ? left-viewViseWidth : left;
			}else{
				left = windowWidth < (left+calendarWidth) ? ((left-calendarWidth)+width) : left; 
			}
			this.dpWrapper.css({
                position:'absolute',
				top: top,
				left: left,
				zIndex: zIndex
			});
		},
		switchView:function(e){
			if($(e.target).hasClass('disabled'))
				return;
			var id=$(e.target).attr('id');
			var mode = (id == 'switch-month-view') ? 'month' : 'quarter';
			if(this.viewMode !=mode){
				this.toggleView();
			}
		},
		nextPrevMonth:function(e){
			if($(e.target).hasClass('disabled'))
				return;
			var month=parseInt(this.dpMDropdown.val());
			var year=parseInt(this.dpYDropdown.val());
			if($(e.target).hasClass('next-month')){
				if((month+1)==12){
					month = 0;
					year = year + 1;
				}else{
					month = month+1;	
				}
			}else{
				if((month-1) < 0){
					month = 11;
					year = year - 1;
				}else{
					month = month-1;	
				}
			}
			this.dpMDropdown.val(month);
			this.dpYDropdown.val(year);
			this.createCalendar((month+1),year);
		},
		nextPrevYear:function(e){
			if($(e.target).hasClass('disabled'))
				return;
			var month=parseInt(this.dpMDropdown.val());
			var year=parseInt(this.dpYDropdown.val());
			var newYear = year;
			if($(e.target).hasClass('next-year')){
				if(this.dpYDropdown.find('option[value="'+(year+1)+'"]').length == 1){
					newYear = newYear+1;
				}
			}else{
				if(this.dpYDropdown.find('option[value="'+(year-1)+'"]').length == 1){
					newYear = newYear-1;
				}
			}
			if(newYear!=year){
				this.dpYDropdown.val(newYear);
				this.createCalendar((month+1),newYear);
			}
		},
		nextPrevQuarter:function(e){
			if($(e.target).hasClass('disabled'))
				return;
			var Q = this.dpQDropdown.val();
			var year=parseInt(this.dpYDropdown.val());
			var newYear = year;
			var QParts = Q.split('');
			if($(e.target).hasClass('next-quarter')){
				if(Q == 'Q4'){
					Q = 'Q1';
					newYear = year+1;
				}else{
					Q = 'Q'+(parseInt(QParts[1])+1);
				}
			}else{
				if(Q == 'Q1'){
					Q = 'Q4';
					newYear = year-1;
				}else{
					Q = 'Q'+(parseInt(QParts[1])-1);
				}
			}
			var month=dpObject.monthsOfQuarters[Q];
			this.dpYDropdown.val(newYear);
			this.dpQDropdown.val(Q);
			this.createCalendar((month+1),newYear);
		},
		changeMonth:function(e,month){
			if(!month){
				month=parseInt($(e.target).val())+1;
			}
			var year=parseInt(this.dpYDropdown.val());
			this.createCalendar(month,year);
		},
		changeYear:function(e,year){
			if(!year){
				year=parseInt($(e.target).val());
			}
			var month=parseInt(this.dpMDropdown.val())+1;
			this.createCalendar(month,year);
		},
		changeQuarter:function(e,Q){
			if(!Q){
				Q=$(e.target).val();
			}
			var month=dpObject.monthsOfQuarters[Q];
			var year=parseInt(this.dpYDropdown.val());
			this.createCalendar(month,year);
		},
		createMYDropdown:function(selectedM,selectedY){
			var $monthsHtml='';
			for (var i=0; i<12;i++){
				$monthsHtml +='<option value="'+i+'" '+(selectedM==i?'selected':'')+'>'+this.dates.monthsShort[i]+'</option>'; 
			}
			this.dpMDropdown.html($monthsHtml);
			var yearRangeStr=this._o.yearRangeVal.split(':');
			var startYear = selectedY + parseInt(yearRangeStr[0]);
			var endYear = selectedY + parseInt(yearRangeStr[1]);
			var $yearsHtml='';
			for (var j=startYear; j <= endYear;j++){
				$yearsHtml +='<option value="'+j+'" '+(selectedY==j?'selected':'')+'>'+j+'</option>'; 
			}
			this.dpYDropdown.html($yearsHtml);
		},
        createCalendar : function(month,year){
			this.dpWrapper.find('.custom-datepicker').html('');
			this.dpWrapper.removeClass('quarter-view month-view').addClass(this.viewMode+'-view');
			this.viewDate = this.element.val() ? ( !dpObject.isDMYFormat(this.element.val()) ? new Date(this.element.val()) : dpObject.isDMYFormat(this.element.val(),true) ) : new Date();
			if(!month && !year){
				month=this.viewDate.getMonth() + 1;
				year=this.viewDate.getFullYear();
				this.createMYDropdown((month-1),year);
			}
            if(this.viewMode=='month'){
				//update month view
				this.createMonth(month,year);
			}else if(this.viewMode=='quarter'){
				//update quarter view
				var Q=this.getQuarter((month-1));
				this.dpQDropdown.val(Q);
				month=dpObject.monthsOfQuarters[Q];
				for (var i=0;i<3;i++){
					this.createMonth((month+i),year);
				}
			}
		},
		createMonth: function(month,year){
			var $table = $('<div class="single-calender" data-month-value="'+month+'"><div class="custom-datepicker-title"><span class="custom-datepicker-month">'+this.dates.months[month-1]+'</span></div><table class=""><thead></thead><tbody></tbody></table></div>');
			this.createWeekNames($table);
			var totalDaysOfMonth = dpObject.getDaysInMonth(year,(month-1)),
			currentRow = 1,
			date = new Date(month+'/01/'+year).getDate(),
			day = new Date(month+'/01/'+year).getDay();
			var currentDay = this.getCalendarStart(day,date);
			var $week = this.getCalendarRow($table),
			$day,$dayDate,j = 1,todayday = new Date().getDate(),currentMonth = new Date().getMonth(),currentYear = new Date().getFullYear(),selectedDate = this.element.val() ? ( !dpObject.isDMYFormat(this.element.val()) ? new Date(this.element.val()) : dpObject.isDMYFormat(this.element.val(),true) ) : undefined;
			
			for (; j <= totalDaysOfMonth; j++) {
				$day = $week.find('td').eq(currentDay);
				$day.removeClass('non-day');
				$day.find('a').text(j).attr({'data-month':month,'data-year':year});
				$dayDate = new Date(year,(month-1),j);
				var isDisabled = false;
				if (j === todayday && currentMonth == (month-1) && currentYear == year ) {
					$day.addClass('today');
				}
				if(selectedDate!=undefined){
					if(j === selectedDate.getDate() && (month-1) == selectedDate.getMonth() && selectedDate.getFullYear() == year)
						$day.addClass('active');
				} 
				//check for disable dates
				if(this.o.minDate){
					if($dayDate.getTime() < this.o.minDate.getTime())
						isDisabled = true;
				}
				if(this.o.maxDate){
					if($dayDate.getTime() > this.o.maxDate.getTime())
						isDisabled = true;
				}

				if(isDisabled){
					$day.addClass('disabled');
				}
				// +1 next day until Saturday (6), then reset to Sunday (0)
				currentDay = ++currentDay % 7;
				// Generate new row when day is Saturday, but only if there are
				// additional days to render
				if (currentDay === 0 && (j + 1 <= totalDaysOfMonth)) {
					$week = this.getCalendarRow($table);
					currentRow++;
				}
			}
			this.dpWrapper.find('.custom-datepicker').append($table);
		},
		getCalendarRow: function($table){
			var $tr = $('<tr/>');
			for (var i = 0, len = 7; i < len; i++) {
				$tr.append($('<td class="dp-day non-day"><a href="javascript:void(0);"></a></td>'));
			}
			$table.find('tbody').append($tr);
			return $tr;
		},
		createWeekNames:function($table){
			var $tr = $('<tr/>');
			for (var i = 0, len = 7; i < len; i++) {
				$tr.append('<td>'+ this.dates.daysShortest[i] +'</td>');
			}
			$table.find('thead').append($tr);
		},
		getCalendarStart: function(dayOfWeek, currentDate) {
			var date = currentDate - 1;
			var startOffset = (date % 7) - dayOfWeek;
			if (startOffset > 0) {
			  startOffset -= 7;
			}
			return Math.abs(startOffset);
		},
		getQuarter: function(month,date){
			month = (month >= 0) ? month : (date ? date.getMonth() : new Date().getMonth());
			var slectedQ = "Q1";
			if (month >= 0 && month < 3) {
				slectedQ = "Q1";
			} else if (month >= 3 && month < 6) {
				slectedQ = "Q2";
			} else if (month >= 6 && month < 9) {
				slectedQ = "Q3";
			} else if (month >= 9 && month < 12) {
				slectedQ = "Q4";
			}
			return slectedQ;
    	},
        toggleView : function(){
			var prevView = this.viewMode;
			this.viewMode = this.viewMode=='month'? 'quarter' : 'month';
			var year=parseInt(this.dpYDropdown.val());
			var month=parseInt(this.dpMDropdown.val())+1;
			var Q=this.dpQDropdown.val();
			if(prevView =='month'){
				Q = this.getQuarter((month-1));
				this.dpQDropdown.val(Q);
			}else{
				var monthViewQuarter = this.getQuarter((month-1));
				if(monthViewQuarter != Q){
					month=dpObject.monthsOfQuarters[Q];
					this.dpMDropdown.val(month-1);
				}
			}
			this.createCalendar(month,year);
			this.place();
		},
		setValue: function(date){
			var formatted = this.getFormattedDate(date);
			if (!this.isInput){
				if (this.component){
					this.element.find('input').val(formatted).change();
				}
			}else {
				this.element.val(formatted).change();
			}
		},
		getFormattedDate: function(d,format){
			if (format === undefined)
				format = this.o.format;
			return dpObject.formatDate(d, format);
		}
    };
   
    $(document).on(
		'focus.custom-datepicker-input click.custom-datepicker-input',
		function(e){
			var $this = $(this);
			if ($this.data('datepicker'))
				return;
			e.preventDefault();
		}
	);
    $.fn.jqueryDatePicker = function (option) {
        return this.each(function () {
            var $this = $(this),
				data = $this.data('datepicker'),
                options = typeof option === 'object' && option;
            if (!data){
                var opts = $.extend({}, defaults, options);
                $this.data('datepicker', (data = new jqueryDatepicker(this, opts)));
            }
        });
    };
    var defaults = $.fn.jqueryDatePicker.defaults = {
        yearRangeVal: "-20:+20",
        defaultView: 'month',// can be `month` or `quarter`
        defaultDate: null,
        format: 'mm/dd/yyyy',
        minDate: null,
		maxDate: null,
		closeOnSelect:true,
        onSelect: $.noop,
        beforeShow: $.noop
	};
	var dates = $.fn.jqueryDatePicker.dates = {
			days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
			daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
			daysShortest: ["S", "M", "T", "W", "T", "F", "S"],
			months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
		};
    $.fn.jqueryDatePicker.dpObject = dpObject;
})(window.jQuery);