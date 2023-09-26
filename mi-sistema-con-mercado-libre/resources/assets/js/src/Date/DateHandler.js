import moment from "moment";

class DateHandler {
    
    static DateNowToDDMMYYYY()
    {
        return moment(new Date()).format("DD/MM/YYYY");
    }

    static DateToDDMMYYYY(date)
    {
        return moment(date).format("DD/MM/YYYY");
    }

    static DateNowToYYYYMMDD()
    {
        return moment(new Date()).format("YYYYMMDD");
    }

    static DateToYYYYMMDD(date)
    {
        return moment(date).format("YYYYMMDD");
    }
}

export default DateHandler;