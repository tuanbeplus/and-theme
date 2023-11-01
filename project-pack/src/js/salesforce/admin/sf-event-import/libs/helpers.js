export const RandomColor = () => {
  return '#' + Math.round((0x1000000 + 0xffffff * Math.random())).toString(16).slice(1);
}