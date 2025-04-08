const normalizeDate = date => {
  console.log(`[normalizeDate] Received date string: ${date}`)
  if (!date) {
    console.log('[normalizeDate] Date was empty or null')
    return null
  }

  const d = new Date(`${date}T00:00:00Z`)
  if (isNaN(d.getTime())) {
    console.log('[normalizeDate] Invalid Date encountered, returning null')
    return null
  }

  const year = d.getUTCFullYear()
  const month = d.getUTCMonth() + 1
  let day = d.getUTCDate()

  console.log(`[normalizeDate] Parsed (UTC) => year=${year}, month=${month}, day=${day}`)

  // Solo forzamos al último día si el día NO es 1, 28 ni 30.
  // Si es 1, 28 o 30, no lo cambiamos.
  if (day !== 1 && day !== 28 && day !== 30) {
    const lastDay = new Date(Date.UTC(year, month, 0)).getUTCDate()
    console.log(`[normalizeDate] day != 1/28/30; forcing day from ${day} to lastDay=${lastDay}`)
    day = lastDay
  } else {
    console.log(`[normalizeDate] day is ${day}, leaving it as is`)
  }

  const mm = month < 10 ? '0' + month : month
  const dd = day < 10 ? '0' + day : day
  const normalizedDate = `${year}-${mm}-${dd}`
  console.log(`[normalizeDate] Final normalized date: ${normalizedDate}`)
  return normalizedDate
}

const isValidDate = date => {
  const valid = !isNaN(new Date(`${date}T00:00:00Z`).getTime())
  console.log(`[isValidDate] Checking date="${date}" => valid=${valid}`)
  return valid
}

export const setCurrentPeriod = data => {
  console.log('[setCurrentPeriod] Starting with data array:', data)
  const now = new Date()
  const year = now.getUTCFullYear()
  const month = now.getUTCMonth() + 1
  const day = new Date(Date.UTC(year, month, 0)).getUTCDate()
  const currentPeriod = `${year}-${month < 10 ? '0' + month : month}-${day}`

  console.log(`[setCurrentPeriod] Now year=${year}, month=${month}, lastDay=${day}`)
  console.log(`[setCurrentPeriod] currentPeriod to add if missing: ${currentPeriod}`)

  const filteredData = data.filter(isValidDate)
  console.log('[setCurrentPeriod] After filtering invalid dates:', filteredData)

  const normalizedData = filteredData.map(normalizeDate)
  console.log('[setCurrentPeriod] After normalization:', normalizedData)

  if (!normalizedData.includes(currentPeriod)) {
    console.log(`[setCurrentPeriod] currentPeriod (${currentPeriod}) not found, adding it`)
    normalizedData.push(currentPeriod)
  } else {
    console.log(`[setCurrentPeriod] currentPeriod (${currentPeriod}) is already included`)
  }

  const sortedPeriods = normalizedData.sort((a, b) => new Date(`${b}T00:00:00Z`) - new Date(`${a}T00:00:00Z`))
  console.log('[setCurrentPeriod] Final sortedPeriods:', sortedPeriods)
  return sortedPeriods
}

export const floatToInt = value => {
  console.log(`[floatToInt] Received value: ${value}`)
  if (!value) {
    console.log('[floatToInt] Value is empty or null, returning 0')
    return 0
  }
  const newInt = value.replace(/[^\d.-]/g, '')
  const finalNumber = Number(newInt)
  console.log(`[floatToInt] Cleaned string="${newInt}", finalNumber=${finalNumber}`)
  return finalNumber
}
