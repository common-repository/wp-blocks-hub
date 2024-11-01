'use strict';

window.WPBHHelper = {

	toBoolean: function( string ) {
    switch( string ){
			case "true": case "yes": case "1": return true;
			case "false": case "no": case "0": case null: return false;
			default: return Boolean( string );
		}
	},

	formatCurrency: function( number, decimals, dec_point, thousands_sep, currency, currencyPos) {

    var n = !isFinite(+number) ? 0 : +number, 
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		toFixedFix = function (n, prec) {
			// Fix for IE parseFloat(0.55).toFixed(0) = 0;
			var k = Math.pow(10, prec);
			return Math.round(n * k) / k;
		},
		s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
    if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		
    var str = s.join(dec);

		switch( currencyPos ) {
			case 'left':
				str = currency + str;
			break;
			case 'right':
				str = str + currency;
			break;
			case 'left_space':
				str = currency + ' ' + str;
			break;
			case 'right_space':
				str = str + ' ' + currency;
			break;
		}

		return str;
	}

};