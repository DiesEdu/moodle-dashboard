// src/utils/soundEffects.js
export const playSound = (type) => {
  // Optional: Add sound effects for interactions
  // You can use the Web Audio API for simple beeps
  const audioContext = new (window.AudioContext || window.webkitAudioContext)()

  const playBeep = (frequency, duration, volume) => {
    const oscillator = audioContext.createOscillator()
    const gainNode = audioContext.createGain()

    oscillator.connect(gainNode)
    gainNode.connect(audioContext.destination)

    oscillator.frequency.value = frequency
    gainNode.gain.value = volume

    oscillator.start()
    oscillator.stop(audioContext.currentTime + duration)
  }

  switch (type) {
    case 'success':
      playBeep(800, 0.1, 0.1)
      setTimeout(() => playBeep(1200, 0.1, 0.1), 100)
      break
    case 'notification':
      playBeep(600, 0.05, 0.05)
      break
    case 'click':
      playBeep(400, 0.02, 0.02)
      break
  }
}
