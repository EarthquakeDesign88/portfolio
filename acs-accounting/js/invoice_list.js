function numberFormat(number, decimals = 0, dec_point = '.', thousands_sep = ',') {
    number = parseFloat(number);

    if (isNaN(number)) return NaN;

    const fixedNumber = number.toFixed(decimals);

    const [integerPart, decimalPart] = fixedNumber.split('.');

    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);

    if (decimals > 0) {
        return formattedInteger + dec_point + decimalPart;
    } else {
        return formattedInteger;
    }
}

function convertDateThai(date) {

    month_th = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];

    if (date !== null && date !== undefined) {
        data = date.split('-');
        y = Number(data[0]) + 543
        m = month_th[Number(data[1])]
        d = Number(data[2])
        y = y.toString().substr(2, 4)

        return `${d} ${m} ${y}`
    }else{
        return ''
    }

}