$(function() {

var moment = rome.moment;
  // rome(inline_cal, { time: false });


	rome(inline_cal, {min:moment() ,time: false, inputFormat: 'YYYY-MM-DD', dateValidator: function (d) {
var daysOfWeek = {
  "Domingo": 0,
  "Lunes": 1,
  "Martes": 2,
  "Miercoles": 3,
  "Jueves": 4,
  "Viernes": 5,
  "Sabado": 6
};

var daysArray = inl.innerText.split(",");
var daysNumbers = daysArray.map(day => daysOfWeek[day.trim()]);

console.log(daysArray);

    return daysNumbers.includes(moment(d).day());
  }}).on('data', function (value) {
	  result.value = value;
      result.dispatchEvent(new Event('change'));
	});

});
