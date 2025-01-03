// Normaliza una fecha al formato YYYY-MM-DD
const normalizeDate = date => {
    if (!date) return null; // Manejar valores nulos o indefinidos
    const d = new Date(date);
    if (isNaN(d.getTime())) return null; // Validar si la fecha es inválida
    const year = d.getFullYear();
    const month = (d.getMonth() + 1).toString().padStart(2, '0');
    const day = d.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
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

    // Filtrar y normalizar los datos existentes
    const normalizedData = data.filter(isValidDate).map(normalizeDate);

    // Agregar el periodo actual si no existe
    if (!normalizedData.includes(currentPeriod)) {
        normalizedData.push(currentPeriod);
    }

    // Ordenar periodos de forma descendente
    return normalizedData.sort((a, b) => new Date(b) - new Date(a));
};

// Convierte un valor de cadena flotante a entero
export const floatToInt = value => {
    if (!value) return 0; // Si no hay valor, retornar 0

    // Eliminar caracteres no numéricos
    const newInt = value.replace(/[^\d.-]/g, '');

    return Number(newInt);
};
