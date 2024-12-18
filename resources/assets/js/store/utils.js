// Normaliza una fecha al formato YYYY-MM-DD
const normalizeDate = date => {
    if (!date) return null;
    const d = new Date(date);
    if (isNaN(d.getTime())) return null;

    const year = d.getFullYear();
    const month = d.getMonth() + 1; // Mes (1-12)
    const day = new Date(year, month, 0).getDate(); // Último día del mes
    const normalizedDate = `${year}-${month < 10 ? '0' + month : month}-${day}`;
    
    console.log(`[normalizeDate] Fecha original: ${date}, Fecha normalizada: ${normalizedDate}`);
    return normalizedDate;
};


// Verifica si una fecha es válida
const isValidDate = date => {
    return !isNaN(new Date(date).getTime());
};

// Agrega el período actual si no existe
export const setCurrentPeriod = data => {
    const date = new Date();
    const year = date.getFullYear();
    const month = date.getMonth() + 1; // Mes actual (1 a 12)
    const day = new Date(year, month, 0).getDate(); // Último día del mes actual
    const currentPeriod = `${year}-${month < 10 ? '0' + month : month}-${day}`;

    console.log(`[setCurrentPeriod] Año: ${year}, Mes: ${month}, Día final: ${day}`);
    console.log(`[setCurrentPeriod] currentPeriod generado: ${currentPeriod}`);

    const normalizedData = data.filter(isValidDate).map(normalizeDate);

    if (!normalizedData.includes(currentPeriod)) {
        normalizedData.push(currentPeriod);
    }

    const sortedPeriods = normalizedData.sort((a, b) => new Date(b) - new Date(a));
    console.log(`[setCurrentPeriod] sortedPeriods: ${sortedPeriods}`);

    return sortedPeriods;
};



// Convierte un valor de cadena flotante a entero
export const floatToInt = value => {
    if (!value) return 0; // Si no hay valor, retornar 0

    // Eliminar caracteres no numéricos
    const newInt = value.replace(/[^\d.-]/g, '');

    return Number(newInt);
};
