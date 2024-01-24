export const setCurrentPeriod = data => {
    const date = new Date();
    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = new Date(year, month, 0).getDate();
    const currentPeriod = `${year}-${month < 10 ? '0' + month : month}-${day}`;

    // Agregar el periodo actual si no existe
    if (data.indexOf(currentPeriod) === -1) {
        data.push(currentPeriod);
    }

    return data;
};

export const floatToInt = value => {
    if (!value) return 0;

    value.replace(/[^\d.-]/g, '');

    return parseInt(value, 10);
};
